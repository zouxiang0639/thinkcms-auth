<?php


namespace thinkcms\auth\controller;


use think\Validate;
use thinkcms\auth\library\Tree;
use thinkcms\auth\model\AuthRole;
use thinkcms\auth\model\Menu;

class Rbac
{

    public $menuValidate    = ['name|名称'=>'require','app|应用'=>'require','model|控制器'=>'require','action|方法'=>'require'];
    public $roleValidate    = ['name|角色名称'  => 'require'];

    public function __construct($request)
    {
        $this->request  = $request;
        $this->param    = $this->request->param();
        $this->post     = $this->request->post();
        $this->data     = ['pach'=>VIEW_PATH];
    }

    /**
     * 菜单and权限列表
     */
    public function menu(){
        $result     = Menu::where('')->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');
        $tree       = new Tree();
        $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

        foreach ($result as $n=> $r) {
            $result[$n]['level'] = $tree->get_level($r['id'], $result);
            $result[$n]['parent_id_node'] = ($r['parent_id']) ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';
            $result[$n]['str_manage'] = '
                <a href="'.url("auth/menuAdd",["parent_id" => $r['id']]).'">添加子菜单</a> |
                <a href="'.url("auth/menuEdit",["id" => $r['id']]).'">编辑</a> |
                <a class="a-post" post-msg="你确定要删除吗" post-url="'.url("auth/menuDelete",["id" => $r['id']]).'">删除</a>';
            $result[$n]['status'] = $r['status'] ? '开启' : '隐藏';
        }
        $str = "<tr id='node-\$id' \$parent_id_node>
                    <td style='padding-left:20px;'>
                        <input name='listorders[\$id]' type='text' size='3' value='\$list_order' data='\$id' class='listOrder'>
                    </td>
                    <td>\$id</td>
                    <td>\$spacer  \$name</td>
                    <td>\$app</td>
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
    public function menuEdit(){

        $param  = $this->param;
        $post   = $this->post;
        $info   = Menu::get($param['id']);

        if($this->request->isPost()){

            $validate = new Validate($this->menuValidate);

            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }

            if($info->menuEdit($post)){
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
    public function menuAdd(){
        $parent_id  = isset($this->param['parent_id'])?$this->param['parent_id']:'';

        if($this->request->isPost()){
            $post   = $this->post;
            $validate = new Validate($this->menuValidate);

            if (!$validate->check($post)) {
                return ['code'=>0,'msg'=>$validate->getError()];
            }
            $menu   = new Menu();
            if($menu->menuAdd($post)){
                return ['code'=>1,'msg'=>'修改成功','url'=>url('auth/menu')];
            }else{
                return ['code'=>0,'msg'=>'修改失败'];
            }
        }

        $info['selectCategorys']  = menu($parent_id);
        return [VIEW_PATH.'menuAdd.php',array_merge($this->data,['info'=>$info])];
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
    public function roleEdit(){
        $param  = $this->param;
        $post   = $this->post;
        $info   = AuthRole::get($param['id']);

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
    public function roleAdd(){

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

}

/**
 * 所有后台菜单
 * @param array  $selected      默认id
 * @param bool  $cid            类型id
 * @param bool  $parentid       父级id
 * @return mixed
 */
function menu($selected = '1',$where=''){

    $result = Menu::where($where)->order(["list_order" => "asc",'id'=>'asc'])->column('*','id');

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