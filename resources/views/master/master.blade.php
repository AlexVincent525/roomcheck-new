<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>@yield('title') - 集大水院自律会</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="av@alexv525.com">
    <meta name="description" content="宿舍成绩后台管理">
    <meta name="keywords" content="宿舍成绩,集大,水院,自律会">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- Chrome Theme Color -->
    <meta name="theme-color" content="#367fa9">
    <!-- Start Favicon Part -->
    <meta name="msapplication-TileImage" content="/favicon.ico">
    <link rel="bookmark" href="favicon.ico">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="icon" href="/favicon.ico" sizes="32x32">
    <link rel="icon" href="/favicon.ico" sizes="192x192">
    <link rel="apple-touch-icon-precomposed" href="/favicon.ico">
    <!-- End Favicon Part -->
    <!-- Start Public StyleSheet -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/font-awesome/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/pace/pace.min.css">
    <link rel="stylesheet" href="/assets/admin/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/assets/admin/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/assets/admin/vendor/sweetalert2/sweetalert2.min.css">
    <!-- End Public StyleSheet -->
    <!-- Start Custom StyleSheet -->
    @yield('css')
    <!-- End Custom StyleSheet -->
    <link rel="stylesheet" href="/assets/admin/css/main.css">
        <!-- CSRF Token -->
    <meta name="csrf_token" content="{{ csrf_token() }}">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <a href="/admin.html" class="logo">
                <span class="logo-mini">
                    <img src="/favicon.ico" width="30">
                </span>
                <span class="logo-lg">
                    <b>宿舍成绩</b> 后台管理
                </span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <span class="navbar-broadcast">通知</span>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i>
                                <span class="hidden-xs username">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <i class="fa fa-user fa-5x"></i>
                                    <p class="username">{{ Auth::user()->name }}</p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="/pages/user/changeProfile.html" class="btn btn-default btn-flat" tabindex="0">更改信息</a>
                                    </div>
                                    <div class="pull-right">
                                        <a id="logout" class="btn btn-default btn-flat">注销</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu">
                    <li class="header">导览</li>
                    <li class="{{ active_class(if_route('home-page')) }}">
                        <a href="{{ route('home-page') }}">
                            <i class="fa fa-circle-o" aria-hidden="true"></i>
                            <span>总览</span>
                        </a>
                    </li>
                    <li class="{{ active_class(if_route('room-check-page')) }}">
                        <a href="{{ route('room-check-page') }}">
                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                            <span>宿舍检查</span>
                        </a>
                    </li>
                    <li class="{{ active_class(if_route(['create-check-page', 'distribute-check-page'])) }}">
                        <a href="#">
                            <i class="fa fa-check-square-o" aria-hidden="true"></i>
                            <span>检查管理</span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ active_class(if_route('create-check-page')) }}">
                                <a href="{{ route('create-check-page') }}">
                                    <i class="fa fa-bullhorn" aria-hidden="true"></i>
                                    <span>检查创建</span>
                                </a>
                            </li>
                            <li class="{{ active_class(if_route('distribute-check-page')) }}">
                                <a href="{{ route('distribute-check-page') }}">
                                    <i class="fa fa-calendar-plus-o" aria-hidden="true"></i>
                                    <span>检查分配</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ active_class(if_route(['room-management-page', 'building-management-page', 'item-management-page'])) }}">
                        <a href="#">
                            <i class="fa fa-building" aria-hidden="true"></i>
                            <span>基础管理</span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ active_class(if_route('room-management-page')) }}">
                                <a href="{{ route('room-management-page') }}">
                                    <i class="fa fa-home" aria-hidden="true"></i>
                                    <span>宿舍管理</span>
                                </a>
                            </li>
                            <li class="{{ active_class(if_route('building-management-page')) }}">
                                <a href={{ route('building-management-page') }}>
                                    <i class="fa fa-building-o" aria-hidden="true"></i>
                                    <span>楼栋管理</span>
                                </a>
                            </li>
                            <li class="{{ active_class(if_route('item-management-page')) }}">
                                <a href="{{ route('item-management-page') }}">
                                    <i class="fa fa-cubes" aria-hidden="true"></i>
                                    <span>项目管理</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-table" aria-hidden="true"></i>
                            <span>成绩管理</span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="/pages/result/resultReview.html">
                                    <i class="fa fa-balance-scale" aria-hidden="true"></i>
                                    <span>成绩审核</span>
                                </a>
                            </li>
                            <li>
                                <a href="/pages/result/resultOperate.html">
                                    <i class="fa fa-edit" aria-hidden="true"></i>
                                    <span>成绩处理</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ active_class(if_route(['members-management-page', 'vice-leader-management-page', 'change-profile-page'])) }}">
                        <a href="#">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>用户管理</span>
                        </a>
                        <ul class="treeview-menu">
                            <li class="{{ active_class(if_route('members-management-page')) }}">
                                <a href="{{ route('members-management-page') }}">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <span>干事管理</span>
                                </a>
                            </li>
                            <li class="{{ active_class(if_route('vice-leader-management-page')) }}">
                                <a href="{{ route('vice-leader-management-page') }}">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <span>副部管理</span>
                                </a>
                            </li>
                            <li class="{{ active_class(if_route('change-profile-page')) }}">
                                <a href="{{ route('change-profile-page') }}">
                                    <i class="fa fa-key" aria-hidden="true"></i>
                                    <span>信息修改</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/pages/sys/sysAction.html">
                            <i class="fa fa-gear" aria-hidden="true"></i>
                            <span>系统操作</span>
                        </a>
                    </li>
                </ul>
            </section>
        </aside>
        <div class="content-wrapper" id="main">
            @yield('content')
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 0.1.2 v20171029
            </div>
            <strong>
                Developed By
                <a href="https://www.hhyhhy.com/">Cal Huang</a>
                and
                <a href="https://blog.alexv525.com/">Alex Vicnent</a>
                . 2014-2017 All rights reserved.
            </strong>
        </footer>
        <aside class="control-sidebar control-sidebar-dark">
            <div class="tab-content">
                <div id="control-sidebar-theme-demo-options-tab" class="tab-pane active">
                    <h4 class="control-sidebar-heading">更改配色
                        <small>Change Your Skin.</small>
                    </h4>
                    <ul class="list-unstyled clearfix">
                        <li>
                            <a data-skin="skin-blue" class="clearfix full-opacity-hover">
                                <span style="background: #367fa9;"></span>
                                <span class="bg-light-blue"></span>
                                <span style="background: #222d32;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-black" class="clearfix full-opacity-hover">
                                <span style="background: #fefefe;"></span>
                                <span style="background: #fefefe;"></span>
                                <span style="background: #222;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-purple" class="clearfix full-opacity-hover">
                                <span class="bg-purple-active"></span>
                                <span class="bg-purple"></span>
                                <span style="background: #222d32;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-green" class="clearfix full-opacity-hover">
                                <span class="bg-green-active"></span>
                                <span class="bg-green"></span>
                                <span style="background: #222d32;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-red" class="clearfix full-opacity-hover">
                                <span class="bg-red-active"></span>
                                <span class="bg-red"></span>
                                <span style="background: #222d32;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-yellow" class="clearfix full-opacity-hover">
                                <span class="bg-yellow-active"></span>
                                <span class="bg-yellow"></span>
                                <span style="background: #222d32;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-blue-light" class="clearfix full-opacity-hover">
                                <span style="background: #367fa9;"></span>
                                <span class="bg-light-blue"></span>
                                <span style="background: #f9fafc;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-black-light" class="clearfix full-opacity-hover">
                                <span style="background: #fefefe;"></span>
                                <span style="background: #fefefe;"></span>
                                <span style="background: #f9fafc;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-purple-light" class="clearfix full-opacity-hover">
                                <span class="bg-purple-active"></span>
                                <span class="bg-purple"></span>
                                <span style="background: #f9fafc;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-green-light" class="clearfix full-opacity-hover">
                                <span class="bg-green-active"></span>
                                <span class="bg-green"></span>
                                <span style="background: #f9fafc;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-red-light" class="clearfix full-opacity-hover">
                                <span class="bg-red-active"></span>
                                <span class="bg-red" ></span>
                                <span style="background: #f9fafc;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                        <li>
                            <a data-skin="skin-yellow-light" class="clearfix full-opacity-hover">
                                <span class="bg-yellow-active"></span>
                                <span class="bg-yellow"></span>
                                <span style="background: #f9fafc;"></span>
                                <span style="background: #f4f5f7;"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- Start Public Script -->
    <script src="/assets/admin/js/uaJudge.js"></script>
    <script src="/assets/admin/vendor/jQuery/jquery-3.2.1.min.js"></script>
    <script src="/assets/admin/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/admin/vendor/pace/pace.min.js"></script>
    <script src="/assets/admin/vendor/sweetalert2/sweetalert2.js"></script>
    <script src="/assets/admin/js/app.js"></script>
    <script src="/assets/admin/js/common.js"></script>
    <!-- End Public Script -->

    <!-- Start Custom Script -->
    @yield('js')
    <!-- End Custom Script -->

</body>
</html>