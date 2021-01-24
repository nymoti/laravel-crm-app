<!DOCTYPE html>
<html lang="fr">
<head>


<meta charset="UTF-8">
    <title>
         {{ $activeHeader->title }}       
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="/uploads/admin/favicon.ico"/>
    
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
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css'>
    @yield( 'css' )
    <!--end of page level css--> 

</head>
<body class="skin-default">
<div class="preloader">
    <div class="loader_img"><img src="/uploads/admin/loader.gif" alt="loading..." height="64" width="64"></div>
</div>
<!-- header logo: style can be found in header-->
<header class="header">
    @include('partials.admin.topbar')
</header>
<div class="wrapper row-offcanvas row-offcanvas-left" style="height: 1268px;">
    <!-- Left side column. contains the logo and sidebar -->
   
    @include('partials.admin.sidebar')

    <aside class="right-side">
    <section class="content-header">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-md-offset-4 col-lg-offset-4">
                <div class="header-element">
                    <h3>{{ strtoupper($activeHeader->title) }}
                        <small>                           
                            @if (!empty($page_title))
                                {{$page_title}}
                            @endif
                        </small>
                    </h3>
                </div>
            </div> 
        </div>


    </section>



        
    <div class="row">
            <div class="col-md-4 col-md-offset-8">
                    @if (Session::has('message'))
                    <div class="alert alert-info error0">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                            style="margin-top: -12px;margin-right: -8px;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        <p>{{ Session::get('message') }}</p>
                    </div>
                    @endif
                    @if ($errors->count() > 0)
                        <div class="alert alert-danger error1" id="error1">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"
                                style="margin-top: -12px;margin-right: -8px;">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>

                    @endif
            </div>
        </div>
        
                <!-- @guest
                @else
                    @if(Auth::user()->hasRole('Agent'))
                        <h1>Agent </h1>
                    @elseif(Auth::user()->hasRole('Admin'))
                        <h1>Admin </h1>
                    @elseif(Auth::user()->hasRole('Closer'))
                        <h1>Closer </h1> 

                    @endif 
                @endguest -->
        @if(Auth::user()->active === 1)
            @yield('content')
        @else    
        <div class="row">
        <div class="col-md-12"> 
                <div class="row">                        
                    <div class="col-sm-6 col-md-6 col-sm-offset-3  col-md-offset-3">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    Compte désactivé
                                </h3>
                            </div>
                            <div class="panel-body">
                                <p>Votre compte est désactivé. Contactez votre admin pour le activé !</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
        @endif
        <!-- /.content --> 
    </aside>
    <!-- /.right-side --> 
</div>
<!-- ./wrapper -->


<div class="modal fade animated" id="addNewCategory" tabindex="-1" role="dialog" aria-labelledby="addNewCategory" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title custom_align" id="Heading5">Ajouter un libellé</h4>
            </div>
            <div class="modal-body"  >                                                             
                    {!! Form::open(array(
                        'style' => 'display: inline-block;',
                        'method' => 'POST',
                        'route' => 'addNewCategory')) !!} 
                             
                    <div class="row" style="width: 550px;">
                        <div class="col-md-12">                                                                              
                            <div class="form-group">
                                <label for="signature" class="col-sm-3 control-label" style="margin-top: 5px;text-align: right;">Titre  :</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-text"
                                            placeholder="Titre du libellé" name="title" minlength="2" required >
                                </div>
                            </div>
                        </div> 
                    </div>                                                          
                    <div style="margin-top: 20px;"> 
                        <button type="submit" class="btn btn-success pull-right">
                        <i class="ti-plus"></i>&nbsp;&nbsp;Sauvegarder
                    </button>
                    </div>
                    {!! Form::close() !!}
            </div>
            <div class="modal-footer ">  
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    Annuler
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- global js -->
<div id="qn"></div>
<script src="{{ asset('admin') }}/js/app.js" type="text/javascript"></script>
<!-- end of global js -->
<script src="{{ asset('admin') }}/vendors/moment/js/moment.min.js" type="text/javascript"></script>


<script src="{{ asset('admin') }}/vendors/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('admin') }}/js/custom_js/advanced_modals.js"></script>
<!-- begining of page level js -->
<script type="text/javascript" src="{{ asset('admin') }}/vendors/datatables/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/vendors/datatables/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/js/custom_js/datatables_custom.js"></script>
<!-- end of page level js --> 
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/bootstrap-datepicker/css/bootstrap-datepicker.css">
<script type="text/javascript" src="{{ asset('admin') }}/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
@yield('javascript')
 

<script src="{{ asset('admin') }}/js/toastr.min.js"></script>
{!! Toastr::message() !!}
<!-- end of page level js -->
<script> 
 
CKEDITOR.replace('textEditor');  
$(document).ready(function(){       
    $('.error0').delay(4000).slideUp(); 
    $('#error1').delay(4000).slideUp();  
 

    
});

 
</script> 
 
</body>

</html>