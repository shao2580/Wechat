<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">

    <title>微信公众号-@yield('title')</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <link href="{{url('wechat/css')}}/style.css?v=4.1.0" rel="stylesheet">

    <link rel="shortcut icon" href="favicon.ico"> <link href="{{url('wechat/css')}}/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="{{url('wechat/css')}}/font-awesome.min.css?v=4.4.0" rel="stylesheet">
    <link href="{{url('wechat/css')}}/animate.css" rel="stylesheet">
    <link href="{{url('wechat/css')}}/style.css?v=4.1.0" rel="stylesheet">

    <script src="{{url('wechat/js')}}/jquery-3.3.1.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<div style="margin-top:3%;" class="container">

 @yield('content')
 
</div>
 <!-- 全局js -->
    <script src="{{url('wechat/js')}}/jquery.min.js?v=2.1.4"></script>
    <script src="{{url('wechat/js')}}/bootstrap.min.js?v=3.3.6"></script>
    <script src="{{url('wechat/js')}}/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="{{url('wechat/js')}}/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="{{url('wechat/js')}}/plugins/layer/layer.min.js"></script>

    <!-- 自定义js -->
    <script src="{{url('wechat/js')}}/hAdmin.js?v=4.1.0"></script>
    <script type="text/javascript" src="{{url('wechat/js')}}/index.js"></script>

    <!-- 第三方插件 -->
    <script src="{{url('wechat/js')}}/plugins/pace/pace.min.js"></script>
<div style="text-align:center;">
<!-- <p>来源:<a href="http://www.mycodes.net/" target="_blank">源码之家</a></p> -->
</div>
</body>

</html>