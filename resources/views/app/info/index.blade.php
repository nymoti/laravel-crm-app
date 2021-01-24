
@extends('layouts.app')

@section('css')
  
<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet"/>
<link href="{{ asset('admin') }}/vendors/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom.css">
<link rel="stylesheet" href="{{ asset('admin') }}/css/custom_css/skins/skin-default.css" type="text/css" id="skin"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/formelements.css">
@endsection

@section('content')
 
<style type="text/css">
    
[data-title] { 
  position: relative; 
}
[data-title]:hover::before {
  content: attr(data-title);
  position: absolute;
  top: -33px;
  left:-8px;
  display: inline-block;
  padding: 3px 6px;
  border-radius: 4px;
  background: #000;
  color: #fff;
  font-size: 12px;
  font-family: sans-serif;
  white-space: nowrap;
}
[data-title]:hover::after {
  content: '';
  position: absolute;
  top: -11px;
  left: 8px;
  display: inline-block;
  color: #fff;
  border: 8px solid transparent;  
  border-top: 8px solid #000;
}

</style>
<section class="content">

    <div class="row">                        
        <div class="col-md-4 pull-left"> 
            <a class="btn btn-icon btn-primary "
                href="#newHeader" 
                data-target="#newHeader" 
                data-toggle="modal" >
                <i class="ti-plus" aria-hidden="true"></i>
                Nouveau Compaign
            </a>
        </div>
    </div> 
    <br>
    <div class="row">
        <div class="col-md-12"> 
        
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                       <i class="fa fa-fw ti-move"></i> 
                       Liste Compaigns
                    </h3> 
                </div>
                <div class="panel-body" style="margin: 5px;">
                    <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTableSimple">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Compaign</th>
                                    <th>Logo</th> 
                                    <th>Email</th> 
                                    <th>Site</th> 
                                    <th>Tél</th> 
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $order = 0 ?>
                            @foreach ($headers as $header) 
                            <tr @if($header->set_default != 0) style="background-color: #66cc99;color: #fff;" @endif>
                                <td>{{ $order = $order+1 }}</td>
                                <td>{{$header->title}}</td>  
                                <td class="text-center">
                                <img src="/uploads/admin/{{ $header->logo }} " style="height: 60px;">   
                                </td> 
                                <td>{{ $header->email }} </td> 
                                <td>{{ $header->site }} </td> 
                                <td>{{ $header->tel }} </td>                                 
                                <td>  
                                    <a class="btn btn-icon btn-info m-r-50"
                                    href="#show{{$header->id}}" 
                                    data-target="#show{{$header->id}}" 
                                    data-toggle="modal" 
                                    data-title="Voir">
                                    <i class="ti-eye" aria-hidden="true"></i>
                                    </a>  
                                    <a href="{{ route('editHeader' , $header->id) }}" 
                                        class="btn btn-icon btn-warning m-r-50" 
                                        data-title="Modifier"
                                        >
                                        <i class="ti-pencil" aria-hidden="true"></i>
                                    </a>  
                                </td> 
                            </tr> 
                                <div class="modal fade animated" id="show{{$header->id}}" tabindex="-1" role="dialog" aria-labelledby="show{{$header->id}}" aria-hidden="true">
                                    <div  class="modal-dialog modal-lg" style="width: 1300px;">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title custom_align" id="Heading5">Compaign N° / {{$order }}</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row" style="margin-top: 20px;">

                                                    <div class="col-md-4 pull-left"> 
                                                        <div class="row">
                                                            <div class="form-group"> 
                                                                <div class="col-md-12">
                                                                    <img src="/uploads/admin/{{ $header->logo }}" style=" max-width: 250px;    max-height: 100px; margin-top: 3%;"> 
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                    <div class="col-md-4"> 
                                                        <h1 class="text-center" style="margin-top: -24px;">{{$header->title }}</h1>   
                                                    </div>  
 
                                                    <div class="col-md-4" style="left: 10%;">  
                                                        <h4  >Email : <strong >{{$header->email }}</strong> </h4>
                                                  
                                                        <h4  >Site : <strong  >{{$header->site }}</strong> </h4>
                                                   
                                                        <h4  >Tél : <strong   >{{$header->tel }}</strong> </h4>
                                                                
                                                    </div>  

                                                </div> 
                                            </div>
                                            <div class="modal-footer ">  
                                                <button type="button" class="btn btn-success" data-dismiss="modal">                                                    
                                                    <i class="ti-close"></i>
                                                    Fermer
                                                </button>
                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>


 

                                                                                
                            @endforeach  
                            </tbody>
                        </table>
                    </div>  
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>


<div class="modal fade animated" id="newHeader" tabindex="-1" role="dialog" aria-labelledby="newHeader" aria-hidden="true">
    <div  class="modal-dialog modal-lg" style="width: 840px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title custom_align" id="Heading5">Nouveau Compaign</h4>
            </div>
            <div class="modal-body">

                {!! Form::open(['method' => 'POST','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route'=> 'createHeader' ]) !!}    
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="userloged_id" value="{{Auth()->user()->id}}"> 
                        <div class="form-group">
                            <label for="title" class="col-sm-4 control-label">Titre :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="title"
                                        placeholder="Titre" name="title" >
                            </div>
                        </div> 
                        <div class="form-group">
                            <label for="email" class="col-sm-4 control-label">Email :</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control " id="email"
                                        placeholder="votre@email.com" name="email"  >
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">                                
                        <div class="form-group">
                            <label for="site" class="col-sm-4 control-label">Site Web:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control " id="site"
                                        placeholder="http://www.votre-site.com" name="site"    >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="tel" class="col-sm-4 control-label">Tel :</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control " id="tel"
                                        placeholder="N° Tel" name="tel" maxlength="25" ">
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="row" style="margin:5px">                    
                    <div class="col-md-8">
                        <div class="form-group"> 
                            <label for="">Image</label>
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
                    <div class="col-md-4" style="margin-top: 24px; padding-left: 60px;">                        
                        <label class="checkbox-inline icheckbox">
                            <input type="checkbox" id="set_default" name="set_default" value="1" > Par défaut
                        </label>
                    </div>
                </div> 
                <button type="submit" class="btn btn-success"  style="float: right;  margin-top: -24px;" >
                    <span class="ti-plus"></span> Ajouter
                </button>
                {!! Form::close() !!}
            </div>
            <div class="modal-footer ">  
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="ti-close"></i>
                    Fermer
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div> 



       <!--  <div class="col-md-6">
            <div class="form-group">
                <label for="input-text" class="col-sm-4 control-label">Logo:</label>
                <br> 
                <img src="/uploads/admin/{{ $info->logo }}" style=" max-width: 350px;    max-height: 350px;margin-left: 20%; margin-top: 3%;">
            </div> 
            <br>
            <label for="">Nouvelle image</label>
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
        </div> -->




@endsection 


@section('javascript')
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js"></script>
<script src="{{ asset('admin') }}/vendors/bootstrap-fileinput/js/fileinput.min.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/js/custom_js/form_elements.js"></script> 
<script>

$(document).ready(function(){
 
    $('#newHeader').on('shown.bs.modal', function () {
      $(".row").find('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    });
  
});
</script>
@endsection
