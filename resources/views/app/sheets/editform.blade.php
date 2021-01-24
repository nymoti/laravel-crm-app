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

    .ti-minus{
    border: 1px solid #a2a0a0;
    /*padding: 1px; */
    border-radius: 50px;
    font-size: 22px;
    cursor: pointer; 
    }
    .ti-plus{
    border: 1px solid #a2a0a0;
    /*padding: 1px; */
    font-size: 22px;
    cursor: pointer; 
    border-radius: 50px;
    }
</style>
<section class="content">
    <div class="row" id="complex-form2">
        <!--5th tab bank application starting-->
        <div class="col-lg-12">     
            {!! Form::open(['method' => 'PUT','class'=>'grid-form form-horizontal','enctype'=>'multipart/form-data', 'route' => ['sheets.update', $sheet->id] ]) !!}    

                {!! csrf_field() !!}
                @role('Agent')
                <div class="col-md-4 col-md-offset-8" style="padding-top: 35px;"> 
                <button type="submit" class="btn btn-success"  style="margin-top: -44px;float: right; ">
                    <span class="ti-save"></span> Sauvegarder
                </button>
                </div>

                @endrole
                @hasanyrole('Admin|Closer|Super Admin')  
                <div class="col-md-4 col-md-offset-8" style="padding-top: 12px;"> 
                <button type="submit" class="btn btn-success"  style="margin-top: -44px;  margin-left: 14px; ">
                    <span class="ti-save"></span> Sauvegarder
                </button>
                <div class="col-md-4 col-md-offset-8" style="margin-top: -40px;">
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
                </div>

                @endhasanyrole
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
                
                <?php if(!Auth()->user()->hasRole('Agent')) { ?>
                <fieldset>
                <div class="col-md-6">
                    <legend>Status : </legend> 
                    <div data-row-span="4"  > 
                        @hasanyrole('Admin|Closer|Super Admin')
                        <div data-field-span="4"> 
                            <select id="select_country" name="status"> 
                                <option <?php if($sheet->status === 0 ) echo 'selected="true"'?> value="0">:: Non attribué ::</option> 
                                @foreach($status as $status)
                                <option value="{{$status->id}}" title="{{$status->name}}" <?php if($status->id === $sheet->status ) echo 'selected="true"'?>  >{{$status->name}}</option> 
                                @endforeach
                            </select>
                        </div>
                        @endrole 
                    </div> 
                </div>
                <div class="col-md-6">
                    
                    @role('Super Admin')
                    @if(isset($show_btn_etat))
                        @if($show_btn_etat === true)

                            <legend>Etat: </legend>
                            <div data-row-span="4"  > 
                                <div data-field-span="4"> 
                                    <select id="select_state" name="state">                               
                                        <option <?php if($sheet->state === 0 ) echo 'selected="true"'?> value="0">:: Sans ::</option> 
                                        <option value="1" title="confirmed" <?php if($sheet->state === 1 ) echo 'selected="true"'?> >Confirmé</option>                                  
                                        <option value="2" title="annulé"  <?php if($sheet->state === 2 ) echo 'selected="true"'?>>Annulé</option>                                  
                                    </select>
                                </div>
                            </div>


                        @endif
                    @endif
                    @endrole
                </div>
                </fieldset>  
                <?php }?> 
                <br>
                <fieldset>
                    <legend>Q1 - Quels sont les 5 dérniers pays que vous avez visités et en quelle année ?</legend>  
                    <div data-row-span="4" style="border-bottom: none;margin-bottom: 11px;"> 
                        <div data-field-span="4">
                            <textarea id="question_1"  name="question_1" class="resize_vertical" rows="4">{{ old('question_1', $sheet->question_1) }}</textarea>
                        </div>
                    </div>  
                    <legend>Q2 - Dans les 5 prochaines années voulez-vous voyager </legend>
                        <div class="pull-right" style="margin-top: -30px;margin-right: 360px;">                                     
                            <label>&nbsp;Plus 
                            <input type="radio" name="question_2"
                                class="square-blue" value="1" <?php  if( $sheet->q2_more  == 1) echo 'checked' ?> ></label>
                            <label>&nbsp;Ou Moins
                            <input type="radio" name="question_2"
                                class="square-blue" value="0"  <?php if( $sheet->q2_less  == 1) echo 'checked' ?> ></label>
                        </div>
                    <div data-row-span="12">
                        <div data-field-span="2">
                            <input id="times" type="checkbox" name="times" value="1"  @if ( $sheet->times == 1 ||  old('times') == 1)  checked @endif  >&nbsp;
                            <label for="times">Temps</label>
                        </div>
                        <div data-field-span="2">
                            <input id="financials" type="checkbox" name="financials"  value="1" <?php  if(old('financials') == 1 ||   $sheet->financials == 1) echo 'checked' ?>>&nbsp;
                            <label for="financials">Financiers</label>
                        </div>
                        <div data-field-span="2">
                            <input id="childrens" type="checkbox"  name="childrens"  value="1" <?php  if(old('childrens') == 1 ||   $sheet->childrens == 1) echo 'checked' ?>>&nbsp;
                            <label for="childrens">Enfants</label>
                        </div>
                        <div data-field-span="2">
                            <input id="distance" type="checkbox"  name="distance"  value="1" <?php  if(old('distance') == 1 ||   $sheet->distance == 1) echo 'checked' ?>>&nbsp;
                            <label for="distance">Distance</label>
                        </div>
                        <div data-field-span="4">
                            <input id="no_oppportunity" type="checkbox" name="no_oppportunity"  value="1" <?php  if(old('no_oppportunity') == 1 ||   $sheet->no_oppportunity == 1) echo 'checked' ?>>&nbsp;
                            <label for="no_oppportunity">Pas eu l'occasion</label>
                        </div>
                    </div>
                </fieldset>
                <fieldset style="margin-top: 30px;">                         
                    <legend>Q3 - D'habitude vos voyages se font par quels moyens ?</legend> 
                    <div data-row-span="12">
                        <div data-field-span="3">
                            <input id="travel_agency" type="checkbox" name="travel_agency"  value="1" <?php  if($sheet->travel_agency === 1) echo 'checked' ?>>&nbsp;
                            <label for="travel_agency">Agence de voyage</label>
                        </div>
                        <div data-field-span="3">
                            <input id="tour_operator" type="checkbox" name="tour_operator"  value="1" <?php  if($sheet->tour_operator === 1) echo 'checked' ?>>&nbsp;
                            <label for="tour_operator">Tour operateur</label>
                        </div>
                        <div data-field-span="3">
                            <input id="only" type="checkbox"  name="only"  value="1" <?php  if($sheet->only === 1) echo 'checked' ?>>&nbsp;
                            <label for="only">Seul</label>
                        </div>
                        <div data-field-span="3">
                            <input id="company_comittee" type="checkbox"  name="company_comittee"  value="1" <?php  if($sheet->company_comittee === 1) echo 'checked' ?>>&nbsp;
                            <label for="company_comittee">Comite d'entreprise</label>
                        </div> 
                    </div>
                    <div data-row-span="12">
                        <div data-field-span="2" style="height: 50px;padding: 12px;">
                            <input id="others_q3" type="checkbox" name="others_q3" value="1" <?php  if($sheet->others_q3 === 1) echo 'checked' ?>>&nbsp;
                            <label for="others_q3">Autres</label>
                        </div>
                        <div data-field-span="10">
                            <input class="form-control" id="others_desc_q3" type="text" name="others_desc_q3" value="{{ $sheet->others_desc_q3 }}">
                        </div> 
                    </div>
                </fieldset>
                <fieldset style="margin-top: 30px;">                          
                    <legend>Q4 - Quelles activités aimeriez-vous pratiquer durant vos vacances ?</legend> 
                        <div data-row-span="12">
                            <div data-field-span="3">
                                <input id="cultural_visits" type="checkbox" name="cultural_visits"  value="1" <?php  if($sheet->cultural_visits === 1) echo 'checked' ?>>&nbsp;
                                <label for="cultural_visits">Visites culturelles</label>
                            </div>
                            <div data-field-span="3">
                                <input id="exercusions" type="checkbox" name="exercusions"  value="1" <?php  if($sheet->exercusions === 1) echo 'checked' ?>>&nbsp;
                                <label for="exercusions">Excursions</label>
                            </div>
                            <div data-field-span="3">
                                <input id="sports" type="checkbox"  name="sports"  value="1" <?php  if($sheet->sports === 1) echo 'checked' ?>>&nbsp;
                                <label for="sports">Sports</label>
                            </div>
                            <div data-field-span="3">
                                <input id="balneo" type="checkbox"  name="balneo"  value="1" <?php  if($sheet->balneo === 1) echo 'checked' ?>>&nbsp;
                                <label for="balneo">Balneo</label>
                            </div> 
                        </div>
                        <div data-row-span="12">
                            <div data-field-span="2" style="height: 50px;padding: 12px;">
                                <input id="others" type="checkbox" name="others" value="1" <?php  if($sheet->others === 1) echo 'checked' ?>>&nbsp;
                                <label for="others">Autres</label>
                            </div>
                            <div data-field-span="10">
                                <input class="form-control" id="others_desc" type="text" name="others_desc" value="{{ $sheet->others_desc }}">
                            </div> 
                        </div>
                </fieldset>
                <fieldset style="margin-top: 30px;">                          
                    <legend>Q5 - Pouvez-vous m'évaluer votre budget de vacance  annuelle ?</legend> 
                        <div data-row-span="15">
                            <div data-field-span="3">
                                <input id="less_of_1500" type="checkbox" name="less_of_1500"  value="1" <?php  if($sheet->less_of_1500 === 1) echo 'checked' ?>>&nbsp;
                                <label for="less_of_1500">Moins de 1500 <i class="fa fa-fw fa-euro"></i></label>
                            </div>
                            <div data-field-span="3">
                                <input id="from_1500_to_2000" type="checkbox" name="from_1500_to_2000"  value="1" <?php  if($sheet->from_1500_to_2000 === 1) echo 'checked' ?>>&nbsp;
                                <label for="from_1500_to_2000">De 1500 A 2000 <i class="fa fa-fw fa-euro"></i></label>
                            </div>
                            <div data-field-span="3">
                                <input id="from_2000_to_3000" type="checkbox"  name="from_2000_to_3000"  value="1" <?php  if($sheet->from_2000_to_3000 === 1) echo 'checked' ?>>&nbsp;
                                <label for="from_2000_to_3000">De 2000 A 3000 <i class="fa fa-fw fa-euro"></i></label>
                            </div>
                            <div data-field-span="3">
                                <input id="from_3000_to_4000" type="checkbox"  name="from_3000_to_4000"  value="1" <?php  if($sheet->from_3000_to_4000 === 1) echo 'checked' ?>>&nbsp;
                                <label for="from_3000_to_4000">De 3000 A 4000 <i class="fa fa-fw fa-euro"></i></label>
                            </div> 
                            <div data-field-span="3">
                                <input id="or_more" type="checkbox"  name="or_more"  value="1" <?php  if($sheet->or_more === 1) echo 'checked' ?>>&nbsp;
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
                            <input class="form-control mLastName" id="m_last_name" name="m_last_name" type="text"  value="{{ $sheet->m_last_name }}">
                        </div>
                        <div data-field-span="2">
                        <label for="m_first_name">Prenom :</label>
                            <input class="form-control" id="m_first_name" name="m_first_name"  type="text" value="{{ $sheet->m_first_name }}"> 
                        </div>
                        <div data-field-span="2">
                        <label for="client_code">Code Client :</label>
                            <input type="text" class="form-control" id="client_code" name="client_code" value="{{ $sheet->client_code }}" > 
                        </div>
                    </div>
                    <div data-row-span="6">
                        <div data-field-span="2">
                            <label for="m_profession">Profession :</label>
                            <input class="form-control" id="m_profession" name="m_profession" type="text" value="{{ $sheet->m_profession }}">
                        </div>
                        <div data-field-span="2">
                            <label for="m_age">Age :</label>
                            <input class="form-control" id="m_age" type="text"  value="{{ $sheet->m_age }}" name="m_age" maxlength="250"  > 
                        </div> 
                    </div>
                    <div data-row-span="6">
                        <div data-field-span="3">
                            <label for="w_last_name">Nom -F :</label>
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
                            <input class="form-control" id="w_age" type="text"  name="w_age" value="{{ $sheet->w_age }}"maxlength="250"  > 
                        </div> 
                    </div>
                    <div data-row-span="8">
                        <div data-field-span="2">                                    
                            <input id="married" type="checkbox" name="married"  value="1" <?php  if($sheet->married === 1) echo 'checked' ?>>&nbsp;
                            <label for="married">Marié</label>
                        </div>
                        <div data-field-span="2">                                    
                            <input id="single" type="checkbox" name="single"  value="1" <?php  if($sheet->single === 1) echo 'checked' ?>>&nbsp;
                            <label for="single">Célibataire</label>
                        </div>
                        <div data-field-span="2">                                    
                            <input id="divorced" type="checkbox" name="divorced"  value="1" <?php  if($sheet->divorced === 1) echo 'checked' ?>>&nbsp;
                            <label for="divorced">Divorcée</label>
                        </div>
                        <div data-field-span="2">                                    
                            <input id="widower" type="checkbox" name="widower"  value="1" <?php  if($sheet->widower === 1) echo 'checked' ?>>&nbsp;
                            <label for="widower">Veuf</label>
                        </div>
                    </div>
                    <div data-row-span="12">
                        <div data-field-span="4">                                    
                            <input type="checkbox" id="concubinage" name="concubinage"   value="1" <?php  if($sheet->concubinage === 1) echo 'checked' ?>> Concubinage
                            <label for="concubinage">Concubinage</label>
                        </div>
                        <div data-field-span="1" style="border-right: none;">   
                            <label for="concubinage_since">Depuis :</label>                                 
                        </div>
                        <div data-field-span="1" style="height: 41px;">   
                            <input class="form-control" id="concubinage_since" type="number" name="concubinage_since"  value="{{ $sheet->concubinage_since }}"  min="0" max="100" style="width: 65px; margin-top: -5px;">                                    
                        </div>
                        <div data-field-span="1" style="border-right: none;">   
                            <label for="concubinage_since">Ans</label>                                 
                        </div>
                    </div> 

                    <div data-row-span="12" class="fields-container">
                        <div data-field-span="2" style="width: 142px;"> 
                            <label for="dependent_children">Enfants à charge :
                            </label> 
                            <input type="text" value="{{$sheet->dependent_children}}" id="gbId" style="display: none">
                            @if($sheet->dependent_children === 1 && $dc_all_ages[0] == 0)   
                            <input class="form-control col-md-4" id="dependent_children" type="text" value="0" name="dependent_children"  style="width: 50px;" readonly>
                            @else             
                            <input class="form-control col-md-4" id="dependent_children" type="text" value="{{ $sheet->dependent_children }}" name="dependent_children"  style="width: 50px;" readonly>               
                            @endif
                            <div class="col-md-4" style=" margin-top: 6px;">
                                <a  id="ta-minus"  type="button">
                                <span class="ti-minus "></span>  
                                </a>                                      
                            </div>
                            <div class="col-md-4" style="margin-left: -14px; margin-top: 6px;">
                                <a   id="ta-plus" type="button">
                                <span class="ti-plus"></span>
                                </a>
                            </div>
                        </div>  
                        @if($sheet->dependent_children === 1 && $dc_all_ages[0] == 0)  
                        
                        @else
                            @foreach($dc_all_ages as $key=>$age)
                            <div data-field-span="1"  class="field-{{$key+1}}">
                                <label for="dc_age{{ $key+1 }}">Age enfant {{ $key+1 }}</label>
                                <input class="form-control" id="dc_age{{ $key+1 }}" type="number"  min="1" max="25" value="{{ $age }}" name="dc_age{{ $key+1 }}"    >  
                            </div>   
                            @endforeach 
                        @endif
                             
                    </div>

                    <div data-row-span="3">
                        <div data-field-span="1">
                            <label for="tel">N° Tel :</label>
                            <input class="form-control" id="tel" name="tel" type="text" value="{{ $sheet->tel }}">
                        </div>
                        <div data-field-span="1">
                            <label for="gsm">Portable :</label>
                            <input class="form-control" id="gsm" name="gsm" type="text"   value="{{ $sheet->gsm }}">
                        </div>
                        <div data-field-span="1">
                            <label for="email">Mail :</label>
                            <input class="form-control" id="email" name="email" type="email"  value="{{ $sheet->email }}">
                        </div>
                    </div>
                    <div data-row-span="1">
                        <div data-field-span="1">
                            <label for="address">Adresse Postale :</label>
                            <input class="form-control" id="address" name="address" type="text" value="{{ $sheet->address }}">
                        </div>
                    </div>
                </fieldset>
                <br>
                <br>
                <fieldset>
                    <legend>Observations :</legend>
                    <div data-row-span="4" style="border-bottom: none;">
                        <div data-field-span="4">
                            <textarea id="observations"  name="observations" class="resize_vertical" rows="4">{{ $sheet->observations }}</textarea>
                        </div> 
                    </div> 
                </fieldset> 
                <br>   
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
            {!! Form::close() !!} 
        </div>
    </div>
</section>
<!-- /.content -->
 <br><br>
@endsection
@section('javascript') 
 
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js" type="text/javascript"></script> 
<script src="{{ asset('admin') }}/js/custom_js/complex_form2.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/vendors/select2/js/select2.js" type="text/javascript" ></script>



<script>
$(document).ready(function(){   
  
     
    // $('#dependent_children').val(id);
    $('#ta-plus').click(function(e){
        var id = $('#dependent_children').val();
        console.log('plus');
        id++;
        if(id <= 10){
        $('#dependent_children').val(id);
        $('.fields-container').append(`<div data-field-span="1" class="field-${id}">
                                        <label for="dc_age${id}">Age enfant ${id}:</label>
                                        <input class="form-control" id="dc_age${id}" type="number"  min="1" max="25" name="dc_age${id}" >  
                                    </div> `);

        }  
        e.preventDefault();
    });
    $('.ti-minus').click(function(e){
        var id = $('#dependent_children').val();     
        var gbId = $('#gbId').val();
        // if(id != gbId ){
        if(id != 0 ){
            if($("#dc_age"+ id +'').length){ 
                 $('.field-'+ id +'').remove(); 
                id --;              
                  
                $('#dependent_children').val(id); 
                // console.log(id);
            }

        }
        console.log(id);
        e.preventDefault();

    });


    $("#select_state").select2({
        theme: "bootstrap"
    });

    
});
</script>

@endsection