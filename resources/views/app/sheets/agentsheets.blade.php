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
<section class="content">
    <div class="row">
            <div class="col-lg-12 col-xs-12">
                <div class="row">   

                    <section class="content">
                            <div class="row">
                                <div class="col-lg-12"> 
                                    <a href="{{route('sheets.create')}}" class="btn btn-info">
                                      <span class="ti-plus"></span> Nouveau Fiche  
                                    </a>
                                    <h3 class="pull-right" style="margin-top: 0px;">{{ $count_sheets }} @if($count_sheets != 1)Fiches @else Fiche @endif/ {{date('d-m-Y')}}</h3> 
 
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


@endsection
@section('javascript') 
  
<script> 
$( document ).ready(function() { 


  
  $('#agentSheetsList').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('getAgentsSheetsData') }}',
      columns: [
          {data: 'id', name: 'id', visible:false }, 
          {data: 'order', name: 'order'}, 
          {data: 'full_name' , name: 'full_name' }, 
          {data: 'created_at', name: 'created_at'},    
          { data:  'client_full_name'  , name: 'client_full_name'},
          {data: 'tel', name: 'tel'},  
          {data: 'action', name: 'action', orderable: false, searchable: false, width: '100px'}
      ]
  });   
 

}); 
 </script>
 
 @endsection