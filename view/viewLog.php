<?php require $pach . 'public/top.php';?>
<ul class="nav nav-tabs">
    <li><a href="{:Url('auth/log')}">日志列表</a></li>
    <li class="active"><a href="">日志详情</a></li>
</ul>

<div class="bs-example">

    <table class="table table-bordered">

        <tbody>
        <tr>
            <th>标题</th>
            <th>
                {$info.title}
            </th>
        </tr>
        <tr>
            <th>执行地址</th>
            <th>
                <a href="{$info.log_url}">{$info.log_url}</a>
            </th>
        </tr>
        <tr>
            <th width="150">执行者</th>
            <td>
                {$info.username}

            </td>
        </tr>
        <tr>
            <th width="150">执行IP</th>
            <td>
                {$info.action_ip}
            </td>
        </tr>

        <tr>
            <th>执行时间</th>
            <td>
                {:date('Y-m-d H:i:s',$info['create_time'])}
            </td>
        </tr>

        <tr>
            <th colspan="2" style="text-align: center">日志详情</th>

        </tr>
        <tr>
            <td colspan="2">
                {$info.log}
            </td>

        </tr>


        </tbody>
    </table>
</div>


<div class="form-actions col-sm-12">

    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>

</div>


<?php require $pach . 'public/foot.php';?>