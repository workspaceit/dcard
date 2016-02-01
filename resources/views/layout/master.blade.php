<!--
/**
 * Project  : dCard
 * File     : master.blade.php
 * Author   : Abu Bakar Siddique
 * Email    : absiddique.live@gmail.com
 * Date     : 9/2/15 - 1:03 PM
 */
 -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ @$title }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @section("css")

    @show

    <link rel="stylesheet" href="{{ url("bootstrap/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ url("plugins/jvectormap/jquery-jvectormap-1.2.2.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ url("dist/css/AdminLTE.min.css") }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ url("dist/css/skins/_all-skins.min.css") }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @section("header")
        @include("layout.topMenu")
    @show

    @section("sideBar")
        @include("layout.sideBar")
    @show

    <div class="content-wrapper">
        @yield("content")
    </div>
    @section("footer")
        @include("layout.footer")
    @show
</div>

@section("script-bottom")
    <script src="{{ url("plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ url("bootstrap/js/bootstrap.min.js") }}"></script>
    <!-- FastClick -->
    <script src="{{ url("plugins/fastclick/fastclick.min.js") }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ url("dist/js/app.min.js") }}"></script>
    <!-- Sparkline -->
    <script src="{{ url("plugins/sparkline/jquery.sparkline.min.js") }}"></script>
    <!-- jvectormap -->
    <script src="{{ url("plugins/jvectormap/jquery-jvectormap-1.2.2.min.js") }}"></script>
    <script src="{{ url("plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }}"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="{{ url("plugins/slimScroll/jquery.slimscroll.min.js") }}"></script>
    <!-- ChartJS 1.0.1 -->
    <script src="{{ url("plugins/chartjs/Chart.min.js") }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    {{--<script src="{{ url("dist/js/pages/dashboard2.js") }}"></script>--}}
    <!-- AdminLTE for demo purposes -->
    {{--<script src="{{ url("dist/js/demo.js") }}"></script>--}}

@show

@section("raw-script")

@show

</body>
</html>