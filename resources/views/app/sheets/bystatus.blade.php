@extends('layouts.app')

@section('css')

 
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
                                                Liste des fiches avec status <strong>{{$status_name}}</strong>
                                            </h3>
                                            <span class="pull-right">
                                                    <i class="fa fa-fw ti-angle-up clickable"></i> 
                                            </span>
                                        </div>
                                        <div class="panel-body"> 
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="dataTableSheetsListTest">
                                                    <thead>
                                                        <tr>
                                                            <th hidden >id</th>   
                                                            <th >N°</th> 
                                                            <th >Créer le</th>
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
 
<script type="text/javascript">
  
  $( document ).ready(function() {
     
  
      $('#dataTableSheetsListTest').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getCloserSheetsByStatus', $status_id) }}',
            columns: [
                {data: 'id', name: 'id', visible:false }, 
                {data: 'order', name: 'order'}, 
                {data: 'created_at', name: 'created_at'}, 
                {data: 'distributed_at', name: 'distributed_at'},   
                { data:  'client_full_name'  , name: 'client_full_name'},
                {data: 'tel', name: 'tel'},
                {data: 'status', name: 'status'} ,  
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '215px'}
            ]
        });
    

    $('#dataTableSheetsListTest').on('click', '#openSendEmailToClient', function(){
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