@extends('layouts.app')
@section('css')
 
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/clockpicker/css/bootstrap-clockpicker.min.css">
 

@endsection
@section('content') 

<style>
/* Cachons la case à cocher */
[type="checkbox"]:not(:checked),
[type="checkbox"]:checked {
  position: absolute;
  left: -9999px;
}
 
/* on prépare le label */
[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label {
  position: relative; /* permet de positionner les pseudo-éléments */
  padding-left: 25px; /* fait un peu d'espace pour notre case à venir */
  cursor: pointer;    /* affiche un curseur adapté */
}
/* Aspect des checkboxes */
/* :before sert à créer la case à cocher */
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before {
  content: '';
  position: absolute;
  left:-8px ; top: -1px;
  width: 25px; height: 25px; /* dim. de la case */
  border: 1px solid #aaa;
  background: #f8f8f8;
  border-radius: 3px; /* angles arrondis */
  box-shadow: inset 0 1px 3px rgba(0,0,0,.3) /* légère ombre interne */
}
 
/* Aspect général de la coche */
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '\2713\0020';
  position: absolute;
  top: 0; left: -4px;
  font-size: 23px;
  color: #09ad7e;
  transition: all .2s; /* on prévoit une animation */
  font-weight: 900;
}
/* Aspect si "pas cochée" */
[type="checkbox"]:not(:checked) + label:after {
  opacity: 0; /* coche invisible */
  transform: scale(0); /* mise à l'échelle à 0 */
}
/* Aspect si "cochée" */
[type="checkbox"]:checked + label:after {
  opacity: 1; /* coche opaque */
  transform: scale(1); /* mise à l'échelle 1:1 */
}



</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                    <span class="ti-time"></span>
                        Contrôle d'accès
                    </h3>
                    <span class="pull-right">
                            <i class="fa fa-fw ti-angle-up clickable"></i> 
                        </span>
                </div>
                <div class="panel-body">
                {!! Form::open(['method' => 'PUT','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => 'updateControleAccess' ]) !!}    
                        {!! csrf_field() !!}
                        <input type="hidden" name="userloged_id" value="{{Auth()->user()->id}}">
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-6 control-label">Heur d'ouverture :</label>
                                    <div class="col-md-6">   
                                        <div class="input-group time-start" data-placement="left" data-align="top"
                                                data-autoclose="true">
                                            <input type="text" class="form-control" 
                                                name="time_start" id="hour" 
                                                placeholder="HH:MM" value="{{ $info->time_start }}" readonly>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>   
                                    </div>
                                </div> 
                            </div> 
                            <div class="col-md-6  ">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-6 control-label">Heur de fermeture :</label> 
                                    <div class="col-md-6">   
                                        <div class="input-group time-end" data-placement="left" data-align="top"
                                                data-autoclose="true">
                                            <input type="text" class="form-control" 
                                                name="time_end" id="hour" 
                                                placeholder="HH:MM" value="{{ $info->time_end }}" readonly>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-time"></span>
                                            </span>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                        </div>    
                        
                        <div class="row" style="margin-top: 30px;margin-bottom: 30px;">
                            <div class="col-md-6 col-md-offset-4">
                                <input type="hidden" name="weekend" value="0">

                                <div class="form-group"> 
                                <p>
                                    <input type="checkbox" name="weekend" id="weekend"  value="1"  <?php if($info->weekend != 0) echo 'checked'?> >
                                    <label for="weekend">Weekend</label>
                                </p>
                                </div>                                 
                            </div>
                        </div>
 
 
                        <button type="submit" class="btn btn-success"  style="float: right;" >
                            <span class="ti-save"></span> Sauvegarder
                        </button>   
                         
                    {!! Form::close() !!} 
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')
 
 
 
<script>
$(document).ready(function(){
    $('.time-start').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Done'
    });
    $('.time-end').clockpicker({
        placement: 'bottom',
        align: 'left',
        donetext: 'Done'
    });
}); 
</script>
<script src="{{ asset('admin') }}/vendors/clockpicker/js/bootstrap-clockpicker.min.js" type="text/javascript"></script>

@endsection