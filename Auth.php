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

use think\Loader;
use think\Request;
use thinkcms\auth\controller\Rbac;
use thinkcms\auth\model\AuthAccess;
use thinkcms\auth\model\AuthRoleUser;
use thinkcms\auth\model\Menu;

class Auth
{

    const  PATH                 = __DIR__;
    public $noNeedCheckRules    = [];           //不需要检查的路由规则
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

        //如果用户角色是1，则无需判断
        if(empty($uid)){
            return false;
        }
        if($uid == 1){
            return true;
        }

        $controller         = Loader::parseName($this->controller,1); //字符串命名风格转换
        $rule               = strtolower("{$this->module}/{$controller}/{$this->action}");

        //无需认证
        $noNeedCheckRules   = array_merge($this->noNeedCheckRules,[$this->module.'/auth/openfile']);

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
     * 检查权限
     * @param  string          $rule   路由
     * @param  int             $uid
     * @param  string          $relation
     * @return mixed
     */
    protected function authCheck($rule,$uid,$relation='or'){


        $rule   = array($rule);
        $roleId = [];
        $list   = []; //保存验证通过的规则名)
        $param  = $this->param;
        $group  = AuthRoleUser::hasWhere('authRule')->field('a.*')->where(['a.user_id'=>$uid])->select()->toarray();
        foreach($group as $k=>$v){
            $roleId[$k] = $v['role_id'];
        }

        if(in_array(1, $roleId)){
            return true;
        }
        if(empty($roleId)){
            return false;
        }

        $rules = AuthAccess::hasWhere('authRule')->field('a.*,b.*')->where(["a.role_id"=>["in",$roleId],"b.name"=>["in",$rule]])->select()->toarray();

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
            return true;
        }

        $diff = array_diff($rule, $list);

        if ($relation == 'and' and empty($diff)) {

            return true;
        }

        return false;
    }



}