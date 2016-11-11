<?php
namespace thinkcms\auth\model;


class AuthRule extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'auth_rule';

    //初始化属性
    protected function initialize()
    {

    }

    //关联一对多 目录
    public function authAccess()
    {
        return $this->hasMany('AuthAccess','menu_id','menu_id');
    }


    /**
     * 关联 authAccess模型 修改
     * @param array     $param   参数
     * @return bool
     */
    public function authRuleEdit($param){
       if($this->save($param)){
            if($this->authAccess){
                AuthAccess::where(['menu_id'=>$param['menu_id']])->update(['rule_name'=>$param['name'],'type'=>$param['type']]);
            }
           return true;
       }
       return false;
    }
}
?>