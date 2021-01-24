@extends('layouts.app')


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
            <div class="col-lg-12 col-xs-12">
                <div class="row">   

                    <section class="content">
                            <div class="row">
                                <div class="col-lg-12"> 
                                    <a  href="#addNewCategory" class="btn btn-info" data-sfid="addNewCategory" data-toggle="modal" >
                                        <span class="ti-plus"></span> Nouveau libellé
                                    </a>
                                    <div class="panel ">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <i class="ti-layout-grid3"></i>
                                                Liste des libellés
                                            </h3> 
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="dataTableSimple">
                                                    <thead>
                                                        <tr>
                                                            <th>N°</th>
                                                            <th>Titre</th>
                                                            <th>Nombre des fiches</th> 
                                                            <th style="width: 100px">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $order = 0 ?>
                                                    @foreach ($categories as $category) 
                                                    <tr>
                                                        <td>{{$order = $order + 1}}</td>
                                                        <td>{{$category->title}}</td>  
                                                        <td>
                                                        {{count($category->sheets)}}  @if(count($category->sheets)>1)Fiches @else Fiche @endif 
                                                        </td> 
                                                        
                                                        <td>     
                                                            <a class="btn btn-warning btn-circle update" 
                                                                href="#edit-{{ $category->id }}" 
                                                                data-sfid="edit-{{ $category->id }}" 
                                                                data-toggle="modal"
                                                                 data-title="Modifier"
                                                                 >
                                                                <i class="ti-pencil" aria-hidden="true"></i>
                                                            </a>

                                                            <a class="btn btn-danger btn-circle update" 
                                                                href="#deletescript{{$category->id}}" 
                                                                data-sfid="deletescript{{$category->id}}" 
                                                                data-toggle="modal"
                                                                 data-title="Supprimer"
                                                                 >
                                                                <i class="ti-trash" aria-hidden="true"></i>
                                                            </a> 
                                                        </td> 
                                                    </tr>
                                                    <div class="modal fade animated" id="deletescript{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="deletescript{{$category->id}}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                    <h4 class="modal-title custom_align" id="Heading5">Spprimer libellé N°/ {{$order }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="alert alert-info">
                                                                        <span class="glyphicon glyphicon-info-sign"></span>
                                                                        &nbsp;Êtes-vous sûr de vouloir
                                                                        supprimer ce libellé ?
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer "> 
                                                                                
                                                                        {!! Form::open(array(
                                                                            'style' => 'display: inline-block;',
                                                                            'method' => 'DELETE',
                                                                            'route' => ['deleteCategory', $category->id])) !!}
                                                                            
                                                                            <button type="submit" class="btn btn-danger"   >
                                                                                <i class="ti-trash"></i>
                                                                                Oui
                                                                            </button>
                                                                        
                                                                        {!! Form::close() !!}
                                                                    <button type="button" class="btn btn-success" data-dismiss="modal">
                                                                        <i class="ti-close"></i>
                                                                        Non
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>

                                                    
                                                    <div class="modal fade animated" id="edit-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-{{ $category->id }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                    <h4 class="modal-title custom_align" id="Heading5">Modifier le libellé : {{ $category->title }} </h4>
                                                                </div>
                                                                <div class="modal-body"  >                                                             
                                                                        {!! Form::open(array(
                                                                            'style' => 'display: inline-block;',
                                                                            'method' => 'PUT',
                                                                            'route' => 'editCategory')) !!} 
                                                                        <input type="hidden" name="category_id" value="{{ $category->id }}">       
                                                                        <div class="row" style="width: 550px; margin-bottom: 30px;">
                                                                            <div class="col-md-12">                                                                              
                                                                                <div class="form-group">
                                                                                    <label for="signature" class="col-sm-3 control-label" style="margin-top: 5px;text-align: right;">Titre  :</label>
                                                                                    <div class="col-sm-9">
                                                                                        <input type="text" class="form-control" id="input-text"
                                                                                                placeholder="Titre du libellé" name="title" minlength="2" value="{{ $category->title }}"  required  >
                                                                                    </div>
                                                                                </div>
                                                                            </div> 
                                                                        </div>                                                          
                                                                        <div style="margin-top: 20px;margin-bottom: 70px;"> 
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


  
                                                                                                        
                                                    @endforeach  
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                
                                </div> 
                            </div>  
                            <div class="background-overlay"></div>
                    </section>
                </div>
            </div>
        </div>
</section>

@endsection