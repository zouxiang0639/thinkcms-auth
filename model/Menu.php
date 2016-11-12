<?php
namespace thinkcms\auth\model;


class Menu extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'menu';

    //初始化属性
    protected function initialize()
    {

    }

    //关联一对一 目录
    public function authRule()
    {
        return $this->hasOne('AuthRule','menu_id','id');
    }


    /**
     * 关联 authRule模型 修改
     * @param array     $param   参数
     * @return bool
     */
    public function menuEdit($param){

        if($this->save($param)){
            $authRule = $this->authRule;

            if($this->data['action'] == 'default' ||$this->data['type'] == 0) {//判断他们是否需要加入权限
                if($authRule){
                    $this->authRule->authRuleDelete();
                }
                return true;
            }

            $name   = strtolower("{$this->data['app']}/{$this->data['model']}/{$this->data['action']}");

            $authRuledata   = [
                "name"          => $name,
                "module"        => $this->data['app'],
                "type"          => "admin_url",
                "title"         => $this->data['name'],
                'menu_id'       => $this->data['id'],
                'url_param'     => $this->data['url_param'],
                'rule_param'    => $this->data['rule_param'],
            ];
            if($authRule){
                $authRule->authRuleEdit($authRuledata);
                return true;
            }else{
                AuthRule::create($authRuledata);
                return true;
            }

        }
        return false;
    }

    /**
     * 关联 authRule模型 增加
     * @param array     $param   参数
     * @return bool
     */
    public function menuAdd($param){
        $auth = $this->create($param);
        if($auth){

            $name   = strtolower("{$auth->data['app']}/{$auth->data['model']}/{$auth->data['action']}");
            $authRule   = [
                "name"          => $name,
                "module"        => $auth->data['app'],
                "type"          => "admin_url",
                "title"         => $auth->data['name'],
                'menu_id'       => $auth->data['id'],
                'url_param'     => $auth->data['url_param'],
                'rule_param'    => $auth->data['rule_param'],
            ];

            AuthRule::create($authRule);
            return true;
        }
        return false;
    }

    /**
     * 关联 authRule模型 删除
     * @param int     $id   参数
     * @return bool
     */
    public function menuDelete(){
        if($this->delete()){
            if($this->authRule){
                $this->authRule->authRuleDelete();
            }
            return true;
        }
        return false;
    }
}
?>