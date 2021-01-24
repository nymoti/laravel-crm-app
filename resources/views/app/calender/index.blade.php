@extends('layouts.app')

@section('css')

<link href="{{ asset('admin') }}/vendors/fullcalendar/css/fullcalendar.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin') }}/vendors/fullcalendar/css/fullcalendar.print.css" rel="stylesheet" media='print' type="text/css">

<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('admin') }}/css/calendar_custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/clockpicker/css/bootstrap-clockpicker.min.css">
@endsection
@section('content')
<style>
    [type="date"] {
  background:#fff url(https://cdn1.iconfinder.com/data/icons/cc_mono_icon_set/blacks/16x16/calendar_2.png)  97% 50% no-repeat ;
}
[type="date"]::-webkit-inner-spin-button {
  display: none;
}
[type="date"]::-webkit-calendar-picker-indicator {
  opacity: 0;
}
#start-dateD {
  border: 1px solid #c4c4c4; 
  background-color: #fff;
  padding: 3px 5px;
  box-shadow: inset 0 3px 6px rgba(0,0,0,0.1); 
}
.white-color{
    color: #fff;
    border-radius: 0px;
}    
.white-color option{
    font-size: 16px;
}
</style>
 

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12"> 
            <div class="pull-left">            
            <a href="#" class="btn btn-success btn-block" data-toggle="modal" data-target="#addNewRecall">
            <i class="fa ti-plus icon-align"></i> Nouveau 
            </a>
            
            <br>
            </div>
        </div> 
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div id="calendar"></div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addNewRecall" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="fa ti-plus icon-align"></i> Nouveau
                    </h4>
                </div>
                <div class="modal-body">
                    <form action="">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="full_name" id="full-name" class="form-control" placeholder="Nom et Prenom">
                        <div class="input-group-btn">
                            <button type="button" id="color-chooser-btn" class="btn btn-info dropdown-toggle"
                                    data-toggle="dropdown" style="margin-top: 0px;">
                                Selectionez un Coleur
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" id="color-chooser">
                                <li>
                                    <a class="palette-primary" href="#">Bleu</a>
                                </li>
                                <li>
                                    <a class="palette-success" href="#">Vert</a>
                                </li>
                                <li>
                                    <a class="palette-info" href="#">Bleu-ciel</a>
                                </li>
                                <li>
                                    <a class="palette-warning" href="#">Orange</a>
                                </li>
                                <li>
                                    <a class="palette-danger" href="#">Rouge</a>
                                </li>
                                <li>
                                    <a class="palette-default" href="#">Gris</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /btn-group -->
                         
                    </div>
                    <!-- /input-group -->
                    <input type="hidden" id="recall-color" name="recall-color"> 
                    <input type="hidden" name="user_id" id="user-id" value="{{Auth()->user()->id}}">
                    <br>
                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <div class="input-group m-t-10" data-placement="left" data-align="top"
                                    data-autoclose="true">
                                    <input type="text" name="address" id="address"
                                        placeholder="Adresse" class="form-control " >
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                </span>
                            </div> 
                        </div>
                        <div class="col-md-6"> 
                            <div class="input-group m-t-10" data-placement="left" data-align="top"
                                    data-autoclose="true">
                                    <input type="text" name="tel" id="tel"
                                        placeholder="Tel" class="form-control  " >
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-earphone"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row m-t-10">
                        <div class="col-md-6"> 
                            <div class="input-group"  > 
                                <div class="input-group date" id="datepicker">
                                <input type="date" class="form-control" name="date" id="start-date"  placeholder="Date" required /><span class="input-group-append input-group-addon"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                </div>
                            </div>  
                        </div>
                        <div class="col-md-6">   
                            <div class="input-group clockpicker" data-placement="left" data-align="top"
                                    data-autoclose="true">
                                <input type="text" class="form-control" name="hour" id="hour" placeholder="HH:MM">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div>   
                        </div>
                    </div>
                    <div class="row" style="margin-left: 10px;">  
                        <div class="input-group">
                            <h6 style="color:red;" id="dateMessage"></h6>
                        </div>
                    </div>

                    <div class="row ">                        
                        <div class="col-sm-12 col-md-12 m-t-10">
                            <textarea   class="form-control resize_vertical "
                                        placeholder="Observation " 
                                        name="description" 
                                        id="description" maxlength="500"></textarea> 
                        </div>
                    </div>
                    <br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-right" id="close_calendar_event"
                            data-dismiss="modal">
                        Fermer
                        <i class="fa ti-close icon-align"></i>
                    </button>
                    <button type="button" class="btn btn-success pull-left" id="add-new-recall"
                              >
                        <i class="fa ti-plus icon-align"></i> Ajouter
                    </button>
                </div>
            </div>
        </div>
    </div> 

    <!-- Modal -->
    <div class="modal fade" id="recallDetails" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">
                        <i class="fa ti-pencil icon-align"></i> Modifier 
                    </h4>
                </div>
                <div class="modal-body">
                    
                <form action="">
                    @csrf
                    <!-- /input-group -->
                    <input type="hidden" id="recall-colorD" name="recall-colorD" > 
                    <input type="hidden" id="recall-id" name="recall-id">  
                    <input type="hidden" name="user_idD" id="user-idD" value="{{Auth()->user()->id}}">
                    <input type="text" name="recallColor" id="recallColor" style="display: none" >
                    <div class="form-group col-md-8" style="margin-left: -15px;">
                        <input type="text" name="full_nameD" id="full-nameD" class="form-control" placeholder="Nom et Prenom" style="width: 400px;" >                         

                    </div>
                    <div class="form-group col-md-4 pull-right">
                        <select  class="form-control white-color" id="select_color" name="color"> 
                            <option  name="primaryColor" class="palette-primary" id="primaryColor" >Blue</option>  
                            <option  name="successColor" class="palette-success"  id="successColor"  >Vert</option>  
                            <option  name="infoColor" class="palette-info"  id="infoColor" >Bleu-ciel</option>  
                            <option  name="warningColor" class="palette-warning"  id="warningColor" >Orange</option>  
                            <option  name="dangerColor" class="palette-danger"  id="dangerColor" >Rouge</option>  
                            <option  name="defaultColor" class="palette-default"  id="defaultColor" >Gris</option>  
                        </select> 
                    </div>
                    <br>
                    <div class="row m-t-10">
                        <div class="col-md-6"> 
                            <div class="input-group m-t-10" data-placement="left" data-align="top"
                                    data-autoclose="true">
                                    <input type="text" name="addressD" id="addressD"
                                        placeholder="Adresse" class="form-control " >
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-map-marker"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6"> 
                            <div class="input-group m-t-10" data-placement="left" data-align="top"
                                    data-autoclose="true">
                                <input type="text" name="telD" id="telD"
                                        placeholder="Tel" class="form-control " >
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-earphone"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row m-t-10">
                        <div class="col-md-6">
                            <div class="input-group"  > 
                                <div class="input-group date" id="datepicker-2">
                                <input type="date" class="form-control" name="dateD" id="start-dateD"  placeholder="Date"  />
                                <span class="input-group-append input-group-addon">
                                    <span class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">  
                            <div class="input-group clockpicker" data-placement="left" data-align="top"
                                    data-autoclose="true">
                                <input type="text" class="form-control" name="hourD"  id="hourD" placeholder="HH:MM">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-time"></span>
                                </span>
                            </div> 
                        </div>                      
                                
                    </div>
                    <div class="row" style="margin-left: 10px;">  
                        <div class="input-group">
                            <h6 style="color:red;" id="dateMessageD"></h6>
                        </div>
                    </div>

                    <div class="row">                        
                        <div class="col-sm-12 col-md-12 m-t-10">
                            <textarea   class="form-control resize_vertical "
                                        placeholder="Observation " 
                                        name="descriptionD" 
                                        id="descriptionD" maxlength="500"></textarea> 
                        </div>
                    </div>
                    <br>
                    </form>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-right" id="close_calendar_event"
                            data-dismiss="modal">
                        Fermer
                        <i class="fa ti-close icon-align"></i>
                    </button>
                    <button type="button" class="btn btn-success pull-left" id="update-recall"
                             >
                        <i class="fa ti-pencil icon-align"></i> Modifier
                    </button>
                    <button type="button" class="btn btn-danger pull-left" id="delete-recall"
                            data-dismiss="modal">
                        <i class="fa ti-trash icon-align"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="background-overlay"></div>
</section>
<!-- /.content -->

@endsection
@section('javascript')

<script src="{{ asset('admin') }}/vendors/fullcalendar/js/fullcalendar.min.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js"></script> 
<script src="{{ asset('admin') }}/js/custom_js/calendar_custom.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/vendors/clockpicker/js/bootstrap-clockpicker.min.js" type="text/javascript"></script>

<script>
$(document).ready(function(){
// $('#select_color')
// var primaryColor = $('select[name=color]').val("1") ;
// console.log(primaryColor.css("background-color"));
 



// var ff = [];
// $.get('/get-recalls',function(recalls) {   
//     $.each(recalls, function(i, recall){ 
//         ff.push(recall);
//     })
// });
// console.log(ff); 
    var today = new Date();
    $("#datepicker").val(today);
    // $("#datepicker").datepicker({ 
    //   autoclose: true,
    //   format: 'yyyy-mm-dd', 
    //   todayHighlight: true
    // });
    // $("#datepicker-2").datepicker({ 
    //     autoclose: true,
    //     format: 'yyyy-mm-dd'
    // }); 




    
    //clock pickers and call back

    $('.clockpicker').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Done'
    });
    var input = $('.clockpicker-with-callbacks').clockpicker({
        donetext: 'Done',
        placement: "top",
        init: function () {
            console.log("colorpicker initiated");
        },
        beforeShow: function () {
            console.log("before show");
        },
        afterShow: function () {
            console.log("after show");
        },
        beforeHide: function () {
            console.log("before hide");
        },
        afterHide: function () {
            console.log("after hide");
        },
        beforeHourSelect: function () {
            console.log("before hour selected");
        },
        afterHourSelect: function () {
            console.log("after hour selected");
        },
        beforeDone: function () {
            console.log("before done");
        },
        afterDone: function () {
            console.log("after done");
        }
    });

    // Manually toggle to the minutes view
    $('#check-minutes').on('click', function (e) {
        // Have to stop propagation here
        e.stopPropagation();
        input.clockpicker('show')
            .clockpicker('toggleView', 'minutes');
    });

}); 
 </script>
@endsection
