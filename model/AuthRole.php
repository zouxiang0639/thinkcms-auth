<?php
namespace thinkcms\auth\model;


class AuthRole extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'auth_role';

    //初始化属性
    protected function initialize()
    {

    }
    public function authAccess()
    {
        return $this->hasMany('AuthAccess','role_id','id');
    }

    public function authRoleDelete(){
        if($this->delete()){
            if($this->authAccess){
                AuthAccess::where(['role_id'=>$this->id])->delete();
            }
            return true;
        }
        return false;

    }

}
?>