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
    <input type="hidden" id="agent_id" value="{{ $current_agent->id }}">
    <div class="row">        
        <div class="col-md-12">           
            <div class="panel ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="ti-pie-chart"></i> Statistiques d'agent : <strong id="agentName">{{ $current_agent->first_name .''. $current_agent->last_name}}</strong>
                    </h4> 
                </div>
                <div class="panel-body"> 
                    <div class="row">
                      <div class="form-group col-md-8 col-md-offset-2"> 
                        <div class="row">
                          <div class="col-md-4" style="text-align: right;">
                              <label for="closer_id"  style="margin-top: 9px;text-align: right;padding: 0px">Liste des agents :</label>
                          </div>
                          <div class="col-md-6">
                            <select class="form-control" id="select_agent" name="status">  
                                @foreach($list_agent as $agent)
                                <option value="{{$agent->id}}" <?php  if($agent->id === $current_agent->id ) echo 'selected' ?> >{{$agent->last_name .' '. $agent->first_name}}</option> 
                                @endforeach
                            </select>                           
                          </div>
                          
                        </div>


                      </div>
                    </div>
                    <div class="row m-t-10">
                        <div class="col-md-6 col-md-offset-1">
                          <label for="specificDateValue" class="col-md-5" style="margin-top: 9px;text-align: right;">La date de création :</label>

                          <div class="form-group   col-md-7">
                              <div class='input-group date' id='specificDate'>
                                  <input type='text' class="form-control" id="specificDateValue"  placeholder="La date de création"/>
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                          </div>
                        </div>   
                        <div class="col-md-3 text-center">                                                                        
                            <div class="form-group"  >   
                                <button  type="button" class="btn btn-success" id="filter1" style="margin-top: 0px" >
                                  <span class="ti-filter"></span>
                                  Filtrer 
                                </button>  
 
                                <button  type="button" class="btn btn-success" id="filter2" style="margin-top: 0px;display: none" >
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
                        <canvas id="chart-sheets-per-agent" width="1140" height="450"></canvas>  
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

  $('#specificDate').datetimepicker({

    format:'DD-MM-YYYY'

  }); 
  var agentId = $('#agent_id').val();

  $('#select_agent').change(function(e){
    $('#filter1').hide();
    $('#filter2').show();
    $('#specificDateValue').val('');
  });

 


  // chart sheet per agent global
  function loadChartData(agentIdComing){
      $.ajax({
      url: '/chart-agent-statistic/'+ agentIdComing ,
      beforeSend: function(){
      // Show image container
      $("#ajaxloader").show();
      },
      success: function(dataAgent) {
        if(dataAgent.allsheets != 0){
        var errorMessage =  $('#errorMessage');
        var pieData = {
            labels: [
                "Tous les fiche" 
            ],
            datasets: [{
                data: [dataAgent.allsheets],
                backgroundColor: [
                    "#428BCA" 
                ],
                hoverBackgroundColor: [
                    "#428BCA" 
                ]
            }]
        };

        function drawChartSheetsPerAgent() {

            var selector5 = '#chart-sheets-per-agent';

            $(selector5).attr('width', $(selector5).parent().width());
            var myPie = new Chart($("#chart-sheets-per-agent"), {
                type: 'pie',
                data: pieData,
                options:{
                title:{
                  display: true,
                  text: 'Statistique de tous les fiches :' + pieData.datasets[0].data
                } 
              }
            });
        }

        drawChartSheetsPerAgent();

        } else {
          Command: toastr["error"]("Il y a pas de fiche pour cet agent !", "Statistiques")   
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

  loadChartData(agentId);


  $('#filter1').click(function(e){
      $('#chart-sheets-per-agent').remove(); 
      $('#chart-container').append('<canvas id="chart-sheets-per-agent" width="1140" height="450"><canvas>');
        
      e.preventDefault();
      var specificDate =  $('#specificDateValue').val();
      var specificDateSend =  $('#specificDateValue').val(); 

      // specificDateSend =  moment(specificDateSend).format("DD-MM-YYYY");
      // console.log(specificDateSend);
      if(specificDate !='' ){
        specificDate = new Date(specificDate);
         
        reloadChart(specificDateSend, agentId);
        
      } else {   
        loadChartData(agentId); 
      }
  });

  function reloadChart(specificDate, agentIdComing ){
  
    $.ajax({
        url: '/chart-agent-statistic-date/'+ agentIdComing +'/' + specificDate ,
        beforeSend: function(){
        // Show image container
        $("#ajaxloader").show();
        },
        success: function(dataAgent) {
          if(dataAgent.allsheets != 0){
          var errorMessage =  $('#errorMessage');
          var pieData = {
              labels: [
                  "Tous les fiche" 
              ],
              datasets: [{
                  data: [dataAgent.allsheets],
                  backgroundColor: [
                      "#428BCA" 
                  ],
                  hoverBackgroundColor: [
                      "#428BCA" 
                  ]
              }]
          };

          function drawChartSheetsPerAgentAndDate() {

              var selector5 = '#chart-sheets-per-agent';

              $(selector5).attr('width', $(selector5).parent().width());
              var myPie = new Chart($("#chart-sheets-per-agent"), {
                  type: 'pie',
                  data: pieData,
                  options:{
                  title:{
                    display: true,
                    text: 'Statistique de tous les fiches  :' + pieData.datasets[0].data
                  } 
                }
              });
          }

          drawChartSheetsPerAgentAndDate();

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



  $('#filter2').click(function(e){
      $('#chart-sheets-per-agent').remove(); 
      $('#chart-container').append('<canvas id="chart-sheets-per-agent" width="1140" height="450"><canvas>');
          
          
      e.preventDefault();
      var newAgentId = $('#select_agent').val();
      var agentName  = $('#agentName');
      agentName.text('');
      
      var specificDate =  $('#specificDateValue').val();
      var specificDateSend =  $('#specificDateValue').val(); 

      specificDateSend =  moment(specificDateSend).format("YYYY-MM-DD");
      // console.log(specificDateSend);
      // get agent name 
       $.ajax({
        url: '/user-infos/'+ newAgentId , 
        success: function(dataAgent) {  
          agentName.text(dataAgent.user.first_name + ' ' + dataAgent.user.last_name );
        }
      });
      if(specificDate !='' ){
        specificDate = new Date(specificDate);
         
        reloadChart(specificDateSend, newAgentId);
        
      } else {
        loadChartData(newAgentId); 
      }
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
@endsection