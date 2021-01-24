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
                                    @role('Agent')
                                    <a href="{{route('sheets.create')}}" class="btn btn-info">
                                      <span class="ti-plus"></span> Nouveau Fiche  
                                    </a>
                                    <h3 class="pull-right" style="margin-top: 0px;">{{ $count_sheets }} Fiches/ {{date('d-m-Y')}}</h3>
                                    @endrole

                                    @role('Admin|Super Admin')
                                      @if(isset($agent_full_name))
                                      <h4>Liste des fiches créer par : <strong>{{strtoupper($agent_full_name)}}</strong></h4>
                                      @endif
                                    @endrole
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
                                        @role('Admin|Super Admin')       
                                          {!! Form::open(['method' => 'POST','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => ['getSheetsFilterByAgentId', $agent_last_name,$agent_id] ]) !!}    
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
                                                            <?php if($statusId != null && $status->id == $statusId)  echo 'selected="true"'?>
                                                            >{{ $status->name }}</option>
                                                            @endforeach
                                                          </select>
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
                                            @endrole
                                            @role('Closer')                                                    
                                            {!! Form::open(['method' => 'POST','class'=>'form-horizontal','enctype'=>'multipart/form-data', 'route' => 'getCloserFilter' ]) !!}    
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
                                                    <input class="form-control" name="created_at" placeholder="La date de création" value="<?php if($created_at != null) echo $created_at ?>" /><span class="input-group-append input-group-addon"><span class="input-group-text"><i class="fa fa-calendar"></i></span></span>
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
                                            @endrole
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" id="dataTableSheetsList">
                                                    <thead>
                                                        <tr>
                                                            <th style="font-size: 13px;">N°</th> 
                                                            <th style="font-size: 13px;">Créer par</th>
                                                            <th style="font-size: 13px;">Créer le</th>
                                                            @role('Closer')
                                                            <th style="font-size: 13px;" >Attribué le</th>
                                                            @endrole
                                                            <th style="font-size: 13px;">Nom complet</th>
                                                            <th style="font-size: 13px;">N° Tel</th>
                                                            @hasanyrole('Admin|Super Admin|Closer')  
                                                            <th style="font-size: 13px;">
                                                              Status
                                                            </th> 
                                                            @endhasanyrole
                                                            <th style="width: 150px; font-size: 13px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $order = 0 ?>
                                                    @foreach ($sheets as $sheet) 
                                                    <tr>
                                                        <td>{{ $order = $order+1 }}</td> 
                                                        <td>{{strtoupper($sheet->agent['first_name'] ) .' ' . strtoupper($sheet->agent['last_name'])}}</td>
                                                        <td>{{$sheet->created_at->format('Y-m-d')}}</td> 
                                                        @role('Closer')                                                        
                                                        <td>{{ date('Y-m-d',strtotime($sheet->distributed_at))}}</td>
                                                        @endrole
                                                        <td>{{$sheet->m_last_name .' '.$sheet->m_first_name}} - {{$sheet->w_last_name .' '.$sheet->w_first_name}}</td>   
                                                        <td>{{$sheet->tel}}</td>                                                         
                                                        @hasanyrole('Admin|Super Admin|Closer')  
                                                        <td>
                                                            <div class="text-center">
                                                            @if($sheet->status != 0)
                                                            <span class="label label-sm {{$sheet->statusList['color']}}">
                                                            {{$sheet->statusList['name']}}
                                                            </span> 
                                                            @else
                                                            <span class="label label-sm label-primary">
                                                            @role('Admin|Super Admin')
                                                              Non attribué
                                                            @endrole
                                                            @role('Closer')
                                                              Aucun status
                                                            @endrole
                                                            </span> 
                                                            @endif
                                                            </div>
                                                        </td>  
                                                        @endhasanyrole
                                                        <td>     
                                                            <a href="{{ route('sheets.show', $sheet['id'])}}" 
                                                            class="btn btn-icon btn-info m-r-50"                                                            
                                                            data-toggle="tooltip" 
                                                            data-tooltip="tooltip" 
                                                            data-placement="top"
                                                            data-original-title="Voir"
                                                            >
                                                            <i class="ti-eye" aria-hidden="true"></i>
                                                            </a>   
                                                            <a href="{{ route('sheets.edit', $sheet['id'])}}" 
                                                            class="btn btn-icon btn-warning m-r-50"                                                                                                                       
                                                            data-toggle="tooltip" 
                                                            data-tooltip="tooltip" 
                                                            data-placement="top"
                                                            data-original-title="Modifier"
                                                            >
                                                                <i class="ti-pencil" aria-hidden="true"></i>
                                                            </a> 
                                                            @role('Admin|Super Admin') 
                                                                <!-- <span class="bs-example tooltip-demo">
                                                                    <button class="btn tooltips btn-primary" data-toggle="tooltip"
                                                                    data-tooltip="tooltip" data-placement="top"
                                                                    data-original-title="{{ strtoupper($sheet->closer['first_name']) .' ' . strtoupper($sheet->closer['last_name']) }}"
                                                                    style="margin-bottom:10px;"><span class="ti-check-box"></span></button>
                                                                </span> -->  
                                                            <a class="btn btn-success btn-circle update" 
                                                                href="#distributeSheet{{$sheet['id']}}" 
                                                                data-sfid="distributeSheet{{$sheet['id']}}" 
                                                                data-toggle="modal"  >
                                                                <?php if($sheet->closer_id !=0){ ?>
                                                                <i class="fa fa-fw fa-wrench"></i>
                                                                <?php } else { ?>
                                                                <i class="ti-wand" aria-hidden="true"></i>
                                                                <?php } ?>
                                                            </a>  
                                                            <a class="btn btn-danger btn-circle update" 
                                                                href="#deletesheet{{$sheet['id']}}" 
                                                                data-sfid="deletesheet{{$sheet['id']}}" 
                                                                data-toggle="modal">
                                                                <i class="ti-trash" aria-hidden="true"></i>
                                                            </a>
                                                            @if($sheet->email != '')
                                                            <a class="btn btn-default btn-circle update" 
                                                                href="#sendEmailToClient{{$sheet['id']}}" 
                                                                data-sfid="sendEmailToClient{{$sheet['id']}}" 
                                                                data-toggle="modal" > 
                                                                <span class="ti-email"></span> 
                                                            </a> 
                                                            @endif
                                                            @endrole
                                                            @role('Closer')
                                                                @can('send-email')
                                                              @if($sheet->email != '')
                                                              <a class="btn btn-default btn-circle update" 
                                                                  href="#sendEmailToClient{{$sheet['id']}}" 
                                                                  data-sfid="sendEmailToClient{{$sheet['id']}}" 
                                                                  data-toggle="modal" > 
                                                                  <span class="ti-email"></span> 
                                                              </a> 
                                                              @endif

                                                                @endcan
                                                            @endrole
                                                        </td> 
                                                    </tr> 

                                                    @can('send-email')
                                                    <div class="modal fade animated" id="sendEmailToClient{{$sheet['id']}}" tabindex="-1" role="dialog" aria-labelledby="sendEmailToClient{{$sheet['id']}}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                    <h4 class="modal-title custom_align" id="Heading5">Envoyer Email</h4>
                                                                </div>
                                                                <div class="modal-body" style="margin-bottom: 20px">                                                             
                                                                        {!! Form::open(array(
                                                                            'style' => 'display: inline-block;',
                                                                            'method' => 'POST',
                                                                            'route' => 'sendEmail')) !!}
                                                                        <input type="hidden" name="sheet_id" value="{{ $sheet->id }}"> 
                                                                          
                                                                          <div class="alert alert-info">                                                                              
                                                                              <span class="ti-info-alt" style="font-size: 34px;"></span>
                                                                              &nbsp;<h5 class="text-center" style="margin-top: -30px;">Envoyer l'email de Confirmation a : <strong>{{ $sheet->m_first_name.' ' .$sheet->m_last_name }}</strong></h5>
                                                                               <br>
                                                                               <h5 class="text-center">L'adresse email : <strong>{{ $sheet->email }}</strong></h5>
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
                                                    @role('Admin|Super Admin')    
                                                    <div class="modal fade animated" id="deletesheet{{$sheet['id']}}" tabindex="-1" role="dialog" aria-labelledby="deletesheet{{$sheet['id']}}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                    <h4 class="modal-title custom_align" id="Heading5">Spprimer la fiche N°/ {{$sheet->id }}</h4>
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
                                                                            'route' =>  'deleteSheet')) !!}
                                                                              <input type="hidden" name="sheet_id" id="md_delete_sheet_id"   value="{{$sheet->id }}">
                                                                        {!! Form::submit('Oui', array('class' => 'btn btn-danger','style'=>'margin-bottom: -10px;')) !!}
                                                                        {!! Form::close() !!}
                                                                    <button type="button" class="btn btn-success" data-dismiss="modal">
                                                                        Non
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div> 

                                                    <div class="modal fade animated" id="distributeSheet{{$sheet['id']}}" tabindex="-1" role="dialog" aria-labelledby="distributeSheet{{$sheet['id']}}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                    <h4 class="modal-title custom_align" id="Heading5">Distribuer la fiche N°/ {{$sheet->id }} a un closer </h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-10 col-md-offset-1">                                                                                                                                     
                                                                        {!! Form::open(array(
                                                                            'style' => 'display: inline-block;',
                                                                            'method' => 'PUT',
                                                                            'route' => ['distributeToCloser'])) !!} 
                                                                            {!! csrf_field() !!}         
                                                                            <input type="hidden" name="sheet_id" value="{{ $sheet->id }}">                                                                   
                                                                        <div class="custom-select" style="width:auto;">
                                                                            <select name="closer_id">
                                                                                <option value="0">::Selectioner un Closer ::</option>
                                                                                @foreach($users_closer as $closer) 
                                                                                <option value="{{$closer->id}}" <?php if($sheet->closer_id === $closer->id) echo 'selected="true"'?>>{{ $closer->first_name .' ' . $closer->last_name }}</option> 
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <br><br>  
                                                                        <div class="pull-right">
                                                                        @if($sheet->closer_id != 0)
                                                                            {!! Form::submit('Redistribuer', array('class' => 'btn btn-success','style'=>'margin-bottom: -10px;')) !!}
                                                                        @else
                                                                            {!! Form::submit('Confirmer', array('class' => 'btn btn-success','style'=>'margin-bottom: -10px;')) !!}
                                                                        @endif           
                                                                        </div>
                                                                        {!! Form::close() !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer ">  
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                                                        Non
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                    @endrole                                                
                                                    @endforeach  
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
 var x, i, j, selElmnt, a, b, c;
/* Look for any elements with the class "custom-select": */
x = document.getElementsByClassName("custom-select");
for (i = 0; i < x.length; i++) {
  selElmnt = x[i].getElementsByTagName("select")[0];
  /* For each element, create a new DIV that will act as the selected item: */
  a = document.createElement("DIV");
  a.setAttribute("class", "select-selected");
  a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
  x[i].appendChild(a);
  /* For each element, create a new DIV that will contain the option list: */
  b = document.createElement("DIV");
  b.setAttribute("class", "select-items select-hide");
  for (j = 1; j < selElmnt.length; j++) {
    /* For each option in the original select element,
    create a new DIV that will act as an option item: */
    c = document.createElement("DIV");
    c.innerHTML = selElmnt.options[j].innerHTML;
    c.addEventListener("click", function(e) {
        /* When an item is clicked, update the original select box,
        and the selected item: */
        var y, i, k, s, h;
        s = this.parentNode.parentNode.getElementsByTagName("select")[0];
        h = this.parentNode.previousSibling;
        for (i = 0; i < s.length; i++) {
          if (s.options[i].innerHTML == this.innerHTML) {
            s.selectedIndex = i;
            h.innerHTML = this.innerHTML;
            y = this.parentNode.getElementsByClassName("same-as-selected");
            for (k = 0; k < y.length; k++) {
              y[k].removeAttribute("class");
            }
            this.setAttribute("class", "same-as-selected");
            break;
          }
        }
        h.click();
    });
    b.appendChild(c);
  }
  x[i].appendChild(b);
  a.addEventListener("click", function(e) {
    /* When the select box is clicked, close any other select boxes,
    and open/close the current select box: */
    e.stopPropagation();
    closeAllSelect(this);
    this.nextSibling.classList.toggle("select-hide");
    this.classList.toggle("select-arrow-active");
  });
}

function closeAllSelect(elmnt) {
  /* A function that will close all select boxes in the document,
  except the current select box: */
  var x, y, i, arrNo = [];
  x = document.getElementsByClassName("select-items");
  y = document.getElementsByClassName("select-selected");
  for (i = 0; i < y.length; i++) {
    if (elmnt == y[i]) {
      arrNo.push(i)
    } else {
      y[i].classList.remove("select-arrow-active");
    }
  }
  for (i = 0; i < x.length; i++) {
    if (arrNo.indexOf(i)) {
      x[i].classList.add("select-hide");
    }
  }
}

/* If the user clicks anywhere outside the select box,
then close all select boxes: */
document.addEventListener("click", closeAllSelect); 

$( document ).ready(function() {
    $("#datepicker").datepicker({ 
      autoclose: true,
      format: 'yyyy-mm-dd', 
      todayHighlight: true
    });
}); 
 </script>
 
 @endsection