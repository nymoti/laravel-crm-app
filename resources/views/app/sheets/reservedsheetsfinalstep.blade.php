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
 
<input type="hidden" id="comingReservedAt" value="{{ $reserved_at }}">
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
                                                Liste réservation validé
                                            </h3> 
                                        </div>
                                        <div class="panel-body"> 
                                          <div class="row"  style="border: 2px solid #eee;border-radius: 5px;  padding-top: 16px;margin-left: 0px;    margin-right: 0px;">                    
                                          <div class="col-md-9">                    
                                          {!! Form::open(['method' => 'POST','class'=>'form-horizontal form-filter','enctype'=>'multipart/form-data', 'route' => 'getReservedSheetsFinalStepFilterPage']) !!}    
                                            <input name="_token" type="hidden" id="_token" value="{{ csrf_token() }}" />  
                                            <div class="row">                                            
                                              <div class="col-md-4 pull-left"> 
                                                <div class="form-group" style="margin-left: 0px;"> 
                                                  <div class="input-group date" id="datepicker">
                                                    <input class="form-control" name="reserved_at"  id="reserved-at"   
                                                        placeholder="La date de réservation" 
                                                        value="<?php if($reserved_at != null) echo $reserved_at ?>" />
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
                                                    <span class="ti-settings"></span> Déplacer vers
                                                </a>
                                            </div>
                                          </div> 
                                            <br>

                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="sheetsFinalStepList">
                                                    <thead>
                                                        <tr>
                                                            <th   hidden >id</th>
                                                            <th>N°</th>  
                                                            <th>Réserver le</th>  
                                                            <th class="text-center">Nom complet</th>
                                                            <th>Nombre de PAX</th>
                                                            <th>Date d'arrivée</th>
                                                            <th>Date de départ</th> 
                                                            <th>Montant</th> 
                                                            <th  class="text-center">Multi selection</th>
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
                <h4 class="modal-title custom_align" id="Heading5">Attribué les fiches sélectionné a un libellé </h4>
            </div>
            <div class="modal-body">
              <div class="alert alert-info" style="height: 48px;  padding-top: 4px; ">                                                                              
                <span class="ti-info-alt" style="font-size: 33px;"></span>
                <h4 class="text-center" style="margin-top: -25px;">Vous avez sélectionnez <strong class="numberSheets"></strong>  fiches !</h4>                  
              </div> 
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">                                                                                                                                     
                    {!! Form::open(array(
                        'style' => 'display: inline-block; width: 470px;',
                        'method' => 'POST',
                        'route' => ['assignMultiSheetToCategory'])) !!} 
                        {!! csrf_field() !!}          
                    <input type="hidden" name="sheetsList" id="sheetsList" >  
                    <div class="form-group" >
                        <select name="category_id" class="form-control">
                            <option value="0">:: Sélectionner un libellé ::</option>
                            @foreach($categories as $category) 
                            <option value="{{$category->id}}" >{{ $category->title }}</option> 
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
                  <i class="ti-close"></i>&nbsp;
                    Annuler
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
                <h4 class="modal-title custom_align" id="Heading5">Attribué la fiche N°/ <strong id="md-sheet-id"></strong> a un libellé </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">                                                                                                                                     
                    {!! Form::open(array(
                        'style' => 'display: inline-block;width: 470px;',
                        'method' => 'POST',
                        'route' => ['assignSheetToCategory'])) !!} 
                        {!! csrf_field() !!}         
                    <input type="hidden" name="sheet_id"  id="md_sheet_id" value="">                                                                 
 
                    <div class="form-group" >
                        <select name="category_id" class="form-control" id="categoryId">
                            <option value="0">:: Sélectionner un libellé ::</option>
                            @foreach($categories as $category) 
                            <option value="{{$category->id}}" >{{ $category->title }}</option> 
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
                    <i class="ti-close"></i>&nbsp;
                    Annuler
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
 
    var comingReservedAt = $('#comingReservedAt').val(); 
 
     

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

    if(comingReservedAt != ''){
        $('#sheetsFinalStepList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getReservedSheetsFinalStepFilterData', $reserved_at ) }}',
            columns: [
                {data: 'id', name: 'id', visible:false }, 
                {data: 'order', name: 'order'}, 
                {data: 'reserved_at', name: 'reserved_at'}, 
                {data: 'client_full_name'  , name: 'client_full_name'},
                {data: 'nbr_pax', name: 'nbr_pax'},
                {data: 'date_arrived', name: 'date_arrived'} , 
                {data: 'date_departure', name: 'date_departure'} , 
                {data: 'amount', name: 'amount'} , 
                {data: 'multi_selection', name: 'multi_selection', orderable: false, searchable: false },
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '150px'}
            ]
        });
    }else{
        $('#sheetsFinalStepList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getReservedSheetsFinalStepData') }}',
            columns: [
                {data: 'id', name: 'id', visible:false }, 
                {data: 'order', name: 'order'}, 
                {data: 'reserved_at', name: 'reserved_at'},  
                {data: 'client_full_name'  , name: 'client_full_name'},
                {data: 'nbr_pax', name: 'nbr_pax'},
                {data: 'date_arrived', name: 'date_arrived'} , 
                {data: 'date_departure', name: 'date_departure'} ,
                {data: 'amount', name: 'amount'} ,  
                {data: 'multi_selection', name: 'multi_selection', orderable: false, searchable: false },
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '150px'}
            ]
        });
    }


    var table = $('#sheetsFinalStepList').DataTable();    
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
        Command: toastr["error"]("Sélectionnez au moin une fiche !", "Multi Déplacement")        
      }
      // alert("My favourite sports are: " + selectedSheets.join(", "));
      e.preventDefault();
  });




  $('#sheetsFinalStepList').on('click', '#openDistributeSheet', function(){ 
    $('#md-sheet-id').text($(this).attr('name')); 
    $('#md_sheet_id').val($(this).attr('value'));
    

    $.get("/check-sheet-caty/"+ $(this).attr('value'), function( data ) {
        console.log(data);
          
        $("#categoryId option[value=" + data + "]").prop("selected",true);
    });

    $('#distributeSheet').modal('show');
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