<?php


namespace thinkcms\auth\controller;





use think\Validate;
use thinkcms\auth\model\AuthRole;

class Rbac
{
    public function __construct($request)
    {

        $this->request  = $request;
        $this->param    = $this->request->param();
        $this->post     = $this->request->post();
        $this->data     = ['pach'=>VIEW_PATH];
    }


    public function menu(){
        return VIEW_PATH.'menu.php';
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

            $validate = new Validate([
                'name|角色名称'  => 'require',
            ]);

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
            $validate = new Validate([
                'name|角色名称'  => 'require',
            ]);

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