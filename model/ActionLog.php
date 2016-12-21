<?php
namespace thinkcms\auth\model;


class ActionLog extends \think\Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'action_log';

    //初始化属性
    protected function initialize()
    {

    }

    // 读取器 订单状态
    protected function getActionIpAttr($reg='',$data='')
    {
        return long2ip($data['action_ip']);
    }
}
?>