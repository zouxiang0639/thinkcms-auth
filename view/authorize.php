<?php require $pach . 'public/top.php';?>
<link href="__PublicDefault__/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
<style>
    .checkmod{
        margin-bottom:20px;
        border: 1px solid #ebebeb;padding-bottom: 5px;
    }
    .checkmod dt{
        padding-left:10px;
        height:30px;
        line-height:30px;
        font-weight:bold;
        border-bottom: 1px solid #ebebeb;
        background-color:#ECECEC;
    }
    .checkmod dt{
        margin-bottom: 5px;
        border-bottom-color:#ebebeb;
        background-color:#ECECEC;
    }
    .checkbox , .radio{
        display:inline-block;
        height:20px;
        line-height:20px;
    }
    .checkmod dd{
        padding-left:10px;
        line-height:30px;
    }
    .checkmod dd .checkbox{
        margin:0 10px 0 0;
    }
    .checkmod dd .divsion{
        margin-right:20px;
    }
    .checkmod dt{
        line-height:30px;
        font-weight:bold;
    }

    .rule_check{border: 1px solid #ebebeb;margin: auto;padding: 5px 10px;}
    .menu_parent{margin-bottom: 5px;}

</style>

<div class="wrap js-check-wrap">
    <ul class="nav nav-tabs">
        <li><a href="<?php echo Url('auth/role')?>">角色管理</a></li>
        <li><a href="<?php echo Url('auth/roleAdd')?>">增加角色</a></li>
        <li class="active"><a href="">权限设置</a></li>
    </ul>

    <form class="form-horizontal"  action="{:Url('auth/authorize',['id'=>$info['id']])}" method="post">

        <div class="table_full">
            <table width="100%" cellspacing="0" id="dnd-example">
                <tbody>
                    <?php echo $info['html']?>
                </tbody>
            </table>
        </div>
        <div class="form-actions">

            <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
                保存
            </button>
            <a class="btn" href="JavaScript:history.go(-1)">返回</a>
        </div>
    </form>
</div>
<script>

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })

</script>

<script type="text/javascript">

    function checknode(obj) {

        var chk = $("input[type='checkbox']");
        var count = chk.length;
        var num = chk.index(obj);
        var level_top = level_bottom = chk.eq(num).attr('level');

        for (var i = num; i >= 0; i--) {
            var le = chk.eq(i).attr('level');
            if (eval(le) < eval(level_top)) {
                chk.eq(i).prop("checked",true);
                var level_top = level_top - 1;
            }
        }

        for (var j = num + 1; j < count; j++) {
            var le = chk.eq(j).attr('level');
            if (chk.eq(num).prop("checked")) {
                if (eval(le) > eval(level_bottom)){

                    chk.eq(j).prop("checked",true);
                }
                else if (eval(le) == eval(level_bottom)){
                    break;
                }
            } else {
                if (eval(le) > eval(level_bottom)){
                    chk.eq(j).prop("checked",false);
                }else if(eval(le) == eval(level_bottom)){
                    break;
                }
            }
        }
    }
</script>
<?php require $pach . 'public/foot.php';?>