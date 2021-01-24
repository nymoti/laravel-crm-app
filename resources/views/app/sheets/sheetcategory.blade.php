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
                                                Liste des fiches : {{ $category->title  }}
                                            </h3> 
                                        </div>
                                        <div class="panel-body">  
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
  
@endsection
@section('javascript') 
<script type="text/javascript">

$( document ).ready(function() { 
   

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
 
    $('#sheetsFinalStepList').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getSheetCategoryPageData', $category->id ) }}',
            columns: [
                {data: 'id', name: 'id', visible:false }, 
                {data: 'order', name: 'order'}, 
                {data: 'reserved_at', name: 'reserved_at'}, 
                {data: 'client_full_name'  , name: 'client_full_name'},
                {data: 'nbr_pax', name: 'nbr_pax'},
                {data: 'date_arrived', name: 'date_arrived'} , 
                {data: 'date_departure', name: 'date_departure'} , 
                {data: 'amount', name: 'amount'} ,  
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '100px'}
            ]
    });

 

 
});

</script>
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js" type="text/javascript"></script> 
 
 
 @endsection