@extends('layouts.app')

@section('css')
 
<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/formelements.css">
 
 @endsection

@section('content') 
<style> 
[data-title] { 
  position: relative; 
}
[data-title]:hover::before {
  content: attr(data-title);
  position: absolute;
  top: -33px;
  left:-5px;
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

<input type="hidden" id="comingCreatedAt" value="{{ $created_at }}">
<input type="hidden" id="comingDateType" value="{{ $date_type }}">
<section class="content">
    <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="row">   

                    <section class="content">
                            <div class="row">
                                <div class="col-lg-12"> 
                                    <br>
                                    <div class="panel ">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <i class="ti-layout-grid3"></i>
                                                Liste des fiches attribué
                                            </h3> 
                                        </div>
                                        <div class="panel-body"> 
                                          <div class="row"  style="border: 2px solid #eee;border-radius: 5px;  padding-top: 16px;margin-left: 0px;    margin-right: 0px;">                    
                                          <div class="col-md-9">                    
                                          {!! Form::open(['method' => 'POST','class'=>'form-horizontal form-filter','enctype'=>'multipart/form-data', 'route' => 'getDistributedSheetsFilterPage']) !!}    
                                            <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />  
                                            <div class="row">                                            
                                              <div class="col-md-3 pull-left" style="margin-left: 20px;">                                                                                                               
                                                <div class="form-group" >
                                                    <select name="date_type" id="date-type"  class="form-control">
                                                        <option value="" <?php if($date_type === '') echo 'selected="true"'?>  >:: Type ::</option> 
                                                        <option value="created_at" <?php if($date_type === 'created_at') echo 'selected="true"'?> >La date de création</option>  
                                                        <option value="distributed_at" <?php if($date_type === 'distributed_at') echo 'selected="true"'?> >La date d'attribution </option>  
                                                    </select>
                                                </div>  
                                            </div>                                          
                                              <div class="col-md-5"> 
                                                <div class="form-group" style="margin-left: 0px;"> 
                                                  <div class="input-group date" id="datepicker">
                                                    <input class="form-control" name="created_at"  id="created-at"   
                                                        placeholder="Sélectionnez le type de la date !" 
                                                        value="<?php if($created_at != null) echo $created_at ?>" />
                                                            <span class="input-group-append input-group-addon">
                                                                <span class="input-group-text">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                            </span>
                                                  </div>
                                                </div>   

                                              </div>

                                              <div class="col-md-2 text-center">                                              
                                                <div class="form-group"  >   
                                                    <button  type="submit"  id="filter-btn" class="btn btn-success"  style="margin-top: 0px" >                                                      
                                                      <span class="ti-filter"></span>&nbsp;Filtrer  
                                                    </button>
                                                </div>
                                              </div>

                                            </div>

                                            {!! Form::close() !!} 
                                            </div>
                                            <div class="col-md-3">
                                              <a href="#" class="btn btn-success pull-right" id="getCheckedBox">
                                                <i class="ti-settings" aria-hidden="true"></i>  Multi redistribution
                                              </a>
                                            </div>
                                          </div> 
                                            <br>

                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="distributedSheetsList">
                                                    <thead>
                                                        <tr>
                                                            <th   hidden >id</th>  
                                                            <th  >N°</th>  
                                                            <th >Créer le</th>
                                                            <th >Attribué le</th>
                                                            <th >Distribué a</th>
                                                            <th  class="text-center">Nom complet</th>
                                                            <th  >N° Tel</th>  
                                                            <th  >Status</th>   
                                                            <th >Multi selection</th> 
                                                            <th >Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>  
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



<div class="modal fade animated" id="multiDistributeSheets" tabindex="-1" role="dialog" aria-labelledby="multiDistributeSheets" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title custom_align" id="Heading5">Distribuer les fiches sélectionné a un closer </h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-info" style="height: 48px;  padding-top: 4px; ">                                                                              
                <span class="ti-info-alt" style="font-size: 33px;"></span>
                <h4 class="text-center" style="margin-top: -25px;">Vous avez sélectionnez <strong class="numberSheets"></strong>  fiches !</h4>                  
              </div> 
                <div class="row">
                    <div class="col-md-10 col-md-offset-1"  style="margin-top: 20px;">                                                                                                                                     
                    {!! Form::open(array(
                        'style' => 'display: inline-block; width: 470px;',
                        'method' => 'PUT',
                        'route' => ['multiDistributeToCloser'])) !!} 
                        {!! csrf_field() !!}          
                    <input type="hidden" name="sheetsList" id="sheetsList" >                                                                 
                    <div class="form-group" >
                        <select name="closer_id" class="form-control">
                            <option value="0">:: Sélectionner un Closer ::</option>
                            @foreach($users_closer as $closer) 
                            <option value="{{$closer->id}}" >{{ $closer->first_name .' ' . $closer->last_name }}</option> 
                            @endforeach
                        </select>
                    </div>
                    <br><br>  
                    <div class="pull-right"> 
                      {!! Form::submit('Confirmer', array('class' => 'btn btn-success','style'=>'margin-bottom: -10px;')) !!}           
                    </div>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer ">  
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                   <i class="ti-close"></i>&nbsp; Annuler
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade animated" id="distributeSheet" tabindex="-1" role="dialog" aria-labelledby="distributeSheet" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title custom_align" id="Heading5"><a id="h4-title" style="color: #333333;"></a> la fiche N°/ <strong id="md-sheet-id"></strong>  a un closer </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1" style="margin-top: 30px;">                                                                                                                                     
                    {!! Form::open(array(
                        'style' => 'display: inline-block;width: 470px;',
                        'method' => 'PUT',
                        'route' => ['distributeToCloser'])) !!} 
                        {!! csrf_field() !!}         
                        <input type="hidden" name="sheet_id"  id="md_sheet_id" value="">                                                                   
                    <div class="form-group"  >
                        <select class="form-control"  name="closer_id" id="md_closer_id">
                            <option value="0">:: Sélectionner un Closer ::</option>
                            @foreach($users_closer as $closer) 
                            <option value="{{$closer->id}}"  >{{ $closer->first_name .' ' . $closer->last_name }}</option> 
                            @endforeach
                        </select>
                    </div>
                    <br><br>  
                    <div class="pull-right"> 
                        {!! Form::submit('Confirmer', array('class' => 'btn btn-success','style'=>'margin-bottom: -10px;')) !!}
                    
                    </div>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer ">  
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="ti-close"></i>&nbsp;Annuler
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div> 


<div class="modal fade animated" id="deleteSheetModal" tabindex="-1" role="dialog" aria-labelledby="deleteSheetModal" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title custom_align" id="Heading5">Spprimer la fiche N°/ <strong id="md-delete-sheet-id"></strong></h4>
          </div>
          <div class="modal-body">
              <div class="alert alert-info">
                  <span class="glyphicon glyphicon-info-sign"></span>
                  &nbsp;Êtes-vous sûr de vouloir
                  supprimer cette fiche ?
              </div>
          </div>
          <div class="modal-footer ">                                                               
                  {!! Form::open(array(
                      'style' => 'display: inline-block;',
                      'method' => 'DELETE',
                      'route' => 'deleteSheet')) !!}
                  <input type="hidden" name="sheet_id" id="md_delete_sheet_id"   value="">
                    <button type="submit" class="btn btn-danger" >
                        <i class="ti-trash"></i>&nbsp;Oui
                    </button>
                  {!! Form::close() !!}
              <button type="button" class="btn btn-success" data-dismiss="modal">
                    <i class="ti-close"></i>&nbsp;Non
              </button>
          </div>
      </div>
      <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div> 



<div class="modal fade animated" id="sendEmailToClient" tabindex="-1" role="dialog" aria-labelledby="sendEmailToClient" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title custom_align" id="Heading5">Envoyer Email</h4>
          </div>
          <div class="modal-body" style="margin-bottom: 20px">                                                             
                  {!! Form::open(array(
                      'style' => 'display: inline-block; width: 100%;',
                      'method' => 'POST',
                      'route' => 'sendEmail')) !!}
                  <input type="hidden" name="sheet_id" id="send_email_sheet_id"  value="">  
                    
                    <div class="alert alert-info">                                                                              
                        <span class="ti-info-alt" style="font-size: 34px;"></span>
                        &nbsp;<h5 class="text-center" style="margin-top: 5px;">Envoyer l'email de Confirmation a : <strong id="semail_client_name"> </strong></h5> 
                    </div>     
                    <div class="row">
                      <div class="col-md-12">                                                                              
                        <div class="form-group">
                            <label for="signature" class="col-sm-5 control-label" style="margin-top: 5px;">Ajouter votre signature :</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="input-text"
                                        placeholder="Nom et Prenom" name="signature" minlength="4" required >
                                <br> 
                                <select name="profile"  class="form-control"  >
                                    <option value="-" >:: Votre Profile ::</option> 
                                    <option value="Mr" >Mr</option>  
                                    <option value="Mme" >Mme</option>  
                                    <option value="Mr/Mme" >Mr/Mme</option>  
                                  </select>
                            </div>
                        </div>                                                                          
                        <div class="form-group" >
                            <label for="signature" class="col-sm-5 control-label" style="margin-top: 15px;">Destination :</label>
                            <div class="col-sm-7" style="margin-top:15px;">
                                <textarea class="form-control" name="destination" id=""  placeholder="Destination" cols="30" rows="5"></textarea> 
                                <br> 
                            </div> 
                        </div>
                      </div> 
                    </div>          
                    <br>                                                          
                  <div style="margin-bottom: 20px;"> 
                    <button type="submit" class="btn btn-success pull-right">
                    <i class="ti-email"></i>&nbsp;&nbsp;Envoyer
                  </button>
                  </div>
                  {!! Form::close() !!}
          </div>
          <div class="modal-footer ">  
              <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="ti-close"></i>&nbsp;Annuler
              </button>
          </div>
      </div>
      <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>  
 

@endsection
@section('javascript') 
<script type="text/javascript">

$( document ).ready(function() { 
  
    $("#datepicker").datetimepicker({ 
        format:'DD-MM-YYYY'
    }); 

    var comingDateType = $('#comingDateType').val() ;
    var comingCreatedAt = $('#comingCreatedAt').val();
    var dateTypeValue = '' ;

    $('#date-type').on('change', function() {
        // distributed_at
        if(this.value === 'created_at'){
            $('#created-at').attr("placeholder","La date de création");
            dateTypeValue = 'created_at';
        } else if(this.value === 'distributed_at') {
            $('#created-at').attr("placeholder","La date d'attribution");
            dateTypeValue = 'distributed_at';
        } else {
            $('#created-at').attr("placeholder","Sélectionnez le type de la date !"); 
            dateTypeValue = null ;
        }
        console.log(dateTypeValue);
    });

    
    $('#filter-btn').click(function(e){ 
        var typeDate = $('#date-type').val();
        var createdAtValue = $('#created-at').val();
        if(typeDate === '' ){            
            Command: toastr["error"]("Sélectionnez le type de la date !", "Filter") 
        }else if(dateTypeValue != '' && createdAtValue === '') {
            Command: toastr["error"]("Veuillez entrer la date !", "Filter") 
        } else {
            $('.form-filter').submit();
        }
        e.preventDefault();
    }); 


    function turn_on_icheck(checkboxClass)
    {
      $(".content .row").find('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });
    }




    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    if(comingCreatedAt != ''){
        $('#distributedSheetsList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getDistributedSheetsFilterData',[ $date_type ,$created_at]) }}',
            columns: [
                {data: 'id', name: 'id', visible:false }, 
                {data: 'order', name: 'order'}, 
                {data: 'created_at', name: 'created_at'}, 
                { data: 'distributed_at' , name: 'distributed_at' },
                {data: 'full_name' , name: 'full_name' }, 
                {data:  'client_full_name'  , name: 'client_full_name'},
                {data: 'tel', name: 'tel'},
                {data: 'status', name: 'status'} , 
                {data: 'multi_selection', name: 'multi_selection', orderable: false, searchable: false },
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '225px'}
            ]
        });
    }else{
        $('#distributedSheetsList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getDistributedSheetsData') }}',
            columns: [
                {data: 'id', name: 'id', visible:false }, 
                {data: 'order', name: 'order'}, 
                {data: 'created_at', name: 'created_at'}, 
                { data: 'distributed_at' , name: 'distributed_at' },
                { data: 'full_name' , name: 'full_name' }, 
                { data:  'client_full_name'  , name: 'client_full_name'},
                {data: 'tel', name: 'tel'},
                {data: 'status', name: 'status'} , 
                {data: 'multi_selection', name: 'multi_selection', orderable: false, searchable: false },
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '225px'}
            ]
        });
    }


    var table = $('#distributedSheetsList').DataTable();    
    table.on('draw.dt', function () {
        turn_on_icheck();
    }); 





 
  $('#getCheckedBox').click(function(e){
     var selectedSheets = [];
      $.each($("input[name='check']:checked"), function(){            
          selectedSheets.push($(this).val());
      });

      $('.numberSheets').text(selectedSheets.length);
      $('#sheetsList').val(selectedSheets.join(","));
      if(selectedSheets.length != 0){
        $('#multiDistributeSheets').modal('show');
      }else{
        Command: toastr["error"]("Sélectionnez au moin une fiche !", "Multi Distribution")        
      }

      // alert("My favourite sports are: " + selectedSheets.join(", "));
      e.preventDefault();
  });




  $('#distributedSheetsList').on('click', '#openDistributeSheet', function(){ 
    $('#md-sheet-id').text($(this).attr('name')); 
    $('#md_sheet_id').val($(this).attr('value'));
    var closerId = $(this).attr('title'); 
    console.log(closerId);
    if(closerId != 0){      
      $('#h4-title').text('Redistribuer');
      // $('#md_closer_id').val($(this).attr('title'));
      $("#md_closer_id").val(closerId);
      // $("#md_closer_id option[value="+ closerId +"]").attr('selected', 'selected');
    }else{      
      $("#md_closer_id").val(closerId);
      $('#h4-title').text('Distribuer');
      // $("#md_closer_id option[value=0]").attr('selected', 'selected');
    }
    $('#distributeSheet').modal('show');
  }); 

  
  $('#distributedSheetsList').on('click', '#openDeletesheet', function(){
    $('#md-delete-sheet-id').text($(this).attr('name')); 
    $('#md_delete_sheet_id').val($(this).attr('value'));
    $('#deleteSheetModal').modal('show');
  });

  $('#distributedSheetsList').on('click', '#openSendEmailToClient', function(){
    var currow = $(this).closest('tr');
    var clientName = currow.find('td:eq(4)').text();
    console.log(currow);
    console.log(clientName);
    $('#semail_client_name').text(clientName); 
    $('#send_email_sheet_id').val($(this).attr('value')); 
    $('#sendEmailToClient').modal('show');
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
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "swing",
    "showMethod": "show"
    }
});

</script>
  <script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js" type="text/javascript"></script> 
 
 
 @endsection