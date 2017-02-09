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

    //一对多 权限授权
    public function authAccess()
    {
        return $this->hasMany('AuthAccess','role_id','id');
    }

    /**
     * 关联删除 AuthAccess
     * 判断是否有用户使用此角色,如果有返回使用角色数量
     * 否则删除角色数据,调用authAccess方法如果有数据删除关联AuthAccess模型数据
     *
     * @return bool
     */
    public function authRoleDelete()
    {
        $roleCount = AuthRoleUser::where(['role_id'=>$this->id])->count();
        if($roleCount > 0){
            return "已有{$roleCount}用户在是有此角色不可删除";
        }

        if($this->delete()){
            if($this->authAccess){
                AuthAccess::where(['role_id'=>$this->id,'type'=>'admin_url'])->delete();
            }
            return true;
        }
        return false;
    }

}
?>