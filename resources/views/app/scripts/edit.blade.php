@extends('layouts.app')


@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="fa fa-fw ti-move"></i> 
                                Modfier Script N°: {{ $script->id }}
                        </h3> 
                    </div>
                    <div class="panel-body">
                    {!! Form::open(['method' => 'PUT','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => ['scripts.update', $script->id] ]) !!}    
                            {!! csrf_field() !!}
                            <input type="hidden" name="agent_id" value="{{Auth()->user()->id}}">
                            <div class="form-group">
                                <label for="input-text" class="col-sm-2 control-label">Titre :</label>
                                <div class="col-sm-10">
                                    <input type="text" 
                                        class="form-control" 
                                        id="input-text"
                                        placeholder="Titre de script" 
                                        name="title"
                                        value="{{ $script->title }}" >
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    Contenu :
                                </label>
                                <div class="col-sm-10 col-md-10">
                                    <textarea   
                                        class="form-control resize_vertical textEditor"
                                        placeholder="Contenu" 
                                        name="body" 
                                        id="textEditor" >
                                        {{ $script->body }}
                                    </textarea> 
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