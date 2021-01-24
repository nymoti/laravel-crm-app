@extends('layouts.app')
 
@section('css')
  
<link href="{{ asset('admin') }}/vendors/iCheck/css/all.css" rel="stylesheet"/>
<link href="{{ asset('admin') }}/vendors/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/custom.css">
<link rel="stylesheet" href="{{ asset('admin') }}/css/custom_css/skins/skin-default.css" type="text/css" id="skin"/>
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/formelements.css">
@endsection
@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-fw ti-move"></i> 
                        Modifier une fiche
                    </h3>
                    <span class="pull-right">
                            <i class="fa fa-fw ti-angle-up clickable"></i>
                            <i class="fa fa-fw ti-close removepanel clickable"></i>
                        </span>
                </div>
                <div class="panel-body" style="margin:20px;">
                {!! Form::open(['method' => 'PUT','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => ['sheets.update', $sheet->id] ]) !!}    
                        {!! csrf_field() !!}
                        <input type="hidden" name="created_by" value="{{Auth()->user()->id}}">
                        <input type="hidden" name="updated_by" value="{{Auth()->user()->id}}">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Q1 - Quels sont les 5 dérniers pays que vous avez visités et en quelle annés ?</h4>
                            </div>
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <textarea rows="4" class="form-control resize_vertical"
                                        placeholder="---" 
                                        name="question_1">
                                        {{ old( 'question_1' , $sheet->question_1 ) }}
                                    </textarea>
                                </div> 
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-7">
                                <h4>Q1 - Quels sont les 5 prochaines annés voulez-vous voyager </h4>
                            </div>
                            <div class="col-md-3" style="margin-top: 9px;">                                 
                                <div class="col-sm-12"> 
                                    <div class="iradio">
                                        <label>
                                            <input type="radio" name="question_2" id="optionsRadios2"
                                                    value="1" <?php  if($sheet->question_2 === 1) echo 'checked' ?>  > Plus
                                        </label>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-md-2" style="margin-top: 9px;">                                 
                                <div class="col-sm-12"> 
                                    <div class="iradio">
                                        <label>
                                            <input type="radio" name="question_2" id="optionsRadios2"
                                                    value="0" <?php  if($sheet->question_2 === 0) echo 'checked' ?>> Ou moins
                                        </label>
                                    </div> 
                                </div>
                            </div> 
                        </div>
                        <div class="row">                        
                            <div class="col-md-12"  >
                                <div class="form-group"> 
                                    <div class="col-sm-2">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox1" name="times" value="1" <?php  if($sheet->times === 1) echo 'checked' ?>> Temps
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox2" name="financials"  value="1" <?php  if($sheet->financials === 1) echo 'checked' ?>> Financiers
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="childrens"  value="1" <?php  if($sheet->childrens === 1) echo 'checked' ?>> Enfants
                                        </label>
                                    </div>
                                    <div class="col-sm-2">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="distance"  value="1" <?php  if($sheet->distance === 1) echo 'checked' ?>> Distance
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="no_oppportunity"  value="1" <?php  if($sheet->no_oppportunity === 1) echo 'checked' ?>> Pas eu l'occasion
                                        </label>
                                    </div>
                                </div> 
                            </div> 
                        </div>  
                        <br>
                        <div class="row">
                            <div class="col-md-7">
                                <h4>Q3 - D'habitude vos voyages de font par quels moyens ? </h4>
                            </div>   
                        </div>
                        <div class="row">                        
                            <div class="col-md-12"  >
                                <div class="form-group"> 
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox1" name="travel_agency"  value="1" <?php  if($sheet->travel_agency === 1) echo 'checked' ?>> Agence de voyage
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox2" name="tour_operator"  value="1" <?php  if($sheet->tour_operator === 1) echo 'checked' ?>> Tour operateur
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="only"  value="1" <?php  if($sheet->only === 1) echo 'checked' ?>> Seul
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="company_comittee"  value="1" <?php  if($sheet->company_comittee === 1) echo 'checked' ?>> Comite d'entreprise
                                        </label>
                                    </div> 
                                </div> 
                            </div> 
                        </div>  
                        <br>
                        <div class="row">
                            <div class="col-md-7">
                                <h4>Q4 - Quelles activités aimeriez-vous pratiquer durant vos vacances ? </h4>
                            </div>   
                        </div>
                        <div class="row">                        
                            <div class="col-md-12"  >
                                <div class="form-group"> 
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox1" name="cultural_visits"  value="1" <?php  if($sheet->cultural_visits === 1) echo 'checked' ?>> Visites culturelles
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox2" name="exercusions"  value="1" <?php  if($sheet->exercusions === 1) echo 'checked' ?>> Excursions
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="sports"  value="1" <?php  if($sheet->sports === 1) echo 'checked' ?>> Sports
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="balneo"  value="1" <?php  if($sheet->balneo === 1) echo 'checked' ?>> Balneo
                                        </label>
                                    </div> 
                                </div> 
                            </div> 
                        </div>     
                        <br>
                        
                        <div class="row">
                            <div class="col-md-7">
                                <h4>Q5 - Pouvez-vous m'évlouer votre budget de voyage annuelle ? </h4>
                            </div>   
                        </div>
                        <div class="row">                        
                            <div class="col-md-12"  >
                                <div class="form-group"> 
                                    <div class="col-sm-2">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox1" name="less_of_1500"  value="1" <?php  if($sheet->less_of_1500 === 1) echo 'checked' ?>>Moins de 1500$
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox2" name="from_1500_to_2000"  value="1" <?php  if($sheet->from_1500_to_2000 === 1) echo 'checked' ?>>De 1500 A 2000$
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="from_2000_to_3000"  value="1" <?php  if($sheet->from_2000_to_3000 === 1) echo 'checked' ?>> De 2000 A 3000$
                                        </label>
                                    </div>
                                    <div class="col-sm-3" style="width: 184px;">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="from_3000_to_4000"  value="1" <?php  if($sheet->from_3000_to_4000 === 1) echo 'checked' ?>>De 3000 A 4000$
                                        </label>
                                    </div> 
                                    <div class="col-sm-1" style="width: 150px;">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="or_more"  value="1" <?php  if($sheet->or_more === 1) echo 'checked' ?>>Ou plus
                                        </label>
                                    </div>
                                </div> 
                            </div> 
                        </div>  
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label"  >Nom - H : </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control mLastName" id="input-text"
                                                placeholder="Nom" name="m_last_name" value="{{ $sheet->m_last_name }}">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label">Prenom : </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Prenom" name="m_first_name" value="{{ $sheet->m_first_name }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-5 control-label">Code Client : </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control clientCode" id="input-text"
                                                placeholder="Prenom" name="client_code" readonly value="{{ $sheet->client_code }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Profession : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Profession" name="m_profession" value="{{ $sheet->m_profession }}">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Age : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Age" name="m_age"  value="{{ $sheet->m_age }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Nom - F : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Nom" name="w_last_name" value="{{ $sheet->w_last_name }}">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Prenom : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Prenom" name="w_first_name" value="{{ $sheet->w_first_name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Profession : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Profession" name="w_profession" value="{{ $sheet->w_profession }}">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label">Age : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Age" name="w_age" value="{{ $sheet->w_age }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">                        
                            <div class="col-md-12"  >
                                <div class="form-group"> 
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox1" name="married" value="1" <?php  if($sheet->married === 1) echo 'checked' ?> > Marié
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox2" name="single" value="1" <?php  if($sheet->single === 1) echo 'checked' ?> > Célibataire
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="divorced" value="1" <?php  if($sheet->divorced === 1) echo 'checked' ?> > Divorcée
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox3" name="widower" value="1" <?php  if($sheet->widower === 1) echo 'checked' ?> > Veuf
                                        </label>
                                    </div> 
                                </div> 
                            </div> 
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group"> 
                                    <div class="col-sm-6">
                                        <label class="checkbox-inline icheckbox">
                                            <input type="checkbox" id="inlineCheckbox1"   name="concubinage" value=""  <?php  if($sheet->concubinage === 1) echo 'checked' ?> > Concubinage
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Depuis : </label>
                                    <div class="col-sm-9">
                                        <input type="number" class="form-control" id="input-text"
                                                placeholder="Ages" name="concubinage_since" value="{{ $sheet->concubinage_since }}">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-4 control-label"> Enfants à charge : </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Enfants à charge" name="dependent_children" value="{{ $sheet->dependent_children }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Ages : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Ages" name="dependent_children_ages"  value="{{ $sheet->dependent_children_ages }}">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> N° Tel : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="N° TEL" name="tel" value="{{ $sheet->tel }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> GSM : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="GSM" name="gsm"  value="{{ $sheet->gsm }}">
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Mail : </label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="input-text"
                                                placeholder="email@email.com" name="email"  value="{{ $sheet->email }}">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <br>                        
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Adresse Postale : </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="input-text"
                                                placeholder="Adresse postale" name="address"  value="{{ $sheet->address }}">
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <br>                        
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="input-text" class="col-sm-3 control-label"> Observations : </label>
                                    <div class="col-sm-9"> 
                                        <textarea rows="4" class="form-control resize_vertical"
                                                    placeholder="................." name="observations"> {{ $sheet->observations }}</textarea>
                                    </div>
                                </div> 
                            </div>
                        </div>
                        <br>
                        <input type="submit" value="Modifier" class="btn btn-success" id="modifier" style="float: right;" >
                    {!! Form::close() !!} 
                </div>
            </div>
        </div>
    </div>
</section>
                            
@endsection 


@section('javascript')
<script src="{{ asset('admin') }}/vendors/iCheck/js/icheck.js"></script>
<script src="{{ asset('admin') }}/vendors/bootstrap-fileinput/js/fileinput.min.js" type="text/javascript"></script>
<script src="{{ asset('admin') }}/js/custom_js/form_elements.js"></script> 

<script>

$(document).ready(function(){  
    $('.mLastName'). on('keyup',function(e){        
        var clientCode = $('.clientCode');
        var firstFourChar = $(this).val().substring(0,4); 
        var randomNumber = Math.random().toString(26).substr(2,10);
        // console.log(randomNumber);
        clientCode.val(firstFourChar +'-'+ randomNumber); 
        e.preventDefault();
    });
    $("#modifier").click(function() {
        alert( "Handler for .submit() called." );
        $("input[type=checkbox]").each(function() {
            if($(this).is(':checked')) {
                $(this).val('1');  
            } 
        }); 
    });
        $("input:checkbox:not(:checked)").each(function() {
            console.log($(this));
            if(!$(this).is(':checked')) {
                $(this).val('0');  
            } 
        });
});
</script>
 
@endsection
