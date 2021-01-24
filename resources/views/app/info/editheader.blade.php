
@extends('layouts.app')

@section('css')
  
<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet"/>
<link href="{{ asset('admin') }}/vendors/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
 
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/formelements.css"> 
@endsection

@section('content')
 
<style type="text/css">
    
#newDefaultHeaderBlock{
   display: none; 
}
.center {
    position: absolute;
    display: inline-block;
    margin-left: 41%;
    margin-top: 5%;
    transform: translate(-50%, -50%);
}

/** Custom Select **/
.custom-select-wrapper {
  position: relative;
  display: inline-block;
  user-select: none;
}
  .custom-select-wrapper select {
    display: none;
  }
  .custom-select {
    position: relative;
    display: inline-block;
  }
    .custom-select-trigger {
      position: relative;
      display: block;
      width: 345px;
      padding: 0 84px 0 22px;
      font-size: 16px;
      font-weight: 300;
      color: #fff;
      line-height: 30px;
      background: #5c9cd8;
      border-radius: 4px;
      cursor: pointer;
    }
      .custom-select-trigger:after {
        position: absolute;
        display: block;
        content: '';
        width: 10px; height: 10px;
        top: 50%; right: 25px;
        margin-top: -3px;
        border-bottom: 1px solid #fff;
        border-right: 1px solid #fff;
        transform: rotate(45deg) translateY(-50%);
        transition: all .4s ease-in-out;
        transform-origin: 50% 0;
      }
      .custom-select.opened .custom-select-trigger:after {
        margin-top: 3px;
        transform: rotate(-135deg) translateY(-50%);
      }
  .custom-options {
    position: absolute;
    display: block;
    top: 100%; left: 0; right: 0;
    min-width: 100%;
    margin: 15px 0;
    border: 1px solid #b5b5b5;
    border-radius: 4px;
    box-sizing: border-box;
    box-shadow: 0 2px 1px rgba(0,0,0,.07);
    background: #fff;
    transition: all .4s ease-in-out;
    
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transform: translateY(-15px);
  }
  .custom-select.opened .custom-options {
    opacity: 1;
    visibility: visible;
    pointer-events: all;
    transform: translateY(0);
  }
    .custom-options:before {
      position: absolute;
      display: block;
      content: '';
      bottom: 100%; right: 25px;
      width: 7px; height: 7px;
      margin-bottom: -4px;
      border-top: 1px solid #b5b5b5;
      border-left: 1px solid #b5b5b5;
      background: #fff;
      transform: rotate(45deg);
      transition: all .4s ease-in-out;
    }
    .option-hover:before {
      background: #f9f9f9;
    }
    .custom-option {
      position: relative;
      display: block;
      padding: 0 22px;
      border-bottom: 1px solid #b5b5b5;
      font-size: 14px;
      font-weight: 600;
      color: #565656;
      line-height: 35px;
      cursor: pointer;
      transition: all .4s ease-in-out;
    }
    .custom-option:first-of-type {
      border-radius: 4px 4px 0 0;
    }
    .custom-option:last-of-type {
      border-bottom: 0;
      border-radius: 0 0 4px 4px;
    }
    .custom-option:hover,
    .custom-option.selection {
      background: #f9f9f9;
    }
.hidden{
    display: none;
}
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                       <i class="fa fa-fw ti-move"></i> 
                       Modifier compaign :<strong>{{ $header->title }}</strong>
                    </h3> 
                </div>
                <div class="panel-body">
                {!! Form::open(['method' => 'PUT','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route'=> 'updateHeader' ]) !!}    
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="userloged_id" value="{{Auth()->user()->id}}"> 
                        <input type="hidden" name="headerId" value="{{$header->id}}"> 
                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Titre :</label>
                            <div class="col-sm-8" style="margin-bottom: 15px;">
                                <input type="text" class="form-control" id="title"
                                        placeholder="Titre" name="title" value="{{ $header->title }}">
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="email" class="col-sm-4 control-label">Email :</label>
                            <div class="col-sm-8"  style="margin-bottom: 15px;">
                                <input type="email" class="form-control " id="email"
                                        placeholder="votre@email.com" name="email"  value="{{ $header->email }}" >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">                                
                        <div class="form-group" >
                            <label for="site" class="col-sm-4 control-label">Site Web:</label>
                            <div class="col-sm-8"  style="margin-bottom: 15px;">
                                <input type="text" class="form-control " id="site"
                                        placeholder="http://www.votre-site.com" name="site"   value="{{ $header->site }}"  >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tel" class="col-sm-4 control-label">Tel :</label>
                            <div class="col-sm-8"  style="margin-bottom: 15px;">
                                <input type="text" class="form-control " id="tel"
                                        placeholder="N° Tel" name="tel" maxlength="25"  value="{{ $header->tel }}">
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="row" style="margin:5px">                    
                    <div class="col-md-7">                                                                                       
                        <div class="form-group">
                            <label for="input-text" class="col-sm-4 control-label">Logo:</label>
                            <br> 
                            <img src="/uploads/admin/{{ $header->logo }}" style=" max-width: 350px;    max-height: 350px; margin-top: 3%;">
                        </div> 
                        <br>
                        <div class="form-group"> 
                            <label for="">Nouvelle Image</label>
                            <div class="form-group">                        
                                <div class="col-md-12">
                                    <style>
                                        .fileinput-remove {
                                            margin-top: 0px
                                        }
                                    </style>
                                    <input id="input-42"  name="logo" type="file" class="file-loading">                                
                                </div>                                
                            </div>                                                                                   
                        </div> 
                    </div>
                    <div class="col-md-5" style="margin-top: 24px; padding-left: 60px;">                        
                        <label class="checkbox-inline icheckbox">
                            <input type="checkbox" id="setDefault" name="set_default" value="1"  <?php  if($header->set_default != 0) echo 'checked' ?>> Par défaut
                        </label> 
                        <br> 
                        <div class="center" id="newDefaultHeaderBlock">
                          <select name="newDefaultHeader" id="newDefaultHeader" class="custom-select newDefaultHeader" placeholder="Le nouveau par défaut entête"> 
                            <option value="0" class="hidden" >.........</option>
                            @foreach($other_headers as $header)
                            <option value="{{$header->id}}" >{{$header->title}}</option> 
                            @endforeach
                          </select>
                        </div>
                    </div>
                </div> 
                <button type="submit" class="btn btn-success"  style="float: right;  margin-top: -24px; ">
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
    $(".custom-select").each(function() {
      var classes = $(this).attr("class"),
          id      = $(this).attr("id"),
          name    = $(this).attr("name");
      var template =  '<div class="' + classes + '">';
          template += '<span class="custom-select-trigger">' + $(this).attr("placeholder") + '</span>';
          template += '<div class="custom-options">';
          $(this).find("option").each(function() {
            template += '<span class="custom-option ' + $(this).attr("class") + '" data-value="' + $(this).attr("value") + '">' + $(this).html() + '</span>';
          });
      template += '</div></div>';
      
      $(this).wrap('<div class="custom-select-wrapper"></div>');
      $(this).hide();
      $(this).after(template);
    });
    $(".custom-option:first-of-type").hover(function() {
      $(this).parents(".custom-options").addClass("option-hover");
    }, function() {
      $(this).parents(".custom-options").removeClass("option-hover");
    });
    $(".custom-select-trigger").on("click", function() {
      $('html').one('click',function() {
        $(".custom-select").removeClass("opened");
      });
      $(this).parents(".custom-select").toggleClass("opened");
      event.stopPropagation();
    });
    $(".custom-option").on("click", function() {
      $(this).parents(".custom-select-wrapper").find("select").val($(this).data("value"));
      $(this).parents(".custom-options").find(".custom-option").removeClass("selection");
      $(this).addClass("selection");
      $(this).parents(".custom-select").removeClass("opened");
      $(this).parents(".custom-select").find(".custom-select-trigger").text($(this).text());
    });
         
 
    // $('#setDefault').on('ifChecked', function(event){
    //   console.log('checked');
    // });
    // $('#setDefault').on('ifUnchecked', function(event){
    //   console.log('not checked');
    //   $("#newDefaultHeaderBlock").show();
    // });
    if($('input#setDefault').is(':checked')) {
        $('#setDefault').on('ifUnchecked', function(event){
          console.log('not checked');
          $("#newDefaultHeaderBlock").show();
        });
        $('#setDefault').on('ifChecked', function(event){
          console.log('checked');          
          $("#newDefaultHeaderBlock").hide();
        });
    }
});
</script>
@endsection
