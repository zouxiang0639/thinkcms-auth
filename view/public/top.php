<!DOCTYPE html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($navTabs['title'])?$navTabs['title']:'后台操作系统'?></title>
    <link   href="__PublicAdmin__/css/bootstrap.min.css" rel="stylesheet">
    <link href="__PublicAdmin__/css/site.css" rel="stylesheet">
    <script src="__PublicAdmin__/js/jquery.min.js"></script>
    <script src="__PublicAdmin__/js/bootstrap.min.js"></script>

</head>


<body style="min-width:790px;" >
<style>
    .alert{
        position: fixed !important;z-index: 1000;width: 98%;top: 2%;
    }
</style>

<div class="container-fluid">
    <div id="alert"></div>