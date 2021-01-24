<!DOCTYPE html>
<html>

<head>
    <title>404 error|Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico"/>
    <!-- global level css -->
    <link href="{{ asset('admin') }}/css/bootstrap.min.css" rel="stylesheet">
    <!-- end of global css-->
    <!-- page level styles-->
    <link href="{{ asset('admin') }}/css/404.css" rel="stylesheet">
    <!-- end of page level styles-->
    <style>

    </style>
</head>

<body>
<div class="preloader">
    <div class="loader_img"><img src="/uploads/admin/loader.gif" alt="loading..." height="64" width="64"></div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
            <div class="text-center">
                <div>
                    <div class="error_img">
                        <img src="/uploads/admin/404.gif" alt="404 error image">
                    </div>
                    <hr class="seperator">
                    <a href="#" class="btn btn-primary link-home">Go Home</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- global js -->
<script src="{{ asset('admin') }}/js/jquery.min.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/js/bootstrap.min.js" type="text/javascript"></script>
<!-- end of global js -->
<script type="text/javascript">
    //=================Preloader===========//
    $(window).on('load', function () {
        $('.preloader img').fadeOut();
        $('.preloader').fadeOut();
    });
    //=================end of Preloader===========//
</script>
</body>

</html>

