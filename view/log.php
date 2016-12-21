<?php require $pach . 'public/top.php';?>
<ul class="nav nav-tabs">
    <li class="active"><a href="{:Url('auth/log')}">日志列表</a></li>
</ul>
    <div>
        <div class="cf well form-search" style="height: 68px;">
            <div class="fl ">
                <div class="btn-group">
                    {if condition="checkPath('auth/clear')"}
                     <button type="button"  post-url="{:Url('auth/clear')}" class="btn ajax-post btn-success">清空</button>
                    {/if}
                </div>
            </div>
        </div>
        <table class="table table-hover table-bordered table-list" id="menus-table">
            <thead>
            <tr>
                <th width="100">ID</th>
                <th>标题</th>
                <th width="">用户</th>
                <th width="">执行地址</th>
                <th width="100">IP</th>
                <th width="150">执行时间</th>
                <th width="80">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($list as $v) {?>
                <tr>
                    <td>{$v.id}</td>
                    <td>{$v.title}</td>
                    <td>{$v.username}</td>
                    <td>{$v.log_url}</td>
                    <td>{$v.action_ip}</td>
                    <th>{:date('Y-m-d H:i:s',$v['create_time'])}</th>
                    <td>
                        {if condition="checkPath('auth/viewlog',['id'=>$v['id']])"}
                            <a href="{:url('auth/viewlog',['id'=>$v['id']])}">详细</a>
                        {/if}
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="text-center">
        {$page}
    </div>
<?php require $pach . 'public/foot.php';?>