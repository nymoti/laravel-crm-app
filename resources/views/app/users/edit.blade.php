
@extends('layouts.app')

@section('css')
  
<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet"/>
<link href="{{ asset('admin') }}/vendors/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom.css">
<link rel="stylesheet" href="{{ asset('admin') }}/css/custom_css/skins/skin-default.css" type="text/css" id="skin"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/formelements.css">
@endsection

@section('content')
 

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading"> 
                    <h3 class="panel-title">
                        <i class="ti-user"></i> 
                        Modifier l'ultilisateur : {{ $user->first_name .' ' . $user->last_name  }}
                    </h3>
                    <span class="pull-right">
                            <i class="fa fa-fw ti-angle-up clickable"></i> 
                        </span>
                </div>
                <div class="panel-body">
                {!! Form::open(['method' => 'PUT','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => ['users.update', $user->id] ]) !!}    
                        {!! csrf_field() !!}
                        <input type="hidden" name="userloged_id" value="{{Auth()->user()->id}}">
                        <div class="row">
                            <div class="col-md-6  col-md-offset-1">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label">Nom :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control lastName" id="input-text"
                                                placeholder="Nom" name="last_name" value="{{ $user->last_name }}">
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
                                                placeholder="Prénom" name="first_name"  value="{{ $user->first_name }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">                            
                            <div class="col-md-6  col-md-offset-1">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label">Fixe TMK :</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control fixeTmk" id="input-text"
                                                placeholder="Fixe TMK" name="fixe_tmk"  value="{{ $user->fixe_tmk }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="input-text" class="col-md-3 control-label">Mot de Pass :</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="password"
                                        placeholder="*****************" name="password">
                            </div>
                            <div class="col-md-4 pull-left"> 
                                <button type="button"    id="generatePass" class="btn btn-warning" style="margin-top: 0px; margin-left: -22px;" >
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label m-t-ng-8">Rôle :</label>                            
                                    <div class="col-sm-10">
                                        <div class="iradio">
                                            <label>
                                                <input type="radio" name="role" id="optionsRadios1"
                                                    value="Admin"  
                                                    <?php if($user->hasRole('Admin')) echo 'checked' ?>
                                                    > 
                                                Admin
                                            </label>
                                        </div>
                                        <div class="iradio">
                                            <label>
                                                <input type="radio" name="role" id="optionsRadios2"
                                                    value="Agent" <?php if($user->hasRole('Agent')) echo 'checked' ?> > 
                                                    Agent
                                            </label>
                                        </div>
                                        <div class="iradio">
                                            <label>
                                                <input type="radio" name="role" id="optionsRadios3"
                                                    value="Closer" <?php if($user->hasRole('Closer')) echo 'checked' ?> >
                                                     Closer
                                            </label>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label m-t-ng-8">Etat :</label>                            
                                    <div class="col-sm-10">
                                        <div class="iradio">
                                            <label>
                                                <input type="radio" name="user_etat" id="optionsRadios1"
                                                    value="1" <?php if($user->active === 1) echo 'checked' ?> > Active
                                            </label>
                                        </div>
                                        <div class="iradio">
                                            <label>
                                                <input type="radio" name="user_etat" id="optionsRadios2"
                                                    value="0"<?php if($user->active === 0) echo 'checked' ?> > Non Active
                                            </label>
                                        </div> 
                                    </div>
                                </div> 
                            </div>
                        </div> 
                        @if($user->hasRole('Closer')) 
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="form-group">                                    
                                <label class="checkbox-inline icheckbox">
                                    <input type="checkbox" id="allow-closer-to-send-email" name="allowCloserToSendEmail" 
                                    <?php if($user->hasPermissionTo('send-email')) echo 'checked'?>
                                
                                     > Envoyer Email de confirmation
                                </label>
                                </div>                                 
                            </div>
                        </div>
                        @endif 
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
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js"></script>
<script src="{{ asset('admin') }}/vendors/bootstrap-fileinput/js/fileinput.min.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/js/custom_js/form_elements.js"></script> 
<script>

$(document).ready(function(){ 
    $('.lastName'). on('keyup',function(e){        
        var fixeTmk = $('.fixeTmk');
        var firstFourChar = $(this).val().substring(0,4);
        // console.log(firstFourChar);
        // console.log(idGen.getId());
        var randomNumber = Math.random().toString(16).substr(2, 6);
        // console.log(randomNumber);
        fixeTmk.val(firstFourChar +'-'+ randomNumber); 
        e.preventDefault();
    });

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