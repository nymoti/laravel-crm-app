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


                                                
                                        {!! Form::open(['method' => 'POST','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => 'getCloserSheetsFilterPage' ]) !!}    
                                            <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" /> 
                                            <div class="row">
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <div class="col-md-9">
                                                        <select name="status_id"  class="form-control">
                                                            <option value="" <?php if($statusId === 'x')  echo 'selected="true"' ?>>::Status::</option>
                                                             
                                                            @foreach($all_status as $status)
                                                            <option value="{{ $status->id }}"
                                                            <?php if($statusId != null && $status->id == $statusId)  echo 'selected="true"'?>
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

                                              <div class="col-md-2 text-center">                                              
                                                <div class="form-group"  >   
                                                    <button  type="submit" class="btn btn-success"  style="margin-top: 0px" >
                                                      Filter 
                                                      <span class="ti-filter"></span>
                                                    </button>
                                                </div>
                                              </div>

                                            </div>


                                              {!! Form::close() !!} 



                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="closerSheetsList">
                                                    <thead>
                                                        <tr>
                                                            <th hidden >id</th> 
                                                            <th>N°</th>  
                                                            <th>Créer le</th> 
                                                            <th >Attribué le</th>
                                                            <th >Nom complet</th>
                                                            <th >N° Tel</th> 
                                                            <th >Status</th> 
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

@can('send-email')
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
                     <input type="hidden" name="sheet_id"   id="send_email_sheet_id" value=""> 
                        
                        <div class="alert alert-info">                                                                              
                            <span class="ti-info-alt" style="font-size: 34px;"></span>
                            &nbsp;<h5 class="text-center" style="margin-top: 5px;">Envoyer l'email de Confirmation a : <strong  id="semail_client_name"></strong></h5> 
                        </div>     
                        <div class="row">
                        <div class="col-md-12">                                                                              
                            <div class="form-group">
                                <label for="signature" class="col-sm-5 control-label" style="margin-top: 5px;">Ajouter votre signature  :</label>
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
                    Annuler
                </button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endcan 

@endsection
@section('javascript') 
  
<script> 
$( document ).ready(function() { 


  $("#datepicker").datetimepicker({ 
      format:'DD-MM-YYYY'
  }); 


  var comingStatusId = $('#comingStatusId').val();
  var comingCreatedAt = $('#comingCreatedAt').val();

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  

  
  if(comingStatusId != 'x' || comingCreatedAt != ''){
      //filter     
      $('#closerSheetsList').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{ route('getCloserSheetsFilterData',  [$statusId, $created_at]) }}',
          columns: [
              {data: 'id', name: 'id', visible:false }, 
              {data: 'order', name: 'order'},  
              {data: 'created_at', name: 'created_at'},    
              { data: 'distributed_at' , name: 'distributed_at' },
              { data:  'client_full_name'  , name: 'client_full_name'},
              {data: 'tel', name: 'tel'},  
                    {data: 'status', name: 'status'} ,  
              {data: 'action', name: 'action', orderable: false, searchable: false, width: '150px'}
          ]
      });                            
    }else{
      // all data  
      
      $('#closerSheetsList').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{ route('getCloserSheetsData') }}',
          columns: [
              {data: 'id', name: 'id', visible:false }, 
              {data: 'order', name: 'order'},  
              {data: 'created_at', name: 'created_at'},    
              { data: 'distributed_at' , name: 'distributed_at' },
              { data:  'client_full_name'  , name: 'client_full_name'},
              {data: 'tel', name: 'tel'},  
                    {data: 'status', name: 'status'} ,  
              {data: 'action', name: 'action', orderable: false, searchable: false, width: '150px'}
          ]
      });   
    }   



 
 
  $('#closerSheetsList').on('click', '#openSendEmailToClient', function(){
        var currow = $(this).closest('tr');
        var clientName = currow.find('td:eq(3)').text();
        console.log(currow);
        console.log(clientName);
        $('#semail_client_name').text(clientName); 
        $('#send_email_sheet_id').val($(this).attr('value')); 
        $('#sendEmailToClient').modal('show');
    });




}); 
 </script>
 
 @endsection