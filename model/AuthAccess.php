<?php
namespace thinkcms\auth\model;


class AuthAccess extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'auth_access';

    //初始化属性
    protected function initialize()
    {

    }

    //关联一对一 角色
    public function authRule()
    {
        return $this->hasOne('AuthRule','menu_id','menu_id');
    }
}
?>