<?php
namespace thinkcms\auth\model;


class AuthRoleUser extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'auth_role_user';

    //初始化属性
    protected function initialize()
    {

    }

    //关联一对一 角色
    public function authRule()
    {
        return $this->hasOne('authRole','id','role_id');
    }

    //关联一对一 角色
    public function authAccess()
    {
        return $this->hasOne('authAccess','role_id','role_id');
    }

    /**
     * 加入角色权限
     * @param array     $role_id   角色ID
     * @param int     $user_id   用户ID
     * @return bool
     */
    public function authRoleUserAdd($role_id,$user_id){

        $data = [];
        if(is_array($role_id)){
            self::where(['user_id'=>$user_id])->delete();
            foreach($role_id as $v){
                $data[]  = [
                    'role_id' => $v,
                    'user_id' => $user_id
                ];
            }
            self::saveAll($data);

            return true;
        }
        return false;
    }

    /**
     * 删除角色权限
     * @param int     $user_id   用户ID
     * @return bool
     */
    public function authRoleUserDelete($user_id){
        self::where(['user_id'=>$user_id])->delete();
    }

}
?>