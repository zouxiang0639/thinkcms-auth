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

    /**
     * 缓存后台菜单数据
     */
    public static function actionLogMenu() {
        $log = [];
        $men = Menu::where('request <> "" ')->column('*');

        foreach($men as $v){
            $url = strtolower($v['app'].'/'.$v['model'].'/'.$v['action']);
            $arr = [
                'log_rule'  => $v['log_rule'],
                'request'   => $v['request'],
                'rule_param'=> $v['rule_param'],
                'name'      => $v['name'],
            ];
            if(!isset($log[$url])){
                $log[$url]              = $arr;
            }else{
                $log[$url]['child'][]   = $arr;
            }
        }
        return $log;
    }

    //关联一对一 目录
    public function authRule()
    {
        return $this->hasOne('AuthRule','menu_id','id');
    }


    /**
     * 保存当前数据对象
     * 关联 authRule模型 保存当前数据对象
     * @access public
     * @param array  $data     数据
     * @param array  $where    更新条件
     * @param string $sequence 自增序列名
     * @return integer|false
     */
    public function save($data = [], $where = [], $sequence = null)
    {

        if(parent::save($data, $where, $sequence)){
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
     * 写入数据
     * 关联 authRule模型 写入数据
     * @access public
     * @param array      $data  数据数组
     * @param array|true $field 允许字段
     * @return $this
     */
    public static function create($data = [], $field = null)
    {
        $auth = parent::create($data, $field);

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
            return $auth;
        }
        return false;
    }

    /**
     * 删除当前的记录
     * 关联 authRule模型 删除当前的记录
     * @access public
     * @return integer
     */
    public function delete(){
        if(parent::delete()){
            if($this->authRule){
                $this->authRule->authRuleDelete();
            }
            return true;
        }
        return false;
    }
}
?>