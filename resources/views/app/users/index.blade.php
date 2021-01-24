@extends('layouts.app')
@section('css')
 
<link href="{{ asset('admin') }}/css/bootstrap_toggle/bootstrap-toggle.min.css" rel="stylesheet">

@endsection

@section('content')
<style>
  .toggle.ios, .toggle-on.ios, .toggle-off.ios { border-radius: 20px; }
  .toggle.ios .toggle-handle { border-radius: 20px; }


[data-title] { 
  position: relative; 
}
[data-title]:hover::before {
  content: attr(data-title);
  position: absolute;
  top: -33px;
  left:-25px;
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
                  <a href="{{route('users.create')}}" class="btn btn-info">
                      <span class="ti-plus"></span> Nouveau Utilisateur
                  </a><br>
                  
                  <div class="panel ">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="ti-layout-grid3"></i> Liste des utilisateurs
                            </h3>
                            <span class="pull-right">
                                    <i class="fa fa-fw ti-angle-up clickable"></i> 
                            </span>
                        </div>
                        <div class="panel-body">
                            <div class="bs-example">
                                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                    <li class="active">
                                        <a href="#users_admin" data-toggle="tab">Liste des admins</a>
                                    </li>
                                    <li>
                                        <a href="#users_agent" data-toggle="tab">Liste des agents</a>
                                    </li>
                                    <li>
                                        <a href="#users_closer" data-toggle="tab">Liste des closers</a>
                                    </li> 
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div class="tab-pane fade active in" id="users_admin">                                     
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTableUsersAdmin">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Nom et Prénom</th>
                                                        <th>Fixe TMK</th> 
                                                        <th>Rôle</th> 
                                                        <th>Etat</th> 
                                                        <th>Modifier l'état</th> 
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php $order = 0 ?>
                                                @foreach ($users_admin as $user) 
                                                <tr>
                                                    <td>{{ $order = $order+1 }}</td>
                                                    <td>{{$user->first_name .' ' . $user->last_name}}</td>  
                                                    <td>{{ $user->fixe_tmk }} </td> 
                                                    <td> 
                                                    @if(!empty($user->getRoleNames()))
                                                        @foreach($user->getRoleNames() as $role)
                                                        <div class="text-center">
                                                        <span class="label label-sm label-success">
                                                            {{ $role }}
                                                        </span>
                                                        </div>
                                                        @endforeach
                                                    @endif 
                                                    </td>
                                                    <td>    
                                                        <div class="text-center">  
                                                        @if($user->active === 1)
                                                            <span class="useretat-{{$user->id}} label label-sm label-success">
                                                            Active
                                                            </span>                                          
                                                        @else
                                                            <span class="useretat-{{$user->id}} label label-sm label-danger">
                                                            Non Active
                                                            </span>
                                                        @endif 
                                                        </div> 
                                                    </td> 

                                                    <td> 
                                                    <input type="checkbox" class="switch-{{$user->id}}"  id="{{$user->id}}"   
                                                    data-toggle="toggle" 
                                                    data-on="Active" 
                                                    data-off="Non Active" 
                                                    data-onstyle="success" 
                                                    data-offstyle="danger" 
                                                    data-style="ios"> 
                                                    <!-- <input type="checkbox" id="toggle-{{$user->id}}"> -->
                                                    </td>                                      
                                                    <td>  
                                                        <a class="btn btn-icon btn-info m-r-50"
                                                        href="#show{{$user->id}}" 
                                                        data-target="#show{{$user->id}}" 
                                                        data-toggle="modal"  data-title="Profile" >
                                                        <i class="ti-eye" aria-hidden="true"></i>
                                                        </a>  
                                                        <a href="{{ route('users.edit', $user['id'])}}" 
                                                            class="btn btn-icon btn-warning m-r-50"                                                            
                                                              data-title="Modifier" >
                                                            <i class="ti-pencil" aria-hidden="true"></i>
                                                        </a>  
                                                    </td> 
                                                </tr>
                                                <div class="modal fade animated" id="deleteuser{{$user['id']}}" tabindex="-1" role="dialog" aria-labelledby="deleteuser{{$user['id']}}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading5">Spprimer l'admin N°/ {{$order }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-info">
                                                                    <span class="glyphicon glyphicon-info-sign"></span>
                                                                    &nbsp;Êtes-vous sûr de vouloir
                                                                    supprimer cet utilisateur?
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer ">                                                               
                                                                    {!! Form::open(array(
                                                                        'style' => 'display: inline-block;',
                                                                        'method' => 'DELETE',
                                                                        'route' => ['users.destroy', $user->id])) !!}
                                                                    {!! Form::submit('Oui', array('class' => 'btn btn-danger','style'=>'margin-bottom: -10px;')) !!}
                                                                    {!! Form::close() !!}
                                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>   
                                                
                                                <div class="modal fade animated" id="show{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="show{{$user->id}}" aria-hidden="true">
                                                    <div  class="modal-dialog modal-lg" style="width: 745px;">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading5">Admin / {{$user->first_name .' ' .$user->last_name }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form role="form"> 
                                                                            <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label"  style="text-align: right;">Nom Complet :</label>
                                                                                    <div class="col-md-6" style="margin-left: -30px;">
                                                                                        <strong style="background: #eee;padding: 8px;">{{$user->first_name .' ' .$user->last_name }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label"  style="text-align: right;">Fixe TMK :</label>
                                                                                    <div class="col-md-6" style="margin-left: -30px;">
                                                                                        <strong style="background: #eee;padding: 8px;">{{$user->fixe_tmk }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </div>   

                                                                            <br><br>
                                                                            
                                                                            <div class="row"> 
                                                                            <div class="col-md-3  col-md-offset-3">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label" style="text-align: right;">Etat :</label>
                                                                                    <div class="col-md-6"  style="margin-left: -30px;">
                                                                                        @if($user->active === 1)
                                                                                        <span class="label label-sm label-success" style="font-size: 13px;">
                                                                                            Active
                                                                                        </span>
                                                                                        @else
                                                                                        <span class="label label-sm label-danger" style="font-size: 13px;">
                                                                                            Non Active
                                                                                        </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label" style="text-align: right;">Rôle :</label>
                                                                                    <div class="col-md-6"  style="margin-left: -30px;">
                                                                                        <span class="label label-sm label-success" style="font-size: 13px;">                                                                              
                                                                                        @if(!empty($user->getRoleNames()))
                                                                                            @foreach($user->getRoleNames() as $role) 
                                                                                                {{ $role }} 
                                                                                            @endforeach
                                                                                        @endif
                                                                                        </span>
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
                                    <div class="tab-pane fade" id="users_agent">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTableUsersAgent">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Nom et Prénom</th>
                                                        <th>Fixe TMK</th> 
                                                        <th>Rôle</th> 
                                                        <th>Etat</th> 
                                                        <th >Modifier l'état</th> 
                                                        <th style="width: 201px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                <?php $order_agent = 0 ?>
                                                @foreach ($users_agent as $user) 
                                                <tr>
                                                    <td>{{$order_agent = $order_agent + 1}}</td>
                                                    <td>{{$user->first_name .' ' . $user->last_name}}</td>  
                                                    <td>{{ $user->fixe_tmk }} </td> 
                                                    <td> 
                                                    @if(!empty($user->getRoleNames()))
                                                        @foreach($user->getRoleNames() as $role)
                                                        <div class="text-center">
                                                        <span class="label label-sm label-success">
                                                            {{ $role }}
                                                        </span>
                                                        </div>
                                                        @endforeach
                                                    @endif 
                                                    </td>
                                                    <td >    
                                                        <div class="text-center">  
                                                        @if($user->active === 1)
                                                            <span class="useretat-{{$user->id}} label label-sm label-success">
                                                            Active
                                                            </span>                                          
                                                        @else
                                                            <span class="useretat-{{$user->id}} label label-sm label-danger">
                                                            Non Active
                                                            </span>
                                                        @endif 
                                                        </div> 
                                                    </td> 

                                                    <td> 
                                                        <div class="text-center">
                                                            <input type="checkbox" class="switch-{{$user->id}}"  id="{{$user->id}}"   
                                                            data-toggle="toggle" 
                                                            data-on="Active" 
                                                            data-off="Non Active" 
                                                            data-onstyle="success" 
                                                            data-offstyle="danger" 
                                                            data-style="ios"> 
                                                        </div>
                                                    </td>                                      
                                                    <td>  
                                                        <a class="btn btn-icon btn-info m-r-50"
                                                        href="#show{{$user->id}}" 
                                                        data-target="#show{{$user->id}}" 
                                                        data-toggle="modal" data-title="Profile" >
                                                        <i class="ti-eye" aria-hidden="true"></i>
                                                        </a>  
                                                        <a href="{{ route('getSheetsByAgentPage', [$user->last_name, $user->id])}}" 
                                                            class="btn btn-icon btn-info m-r-50" data-title="Liste des fiches">
                                                            <i class="fa fa-fw fa-files-o"></i>
                                                        </a>    

                                                        <a href="{{ route('users.edit', $user['id'])}}" 
                                                        class="btn btn-icon btn-warning m-r-50" data-title="Modifier">
                                                            <i class="ti-pencil" aria-hidden="true"></i>
                                                        </a>  
                                                          <a class="btn btn-default btn-circle update" 
                                                              href="{{route('getAgentStatisticPage', $user->id)}}"     data-title="Statistiques"> 
                                                              <span class="ti-pie-chart"></span>  
                                                          </a> 
                                                    </td> 
                                                </tr>
                                                <div class="modal fade animated" id="deleteuser{{$user['id']}}" tabindex="-1" role="dialog" aria-labelledby="deleteuser{{$user['id']}}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading5">Spprimer l'agent N°/ {{$order_agent }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-info">
                                                                    <span class="glyphicon glyphicon-info-sign"></span>
                                                                    &nbsp;Êtes-vous sûr de vouloir
                                                                    supprimer cet utilisateur?
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer ">                                                               
                                                                    {!! Form::open(array(
                                                                        'style' => 'display: inline-block;',
                                                                        'method' => 'DELETE',
                                                                        'route' => ['users.destroy', $user->id])) !!}
                                                                    {!! Form::submit('Oui', array('class' => 'btn btn-danger','style'=>'margin-bottom: -10px;')) !!}
                                                                    {!! Form::close() !!}
                                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>   
                                                
                                                <div class="modal fade animated" id="show{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="show{{$user->id}}" aria-hidden="true">
                                                    <div  class="modal-dialog modal-lg" style="width: 840px;">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading5">Agent / {{$user->first_name .' ' .$user->last_name }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form role="form"> 
                                                                            <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label"  style="text-align: right;">Nom Complet :</label>
                                                                                    <div class="col-md-6"  style="margin-left: -30px;">
                                                                                        <strong style="background: #eee;padding: 8px;">{{$user->first_name .' ' .$user->last_name }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label"  style="text-align: right;">Fixe TMK :</label>
                                                                                    <div class="col-md-6"  style="margin-left: -30px;">
                                                                                        <strong style="background: #eee;padding: 8px;">{{$user->fixe_tmk }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </div>   

                                                                            <br><br>
                                                                            
                                                                            <div class="row"> 
                                                                            <div class="col-md-3 col-md-offset-3">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label" style="text-align: right;">Etat :</label>
                                                                                    <div class="col-md-6"  style="margin-left: -30px;">
                                                                                        @if($user->active === 1)
                                                                                        <span class="label label-sm label-success" style="font-size: 13px;">
                                                                                            Active
                                                                                        </span>
                                                                                        @else
                                                                                        <span class="label label-sm label-danger"  style="font-size: 13px;">
                                                                                            Non Active
                                                                                        </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label" style="text-align: right;">Rôle :</label>
                                                                                    <div class="col-md-6"  style="margin-left: -30px;">
                                                                                        <span class="label label-sm label-success"  style="font-size: 13px;">                                                                              
                                                                                        @if(!empty($user->getRoleNames()))
                                                                                            @foreach($user->getRoleNames() as $role) 
                                                                                                {{ $role }} 
                                                                                            @endforeach
                                                                                        @endif
                                                                                        </span>
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
                                    <div class="tab-pane fade" id="users_closer">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="dataTableUsersCloser">
                                                <thead>
                                                    <tr>
                                                        <th>N°</th>
                                                        <th>Nom et Prénom</th>
                                                        <th>Fixe TMK</th> 
                                                        <th>Rôle</th> 
                                                        <th>Etat</th> 
                                                        <th >Modifier l'état</th> 
                                                        <th  style="width: 180px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php $order_closer = 0 ?>
                                                @foreach ($users_closer as $user) 
                                                <tr>
                                                    <td>{{$order_closer = $order_closer + 1}}</td>
                                                    <td>{{$user->first_name .' ' . $user->last_name}}</td>  
                                                    <td>{{ $user->fixe_tmk }} </td> 
                                                    <td> 
                                                    @if(!empty($user->getRoleNames()))
                                                        @foreach($user->getRoleNames() as $role)
                                                        <div class="text-center">
                                                        <span class="label label-sm label-success">
                                                            {{ $role }}
                                                        </span>
                                                        </div>
                                                        @endforeach
                                                    @endif 
                                                    </td>
                                                    <td >    
                                                        <div class="text-center">  
                                                        @if($user->active === 1)
                                                            <span class="useretat-{{$user->id}} label label-sm label-success">
                                                            Active
                                                            </span>                                          
                                                        @else
                                                            <span class="useretat-{{$user->id}} label label-sm label-danger">
                                                            Non Active
                                                            </span>
                                                        @endif 
                                                        </div> 
                                                    </td> 

                                                    <td> 
                                                        <div class="text-center">
                                                            <input type="checkbox" class="switch-{{$user->id}}"  id="{{$user->id}}"   
                                                            data-toggle="toggle" 
                                                            data-on="Active" 
                                                            data-off="Non Active" 
                                                            data-onstyle="success" 
                                                            data-offstyle="danger" 
                                                            data-style="ios"> 
                                                        </div>
                                                    </td>                                      
                                                    <td>  
                                                        <a class="btn btn-icon btn-info m-r-50"
                                                        href="#show{{$user->id}}" 
                                                        data-target="#show{{$user->id}}" 
                                                        data-toggle="modal"  data-title="Profile">
                                                        <i class="ti-eye" aria-hidden="true"></i>
                                                        </a>  
                                                        <a href="{{ route('users.edit', $user['id'])}}" 
                                                        class="btn btn-icon btn-warning m-r-50"  data-title="Modifier">
                                                            <i class="ti-pencil" aria-hidden="true"></i>
                                                        </a> 
                                                          <a class="btn btn-default btn-circle update" 
                                                              href="{{route('getCloserStatisticPage', $user->id)}}"     data-title="Statistiques"> 
                                                              <span class="ti-pie-chart"></span>  
                                                          </a> 
                                                    </td> 
                                                </tr>
                                                <div class="modal fade animated" id="deleteuser{{$user['id']}}" tabindex="-1" role="dialog" aria-labelledby="deleteuser{{$user['id']}}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading5">Spprimer le closer N°/ {{$order_closer }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="alert alert-info">
                                                                    <span class="glyphicon glyphicon-info-sign"></span>
                                                                    &nbsp;Êtes-vous sûr de vouloir
                                                                    supprimer ce closer?
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer ">                                                               
                                                                    {!! Form::open(array(
                                                                        'style' => 'display: inline-block;',
                                                                        'method' => 'DELETE',
                                                                        'route' => ['users.destroy', $user->id])) !!}
                                                                    {!! Form::submit('Oui', array('class' => 'btn btn-danger','style'=>'margin-bottom: -10px;')) !!}
                                                                    {!! Form::close() !!}
                                                                <button type="button" class="btn btn-success" data-dismiss="modal">
                                                                    Non
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>   
                                                
                                                <div class="modal fade animated" id="show{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="show{{$user->id}}" aria-hidden="true">
                                                    <div  class="modal-dialog modal-lg" style="width: 840px;">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                <h4 class="modal-title custom_align" id="Heading5">Closer / {{$user->first_name .' ' .$user->last_name }}</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <form role="form"> 
                                                                            <div class="row">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label"  style="text-align: right;">Nom Complet :</label>
                                                                                    <div class="col-md-6"   style="margin-left: -30px;">
                                                                                        <strong style="background: #eee;padding: 8px;">{{$user->first_name .' ' .$user->last_name }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label"  style="text-align: right;">Fixe TMK :</label>
                                                                                    <div class="col-md-6"   style="margin-left: -30px;">
                                                                                        <strong style="background: #eee;padding: 8px;">{{$user->fixe_tmk }}</strong>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            </div>   

                                                                            <br><br>
                                                                            
                                                                            <div class="row"> 
                                                                            <div class="col-md-3  col-md-offset-3">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label" style="text-align: right;">Etat :</label>
                                                                                    <div class="col-md-6"   style="margin-left: -30px;">
                                                                                        @if($user->active === 1)
                                                                                        <span class="label label-sm label-success" style="font-size: 13px;" >
                                                                                            Active
                                                                                        </span>
                                                                                        @else
                                                                                        <span class="label label-sm label-danger" style="font-size: 13px;">
                                                                                            Non Active
                                                                                        </span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <label for="" class="col-md-6 control-label" style="text-align: right;">Rôle :</label>
                                                                                    <div class="col-md-6"   style="margin-left: -30px;">
                                                                                        <span class="label label-sm label-success" style="font-size: 13px;">                                                                              
                                                                                        @if(!empty($user->getRoleNames()))
                                                                                            @foreach($user->getRoleNames() as $role) 
                                                                                                {{ $role }} 
                                                                                            @endforeach
                                                                                        @endif
                                                                                        </span>
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
@section('javascript')
 
<!-- bootstrap toggle - switch button -->
<script src="{{ asset('admin') }}/js/bootstrap_toggle/bootstrap-toggle.min.js"></script>
 
<script>
$(document).ready(function(){

    $.get('/get-all-users',function(data) {   
        $.each(data.users, function(i, user){  
            var stateCheckbox = 'nothing' ;
            if(user.active === 1){
                $('.switch-'+ user.id+'').bootstrapToggle('on');
                // $('#toggle-'+ user.id+'').prop("checked", true).change(); 
            }else if(user.active === 0){
                $('.switch-'+ user.id+'').bootstrapToggle('off');
                // $('#toggle-'+ user.id+'').prop("checked", false).change(); 
            } 

            $('.switch-'+ user.id+'').change(function(e){                
                var id = $(this).attr("id"); 
                var active = 1 ;
                if(e.target.checked === false){
                    // deactivate  user  
                    active = 0    ;  
                } else {
                    // activate  user
                    active = 1;
                } 
                Command: toastr["warning"]("Veuillez attendre...", "Compte d'utilisateur")                
                $.ajax({
                    type:"GET",
                    url:"/deactivateuser",
                    data :  {
                        "id": id,
                        "active": active,
                    },
                    async: true,
                    success:function(data) {
                        setTimeout(function(){
                            if(active == 1){
                                Command: toastr["success"]("Le compte d'utilisateur a étè activé avec succès !", "Compte d'utilisateur");
                                $('.useretat-'+ user.id+'').removeClass("label-danger").addClass("label-success");
                            }else if (active == 0){
                                Command: toastr["success"]("Le compte d'utilisateur a étè désactivé avec succès !", "Compte d'utilisateur");
                                $('.useretat-'+ user.id+'').removeClass("label-success").addClass("label-danger");
                            } 
                            
                            setTimeout(function(){
                                location.reload();
                            },2000);
                        },3000);
                    }
                }); 
                toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "1000",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "swing",
                "showMethod": "show"
                }
            }); 
        })
    });

 
});
 

</script>
@endsection