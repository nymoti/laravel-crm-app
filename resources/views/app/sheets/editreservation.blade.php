@extends('layouts.app')
@section('css')

<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/gridforms/css/gridforms.css"/> 
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom_css/complex_forms2.css"/>
@endsection
@section('content')
<style type="text/css">
    .grid-form fieldset legend{
        border-bottom: 1px solid #eee;
    }
    .grid-form [data-row-span] {
    border-bottom: 1px solid #eee;
    }
 
    .grid-form [data-row-span] [data-field-span] {
        border-right: 1px solid #eee;
    }
    
</style>
<section class="content">
            <div class="row" id="complex-form2">
                <!--5th tab bank application starting-->
                <div class="col-lg-12"> 
                    {!! Form::open(['method' => 'PUT','class'=>'grid-form form-horizontal','enctype'=>'multipart/form-data', 'route' => ['updateReservation', $sheet->id] ]) !!}    
                        {!! csrf_field() !!} 
                        <br> 
                        <div class="row" style="margin-top: 20px;">

                            <div class="col-md-4 pull-left"> 
                                <div class="row">
                                    <div class="form-group"> 
                                        <div class="col-md-12">
                                            <img src="/uploads/admin/{{ $header->logo }}" style=" max-width: 350px;    max-height: 100px; margin-top: 3%;margin-left: 10px;"> 
                                        </div>
                                    </div>
                                </div>  
                            </div>
                            <div class="col-md-4"> 
                                <h2 class="text-center" style="margin-top: 0px;">--{{strtoupper($header->title) }}--</h2>   
                            </div>  

                            <div class="col-md-4" style="left: 10%;">  
                                <h5  >Email : <strong >{{$header->email }}</strong> </h5>
                          
                                <h5  >Site : <strong  >{{$header->site }}</strong> </h5>
                           
                                <h5  >Tél : <strong   >{{$header->tel }}</strong> </h5>
                                        
                            </div>  

                        </div>   
                        <br>
                        <br>
                        <br>
                        <br> 
                        <fieldset>
                            <legend>MADAME :</legend>
                            <div data-row-span="6">
                                <div data-field-span="3">
                                    <label for="w_last_name">Nom :</label>
                                    <input class="form-control" id="w_last_name" name="w_last_name" type="text" value="{{ $sheet->w_last_name }}" >
                                </div>
                                <div data-field-span="3">
                                <label for="w_first_name">Prenom :</label>
                                    <input class="form-control" id="w_first_name" name="w_first_name"  type="text" value="{{ $sheet->w_first_name }}" > 
                                </div> 
                            </div>
                            <div data-row-span="6">
                                <div data-field-span="2">
                                    <label for="w_profession">Profession :</label>
                                    <input class="form-control" id="w_profession" name="w_profession" type="text" value="{{ $sheet->w_profession }}" >
                                </div>
                                <div data-field-span="2">
                                    <label for="w_age">Age :</label>
                                    <input class="form-control" id="w_age" type="text"  name="w_age"    value="{{ $sheet->w_age }}"  > 
                                </div> 
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>MONSIEUR :</legend>
                            <div data-row-span="6">
                                <div data-field-span="3">
                                    <label for="m_last_name">Nom :</label>
                                    <input class="form-control mLastName" id="m_last_name" name="m_last_name" type="text" value="{{ $sheet->m_last_name  }}"  >
                                </div>
                                <div data-field-span="3">
                                <label for="m_first_name">Prenom :</label>
                                    <input class="form-control" id="m_first_name" name="m_first_name"  type="text" value="{{ $sheet->m_first_name  }}"  > 
                                </div> 
                            </div>
                            <div data-row-span="6">
                                <div data-field-span="2">
                                    <label for="m_profession">Profession :</label>
                                    <input class="form-control" id="m_profession" name="m_profession" type="text" value="{{ $sheet->m_profession  }}" >
                                </div>
                                <div data-field-span="2">
                                    <label for="m_age">Age :</label>
                                    <input class="form-control" id="m_age" type="text"  name="m_age"    value="{{ $sheet->m_age  }}" > 
                                </div> 
                            </div>   
                            <div data-row-span="3">
                                <div data-field-span="1">
                                    <label for="tel">N° Tel :</label>
                                    <input class="form-control" id="tel" name="tel" type="text"  value="{{ $sheet->tel }}"   >
                                </div>
                                <div data-field-span="1">
                                    <label for="gsm">GSM :</label>
                                    <input class="form-control" id="gsm" name="gsm" type="text" value="{{$sheet->gsm }}" >
                                </div>
                                <div data-field-span="1">
                                    <label for="email">E-mail :</label>
                                    <input class="form-control" id="email" name="email" type="email" value="{{ $sheet->email }}" >
                                </div>
                            </div>
                            <div data-row-span="1">
                                <div data-field-span="1">
                                    <label for="address">Adresse :</label>
                                    <input class="form-control" id="address" name="address" type="text" value="{{ $sheet->address }}" >
                                </div>
                            </div>
                            <div data-row-span="5">
                                <div data-field-span="1">
                                    <label for="nbr_pax" >Nombre de PAX :</label>
                                    <input class="form-control" id="nbr_pax" name="nbr_pax" type="text" value="{{ $sheet->nbr_pax }}" >
                                </div> 
                            </div>
                            <div data-row-span="4"> 
                                <div data-field-span="2">
                                    <label for="date_arrived"  >Arrivée :</label>
                                    <div class="form-group  col-md-6"  style=" /*margin-left: 25%;*/">
                                      <div class='input-group date' id='date_arrived'>
                                          <input type='text' class="form-control" id="date_arrived"  name="date_arrived" placeholder="Arrivée"  value="{{ date('d-m-Y', strtotime($sheet->date_arrived))   }}" />
                                          <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-calendar"></span>
                                          </span>
                                      </div>
                                    </div>
                                </div> 
                                <div data-field-span="2">
                                    <label for="date_departure"  >Départ :</label>
                                    <div class="form-group  col-md-6" style=" /*margin-left: 25%;*/">
                                      <div class='input-group date' id='date_departure'>
                                          <input type='text' class="form-control" id="date_departure"  name="date_departure" placeholder="Départ"   value="{{ date('d-m-Y', strtotime($sheet->date_departure))   }}" />
                                          <span class="input-group-addon">
                                              <span class="glyphicon glyphicon-calendar"></span>
                                          </span>
                                      </div>
                                    </div>
                                </div> 
                            </div>
                            <div data-row-span="4"> 
                                <div data-field-span="2">
                                    <label for="da_flight_number" >Numéro de vol :</label>
                                    <div class="col-md-6  " style="margin-left: -12px;">
                                        <input class="form-control" id="da_flight_number" name="da_flight_number" type="text"   value="{{ $sheet->da_flight_number }}">                                                                             
                                    </div>
                                </div> 
                                <div data-field-span="2">
                                    <label for="dd_flight_number" >Numéro de vol :</label>
                                    <div class="col-md-6" style="margin-left: -12px;">
                                        <input class="form-control " id="dd_flight_number" name="dd_flight_number" type="text"   value="{{ $sheet->dd_flight_number }}">                                     
                                    </div>
                                </div> 
                            </div>
                            <div data-row-span="3"> 
                                <div data-field-span="3">
                                    <label for="establishment" >2 Choix d'établissements Obligatoires:</label>
                                    <input class="form-control" id="establishment" name="establishment" type="text"  value="{{ $sheet->establishment }}" >
                                </div>  
                            </div> 
                        </fieldset>  

                        <fieldset style="margin-top: 6px;">                         
                            <legend   style="font-size: 14px;font-weight: inherit;">Supplements</legend>
                            <div data-row-span="8">
                                <div data-field-span="3">                                    
                                    <select id="select_supplements" name="supplements"> 
                                        <option  value="0" <?php  if($sheet->supplements =="0") echo 'selected="true"' ?> >:: Supplements ::</option>  
                                        <option value="LIT" title="LIT"   <?php  if($sheet->supplements =="LIT") echo 'selected="true"' ?> > LIT </option>  
                                        <option value="NUIT" title="NUIT" <?php  if($sheet->supplements =="NUIT") echo 'selected="true"' ?>   > NUIT </option>  
                                        <option value="CHAMBRE" title="CHAMBRE"  <?php  if($sheet->supplements =="CHAMBRE") echo 'selected="true"' ?>  > CHAMBRE </option>  
                                        <option value="SEMAINE" title="SEMAINE"  <?php  if($sheet->supplements =="SEMAINE") echo 'selected="true"' ?>  > SEMAINE </option>  
                                    </select>
                                </div> 
                            </div>
                            <div data-row-span="5"> 
                                <div data-field-span="1">
                                    <label for="amount"  >Montant à payer sur place :</label>
                                    <input class="form-control" id="amount" name="amount" type="text"   value="{{ $sheet->amount }}">
                                </div> 
                            </div>
                        </fieldset>
                        <br>
                        <fieldset>
                            <legend>Commentaire :</legend>
                            <div data-row-span="4" style="border-bottom: none;">
                                <div data-field-span="4">
                                    <textarea id="comments"  name="comments" class="resize_vertical" rows="4">   {{ $sheet->comments }} </textarea>
                                </div> 
                            </div> 
                        </fieldset>
                        <br>   
                        <br>   
                        <div class="pull-right">  
                            <button type="submit" class="btn btn-success"  style="float: right;" >
                                <span class="ti-save"></span> Sauvegarder
                            </button>
                        </div>
                    {!! Form::close() !!} 
                </div>
            </div>
        </section>
        <!-- /.content -->
 
@endsection
@section('javascript') 
 
<script type="text/javascript" >
"use strict";
$(document).ready(function() { 

  $('#date_departure').datetimepicker({
    format:'DD-MM-YYYY',
  });
  $('#date_arrived').datetimepicker({
    format:'DD-MM-YYYY',
  });
  
 $("#select_supplements").select2({
        theme: "bootstrap"
    });
});
</script>
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js" type="text/javascript"></script> 
<script src="{{ asset('admin') }}/js/custom_js/complex_form2.js" type="text/javascript"></script>


@endsection