@extends('layouts.app')

@section('css') 
<link rel="stylesheet" href="{{ asset('admin') }}/vendors/lcswitch/css/lc_switch.css">
<link href="{{ asset('admin') }}/css/custom_css/chartjs-charts.css" rel="stylesheet" type="text/css">

@endsection

@section('content') 
<style>
    

[data-title] { 
  position: relative; 
}
[data-title]:hover::before {
  content: attr(data-title);
  position: absolute;
  top: -27px;
  left:0px;
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
  top: -10px;
  left: 30px;
  display: inline-block;
  color: #fff;
  border: 8px solid transparent;  
  border-top: 8px solid #000;
}
</style>


<section class="content">

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif  
    <input type="hidden" id="closer_id" value="{{ $current_closer->id }}">
    <div class="row">        
        <div class="col-md-12">           
            <div class="panel ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="ti-pie-chart"></i> Statistiques du closer : <strong id="agentName"> {{ $current_closer->first_name .''. $current_closer->last_name}}</strong>
                    </h4> 
                </div>
                <div class="panel-body"> 
                    <div class="row m-t-10">
                        <div class="col-md-5 ">
                          <label for="dateBeginValue" class="col-md-5" style="margin-top: 9px;text-align: right;padding: 0px">La date de début :</label>
                          <div class="form-group  col-md-7">
                              <div class='input-group date' id='dateBegin'>
                                  <input type='text' class="form-control" id="dateBeginValue" placeholder="La date de début" />
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                          </div>
                        </div> 
                        <div class="col-md-5">
                          <label for="dateEndValue" class="col-md-5" style="margin-top: 9px;text-align: right;padding: 0px;">La date de fin :</label>

                          <div class="form-group  col-md-7">
                              <div class='input-group date' id='dateEnd'>
                                  <input type='text' class="form-control" id="dateEndValue"  placeholder="La date de fin" />
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                          </div>
                        </div> 
                        <div class="col-md-2 text-center">                                                                        
                            <div class="form-group"  >   
                                <button  type="button" class="btn btn-success" id="filter" style="margin-top: 0px" >                                  
                                  <span class="ti-filter"></span>
                                  Filtrer 
                                </button>
                            </div> 
                        </div>
                    </div> 
                    <br> 
                    <div class="col-md-6 col-md-offset-3 alert alert-danger showError" style="display: none">
                      <h3 style="color: #fff" id="errorMessage" class="text-center"></h3>
                    </div> 
                    <div id='ajaxloader' style='display: none;'>
                      <img src='/uploads/admin/loader2.gif' width='200px' height='200px' style="margin-top: -10%; margin-left: 41%; margin-bottom: -450px;">
                    </div>
                    <div id="chart-container">
                        <canvas id="chart-sheets-per-closer" width="1140" height="450"></canvas>  
                    </div>
                </div>
            </div>
        </div>
    </div> 


 


</section>
@endsection

@section('javascript')    
<script src="{{ asset('admin') }}/vendors/chartjs/js/Chart.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/vendors/flip/js/jquery.flip.min.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/vendors/lcswitch/js/lc_switch.min.js"></script>

 

<script type="text/javascript" >
"use strict";
$(document).ready(function() { 

  $('#dateBegin').datetimepicker({
    format:'DD-MM-YYYY'
  });
  $('#dateEnd').datetimepicker({
    format:'DD-MM-YYYY'
  });

  var closerId = $('#closer_id').val();
// chart sheet per agent global
$.ajax({
    url: '/chart-closer-statistic/'+ closerId ,
    beforeSend: function(){
    // Show image container
    $("#ajaxloader").show();
    },
    success: function(dataCloser) {
      if(dataCloser.sheetPerCloser != 0){ 
        var pieData = {
            labels: [ 
                "Réservation",
                "Rappel",
                "NRP",
                "Refus"
            ],
            datasets: [{
                data: [dataCloser.countReservationSheets, dataCloser.countRappelSheets , dataCloser.countNRPSheets, dataCloser.countRefusSheets],
                backgroundColor: [ 
                    "#22d69d",
                    "#f0ad4e",
                    "#0fafff",
                    "#ff3333"
                ],
                hoverBackgroundColor: [ 
                    "#22d69d",
                    "#f0ad4e",
                    "#0fafff",
                    "#ff3333"
                ]
            }]
        };

        function draw4() {

            var selector5 = '#chart-sheets-per-closer';

            $(selector5).attr('width', $(selector5).parent().width());
            var myPie = new Chart($("#chart-sheets-per-closer"), {
                type: 'pie',
                data: pieData,
                options:{
                title:{
                  display: true,
                  text: 'Total des fiches : ' + (dataCloser.countReservationSheets + dataCloser.countRappelSheets + dataCloser.countNRPSheets + dataCloser.countRefusSheets)
                } 
              }
            });
        }

        draw4();

      } else {
          Command: toastr["error"]("Il y a pas de fiche pour ce closer !", "Statistiques")   
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
      }

    },
    complete:function(data){
          // Hide image container
          $("#ajaxloader").hide();
    }
});
 
  $('#filter').click(function(e){
      e.preventDefault();
      var dateBegin = $('#dateBeginValue').val();
      var dateEnd = $('#dateEndValue').val();

      var dateBeginSend = $('#dateBeginValue').val();
      var dateEndSend = $('#dateEndValue').val(); 
      
      // dateEndSend = moment(dateEndSend).add(1, 'days');
      // dateEndSend =  moment(dateEndSend).format("YYYY-MM-DD");
      dateEndSend =  dateEnd;
      console.log(dateEndSend);
      if(dateBegin !='' && dateEnd !='' ){
        dateBegin = new Date(dateBegin);
        dateEnd = new Date(dateEnd);
        // console.log(dateBegin);
        // console.log(dateEnd); 
        if (dateBegin > dateEnd)
        { 
            console.log('start date is greater than end date');
            //error
            Command: toastr["error"]("La date de début doit être inférieure à la date de fin !", "Statistiques")  
            
        }
        else if(dateBegin < dateEnd)
        {
            console.log('start date is smaller than end date');
            // magic
            reloadChart(dateBeginSend, dateEndSend );
        }else{
          console.log('equal');
            reloadChart(dateBeginSend, dateEndSend );
        }


      } else {
        Command: toastr["error"]("La date de début/fin est obligatoire !", "Statistiques")   
      }
  });

  function reloadChart(dateBegin, dateEnd ){
    $('#chart-sheets-per-closer').remove(); 
    $('#chart-container').append('<canvas id="chart-sheets-per-closer" width="1140" height="450"><canvas>');
                 
    $.ajax({
        url: '/chart-closer-statistic-date/'+ closerId +'/' + dateBegin + '/' + dateEnd ,
      beforeSend: function(){
      // Show image container
      $("#ajaxloader").show();
      },
        success: function(dataCloser) {
          if(dataCloser.sheetPerCloser != 0){ 
            var pieData = {
                  labels: [ 
                      "Réservation",
                      "Rappel",
                      "NRP",
                      "Refus"
                  ],
                  datasets: [{
                      data: [dataCloser.countReservationSheets, dataCloser.countRappelSheets , dataCloser.countNRPSheets, dataCloser.countRefusSheets],
                      backgroundColor: [ 
                          "#22d69d",
                          "#f0ad4e",
                          "#0fafff",
                          "#ff3333"
                      ],
                      hoverBackgroundColor: [ 
                          "#22d69d",
                          "#f0ad4e",
                          "#0fafff",
                          "#ff3333"
                      ]
                  }]
              };

              function draw4() {

                  var selector5 = '#chart-sheets-per-closer';

                  $(selector5).attr('width', $(selector5).parent().width());
                  var myPie = new Chart($("#chart-sheets-per-closer"), {
                      type: 'pie',
                      data: pieData,
                      options:{
                      title:{
                        display: true,
                        text: 'Total des fiches par date : ' + (dataCloser.countReservationSheets + dataCloser.countRappelSheets + dataCloser.countNRPSheets + dataCloser.countRefusSheets)
                      } 
                    }
                  });
              }

              draw4();

          } else {  
            Command: toastr["error"]("Il y a pas de fiche dans cette date !", "Statistiques")  
                // setTimeout(function() {
                //     $("#errorMessage").text('');                       
                // }, 5000);
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
            }

        },
        complete:function(data){
            // Hide image container
            $("#ajaxloader").hide();
       }
    });
  }


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
@endsection