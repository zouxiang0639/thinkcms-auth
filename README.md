# thinkphp5 权限认证 RBAC 加 行为日志
这个插件主要有一整套RBAC  行为日志 视图 只需要 composer安装即可和你的系统融为一体

## 安装
~~~
> composer require zouxiang0639/thinkcms-auth
~~~
## v1.1更新

* 1.加入了行为日志
* 2.加入样式文件路由定义,

## 配置 v1.1
~~~
'thinkcms' =>[
        'style_directory' => '/static/admin/'
  ]
~~~

可以不配置  配置以后Js css文件需要放到配置的目录里

## 手动加入日志  v1.1
~~~
    $auth = new Auth();
    $auth->admin = $list['user_name'];
    $auth->createLog('管理员<spen style=\'color: #1dd2af;\'>[ {name} ]</spen>偷偷的进入后台了,','后台登录',$list['id']);
~~~

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
            return abort(404,'页面不存在');
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
* /auth/log.html            行为日志    新v1.1
* /auth/viewLog.html        查看日志    新v1.1
* /auth/clear.html          清空日志    新v1.1

## 权限认证
~~~
    public function __construct(\think\Request $request)
    {
        parent::__construct($request);
        $this->request  = $request;
        $auth     = new Auth();
        $auth->noNeedCheckRules = ['index/index/index','index/index/home'];     //无需权限认证路由
        $auth->log              = true;                                         // v1.1版本  日志开关默认true
        $auth->admin            = $session['name'];                             // v1.1版本  管理员名称
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

## 手动创建日志
~~~
 Auth::menuCheck($this->uid);
~~~

## mysql文件
~~~
tp_action_log.sql
tp_auth_access.sql
tp_auth_role.sql
tp_auth_role_user.sql
tp_auth_rule.sql
tp_menu.sql
~~~

案例下载(http://www.thinkphp.cn/extend/875.html)
