@extends('layouts.app')

@section('css') 
@role('Admin')
<link rel="stylesheet" href="{{ asset('admin') }}/vendors/lcswitch/css/lc_switch.css">
<link href="{{ asset('admin') }}/css/custom_css/chartjs-charts.css" rel="stylesheet" type="text/css">
@endrole
@role('Closer')
<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/vendors/animate/animate.min.css">
<link href="{{ asset('admin') }}/css/timeline.css" rel="stylesheet"/>
@endrole

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
@if(Auth()->user()->active === 0)
    <div class="row">
        <div class="col-md-12"> 
                <div class="row">                        
                    <div class="col-sm-6 col-md-6 col-sm-offset-3  col-md-offset-3">
                        <div class="panel panel-danger">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    Compte désactivé
                                </h3>
                            </div>
                            <div class="panel-body">
                                <p>Votre compte est désactivé. Contactez votre admin pour le activé !</p>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
@else

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
 
    @role('Admin|Super Admin')
    <div class="row">
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="flip">
                <div class="widget-bg-color-icon card-box front">
                    <div class="bg-icon pull-left">
                        <i class="ti-files text-warning"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark"><b>{{ $countAllSheets }}</b></h3>
                        <p>Tous les fiches</p>
                    </div>
                    <div class="clearfix"></div>
                </div> 
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="flip">
                <div class="widget-bg-color-icon card-box front">
                    <div class="bg-icon pull-left">
                        <i class="ti-bookmark-alt text-success"></i>
                    </div>
                    <div class="text-right">
                        <h3><b id="widget_count3">{{ $countReservedSheets }}</b></h3>
                        <p>Total Réservation</p>
                    </div>
                    <div class="clearfix"></div>
                </div> 
            </div>
        </div>

        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="flip">
                <div class="widget-bg-color-icon card-box front">
                    <div class="bg-icon pull-left">
                        <i class="ti-headphone-alt text-danger"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark"><b>{{$countRecallSheets}}</b></h3>
                        <p>Total Rappel</p>
                    </div>
                    <div class="clearfix"></div>
                </div> 
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-3">
            <div class="flip">
                <div class="widget-bg-color-icon card-box front">
                    <div class="bg-icon pull-left">
                        <i class="ti-user text-info"></i>
                    </div>
                    <div class="text-right">
                        <h3 class="text-dark"><b>{{$countActiveAgents}}</b></h3>
                        <p>Les agents active</p>
                    </div>
                    <div class="clearfix"></div>
                </div> 
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8  col-xs-12">            
            <div class="panel ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="ti-pie-chart"></i> Fin de journée
                    </h4> 
                </div>
                <div class="panel-body">
                    <div id='ajaxloader1' style='display: none;'>
                      <img src='/uploads/admin/loader2.gif' width='200px' height='200px' style="margin-top: 0%;margin-left: 33%;">
                    </div>
                    <div  >
                        <canvas id="pie-chart" width="1090" height="410"></canvas>  
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4  col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-sm-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title"><span class="ti-unlink"></span> Fiche non attribué</h3>
                        </div>
                        <div class="panel-body">
                            <div>
                                <ul class="timeline timeline-update">
                                    @foreach($countNewNonDistributedSheets as $sheet)
                                    <li>
                                        <div class="timeline-badge primary wow lightSpeedIn center">
                                            <img src="/uploads/admin/close.png" height="36" width="36"
                                                 class="img-circle pull-right" alt="avatar-image">
                                        </div>
                                        <div class="timeline-panel wow slideInLeft"
                                             style="display:inline-block;">
                                            <a href="{{ route('sheets.show', $sheet['id'])}}" style="text-decoration: none">
                                            <div class="timeline-heading"> 
                                                <h4 class="timeline-title"  data-title="Nom et Prénom" style="color: #000;"><small style="color: #000;">Nom et Prénom</small> :{{ $sheet->m_last_name .' '. $sheet->m_first_name}} - {{ $sheet->w_last_name .' '. $sheet->w_first_name}}</h4>
                                                <p>
                                                    <small class="text-primary">
                                                        Créer le : <strong>{{ $sheet->created_at->format('d-m-Y') }}</strong>
                                                    </small>
                                                </p>
                                            </div>
                                            </a>
                                            <div class="timeline-body">
                                                <p>
                                                    Créer par: {{ $sheet->agent['last_name'].' '. $sheet->agent['first_name'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
        <div class="row">       

        <div class="col-md-12  col-xs-12"> 
            <div class="panel ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <i class="ti-bar-chart"></i> Statistiques des agents
                    </h4>
                    <span class="pull-right"> 
                    </span>
                </div>
                <div class="panel-body">
<!-- 
                    <div class="row m-t-10">
                        <div class="col-md-6 col-md-offset-1">
                            
                        <label for="specificDateValue" class="col-md-5" style="margin-top: 9px;text-align: right;">La date de création :</label>
                          <div class="form-group  col-md-7">
                              <div class='input-group date' id='specificDate'>
                                  <input type='text' class="form-control" id="specificDateValue" placeholder="La date de création" />
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                          </div>
                        </div>   
                        <div class="col-md-3 text-center">                                                                        
                            <div class="form-group"  >   
                                <button  type="button" class="btn btn-success" id="filter" style="margin-top: 0px" >
                                  <span class="ti-filter"></span>
                                  Filtrer 
                                </button>  
                            </div> 
                        </div>
                    </div> 
                    <br><br> -->
                    <div class="row m-t-10">
                        <div class="col-md-5">
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
                                  <input type='text' class="form-control" id="dateEndValue" placeholder="La date de fin" />
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
                    <br><br>
                    <div id='ajaxloader2' style='display: none;'>
                      <img src='/uploads/admin/loader2.gif' width='200px' height='200px' style="margin-top: -10%; margin-left: 41%; margin-bottom: -450px;">
                    </div>
                    <div id="graph-container">
                        <canvas id="allAgentsStatistic"  width="800" height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endrole
    @role('Closer')


    <div class="row"> 
        <div class="col-md-8 timeline_panel">
            <div class="panel ">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-fw ti-time"></i> Dérnier activités
                    </h3> 
                </div>
                <div class="panel-body">
                    <!--timeline-->
                    <div>
                        <ul class="timeline">
                            @foreach($sheetRecallsRefusNRP as $sheet)
                            @if($sheet['status'] === 3)
                            <li>
                                <div class="timeline-badge warning wow bounceInDown center">
                                    <i class="ti-headphone-alt"></i>
                                </div>
                                <div class="timeline-panel wow bounceInDown" style="display:inline-block;">
                                    <a href="{{ route('sheets.show', $sheet['id'])}}" style="text-decoration: none">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title"  data-title="Nom et Prénom" style="color: #000;"><small style="color: #000;">Nom et Prénom</small> :{{ $sheet['m_last_name'] .' '.  $sheet['m_first_name']}} - {{ $sheet['w_last_name'].' '. $sheet['w_first_name']}}</h4>
                                        <p>
                                            <small class="text-primary">Créer le : <strong>{{date('d-m-Y', strtotime($sheet['created_at']))}}</strong></small>
                                        </p>
                                    </div>
                                    </a>
                                    <div class="timeline-body"  style="margin-top: 10px;"> 
                                        @foreach($allSatatus as $status)
                                            @if($status->id === $sheet['status'])
                                            <span class="label label-sm {{$status->color}}" style="font-size: 12px;"> 
                                            {{$status->name}}
                                            </span> 
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </li>                            
                            @endif
                            @if($sheet['status'] === 2)
                            <li class="timeline-inverted">
                                <div class="timeline-badge danger wow lightSpeedIn center">
                                    <span class="ti-target"></span>
                                </div>
                                <div class="timeline-panel wow slideInRight" >
                                    <a href="{{ route('sheets.show', $sheet['id'])}}" style="text-decoration: none">
                                    <div class="timeline-heading">
                                        <h4 class="timeline-title"  data-title="Nom et Prénom" style="color: #000;"><small style="color: #000;">Nom et Prénom</small> :{{ $sheet['m_last_name'] .' '. $sheet['m_first_name']}} - {{ $sheet['w_last_name'].' '. $sheet['w_first_name']}}</h4>
                                        <p>
                                            <small class="text-danger">Créer le : <strong>{{date('d-m-Y', strtotime($sheet['created_at']))}}</strong></small>
                                        </p>
                                    </div>
                                    </a>
                                    <div class="timeline-body" style="margin-top: 10px;">
                                        @foreach($allSatatus as $status)
                                            @if($status->id === $sheet['status'])
                                            <span class="label label-sm {{$status->color}}" style="font-size: 12px;"> 
                                            {{$status->name}}
                                            </span> 
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </li>                            
                            @endif
                            @if($sheet['status'] === 1)                            
                                <li>
                                    <div class="timeline-badge primary wow bounceInDown center">
                                        <i class="ti-headphone-alt"></i>
                                    </div>
                                    <div class="timeline-panel wow bounceInDown" style="display:inline-block;">
                                        <a href="{{ route('sheets.show', $sheet['id'])}}" style="text-decoration: none">
                                        <div class="timeline-heading">
                                            <h4 class="timeline-title"  data-title="Nom et Prénom" style="color: #000;"><small style="color: #000;">Nom et Prénom</small> :{{ $sheet['m_last_name'] .' '.  $sheet['m_first_name']}} - {{ $sheet['w_last_name'].' '. $sheet['w_first_name']}}</h4>
                                            <p>
                                                <small class="text-primary">Créer le : <strong>{{date('d-m-Y', strtotime($sheet['created_at']))}}</strong></small>
                                            </p>
                                        </div>
                                        </a>
                                        <div class="timeline-body"  style="margin-top: 10px;"> 
                                        @foreach($allSatatus as $status)
                                            @if($status->id === $sheet['status'])
                                            <span class="label label-sm {{$status->color}}" style="font-size: 12px;"> 
                                            {{$status->name}}
                                            </span> 
                                            @endif
                                        @endforeach
                                        </div>
                                    </div>
                                </li>
                            @endif
                            @endforeach  
                        </ul>
                    </div>
                    <!--timeline ends-->
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-sm-6 col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="fa fa-fw ti-comment-alt"></i> Dérnier fiches distribué
                            </h3> 
                        </div>
                        <div class="panel-body">
                            <ul class="schedule-cont">
                                @foreach($sheetLastDistributedByAdmin as $sheet)
                                <li class="item {{ $sheet->statusList['class'] }}">
                                    <a href="{{ route('sheets.show', $sheet['id'])}}" style="text-decoration: none">
                                    <div class="data">
                                        <div class="time text-muted" data-title="Nom et Prénom" style="font-size: 12px; font-weight: 900; color: #000;">Nom et Prénom</small> :{{ $sheet['m_last_name']}} {{ $sheet['m_first_name']}} - {{$sheet->w_last_name .' '.$sheet->w_first_name}}
                                        </div>
                                        <p  style="margin-top: 15px;color:#6699cc;">
                                            Créer le : <strong>{{ $sheet->created_at->format('d-m-Y') }}</strong>
                                        </p>
                                    </div>
                                    </a>
                                </li>
                                @endforeach 
                            </ul>
                        </div>
                    </div>
                </div> 
            </div>
        </div>

    </div>





    
    @endrole




@endif 


</section>
@endsection


@section('javascript')    

@role('Admin|Super Admin')
<script src="{{ asset('admin') }}/vendors/chartjs/js/Chart.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/vendors/flip/js/jquery.flip.min.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/vendors/lcswitch/js/lc_switch.min.js"></script>

<script type="text/javascript" src="{{ asset('admin') }}/vendors/advanced_newsTicker/js/newsTicker.js"></script>

 

<script type="text/javascript" >
"use strict";
$(document).ready(function() {

   $('#specificDate').datetimepicker({
 format:'DD-MM-YYYY'

  });

  $('#dateBegin').datetimepicker({
    format:'DD-MM-YYYY'

  });
  $('#dateEnd').datetimepicker({
    format:'DD-MM-YYYY'

  });

// first chart :: global statistic  
$.ajax({
    url: '/chartjs-all-data',
    beforeSend: function(){
    // Show image container
    $("#ajaxloader1").show();
    },
    success: function(data) {
    var pieData = {
        labels: [ 
            "Réservation",
            "Rappel",
            "NRP",
            "Refus"
        ],
        datasets: [{
            data: [data.countReservationSheets, data.countRappelSheets , data.countNRPSheets, data.countRefusSheets],
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

        var selector5 = '#pie-chart';

        $(selector5).attr('width', $(selector5).parent().width());
        var myPie = new Chart($("#pie-chart"), {
            type: 'pie',
            data: pieData,
            options:{
            title:{
              display: true,
              text: 'Statistique de tous les fiches'
            } 
          }
        });
    }

    draw4();
    },
    complete:function(data){
        // Hide image container
        $("#ajaxloader1").hide();
   }
});


 
    // statistic   all agent
    $.ajax({ 
        url: '/chart-all-agents-statistic',
        beforeSend: function(){
        // Show image container
        $("#ajaxloader2").show();
        },
        success: function(data) {                     
        var barChartData = {
            labels: data.nameAgent ,
            datasets: [{
                label: "Nombre de fiches",
                backgroundColor: data.dataColor,
                hoverBackgroundColor: data.dataColor,
                data: data.countPerAgent
            } ]

        };
        function draw1() {
            var selector2 = '#allAgentsStatistic';

            $(selector2).attr('width', $(selector2).parent().width());
            var myBar = new Chart($("#allAgentsStatistic"), {
                type: 'bar',
                data: barChartData,
                options: {
                    scaleShowValues: true,
                    scales: {
                        yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                        }],
                        xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                        }]
                    }
                }
            });
        }

        draw1(); 
        },
        complete:function(data){
            // Hide image container
            $("#ajaxloader2").hide();
       }
    });

 


$('#filter').click(function(e){
      e.preventDefault();
      var dateBegin = $('#dateBeginValue').val();
      var dateEnd = $('#dateEndValue').val();

      var dateBeginSend = $('#dateBeginValue').val();
      var dateEndSend = $('#dateEndValue').val();  
      
    //   dateEndSend =  moment(dateEndSend).format("YYYY-MM-DD");
      console.log(dateEndSend);
      if(dateBegin !='' && dateEnd !='' ){
        dateBegin = new Date(dateBegin);
        dateEnd = new Date(dateEnd); 
        if (dateBegin > dateEnd)
        { 
            //console.log('start date is greater than end date');
            //error
            Command: toastr["error"]("La date de début doit être inférieure à la date de fin !", "Statistiques")  
            
        }
        else if(dateBegin < dateEnd)
        {
            // console.log('start date is smaller than end date'); 
            reloadChart(dateBeginSend, dateEndSend );
        }else{
            // console.log('equal');
            reloadChart(dateBeginSend, dateEndSend );
        }

      } else {
        
        $('#allAgentsStatistic').remove(); 
        $('#graph-container').append('<canvas id="allAgentsStatistic"   width="800" height="500"><canvas>');
        $.ajax({ 
            url: '/chart-all-agents-statistic',
            beforeSend: function(){
            // Show image container
            $("#ajaxloader2").show();
            },
            success: function(data) { 

                var barChartData = {
                    labels: data.nameAgent ,
                    datasets: [{
                        label:  "Nombre de fiches",
                        backgroundColor: data.dataColor,
                        hoverBackgroundColor: data.dataColor,
                        data: data.countPerAgent
                    } ]

                };

                function draw1() {
                    var selector2 = '#allAgentsStatistic';

                    $(selector2).attr('width', $(selector2).parent().width());
                    var myBar = new Chart($("#allAgentsStatistic"), {
                        type: 'bar',
                        data: barChartData,
                        options: {
                            scaleShowValues: true,
                            scales: {
                                yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                                }],
                                xAxes: [{
                                ticks: {
                                    autoSkip: false
                                }
                                }]
                            }
                        }
                    });
                }

                draw1();
            },
            complete:function(data){
                // Hide image container
                $("#ajaxloader2").hide();
           }
        }); 

      }
  });



function reloadChart(dateBegin, dateEnd ){

    $('#allAgentsStatistic').remove(); 
    $('#graph-container').append('<canvas id="allAgentsStatistic"   width="800" height="500"><canvas>');
    $.ajax({ 
        url: '/chart-all-agents-statistic-date/'+ dateBegin + '/' + dateEnd ,
        beforeSend: function(){
        // Show image container
        $("#ajaxloader2").show();
        },
        success: function(data) { 
            if(data.agentNumbers != data.contAllZiros){
                var barChartData = {
                    labels: data.nameAgent ,
                    datasets: [{
                        label:  "Nombre de fiches",
                        backgroundColor: data.dataColor,
                        hoverBackgroundColor: data.dataColor,
                        data: data.countPerAgent
                    } ]

                };

                function draw1() {
                    var selector2 = '#allAgentsStatistic';

                    $(selector2).attr('width', $(selector2).parent().width());                    
                    var myBar = new Chart($("#allAgentsStatistic"), {
                        type: 'bar',
                        data: barChartData
                    });
                }

                draw1();

            }else{
                Command: toastr["error"]("Il y a pas de fiche dans cette date !", "Statistiques")  
                
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
                $("#ajaxloader2").hide();
        }
    });
}



  







// get new data every 3 seconds
// setInterval(getData, 3000);

    //auto timeline update panel

    if ($('.timeline-update').length > 0) {
        $('.timeline-update').newsTicker({
            row_height: 120,
            max_rows: 3,
            speed: 2000,
            direction: 'up',
            duration: 3500,
            autostart: 1,
            pauseOnHover: 1
        });
    }

    //auto timeline update panel ends

});
</script>
@endrole

@role('Closer') 
<script type="text/javascript" src="{{ asset('admin') }}/vendors/wow/js/wow.min.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/vendors/advanced_newsTicker/js/newsTicker.js"></script>
<script type="text/javascript" src="{{ asset('admin') }}/js/custom_js/time_line.js"></script>

@endrole
@endsection