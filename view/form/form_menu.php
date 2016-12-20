<?php
$status  = isset($info['status'])?$info['status']:'';
$type    = isset($info['type'])?$info['type']:'';
?>

<div class="col-sm-12">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th class="col-sm-2">上级</th>
            <th>
                <select class="form-control text" name="parent_id">
                    <option value="0">/</option>
                    <?php echo isset($info['selectCategorys'])?$info['selectCategorys']:'';?>
                </select>
            </th>
        </tr>
        <tr>
            <th>状态</th>
            <td>
                <input type="radio" name="status" <?php echo empty($status)|$status==1?'checked':''?> value="1" checked> 显示
                <input type="radio" name="status" <?php echo $status === 0?'checked':''?>   value="0"> 隐藏
            </td>
        </tr>
        <tr>
            <th>类型</th>
            <td>
                <input type="radio" name="type" <?php echo empty($type)|$type==1?'checked':''?> value="1" > 权限认证+菜单
                <input type="radio" name="type" <?php echo $type === 0?'checked':''?>  value="0"> 只作为菜单
            </td>
        </tr>
        <tr>
            <th>名称</th>
            <td>
                <input class="form-control text" type="text" name="name" value="<?php echo isset($info['name'])?$info['name']:'';?>">
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>应用</th>
            <td>
                <input class="form-control text" type="text" name="app" value="<?php echo isset($info['app'])?$info['app']:'';?>">
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>控制器</th>
            <td>
                <input class="form-control text" type="text" name="model" value="<?php echo isset($info['model'])?$info['model']:'';?>">
                <span class="form-required">*</span>

            </td>
        </tr>
        <tr>
            <th>方法</th>
            <td>
                <input class="form-control text" type="text" name="action" value="<?php echo isset($info['action'])?$info['action']:'';?>">
                <span class="form-required">*</span>
            </td>
        </tr>
        <tr>
            <th>参数</th>
            <td>
                <input class="form-control text" type="text" name="url_param" value="<?php echo isset($info['url_param'])?$info['url_param']:'';?>">

                <span class="span-text">例:id=3&amp;cid=3</span>
            </td>

        </tr>
        <tr>
            <th>验证规则</th>
            <td>
                <input class="form-control text" type="text" name="rule_param" value="<?php echo isset($info['rule_param'])?$info['rule_param']:'';?>">
                <span class="span-text">例:{id}==3 and {cid}==3</span>
            </td>

        </tr>
        <tr>
            <th>图标</th>
            <td>
                <input class="form-control text" type="text" name="icon" id="action" value="<?php echo isset($info['icon'])?$info['icon']:'';?>">
                <span class="span-text"><a href="http://www.thinkcmf.com/font/icons" target="_blank">选择图标</a> 不带前缀fa-，如fa-user => user</span>
            </td>
        </tr>

        <tr>
            <th>日志请求类型</th>
            <td>

                <select class="form-control text" name="request">
                    <option value="">关闭</option>
                    <?php
                        $type       = ['GET','POST','PUT','PUT','DELETE','Ajax'];
                        $request   = isset($info['request'])?$info['request']:'';
                        foreach($type as $v){
                            $selected = $request == $v ?'selected':'';
                            echo '<option '.$selected.' value="'.$v.'">'.$v.'</option>';
                        }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>日志请求类型</th>
            <td>
                <textarea name="log_rule" class="form-control" rows="3" ><?php echo isset($info['log_rule'])?$info['log_rule']:'';?></textarea>
            </td>
        </tr>
        <tr>
            <th>备注</th>
            <td>
                <textarea name="remark" class="form-control" rows="3" ><?php echo isset($info['remark'])?$info['remark']:'';?></textarea>
            </td>
        </tr>
        </tbody>
    </table>
</div>


<div class="form-actions col-sm-12">
    <button type="button" class="btn btn-primary ajax-post " autocomplete="off">
        保存
    </button>
    <a class="btn btn-default active" href="JavaScript:history.go(-1)">返回</a>
</div>