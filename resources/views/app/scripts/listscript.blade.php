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
                                    <div class="panel ">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <i class="ti-layout-grid3"></i>
                                                Liste des scripts
                                            </h3> 
                                        </div>
                                        <div class="panel-body">
                                                
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="scriptClosers">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Titre</th>
                                                        <th>Contenu</th> 
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php $order = 0 ?>
                                                @foreach ($scripts as $script) 
                                                <tr>
                                                    <td>{{$order = $order + 1}}</td>
                                                    <td>{{$script->title}}</td>  
                                                    <td>{!!str_limit(strip_tags($script->body) ,50)!!} </td> 
                                                    
                                                    <td>  
                                                        <a class="btn btn-icon btn-info m-r-50"
                                                        href="#show{{$script->id}}" 
                                                        data-target="#show{{$script->id}}" 
                                                        data-toggle="modal"  data-title="Voir">
                                                        <i class="ti-eye" aria-hidden="true"></i>
                                                        </a>  
                                                    </td> 
                                                </tr> 

                                                    <div class="modal fade animated" id="show{{$script->id}}" tabindex="-1" role="dialog" aria-labelledby="show{{$script->id}}" aria-hidden="true">
                                                        <div  class="modal-dialog modal-lg" style="width: 840px;">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                    <h4 class="modal-title custom_align" id="Heading5">Script N° / {{$order }}</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <form role="form"> 
                                                                                <div class="row">
                                                                                    <div class="form-group">
                                                                                        <label for="" class="col-md-3 control-label">Titre de Script :</label>
                                                                                        <div class="col-md-9">
                                                                                            <strong >{{$script->title }}</strong>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>  
                                                                                <div class="row">
                                                                                    <div class="form-group">
                                                                                        <label for="" class="col-md-12 control-label">Contenu  :</label>
                                                                                        <div class="col-md-12"> 
                                                                                            <div style="padding: 10px;margin: 15px;background: #eee;font-size: 14px;line-height: 24px;">
                                                                                            {!! $script->body !!}
                                                                                            </div>                                                                                               
                                                                                        </div>
                                                                                    </div>

                                                                                    
                                                                                </div>  
                                                                            </form>   
                                                                        </div>                                                                                    
                                                                    </div> 
                                                                </div>
                                                                <div class="modal-footer ">  
                                                                    <button type="button" class="btn btn-success" data-dismiss="modal">
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
                            <div class="background-overlay"></div>
                    </section>
                </div>
            </div>
        </div>
</section>


@endsection