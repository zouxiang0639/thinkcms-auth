<?php


namespace thinkcms\auth\controller;


use think\Cache;
use think\Config;
use think\Validate;
use thinkcms\auth\library\Tree;
use thinkcms\auth\model\ActionLog;
use thinkcms\auth\model\AuthAccess;
use thinkcms\auth\model\AuthRole;
use thinkcms\auth\model\AuthRoleUser;
use thinkcms\auth\model\Menu;

class Rbac
{

    public $menuValidate    = ['name|名称'=>'require' , 'app|应用'=>'require' , 'model|控制器'=>'require' , 'action|方法'=>'require'];
    public $roleValidate    = ['name|角色名称'  => 'require'];
    private $id;
    public function __construct($request)
    {

        $this->request  = $request;
        $this->param    = $this->request->param();
        $this->post     = $this->request->post();
        $this->id       = isset($this->param['id'])?intval($this->param['id']):'';
        $this->data     = ['pach'=>VIEW_PATH];
    }

    /**
     * 菜单and权限列表
     */
    public function menu()
    {
        $result     = Menu::where('')->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
        $tree       = new Tree();
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        foreach ($result as $n=> $r) {
            $result[$n]['level'] = $tree->get_level($r['id'], $result);
            $result[$n]['parent_id_node'] = ($r['parent_id']) ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';

            $result[$n]['str_manage'] = checkPath('auth/menuAdd',["parent_id" => $r['id']]) ? '<a href="'.url("auth/menuAdd",["parent_id" => $r['id']]).'">添加子菜单</a> |':'';
            $result[$n]['str_manage'] .= checkPath('auth/menuEdit',["id" => $r['id']]) ?'<a href="'.url("auth/menuEdit",["id" => $r['id']]).'">编辑</a> |':'';
            $result[$n]['str_manage'] .= checkPath('auth/menuDelete',["id" => $r['id']]) ?'<a class="a-post" post-msg="你确定要删除吗" post-url="'.url("auth/menuDelete",["id" => $r['id']]).'">删除</a>|':'';
            $result[$n]['status'] = $r['status'] ? '开启' : '隐藏';
        }
        $str = "<tr id='node-\$id' \$parent_id_node>
                    <td style='padding-left:20px;'>
                        <input name='listorders[\$id]' type='text' size='3' value='\$list_order' data='\$id' class='listOrder'>
                    </td>
                    <td>\$id</td>
                    <td>\$spacer  \$name</td>
                    <td>\$app</td>
                    <td>\$model</td>
                    <td>\$action</td>
                    <td>\$request</td>
                    <td>\$status</td>
                    <td>\$str_manage</td>
                </tr>";

        $tree->init($result);
        $info = $tree->get_tree(0, $str);
        return [VIEW_PATH.'menu.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 菜单and权限 修改
     */
    public function menuEdit()
    {

        $post   = $this->post;
        $info   = Menu::get($this->id);

        if(empty($info)){
            return false;
        }

        if($this->request->isPost()){

            $validate = new Validate($this->menuValidate);

            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }

            if($info->save($post)){
                return ['code'=>1,'msg'=>'修改成功','url'=>url('auth/menu')];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }

        $info['selectCategorys'] = menu($info['parent_id']);
        return [VIEW_PATH.'menuEdit.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 菜单and权限 增加
     */
    public function menuAdd()
    {
        $parent_id  = isset($this->param['parent_id'])?$this->param['parent_id']:'';

        if($this->request->isPost()){
            $post   = $this->post;
            $validate = new Validate($this->menuValidate);

            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }
            if(Menu::create($post)){
                return ['code'=>1,'msg'=>'增加成功','url'=>url('auth/menu')];
            }else{
                return ['code'=>0,'msg'=>'增加失败'];
            }
        }

        $info['selectCategorys']  = menu($parent_id);
        return [VIEW_PATH.'menuAdd.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 菜单and权限 删除
     */
    public function menuDelete()
    {
        if($this->request->isPost()){
            $result   = Menu::get($this->id);

            if(empty($result)){
                return ['code'=>0,'msg'=>'没有数据'];
            }else if(Menu::where(['parent_id'=>$result['id']])->find()){
                return ['code'=>0,'msg'=>'有子目录不可删除'];
            };

            if($result->delete()){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('auth/menu')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
        return ['code'=>0,'msg'=>'请求方式错误'];
    }

    /**
     * 菜单 排序
     */
    public function menuOrder()
    {
        if($this->request->isPost()) {
            $order  = isset($this->param['order'])?intval($this->param['order']):'';
            $result = Menu::get($this->id);

            if(empty($result)){
                return ['code'=>0,'msg'=>'没有数据'];
            }else if ($result) {
                if ($result->save(['list_order' => $order])) {
                    return ['code' => 1, 'msg' => '数据已更新'];
                }
            }

            return ['code'=>0,'msg'=>'数据无变化'];
        }
        return ['code'=>0,'msg'=>'请求方式错误'];
    }
    /**
     * 角色列表
     */
    public function role(){

        $data = AuthRole::all();
        return [VIEW_PATH.'role.php',array_merge($this->data,['list'=>$data])];
    }

    /**
     * 角色修改
     */
    public function roleEdit()
    {

        $post   = $this->post;
        $info   = AuthRole::get($this->id);
        if(empty($info)){
            return false;
        }
        //post 数据处理
        if($this->request->isPost()){

            $validate = new Validate($this->roleValidate);

            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }

            if($info->save($post)){
                return ['code'=>1,'msg'=>'修改成功','url'=>url('auth/role')];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }

        return [VIEW_PATH.'roleEdit.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 角色增加
     */
    public function roleAdd()
    {

        //post 数据处理
        if($this->request->isPost()){
            $post   = $this->post;

            //现在数据
            $validate = new Validate($this->roleValidate);
            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }

            if(AuthRole::create($post)){
                return ['code'=>1,'msg'=>'增加成功','url'=>url('auth/role')];
            }else{
                return ['code'=>0,'msg'=>'增加失败'];
            }
        }
        return [VIEW_PATH.'roleAdd.php',$this->data];
    }

    public function roleDelete()
    {
        if($this->request->isPost()){
            $result   = AuthRole::get($this->id);

            if($this->id==1){
                return ['code'=>0,'msg'=>'超级管理员不可删除'];
            }else if(empty($result)){
                return ['code'=>0,'msg'=>'没有数据'];
            }
            $delete = $result->authRoleDelete();
            if(is_string($delete)){
                return ['code'=>0,'msg'=>$delete];
            }else if($delete === true){
                return ['code'=>1,'msg'=>'删除成功','url'=>url('auth/role')];
            }else{
                return ['code'=>0,'msg'=>'删除失败'];
            }
        }
        return ['code'=>0,'msg'=>'请求方式错误'];
    }
    /**
     * 角色授权
     */
    public function authorize()
    {


        $menu       = Menu::where('')->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');

        if($this->request->isPost()){//表单处理

            $post   = $this->post;
            $menuid = isset($post['menuid']) ? $post['menuid'] : [];;

            if(empty($this->id)){
                return ['code'=>0,'msg'=>'需要授权的角色不存在'];
            }

            AuthAccess::where(["role_id" => $this->id,'type'=>'admin_url'])->delete();

            if (is_array($menuid) && count($menuid)>0) {
                foreach ($menuid as $v) {

                    $menus   = isset($menu[$v])?$menu[$v]:'';

                    if($menus){
                        $name   = strtolower("{$menus['app']}/{$menus['model']}/{$menus['action']}");
                        $data[]   = [
                            "role_id"   => $this->id,
                            "rule_name" => $name,
                            'type'      => 'admin_url',
                            'menu_id'   => $v
                        ];
                    }
                }

                if(!empty($data)){
                    $AuthAccess = new AuthAccess();
                    if($AuthAccess->saveAll($data)){
                        return ['code'=>1,'msg'=>'增加成功','url'=>url('auth/role')];
                    }else{
                        return ['code'=>0,'msg'=>'增加失败'];
                    }
                }

            }else{
                return ['code'=>0,'msg'=>'没有接收到数据，执行清除授权成功！'];
            }
        }//表单处理结束

        if(empty($this->id)){
            return false;
        }
        $info = self::authorizeHtml($menu,'admin_url');

        return [VIEW_PATH.'authorize.php',array_merge($this->data,['info'=>$info])];
    }
    /**
     *  管理员授权
     */
    public function adminAuthorize()
    {
        $menu       = Menu::where('')->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');

        if($this->request->isPost()){//表单处理

            $post   = $this->post;
            $menuid = isset($post['menuid']) ? $post['menuid'] : [];

            if(empty($this->id)){
                return ['code'=>0,'msg'=>'需要授权的角色不存在'];
            }

            AuthAccess::where(["role_id" => $this->id,'type'=>'admin'])->delete();

            if (is_array($menuid) && count($menuid)>0) {
                foreach ($menuid as $v) {

                    $menus   = isset($menu[$v])?$menu[$v]:'';

                    if($menus){
                        $name   = strtolower("{$menus['app']}/{$menus['model']}/{$menus['action']}");
                        $data[]   = [
                            "role_id"   => $this->id,
                            "rule_name" => $name,
                            'type'      => 'admin',
                            'menu_id'   => $v
                        ];
                    }
                }

                if(!empty($data)){
                    $AuthAccess = new AuthAccess();
                    if($AuthAccess->saveAll($data)){
                        return ['code'=>1,'msg'=>'增加成功','url'=>''];
                    }else{
                        return ['code'=>0,'msg'=>'增加失败'];
                    }
                }

            }else{
                return ['code'=>0,'msg'=>'没有接收到数据，执行清除授权成功！'];
            }
        }//表单处理结束

        if(empty($this->id)){
            return false;
        }

        //管理员所有角色权限
        $roleId     = AuthRoleUser::innerAuthRole($this->id);
        if(in_array(1,$roleId)){
            $AuthAccess = true;
        }else if(empty($roleId)){
            $AuthAccess = [];
        }else{

            $AuthAccess   = AuthAccess::where("role_id" ,'in',$roleId)->column('*','menu_id');

        }


        $info = self::authorizeHtml($menu,'admin',$AuthAccess);

        return [VIEW_PATH.'adminAuthorize.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 注册样式文件
     */
    public function openFile()
    {

        $file       = strtr($this->param['file'], '_', '/');
        $extension  = substr(strrchr($file, '.'), 1);

        switch ($extension)
        {
            case 'css':
                $text = 'text/css';
                break;
            case 'js':
                $text = 'text/js';
                break;
            default:
                return false;
        }

        $pach = VIEW_PATH.'../static/'.$file;

        $file = file_get_contents($pach);

        return ['file'=>response($file, 200, ['Content-Length' => strlen($file)])->contentType($text)];
    }

    /**
     * 日志列表
     */
    public function log()
    {
        $where  = [];
        $param  = $this->param;
        if(!empty($param['username'])){
            $where['username']  = $param['username'];
        }
        if(!empty($param['userId'])){
            $where['user_id']  = $param['userId'];
        }
        if(!empty($param['title'])){
            $where['title']  = ['like','%'.$param['title'].'%'];
        }

        $list   = ActionLog::where($where)->order('id desc')->paginate(20,'',[
            'query'=>$param
        ]);
        $page   = $list->render();

        return [VIEW_PATH.'log.php',array_merge($this->data,['list'=>$list,'page'=>$page])];
    }

    /**
     * 日志详情
     */
    public function viewLog()
    {
        $info   = ActionLog::get($this->id);
        return [VIEW_PATH.'viewLog.php',array_merge($this->data,['info'=>$info])];
    }

    /**
     * 清空日志
     */
    public function clear()
    {
        if(ActionLog::where('1=1')->delete()){
            return ['code'=>1,'msg'=>'数据已清空','url'=>url('auth/log')];
        }
        return ['code'=>0,'msg'=>'操作失败'];
    }

    /**
     * 清除缓存
     */
    public function cache()
    {
        cache('logMenu', '');
        return ['code'=>1,'msg'=>'操作成功','url'=>url('auth/menu')];
    }

    protected function authorizeHtml($menu,$type,$authMenu=[])
    {
        $priv_data  = AuthAccess::where(['role_id'=>$this->id,'type'=>$type])->field("rule_name")->column('menu_id');
        $tree       = new Tree();
        foreach ($menu as $n => $t) {
            $menu[$n]['checked']  = (in_array($t['id'], $priv_data)) ? ' checked' : '';
            $menu[$n]['level']    = $tree->get_level($t['id'], $menu);
            $menu[$n]['width']    = 100-$menu[$n]['level'];
            $menu[$n]['disabled'] = isset($authMenu[$t['id']])||$authMenu===true?[0=>"style='display: none;'disabled=''",1=>'★']:[0=>'',
                1=>''];

        }

        $tree->init($menu);
        $tree->text =[
            'other' => "<label class='checkbox' data-original-title='' data-toggle='' >
                        <input \$checked \$disabled[0] name='menuid[]' value='\$id' level='\$level'
                        onclick='javascript:checknode(this);'type='checkbox'>
                       \$disabled[1] \$name
                   </label>",
            '0' => [
                '0' =>"<dl class='checkmod'>
                    <dt class='hd'>
                        <label class='checkbox' data-original-title='' data-toggle='tooltip'>
                            <input \$checked \$disabled[0] name='menuid[]' value='\$id' level='\$level'
                             onclick='javascript:checknode(this);'
                             type='checkbox'>
                           \$disabled[1] \$name
                        </label>
                    </dt>
                    <dd class='bd'>",
                '1' => "</dd></dl>",
            ],
            '1' => [
                '0' => "
                        <div class='menu_parent'>
                            <label class='checkbox' data-original-title='' data-toggle='tooltip'>
                                <input \$checked \$disabled[0] name='menuid[]' value='\$id' level='\$level'
                                onclick='javascript:checknode(this);' type='checkbox'>
                                \$disabled[1] \$name
                            </label>
                        </div>
                        <div class='rule_check' style='width: \$width%;'>",

                '1' => "</div><span class='child_row'></span>",
            ]

        ];

        $info['html']   = $tree->get_authTree(0);
        $info['id']     = $this->id;
        return $info;
    }
}

/**
 * 所有后台菜单
 * @param int   $selected       默认id
 * @return mixed
 */
function menu($selected = 1)
{
    $array = [];
    $result = Menu::where('')->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');

    $tree = new Tree();
    foreach ($result as $r) {
        $r['selected'] = $r['id'] == $selected ? 'selected' : '';
        $array[] = $r;
    }
    $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
    $tree->init($array);
    $parentid = isset($where['parentid'])?$where['parentid']:0;

    return $tree->get_tree($parentid, $str);
}