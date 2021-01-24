@extends('layouts.app')

@section('css')
 
 
 @endsection

@section('content') 
<style>
 /* The container must be positioned relative: */
 .custom-select {
  position: relative;
  font-family: Arial;
}

.custom-select select {
  display: none; /*hide original SELECT element: */
}

.select-selected {
  background-color: DodgerBlue;
}

/* Style the arrow inside the select element: */
.select-selected:after {
  position: absolute;
  content: "";
  top: 14px;
  right: 10px;
  width: 0;
  height: 0;
  border: 6px solid transparent;
  border-color: #fff transparent transparent transparent;
}

/* Point the arrow upwards when the select box is open (active): */
.select-selected.select-arrow-active:after {
  border-color: transparent transparent #fff transparent;
  top: 7px;
}

/* style the items (options), including the selected item: */
.select-items div,.select-selected {
  color: #ffffff;
  padding: 8px 16px;
  border: 1px solid transparent;
  border-color: transparent transparent rgba(0, 0, 0, 0.1) transparent;
  cursor: pointer;
}

/* Style items (options): */
.select-items {
  position: absolute;
  background-color: DodgerBlue;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 99;
}

/* Hide the items when the select box is closed: */
.select-hide {
  display: none;
}

.select-items div:hover, .same-as-selected {
  background-color: rgba(0, 0, 0, 0.1);
} 

</style>


<input type="hidden" id="comingStatusId" value="{{ $statusId }}">
<input type="hidden" id="comingCreatedAt" value="{{ $created_at }}">
<section class="content">
    <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="row">   

                    <section class="content">
                            <div class="row">
                                <div class="col-lg-12"> 
                                    <h4>Liste des fiches créer par : <strong>{{strtoupper($agent_full_name)}}</strong></h4>
                                    <br>
                                    <div class="panel ">
                                          
                                        <div class="panel-heading">
                                            <h3 class="panel-title">
                                                <i class="ti-layout-grid3"></i>
                                                Liste des fiches
                                            </h3>
                                            <span class="pull-right">
                                                    <i class="fa fa-fw ti-angle-up clickable"></i>
                                                    {{-- <i class="fa fa-fw ti-close removepanel clickable"></i> --}}
                                            </span>
                                        </div>
                                        <div class="panel-body">    



                                        {!! Form::open(['method' => 'POST','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => ['getSheetsByAgentFilterPage', $agent_last_name,$agent_id] ]) !!}    
                                          <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />
                                            <input type="hidden" name="agent_last_name" value="{{ $agent_last_name }}" >
                                            <input type="hidden" name="agent_id" value="{{ $agent_id }}">
                                            <div class="row">
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <div class="col-md-9">
                                                        <select name="status_id"  class="form-control">
                                                            <option value="">::Status::</option> 
                                                            @foreach($all_status as $status)
                                                            <option value="{{ $status->id }}"
                                                            <?php if($statusId != 'x' && $status->id == $statusId)  echo 'selected="true"'?>
                                                            >{{ $status->name }}</option>
                                                            @endforeach
                                                          </select>
                                                      </div>
                                                  </div>
                                              </div>

                                              
                                              <div class="col-md-4 ">  
                                                <div class="form-group"  > 
                                                  <div class="input-group date" id="datepicker">
                                                    <input class="form-control" name="created_at" placeholder="La date de création" value="<?php if($created_at != 'x') echo $created_at ?>" /><span class="input-group-append input-group-addon"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
                                                  </div>
                                                </div>   
                                              </div>

                                              <div class="col-md-4"> 
                                                  <button  type="submit" class="btn btn-success"  style="margin-top: 0px" >
                                                    Filter 
                                                    <span class="ti-filter"></span>
                                                  </button>   
                                              </div> 

                                            </div>

                                        {!! Form::close() !!} 

                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="agentSheetsList">
                                                    <thead>
                                                        <tr>
                                                            <th hidden >id</th> 
                                                            <th  >N°</th> 
                                                            <th  >Créer par</th>
                                                            <th  >Créer le</th> 
                                                            <th  >Nom complet</th>
                                                            <th  >N° Tel</th> 
                                                            <th  >Status</th> 
                                                            <th  >Action</th>
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
  
<script> 
$( document ).ready(function() { 

    $("#datepicker").datetimepicker({ 
      format:'DD-MM-YYYY'
    }); 

    // agentSheetsList  

    // $(".content .row").find('input').iCheck({
    //     checkboxClass: 'icheckbox_square-blue',
    //     radioClass: 'iradio_square-blue',
    //     increaseArea: '20%' // optional
    // });

    // function turn_on_icheck(checkboxClass)
    // {
    //   $(".content .row").find('input').iCheck({
    //     checkboxClass: 'icheckbox_square-blue',
    //     radioClass: 'iradio_square-blue',
    //     increaseArea: '20%' // optional
    //     });
    // }
    var comingStatusId = $('#comingStatusId').val();
    var comingCreatedAt = $('#comingCreatedAt').val();
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
      });
    if(comingStatusId != 'x' || comingCreatedAt != ''){
      //filter                               

      $('#agentSheetsList').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{ route('getSheetsByAgentFilterData', [$statusId, $created_at, $agent_id]) }}',
          columns: [
              {data: 'id', name: 'id', visible:false }, 
              {data: 'order', name: 'order'}, 
              {               
                data: 'full_name' , name: 'full_name'
              }, 
              {data: 'created_at', name: 'created_at'}, 
              { data:  'client_full_name'  , name: 'client_full_name'},
              {data: 'tel', name: 'tel'},
              {data: 'status', name: 'status'} ,  
              {data: 'action', name: 'action', orderable: false, searchable: false, width: '220px'}
          ]
      });
    }else{
      // all data                                         
      $('#agentSheetsList').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{ route('getSheetsByAgentData', $agent_id ) }}',
          columns: [
              {data: 'id', name: 'id', visible:false }, 
              {data: 'order', name: 'order'}, 
              {               
                data: 'full_name' , name: 'full_name'
              }, 
              {data: 'created_at', name: 'created_at'}, 
              { data:  'client_full_name'  , name: 'client_full_name'},
              {data: 'tel', name: 'tel'},
              {data: 'status', name: 'status'} ,  
              {data: 'action', name: 'action', orderable: false, searchable: false, width: '220px'}
          ]
      });
    }   

    var table = $('#agentSheetsList').DataTable(); 
    
    // table.on('draw.dt', function () {
    //     turn_on_icheck();
    // });
    
  // $('.check').change(function(e){
  //     var id = $(this).attr("id");
  //     if(e.target.checked === false){
  //       console.log(id);
  //     }
  //     if ($('input#'+id+'').is(':checked')){
  //       console.log(id);
  //     }
  //     e.preventDefault();
  //   });
 
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


  $('#agentSheetsList').on('click', '#openDistributeSheet', function(){ 
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

  
  $('#agentSheetsList').on('click', '#openDeletesheet', function(){
    $('#md-delete-sheet-id').text($(this).attr('name')); 
    $('#md_delete_sheet_id').val($(this).attr('value'));
    $('#deleteSheetModal').modal('show');
  });

  $('#agentSheetsList').on('click', '#openSendEmailToClient', function(){
    var currow = $(this).closest('tr');
    var clientName = currow.find('td:eq(3)').text();
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
 
 @endsection