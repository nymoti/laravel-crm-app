@extends('layouts.app')
@section('css')

<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/gridforms/css/gridforms.css"/> 
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom_css/complex_forms2.css"/>

<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/select2/css/select2.min.css">

<link href="{{ asset('admin') }}/vendors/select2/css/select2-bootstrap.css" rel="stylesheet" type="text/css">
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
            {!! Form::open(['method' => 'PUT','class'=>'grid-form form-horizontal','enctype'=>'multipart/form-data', 'route' => ['sheets.update', $sheet->id] ]) !!}    

                {!! csrf_field() !!}
                @hasanyrole('Admin|Closer|Super Admin')
                <div class="col-md-4 col-md-offset-8">
                                                             
                    <a href="{{ route('sheets.edit', $sheet['id'])}}" class="btn btn-icon btn-warning m-r-50" style="margin-top: -8px;margin-left: 70px;">
                        <span class="ti-pencil"></span> Modifier
                    </a>
                    
                    <div class="pull-right">
                        @if($sheet->status != 0)
                        <span class="label label-sm {{$sheet->statusList['color']}}" style="font-size: 15px;">
                        {{$sheet->statusList['name']}}
                        </span> 
                        @else
                        <span class="label label-sm label-primary" style="font-size: 15px;">
                        Non attribué
                        </span> 
                        @endif
                    </div>
                </div>
                @endhasanyrole
                <br>
                <input type="hidden" name="created_by" value="{{Auth()->user()->id}}">
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
                @hasanyrole('Admin|Closer|Super Admin')
                <div class="row" style="padding: 0px 5px 0 5px;"> 
                    <div class="col-md-6">
                        <h4>Créer par : <strong> {{strtoupper($sheet->agent['first_name'].' '. $sheet->agent['last_name'])}}</strong></h4>
                    </div> 
                    <div class="col-md-6">
                        <h4 class="pull-right">Le : <strong>{{ $sheet->created_at->format('d-m-Y') }}</strong></h4>
                    </div>
                </div>
                <br>
                @endhasanyrole  
                <fieldset>
                    <legend>Q1 - Quels sont les 5 dérniers pays que vous avez visités et en quelle année ?</legend>  
                    <div data-row-span="4" style="border-bottom: none;margin-bottom: 11px;"> 
                        <div data-field-span="4">
                            <textarea id="question_1"  name="question_1" class="resize_vertical" rows="4" readonly>{{ $sheet->question_1 }}</textarea>
                        </div>
                    </div>  
                    <legend>Q2 - Dans les 5 prochaines années voulez-vous voyager  </legend>
                        <div class="pull-right" style="margin-top: -30px;margin-right: 360px;">                                     
                            <label>&nbsp;Plus
                            <input type="radio" name="question_2"
                                class="square-blue" value="1" <?php  if( $sheet->q2_more  == 1) echo 'checked' ?>  disabled></label>
                            <label>&nbsp;Ou Moins
                            <input type="radio" name="question_2"
                                class="square-blue" value="0"    <?php if( $sheet->q2_less  == 1) echo 'checked' ?> disabled></label>
                        </div>
                    <div data-row-span="12">
                        <div data-field-span="2">
                            <input id="times" type="checkbox" name="times" value="1"  <?php  if($sheet->times === 1) echo 'checked' ?>  disabled>&nbsp;
                            <label for="times">Temps</label>
                        </div>
                        <div data-field-span="2">
                            <input id="financials" type="checkbox" name="financials"  value="1" <?php  if($sheet->financials === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="financials">Financiers</label>
                        </div>
                        <div data-field-span="2">
                            <input id="childrens" type="checkbox"  name="childrens"  value="1" <?php  if($sheet->childrens === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="childrens">Enfants</label>
                        </div>
                        <div data-field-span="2">
                            <input id="distance" type="checkbox"  name="distance"  value="1" <?php  if($sheet->distance === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="distance">Distance</label>
                        </div>
                        <div data-field-span="4">
                            <input id="no_oppportunity" type="checkbox" name="no_oppportunity"  value="1" <?php  if($sheet->no_oppportunity === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="no_oppportunity">Pas eu l'occasion</label>
                        </div>
                    </div>
                </fieldset>
                <fieldset style="margin-top: 30px;">                         
                    <legend>Q3 - D'habitude vos voyages se font par quels moyens ?</legend> 
                    <div data-row-span="12">
                        <div data-field-span="3">
                            <input id="travel_agency" type="checkbox" name="travel_agency"  value="1" <?php  if($sheet->travel_agency === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="travel_agency">Agence de voyage</label>
                        </div>
                        <div data-field-span="3">
                            <input id="tour_operator" type="checkbox" name="tour_operator"  value="1" <?php  if($sheet->tour_operator === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="tour_operator">Tour operateur</label>
                        </div>
                        <div data-field-span="3">
                            <input id="only" type="checkbox"  name="only"  value="1" <?php  if($sheet->only === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="only">Seul</label>
                        </div>
                        <div data-field-span="3">
                            <input id="company_comittee" type="checkbox"  name="company_comittee"  value="1" <?php  if($sheet->company_comittee === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="company_comittee">Comite d'entreprise</label>
                        </div> 
                    </div> 
                    <div data-row-span="12">
                        <div data-field-span="2" style="height: 50px;padding: 12px;">
                            <input id="others_q3" type="checkbox" name="others_q3" value="1" <?php  if($sheet->others_q3 === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="others_q3">Autres</label>
                        </div>
                        <div data-field-span="10">
                            <input class="form-control" id="others_desc_q3" type="text" name="others_desc_q3" value="{{ $sheet->others_desc_q3 }}" readonly>
                        </div> 
                    </div>
                </fieldset>
                <fieldset style="margin-top: 30px;">                          
                    <legend>Q4 - Quelles activités aimeriez-vous pratiquer durant vos vacances ?</legend> 
                    <div data-row-span="12">
                        <div data-field-span="3">
                            <input id="cultural_visits" type="checkbox" name="cultural_visits"  value="1" <?php  if($sheet->cultural_visits === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="cultural_visits">Visites culturelles</label>
                        </div>
                        <div data-field-span="3">
                            <input id="exercusions" type="checkbox" name="exercusions"  value="1" <?php  if($sheet->exercusions === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="exercusions">Excursions</label>
                        </div>
                        <div data-field-span="3">
                            <input id="sports" type="checkbox"  name="sports"  value="1" <?php  if($sheet->sports === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="sports">Sports</label>
                        </div>
                        <div data-field-span="3">
                            <input id="balneo" type="checkbox"  name="balneo"  value="1" <?php  if($sheet->balneo === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="balneo">Balneo</label>
                        </div> 
                    </div>
                    <div data-row-span="12">
                        <div data-field-span="2" style="height: 50px;padding: 12px;">
                            <input id="others" type="checkbox" name="others" value="1" <?php  if($sheet->others === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="others">Autres</label>
                        </div>
                        <div data-field-span="10">
                            <input class="form-control" id="others_desc" type="text" name="others_desc" value="{{ $sheet->others_desc }}" readonly>
                        </div> 
                    </div>
                </fieldset>
                <fieldset style="margin-top: 30px;">                          
                    <legend>Q5 - Pouvez-vous m'évaluer votre budget de vacance  annuelle ?</legend> 
                        <div data-row-span="15">
                            <div data-field-span="3">
                                <input id="less_of_1500" type="checkbox" name="less_of_1500"  value="1" <?php  if($sheet->less_of_1500 === 1) echo 'checked' ?> disabled>&nbsp;
                                <label for="less_of_1500">Moins de 1500 <i class="fa fa-fw fa-euro"></i></label>
                            </div>
                            <div data-field-span="3">
                                <input id="from_1500_to_2000" type="checkbox" name="from_1500_to_2000"  value="1" <?php  if($sheet->from_1500_to_2000 === 1) echo 'checked' ?> disabled>&nbsp;
                                <label for="from_1500_to_2000">De 1500 A 2000 <i class="fa fa-fw fa-euro"></i></label>
                            </div>
                            <div data-field-span="3">
                                <input id="from_2000_to_3000" type="checkbox"  name="from_2000_to_3000"  value="1" <?php  if($sheet->from_2000_to_3000 === 1) echo 'checked' ?> disabled>&nbsp;
                                <label for="from_2000_to_3000">De 2000 A 3000 <i class="fa fa-fw fa-euro"></i></label>
                            </div>
                            <div data-field-span="3">
                                <input id="from_3000_to_4000" type="checkbox"  name="from_3000_to_4000"  value="1" <?php  if($sheet->from_3000_to_4000 === 1) echo 'checked' ?> disabled>&nbsp;
                                <label for="from_3000_to_4000">De 3000 A 4000 <i class="fa fa-fw fa-euro"></i></label>
                            </div> 
                            <div data-field-span="3">
                                <input id="or_more" type="checkbox"  name="or_more"  value="1" <?php  if($sheet->or_more === 1) echo 'checked' ?> disabled>&nbsp;
                                <label for="or_more">Ou plus</label>
                            </div>
                        </div>
                </fieldset>  
                <br>
                <br> 
                <fieldset>
                    <legend>Infos Personnel :</legend>
                    <div data-row-span="6">
                        <div data-field-span="2">
                            <label for="m_last_name">Nom -H :</label>
                            <input class="form-control mLastName" id="m_last_name" name="m_last_name" type="text"  value="{{ $sheet->m_last_name }}" readonly>
                        </div>
                        <div data-field-span="2">
                        <label for="m_first_name">Prenom :</label>
                            <input class="form-control" id="m_first_name" name="m_first_name"  type="text" value="{{ $sheet->m_first_name }}" readonly> 
                        </div>
                        <div data-field-span="2">
                        <label for="client_code">Code Client :</label>
                            <input type="text" class="form-control" id="client_code" name="client_code" value="{{ $sheet->client_code }}" readonly  > 
                        </div>
                    </div>
                    <div data-row-span="6">
                        <div data-field-span="2">
                            <label for="m_profession">Profession :</label>
                            <input class="form-control" id="m_profession" name="m_profession" type="text" value="{{ $sheet->m_profession }}" readonly>
                        </div>
                        <div data-field-span="2">
                            <label for="m_age">Age :</label>
                            <input class="form-control" id="m_age" type="text"  value="{{ $sheet->m_age }}" name="m_age"   maxlength="250" readonly > 
                        </div> 
                    </div>
                    <div data-row-span="6">
                        <div data-field-span="3">
                            <label for="w_last_name">Nom -F :</label>
                            <input class="form-control" id="w_last_name" name="w_last_name" type="text" value="{{ $sheet->w_last_name }}"  readonly>
                        </div>
                        <div data-field-span="3">
                        <label for="w_first_name">Prenom :</label>
                            <input class="form-control" id="w_first_name" name="w_first_name"  type="text" value="{{ $sheet->w_first_name }}" readonly > 
                        </div> 
                    </div>
                    <div data-row-span="6">
                        <div data-field-span="2">
                            <label for="w_profession">Profession :</label>
                            <input class="form-control" id="w_profession" name="w_profession" type="text" value="{{ $sheet->w_profession }}" readonly >
                        </div>
                        <div data-field-span="2">
                            <label for="w_age">Age :</label>
                            <input class="form-control" id="w_age" type="text"  name="w_age" value="{{ $sheet->w_age }}"  maxlength="250"  readonly  > 
                        </div> 
                    </div>
                    <div data-row-span="8">
                        <div data-field-span="2">                                    
                            <input id="married" type="checkbox" name="married"  value="1" <?php  if($sheet->married === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="married">Marié</label>
                        </div>
                        <div data-field-span="2">                                    
                            <input id="single" type="checkbox" name="single"  value="1" <?php  if($sheet->single === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="single">Célibataire</label>
                        </div>
                        <div data-field-span="2">                                    
                            <input id="divorced" type="checkbox" name="divorced"  value="1" <?php  if($sheet->divorced === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="divorced">Divorcée</label>
                        </div>
                        <div data-field-span="2">                                    
                            <input id="widower" type="checkbox" name="widower"  value="1" <?php  if($sheet->widower === 1) echo 'checked' ?> disabled>&nbsp;
                            <label for="widower">Veuf</label>
                        </div>
                    </div>
                    <div data-row-span="12">
                        <div data-field-span="4" >                                    
                            <input type="checkbox" id="concubinage" name="concubinage"   value="1" <?php  if($sheet->concubinage === 1) echo 'checked' ?>  disabled> Concubinage
                            <label for="concubinage">Concubinage</label>
                        </div>
                        <div data-field-span="1" style="border-right: none;">   
                            <label for="concubinage_since">Depuis :</label>                                 
                        </div>
                        <div data-field-span="1" style="height: 41px;">   
                            <input class="form-control" id="concubinage_since" type="number" name="concubinage_since"  value="{{ $sheet->concubinage_since }}"  min="0" max="100" style="width: 65px; margin-top: -5px;" readonly>                                    
                        </div>
                        <div data-field-span="1" style="border-right: none;">   
                            <label for="concubinage_since">Ans</label>                                 
                        </div>
                    </div>
                    <div data-row-span="12">
                        <div data-field-span="2"  style="width: 142px;">
                            <label for="dependent_children">Enfants à charge :
                            </label> 
                            @if($sheet->dependent_children === 1 && $dc_all_ages[0] == 0)   
                            <input class="form-control" id="dependent_children" type="text" value="0" name="dependent_children"  readonly>
                            @else             
                            <input class="form-control" id="dependent_children" type="text" value="{{ $sheet->dependent_children }}" name="dependent_children"  readonly>               
                            @endif

                        </div>
                        @if($sheet->dependent_children === 1 && $dc_all_ages[0] == 0)  

                        @else
                            @foreach($dc_all_ages as $key=>$age)
                            <div data-field-span="1">
                                <label for="dc_age{{ $key+1 }}">Age enfant {{ $key+1 }}:</label>
                                <input class="form-control" id="dc_age{{ $key+1 }}" type="text" value="{{ $age }}" name="dc_age{{ $key+1 }}"    >  
                            </div>   
                            @endforeach 
                        @endif
                    </div>
                    <div data-row-span="3">
                        <div data-field-span="1">
                            <label for="tel">N° Tel :</label>
                            <input class="form-control" id="tel" name="tel" type="text" value="{{ $sheet->tel }}" readonly>
                        </div>
                        <div data-field-span="1">
                            <label for="gsm">Portable :</label>
                            <input class="form-control" id="gsm" name="gsm" type="text"   value="{{ $sheet->gsm }}" readonly>
                        </div>
                        <div data-field-span="1">
                            <label for="email">Mail :</label>
                            <input class="form-control" id="email" name="email" type="email"  value="{{ $sheet->email }}" readonly>
                        </div>
                    </div>
                    <div data-row-span="1">
                        <div data-field-span="1">
                            <label for="address">Adresse Postale :</label>
                            <input class="form-control" id="address" name="address" type="text" value="{{ $sheet->address }}" readonly>
                        </div>
                    </div>
                </fieldset>
                <br>
                <br>
                <fieldset>
                    <legend>Observations :</legend>
                    <div data-row-span="4" style="border-bottom: none;">
                        <div data-field-span="4">
                            <textarea id="observations"  name="observations" class="resize_vertical" rows="4" readonly>{{ $sheet->observations }}</textarea>
                        </div> 
                    </div> 
                </fieldset>
                
                @hasanyrole('Admin|Super Admin')                
                <div class="col-md-7 col-md-offset-5">
                    <div class="pull-right">
                        <h4 style="margin-top: 25px;">Dernière modification faite par : 
                        <strong style="background: #70a1ff;padding: 5px;color: #fff;border-radius: 22px;">
                            @if($sheet->updated_by != 0) 
                                {{strtoupper($sheet->updatedByUser['first_name'].' '. $sheet->updatedByUser['last_name'])}}
                            @else
                                AUCUN
                            @endif
                        </strong>
                        </h4>  
                    </div>
                </div>   
                <br>  
                @endhasanyrole
                <br>   
                <br>   
                <br>  
                <div class="row">
                    <div class="col-md-6"> 
                        <div class="pull-left">
                        @hasanyrole('Admin|Super Admin')                                       
                            <a href="{{ route('generatePdf', $sheet->id) }}" class="btn btn-icon btn-danger m-r-50">
                                <i class="fa fa-fw ti-file"></i> Exporter PDF
                            </a>
                        @endhasanyrole
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="pull-right"> 
                        </div>
                    </div>
                </div>
            {!! Form::close() !!} 
        </div>
    </div>
</section>
<br><br><br>
<!-- /.content -->
 
@endsection
@section('javascript') 
 
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js" type="text/javascript"></script> 
<script src="{{ asset('admin') }}/js/custom_js/complex_form2.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/vendors/select2/js/select2.js" type="text/javascript" ></script>


@endsection