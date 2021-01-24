<!DOCTYPE html>
<html>

<head>
    <title>:: Login ::</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/uploads/admin/favicon.ico"/>
    <!-- Bootstrap -->
    <link href="{{asset('admin')}}/css/bootstrap.min.css" rel="stylesheet">
    <!-- end of bootstrap -->
    <!--page level css -->
    <link type="text/css" href="{{asset('admin')}}/vendors/themify/css/themify-icons.css" rel="stylesheet"/>
    <link href="{{asset('admin')}}/vendors/iCheck/css/all.css" rel="stylesheet">
    <link href="{{asset('admin')}}/vendors/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet"/>
    <link href="{{asset('admin')}}/css/login.css" rel="stylesheet">
    <!--end page level css-->
</head>

<body id="sign-in">
<div class="preloader">
    <div class="loader_img"><img src="img/loader.gif" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    @yield('content')
</div>
<!-- global js -->
<script src="{{asset('admin')}}/js/jquery.min.js" type="text/javascript"></script>
<script src="{{asset('admin')}}/js/bootstrap.min.js" type="text/javascript"></script>
<!-- end of global js -->
<!-- page level js -->
<script type="text/javascript" src="{{asset('admin')}}/vendors/iCheck/js/icheck.js"></script>
<script src="{{asset('admin')}}/vendors/bootstrapvalidator/js/bootstrapValidator.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{asset('admin')}}/js/custom_js/login.js"></script>
<!-- end of page level js -->
</body>

</html>
