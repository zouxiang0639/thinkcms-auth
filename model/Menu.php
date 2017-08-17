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

}
?>