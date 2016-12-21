<?php require $pach . 'public/top.php';?>
<ul class="nav nav-tabs">

    <li class="active"><a href="{:url('auth/menu')}">后台菜单</a></li>
    {if condition="checkPath('auth/menuAdd')"}
    <li><a href="{:url('auth/menuAdd')}">增加菜单</a></li>
    {/if}
</ul>

<div class="cf well form-search" style="height: 68px;">
    <div class="fl ">
        <div class="btn-group">
            <button type="button"  post-url="{:url('auth/cache')}" class="btn ajax-post btn-success">清除日志缓存</button>
        </div>
    </div>
</div>
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


<input type="hidden" value="{:url('auth/menuOrder')}" class="listOrderUrl">
<?php require $pach . 'public/foot.php';?>