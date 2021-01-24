
    <meta charset="UTF-8">
    <title>
        Travels Books 
        @if(!empty($page_title))
        {{ $page_title }} 
        @endif        
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="img/favicon.ico"/>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link type="text/css" rel="stylesheet" href="{{ asset('admin') }}/css/app.css"/>

     <!-- end of global css -->
    
   <link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/datatables/css/dataTables.bootstrap.css"/> 
    <link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom.css">
    <link rel="stylesheet" href="{{ asset('admin') }}/css/custom_css/skins/skin-default.css" type="text/css" id="skin"/> 
   
    <link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom_css/datatables_custom.css">

    <!--page level css -->
    <link href="{{ asset('admin') }}/css/custom_css/dashboard1.css" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('admin') }}/css/custom_css/dashboard1_timeline.css" rel="stylesheet"/>
    <link href="{{ asset('admin') }}/css/toastr.min.css" rel="stylesheet"/>
 
    <link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/animate/animate.min.css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom_css/advanced_modals.css"/>
    <script src="{{ asset('admin') }}/js/ckeditor/ckeditor.js"></script> 
    @yield('css')
    <!--end of page level css--> 

    
 