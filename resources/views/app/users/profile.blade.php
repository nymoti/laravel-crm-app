@extends('layouts.app')

@section('content') 
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="ti-user"></i> 
                        Modifier votre profile
                    </h3>
                    <span class="pull-right">
                            <i class="fa fa-fw ti-angle-up clickable"></i> 
                        </span>
                </div>
                <div class="panel-body">
                {!! Form::open(['method' => 'PUT','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => ['updateProfile', Auth()->user()->id] ]) !!}    
                        {!! csrf_field() !!}
                        <input type="hidden" name="userloged_id" value="{{Auth()->user()->id}}">
                        <div class="row">
                            <div class="col-md-6  col-md-offset-1">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label">Nom :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control lastName" id="input-text"
                                                placeholder="Nom" name="last_name" value="{{ Auth()->user()->last_name }}">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6  col-md-offset-1">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label">Prénom :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Prénom" name="first_name"  value="{{ Auth()->user()->first_name }}">
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="row">                            
                            <div class="col-md-6 col-md-offset-1">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label">Fixe TMK :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control fixeTmk" id="input-text"
                                                placeholder="Fixe TMK" name="fixe_tmk"  value="{{ Auth()->user()->fixe_tmk }}">
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="input-text" class="col-md-3 control-label">Mot de pass :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="password"
                                        placeholder="*****************" name="password">
                            </div>
                            <div class="col-md-4 pull-left"> 
                                <button type="button"    id="generatePass" 
                                class="btn btn-warning" 
                                data-toggle="tooltip" 
                                data-tooltip="tooltip" 
                                data-placement="top"
                                data-original-title="Génerer"
                                style="margin-top: 0px; margin-left: -22px;" >
                                    <i class="fa fa-fw fa-refresh"></i>
                                </button>  
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-text" class="col-md-3 control-label">Confirmer le mot de pass :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="confirm-password"
                                        placeholder="*****************" name="confirm-password">
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
     function randomPassword(length) {
        var chars = "abcdefghijklmnopqrstuvwxyz@ABCDEFGHIJKLMNOP1234567890";
        var pass = "";
        for (var x = 0; x < length; x++) {
            var i = Math.floor(Math.random() * chars.length);
            pass += chars.charAt(i);
        }
        return pass;
    }
    $('#generatePass').click(function(e){
        // console.log(randomPassword(10));
        e.preventDefault();
        $('#password').val(randomPassword(10)) ; 
        $('#confirm-password').val($('#password').val()) ; 
    });



});
</script>
@endsection