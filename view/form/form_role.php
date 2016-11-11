

<div class="form-group ">
    <label class="col-lg-2 control-label" for="signupform-username">角色名称</label>
    <div class="col-lg-3">
        <input type="text" class="form-control" value="<?php echo isset($info['name'])?$info['name']:''?>" name="name" >
    </div>
</div>

<div class="form-group">
    <label class="col-lg-2 control-label">角色描述</label>
    <div class="col-lg-3">
        <textarea name="remark" class="form-control" rows="3"><?php echo isset($info['remark'])?$info['remark']:''?></textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-lg-2 control-label">状态 </label>
    <div class="col-lg-3">
        <?php
        $status = isset($info['status'])?$info['status']:'';
        ?>
        <label class="radio-inline">
            <input type="radio" <?php echo empty($status)|$status==1?'checked':''?> name="status" value="1"> 开启
        </label>
        <label class="radio-inline">
            <input type="radio" <?php echo $status === 0?'checked':''?> name="status" value="0"> 禁用
        </label>
    </div>
</div>

<div class="form-actions">
    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
        保存
    </button>
    <a class="btn btn-default active" onclick="history.go(-1)">返回</a>
</div>