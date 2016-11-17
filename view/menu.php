<?php require $pach . 'public/top.php';?>
<ul class="nav nav-tabs">
    <li class="active"><a href="{:Url('auth/menu')}">后台菜单</a></li>
    <li><a href="{:Url('auth/menuAdd')}">增加菜单</a></li>
</ul>
<form class="js-ajax-form" action="{:Url('auth/menuOrder')}" method="post">

    <table class="table table-hover table-bordered table-list" id="menus-table">
        <thead>
        <tr>
            <th width="80">排序</th>
            <th width="50">ID</th>
            <th>菜单名称</th>
            <th>应用</th>
            <th>控制器</th>
            <th>方法</th>
            <th>日志请求</th>
            <th width="80">状态</th>
            <th width="180">操作</th>
        </tr>
        </thead>
        <tbody>
            <?php echo $info?>
        </tbody>
    </table>

</form>
<input type="hidden" value="{:Url('auth/menuOrder')}" class="listOrderUrl">
<?php require $pach . 'public/foot.php';?>