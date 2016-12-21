
<?php require $pach . 'public/top.php';?>
<ul class="nav nav-tabs">
    <li><a href="{:url('auth/menu')}">后台菜单</a></li>
    <li><a href="{:Url('auth/menuAdd')}">增加菜单</a></li>
    <li class="active"><a href="">编辑菜单</a></li>
</ul>

<form  class="form-horizontal" action="{:url('auth/menuEdit',['id'=>$info['id']])}" method="post">
    <?php require $pach . 'form/form_menu.php';?>
</form>
<?php require $pach . 'public/foot.php';?>