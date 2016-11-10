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

use think\Request;
use thinkcms\auth\controller\Rbac;

class Auth
{

    const PATH              = __DIR__;

    public function __construct()
    {
        $this->request      = Request::instance();
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



}