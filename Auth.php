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

class Auth
{

    const  PATH                 = __DIR__;
    public $noNeedCheckRules    = [];           //不需要检查的路由规则
    public function __construct()
    {
        $this->request      = Request::instance();
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
        if($uid == 1){
            return true;
        }

        $controller = strtolower(Loader::parseName($this->controller,1)); //字符串命名风格转换
        $rule       = "{$this->module}/{$controller}/{$this->action}";

        if( !in_array($rule,$this->noNeedCheckRules) ){

            return self::authCheck($rule,$uid,'or');
        }else{
            return true;
        }

    }

    protected function authCheck($name,$uid,$relation='or'){

        dump($name);die;
        if(empty($uid)){
            return false;
        }
        if($uid==1){
            return true;
        }

        if (is_string($name)) {
            $name = strtolower($name);

            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);

            }
        }
        $list = array(); //保存验证通过的规则名)



        $group=DB("auth_role_user")->alias("a")->join(config('database.prefix').'auth_role b','a.role_id = b.id')->where(array("user_id"=>$uid,"status"=>1))->field("role_id")->select();

        $groups = array();

        foreach($group as $k=>$v){
            $groups[$k] = $v['role_id'];
        }

        if(in_array(1, $groups)){
            return true;
        }

        if(empty($groups)){
            return false;
        }

        $rules = DB("auth_access")->alias("a")->join(config('database.prefix').'auth_rule b','a.rule_name = b.name')->where(array("a.role_id"=>array("in",$groups),"b.name"=>array("in",$name)))->select();


        foreach ($rules as $rule){
            if (!empty($rule['condition'])) { //根据condition进行验证

                // $user = $this->getUserInfo($uid);//获取用户信息,一维数组


                $command = preg_replace('/\{(\w*?)\}/', '$param[\'\\1\']', $rule['condition']);


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

        $diff = array_diff($name, $list);

        if ($relation == 'and' and empty($diff)) {
            return true;
        }

        return false;
    }


}