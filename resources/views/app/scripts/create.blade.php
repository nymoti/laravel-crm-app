@extends('layouts.app')
 

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-fw ti-move"></i> 
                        Nouveau Script
                    </h3> 
                </div>
                <div class="panel-body">
                {!! Form::open(['method' => 'POST','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => 'scripts.store' ]) !!}    
                        {!! csrf_field() !!} 
                        <div class="row">
                            <div class="col-md-4 col-md-offset-3">
                                <div class="form-group" >
                                    <select name="type" class="form-control">
                                        <option  selected="true" disabled="disabled">:: Sélectionner le type ::</option> 
                                        <option value="Agent" >Agent </option>  
                                        <option value="Closer" >Closer </option>  
                                    </select>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-2 control-label">Titre :</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Titre de script" name="title" value="{{ old('title') }}">
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">
                                        Contenu :
                                    </label>
                                    <div class="col-sm-10 col-md-10">
                                        <textarea   class="form-control resize_vertical textEditor"
                                                    placeholder="Contenu" name="body" id="textEditor" > {{ old('body') }} </textarea> 
                                    </div>
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