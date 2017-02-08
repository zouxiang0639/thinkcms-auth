<!DOCTYPE html>
<?php

    function get_file($file){
        $directory =  \think\Config::get('thinkcms.style_directory');

        if(empty($directory)){
            return url('auth/openFile',['file'=>$file]);
        }else{
            $file       = strtr($file, '_', '/');
            return $directory.$file;
        }

    }
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>后台操作系统</title>
    <link href="<?php echo get_file('css_bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo get_file('css_site.css')?>" rel="stylesheet">
    <script src="<?php echo get_file('js_jquery.min.js')?>"></script>
    <script src="<?php echo get_file('js_bootstrap.min.js')?>"></script>
</head>


<body style="min-width:790px;" >
<style>
    .alert{
        position: fixed !important;z-index: 1000;width: 98%;top: 2%;
    }
</style>

<div class="container-fluid">
    <div id="alert"></div>