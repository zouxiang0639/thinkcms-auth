<?php
// +----------------------------------------------------------------------
// | [ Only to facilitate the creation of it]
// +----------------------------------------------------------------------
// | Personal development
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zouxiang <542506511@qq.com>
// +----------------------------------------------------------------------
namespace thinkcms\auth;

defined('VIEW_PATH') or define('VIEW_PATH', __DIR__ . DS.'view'. DS);

use think\Cache;
use think\Loader;
use think\Request;
use thinkcms\auth\controller\Rbac;
use thinkcms\auth\model\ActionLog;
use thinkcms\auth\model\AuthAccess;
use thinkcms\auth\model\AuthRoleUser;
use thinkcms\auth\model\Menu;

class Auth
{
    const  PATH                 = __DIR__;
    public $log                 = true;
    public $noNeedCheckRules    = [];           //不需要检查的路由规则
    public $admin               = '';           //管理员

    public function __construct()
    {
        $this->request      = Request::instance();
        $this->param        = $this->request->param();
        $this->module       = $this->request->module();
        $this->controller   = $this->request->controller();
        $this->action       = $this->request->action();
    }

    /**
     * 加载控制器方法
     * @param  string  $name 方法名
     * @return mixed
     */
    public function autoload($name){

        $controller = new Rbac($this->request);

        if(strtolower($this->controller) == 'auth' && method_exists($controller,$name)){
          return  call_user_func([$controller, $name]);
        }

       return false;
    }

    /**
     * 权限认证
     * @param  int          $uid
     * @return mixed
     */
    public function auth($uid){

        $controller         = Loader::parseName($this->controller,1); //字符串命名风格转换
        $rule               = strtolower("{$this->module}/{$controller}/{$this->action}");
        //如果用户角色是1，则无需判断
        if(empty($uid)){
            return false;
        }
        if($uid == 1){
            self::actionLog($rule,$uid);
            return true;
        }
        //无需认证
        $noNeedCheckRules   = array_merge($this->noNeedCheckRules,[$this->module.'/auth/openfile',$this->module.'/auth/cache']);

        if( !in_array($rule,$noNeedCheckRules) ){
            return self::authCheck($rule,$uid,'or');
        }else{
            return true;
        }

    }

    /**
     * 菜单权限检查
     * @param  int             $uid
     * @return array
     */
    public static function menuCheck($uid){

        if(empty($uid)){
            return false;
        }

        $authRoleUser = AuthRoleUser::where(['user_id'=>$uid,'role_id'=>1])->find();
        $where['status'] = 1;

        if($uid==1 || !empty($authRoleUser)){
        }else{
            $menu_id    = AuthRoleUser::hasWhere('authAccess')->field('b.*')->where(['a.user_id'=>$uid])->column('b.menu_id');
            $where['id']=['in',$menu_id];
        }

        $menu       = Menu::where($where)->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
        return $menu;
    }


    /**
     * 行为日志检查
     * @param  string          $rule        日志规则
     * @param  int             $uid         执行者ID
     * @return array
     */
    private function actionLog($rule,$uid){

        //是否需要打开 行为日志检查
        if($this->log === false){
            return true;
        }

        $logMenu    = Cache::get('logMenu');
        if(empty($logMenu)){    //缓存日志24小时
            $logMenu    = Menu::actionLogMenu();
            Cache::set('logMenu',$logMenu,86400);
        }
        $menu       =   isset($logMenu[$rule])?$logMenu[$rule]:'';

        $log        = [];
        if(empty($menu)){
            return true;
        }

        //子集行为日志菜单匹配
        if(isset($menu['child'])){

           foreach($menu['child'] as $v){
             if(!empty($v['rule_param'])){
                 $condition = '';
                 $command   = preg_replace('/\{(\w*?)\}/', '$this->param[\'\\1\']', $v['rule_param']);
                 @(eval('$condition=(' . $command . ');'));
                 if ($condition and $v['request'] == $this->request->method()) {
                     $log = $v;
                 }
             }
           }
        }

        //父集行为日志菜单匹配
        if(empty($log)){
            if($menu['request'] == $this->request->method()){
                $log    = $menu;
            }
        }

        if(!empty($log)){
            return self::createLog($log['log_rule'],$log['name'],$uid);
        }

        return true;
    }


    /**
     * 创建行为日志
     * @param  string       $logrule    行为日志规则
     * @param  string       $title      标题
     * @param  int          $uid        执行者ID
     * @return array
     */
    public function  createLog($logrule,$title,$uid){
        $param = $this->param;

        $condition = '';
        $command   = preg_replace('/\{(\w*?)\}/', '{$param[\'\\1\']}', $logrule);
        @(eval('$condition=("' . $command . '");'));

        $data   = [
            'action_ip'     => ip2long($this->request->ip()),
            'username'      => $this->admin,
            'create_time'   => time(),
            'log_url'       => '/'.$this->request->pathinfo(),
            'log'           => $condition,
            'user_id'       => $uid,
            'title'         => $title
        ];
        return ActionLog::create($data);
    }

    /**
     * 检查权限
     * @param  string          $url   路由
     * @param  int             $uid
     * @param  string          $relation
     * @return mixed
     */
    protected function authCheck($url,$uid,$relation='or'){


        $rule   = array($url);
        $roleId = [];
        $list   = []; //保存验证通过的规则名)
        $param  = $this->param;
        $group  = AuthRoleUser::hasWhere('authRule')->field('a.*')->where(['a.user_id'=>$uid])->select();
        foreach($group as $k=>$v){
            $roleId[$k] = $v['role_id'];
        }

        if(in_array(1, $roleId)){

            //行为日志
            self::actionLog($rule,$uid);
            return true;
        }
        if(empty($roleId)){
            return false;
        }

        $rules = AuthAccess::hasWhere('authRule')->field('a.*,b.*')->where(["a.role_id"=>["in",$roleId],"b.name"=>["in",$rule]])->select();

        foreach ($rules as $rule){
            if (!empty($rule['rule_param'])) { //根据rule_param进行验证
                $condition  = false;
                // $user = $this->getUserInfo($uid);//获取用户信息,一维数组

                $command = preg_replace('/\{(\w*?)\}/', '$param[\'\\1\']', $rule['rule_param']);

                @(eval('$condition=(' . $command . ');'));
                if ($condition) {
                    $list[] = strtolower($rule['name']);
                }

            }else{
                $list[] = strtolower($rule['name']);
            }
        }

        if ($relation == 'or' and !empty($list)) {

            //行为日志
            self::actionLog($url,$uid);
            return true;
        }

        $diff = array_diff($rule, $list);

        if ($relation == 'and' and empty($diff)) {

            return true;
        }

        return false;
    }


}