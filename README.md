# thinkphp5 权限认证 RBAC auth
thinkphp5.0 auth
## 安装
> composer require zouxiang0639/thinkcms-auth:dev-master


## 视图调用
~~~
     public function _empty($name)
        {
            $auth =  new \thinkcms\auth\Auth();
            $auth = $auth->autoload($name);
            if($auth){
                if(isset($auth['code'])){
                    return json($auth);
                }elseif(isset($auth['file'])){
                    return $auth['file'];
                }
                $this->view->engine->layout(false);
                return $this->fetch($auth[0],$auth[1]);
            }
        }
~~~
在模块中创建一个Auth控制器，把_empty方法复制上去，这样就可以访问以下视图

* /auth/default.html        角色列表 
* /auth/roleAdd.html        角色添加
* /auth/roleEdit.html       角色修改
* /auth/authorize/id/2.html 权限设置
* /auth/menu.html           菜单列表
* /auth/menuAdd.html        菜单增加
* /auth/menuEdit.html       菜单修改

## 权限认证
~~~
    public function __construct(\think\Request $request)
    {
        parent::__construct($request);
        $this->request  = $request;
        $auth     = new Auth();
        $auth->noNeedCheckRules = ['index/index/index','index/index/home'];  //无需权限认证路由  
        $this->uid      = Session::get('admin.id');
        if(!empty(Session::get('admin.name'))){//用户登录状态
            if(!$auth->auth($this->uid)){ // 权限认证
                return $this->error("你没有权限访问！");
            }
        }else{
            return $this->error("您还没有登录！",url("publics/login"));
        }
    }
~~~

这里在公共控制器上加入验证即可

## 授权菜单
~~~
 Auth::menuCheck($this->uid);
~~~
这个方法返回授权及非隐藏的所有菜单，这样我们后台的菜单就可以根据管理员的权限来来展示授权的目录 

案例下载(http://www.thinkphp.cn/extend/875.html)
