
<?php require $pach . 'public/top.php';?>

<ul class="nav nav-tabs">
    {if condition="checkPath('auth/role')"}
        <li><a href="<?php echo Url('auth/role')?>">角色管理</a></li>
    {/if}
    <li  class="active"><a href="<?php echo Url('auth/roleAdd')?>">增加角色</a></li>
</ul>
<div class="site-signup">
    <div class="row">
        <form class="form-horizontal" action="<?php echo Url('auth/roleAdd')?>" method="post" >
            <?php require $pach . 'form/form_role.php';?>
        </form>
    </div>
</div>


<?php require $pach . 'public/foot.php';?>