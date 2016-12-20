
<?php include $pach . 'public/top.php';?>
<ul class="nav nav-tabs">
    <li ><a href="<?php echo Url('auth/role')?>">角色管理</a></li>
    <li><a href="<?php echo Url('auth/roleAdd')?>">增加角色</a></li>
    <li class="active"><a href="">角色修改</a></li>
</ul>
<div class="site-signup">
    <div class="row">
        <form class="form-horizontal" action="<?php echo Url('auth/roleEdit',['id'=>$info['id']])?>" method="post" >
            <?php  include $pach.'form/form_role.php';?>
        </form>
    </div>
</div>
<?php include $pach . 'public/foot.php';?>