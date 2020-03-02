<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>宿舍成绩 | OA</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="av@alexv525.com">
    <meta name="description" content="宿舍成绩后台管理">
    <meta name="keywords" content="宿舍成绩,集大,水院,自律会">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Chrome Theme Color -->
    <meta name="theme-color" content="#367fa9">
    <!-- Start Favicon Part -->
    <meta name="msapplication-TileImage" content="/favicon.ico">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/favicon.ico" sizes="192x192">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="/favicon.ico">
    <!-- End Favicon Part -->
    <!-- Start Public StyleSheet -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/admin/css/auth.css">
    <meta name="csrf_token" content="{{ csrf_token() }}">
</head>
<body class="hold-transition login-page" style="background-color:#616F77">
    <div class="login-box">
        <div class="login-logo">
            <img src="/assets/admin/images/logo.png" width="45">
            <a href="/auth/login"><b>宿舍成绩</b> | OA</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">登录以开始你的工作</p>
            <div class="form-group has-feedback">
                <input type="text" name="usernumber" class="form-control require" placeholder="学号">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" class="form-control require" placeholder="密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4 col-xs-offset-4">
                    <button id="login" class="btn btn-primary btn-block btn-flat">登录</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/admin/js/uaJudge.js"></script>
    <script src="/assets/admin/vendor/jQuery/jquery-3.2.1.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/admin/vendor/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/admin/js/login.js"></script>
</body>
</html>
