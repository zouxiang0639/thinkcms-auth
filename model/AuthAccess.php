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

    /**
     * innerAuthRule 全连 AuthRule表 返回 授权角色
     * @param int     $roleId
     * @param int     $uid
     * @return array
     */
    public static function innerAuthRule($roleId,$uid,$where=[])
    {
        return AuthAccess::alias('AuthAccess')
            ->join('__AUTH_RULE__ AuthRule','AuthAccess.menu_id = AuthRule.menu_id')
            ->where($where)
            ->where('(AuthAccess.type="admin_url" and AuthAccess.role_id in(:roleId))or(AuthAccess.type="admin" and AuthAccess.role_id =:uid)',['roleId'=>$roleId,'uid'=>$uid])
            ->column('*','menu_id');
    }
}
?>