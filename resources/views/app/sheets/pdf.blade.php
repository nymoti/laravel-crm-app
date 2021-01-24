<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Fiche-PDF</title>
  
  <link type="text/css" rel="stylesheet" href="{{ asset('admin') }}/css/bootstrap.min.css"/>

  <link rel='stylesheet' href="{{ asset('admin') }}/vendors/themify/css/themify-icons.css">
  <link rel='stylesheet' href="{{ asset('admin') }}/css/font-awesome.css">
  <style> 
  @font-face {
      font-family: "source_sans_proregular";           
      src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
      font-weight: normal;
      font-style: normal;

  }        
  body{
      font-family: "source_sans_proregular", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;            
			font-size:10px;
  }     
  h3,h5,h6,h4,p{
    font-family: "source_sans_proregular", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;            

  }
  .container{
    border: solid 1px #eee;
    border-radius: 5px; 
  } 
	.fixe-tmk{
		margin-right:150px;
	} 
  .mb-20{
    margin-bottom: 0px;
  } 
  .icon-font-14{
    font-size:14px;
  }
  .icon-font-12{
    font-size:12px; 
  }
  .green{
  color: green;font-weight: bold;
  }
  .checkbox-img{
    height: 16px;
    margin-left: 10px;
    /*margin-bottom: 4px;*/
    margin-right: 10px;
  }

  </style>
  
</head>

<body>

<div class="container">  

  <div class="row">
    <div class="col-md-3 col-md-offset-4">
      <h4 class="text-center ">-- {{ strtoupper($header->title) }} --</h4>
    </div>
  </div>  

  <div class="row">
    <div class="col-md-6 pull-left"  >
      <img src="{{ asset('uploads') }}/admin/{{ $header->logo }}" style="height: 42px;margin-top: -15px;">
    </div>
    <div class="col-md-6 pull-right"  style="margin-top: -25px;">
      Email : {{$header->email}} <br>
      Site web : {{$header->site}}<br>
      Tél : {{$header->tel}}
    </div>
  </div> 
  <br>
  <div class="row" style="margin-top: -15px;">
    <div class="col-md-12 "> 	 
      <div class="table-responsive" >
          <table class="table" id="table1" style="margin-bottom: 0px;">  
              <tbody>
              <tr>
                  <td>FIXE TMK: <strong >{{ $sheet->agent['fixe_tmk'] }}</strong> </td>
                  <td>CLOSER:
                    <strong >
                    @if($sheet->closer_id != 0)
                    {{strtoupper($sheet->closer['first_name'].' '. $sheet->agent['last_name'])}}
                    @else
                    AUCUN
                    @endif
                    </strong>
                  </td>
                  <td>DATE: <strong >{{ $sheet->created_at->format('d-m-Y') }}</strong></td>
                  <td>CODE CLIENT: <strong  >{{ $sheet->client_code }}</strong></td>
              </tr>
              
              </tbody>
          </table>
      </div> 
      <h5 style="margin-top: 0px;margin-bottom: 2px;">Q1 - Quels sont les 5 dérniers pays que vous avez visités et en quelle année ?</h5>       	 
      <div class="row">
          <div class="col-md-12" style="margin-bottom: -15px;">
           <p><strong>{{ $sheet->question_1 }}</strong></p>  
          </div>           
      </div>
      <div class="row" style="margin-top: 5px;">
        <div class="col-md-6 pull-left">
          <h5  >Q2 - Dans les 5 prochaines années voulez-vous voyager  </span></h5>       	      	 
        </div>
        <div class="col-md-6  pull-left"> 
          <h5  >Plus           
          @if($sheet->q2_more != 0)
          <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
          @else
          <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
          @endif  
            Ou Moins
          @if($sheet->q2_less != 0) 
          <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img">
          @else
          <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
          @endif
          </h5>
        </div>
      </div> 
      <div class="table-responsive " >
          <table class="table " id="table1" style="margin-bottom: 0px; margin-top: -1px;">  
              <tbody>
              <tr>
                  <td>                   
                    Temps           
                    @if($sheet->times != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td> 
                  <td>                   
                    Financiers           
                    @if($sheet->financials != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
                  <td>                   
                    Enfants           
                    @if($sheet->childrens  != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
                  <td>                   
                    Distance           
                    @if($sheet->distance != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
                  <td>                   
                    Pas eu l'occasion           
                    @if($sheet->no_oppportunity != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
              </tr>              
              </tbody>
          </table>
      </div>          

      <h5 style="margin-top: 0px;margin-bottom: 0px;">Q3 - D'habitude vos voyages se font par quels moyens ?</h5>  
      <div class="table-responsive ">
          <table class="table " id="table1" style="margin-bottom: 0px; margin-top: -1px;">  
              <tbody>
              <tr>
                  <td>                   
                    Agence de voyage           
                    @if($sheet->travel_agency != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td> 
                  <td>                   
                    Tour operateur           
                    @if($sheet->tour_operator != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
                  <td>                   
                    Seul           
                    @if($sheet->only != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
                  <td>                   
                    Comite d'entreprise           
                    @if($sheet->company_comittee != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td> 
              </tr>    
              <tr>         
                  <div class="table-responsive ">
                    <table class="table " id="table1" style="margin-bottom: -10px;margin-top: -1px;">
                      <tr>
                          <td style="width: 20%;">
                            <h6 style="margin-top: 0px">Autres           
                            @if($sheet->others_q3 != 0)
                            <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                            @else
                            <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                            @endif
                            </h6>
                          </td> 
                          <td>
                           <p><strong>{{ $sheet->others_desc_q3 }}</strong></p>  
                          </td>
                      </tr>
                    </table>
                  </div>   
              </tr>          
              </tbody>
          </table>
      </div>        

      <h5 style="margin-top: 0px;margin-bottom: 0px;">Q4 - Quelles activités aimeriez-vous pratiquer durant vos vacances ?</h5>  
      <div class="table-responsive ">
          <table class="table " id="table1" style="margin-bottom: 0px;margin-top: -1px;">  
              <tbody>
              <tr>
                  <td>                   
                    Visites culturelles          
                    @if($sheet->cultural_visits != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td> 
                  <td>                   
                    Excursions           
                    @if($sheet->exercusions != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
                  <td>                   
                    Sports           
                    @if($sheet->sports != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td>
                  <td>                   
                    Balneo          
                    @if($sheet->balneo != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif
                    
                  </td> 
              </tr>   
              <tr>         
                  <div class="table-responsive ">
                    <table class="table " id="table1" style="margin-bottom: -10px;margin-top: -1px;">
                      <tr>
                          <td style="width: 20%;">
                            <h6 style="margin-top: 0px">Autres           
                            @if($sheet->others != 0)
                            <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                            @else
                            <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                            @endif
                            </h6>
                          </td> 
                          <td>
                           <p><strong>{{ $sheet->others_desc }}</strong></p>  
                          </td>
                      </tr>
                    </table>
                  </div>   
              </tr>           
              </tbody>
          </table>
      </div>          

      <h5 style="margin-top: 0px;margin-bottom: 0px;">Q5 - Pouvez-vous m'évaluer votre budget de vacance  annuelle ?</h5>  
      <div class="table-responsive ">
          <table class="table " id="table1" style="margin-bottom: 0px;margin-top: -1px;">  
              <tbody>
              <tr>
                  <td>                   
                    Moins de 1500 <i class="fa fa-fw fa-euro" style="margin-top: 1px;"></i>
                    @if($sheet->less_of_1500  != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td> 
                  <td>                   
                    De 1500 A 2000 <i class="fa fa-fw fa-euro"  style="margin-top: 1px;"></i>
                    @if($sheet->from_1500_to_2000 != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td>
                  <td>                   
                    De 2000 A 3000 <i class="fa fa-fw fa-euro"  style="margin-top: 1px;"></i>
                    @if($sheet->from_2000_to_3000 != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td>
                  <td>                   
                    De 3000 A 4000 <i class="fa fa-fw fa-euro"  style="margin-top: 1px;"></i>
                    @if($sheet->from_3000_to_4000 != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td>
                  <td>                   
                    Ou plus           
                    @if($sheet->or_more != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td>  
              </tr>          
              </tbody>
          </table>
      </div>   
      <h5 style="margin-top: 0px;margin-bottom: 0px;">Infos Personnel :</h5>  
      <div class="table-responsive" >
          <table class="table" id="table1"  style="margin-bottom: 0px;margin-top: -1px;"> 
              <tbody> 
              <tr>
                  <td>Nom -H :<strong>{{ $sheet->m_last_name  }}</strong> </td>
                  <td>Prenom :<strong>{{ $sheet->m_first_name }}</strong> </td> 
              </tr>
              <tr> 
                  <td>Profession :<strong>{{ $sheet->m_profession }}</strong> </td>
                  <td>Age :<strong>{{ $sheet->m_age }}</strong> </td> 
              </tr> 
              <tr>
                  <td>Nom -F :<strong>{{ $sheet->w_last_name }}</strong> </td>
                  <td>Prenom :<strong>{{ $sheet->w_first_name  }}</strong> </td> 
              </tr>
              <tr> 
                  <td>Profession :<strong>{{ $sheet->w_profession }}</strong> </td>
                  <td>Age :<strong>{{ $sheet->w_age }}</strong> </td> 
              </tr>
              </tbody>
          </table>
      </div> 

      <div class="table-responsive "  style="margin-top: -15px;">
          <table class="table " id="table1" style="margin-bottom: 0px;margin-top: 0px;">  
              <tbody>
              <tr>
                  <td>                   
                    Marié           
                    @if($sheet->married != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td> 
                  <td>                   
                    Célibataire           
                    @if($sheet->single != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td>
                  <td>                   
                    Divorcée           
                    @if($sheet->divorced != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td>
                  <td>                   
                    Veuf           
                    @if($sheet->divorced != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                    @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">
                    @endif                    
                  </td>  
              </tr>   
              <tr>
                <td>                   
                  Concubinage Concubinage           
                  @if($sheet->concubinage != 0)
                    <img src="{{ asset('uploads') }}/admin/checked.png" class="checkbox-img"  >
                  @else
                    <img src="{{ asset('uploads') }}/admin/unchecked.png" class="checkbox-img">  
                  @endif                    
                </td>
                <td>Depuis 
                  <strong>
                  @if($sheet->concubinage_since != '')
                  {{ $sheet->concubinage_since }}
                  @else
                  -----
                  @endif
                  </strong> Ans
                </td>
                <!-- <td>Enfants à charge : <strong>{{ $sheet->dependent_children }}</strong> </td> --> 
              </tr>  
              </tbody>
          </table>
          <table  class="table " id="table1" style="margin-bottom: 0px;margin-top: 0px;">
            <tbody>           
                <tr>

                  @if($sheet->dependent_children === 1 && $dc_all_ages[0] == 0)    
                  <td style="width: 33px;">Enfants à charge : <strong>0</strong> </td>
                  @else                            
                  <td style="width: 33px;">Enfants à charge : <strong>{{ $sheet->dependent_children }}</strong> </td>
                  @endif

                  @if($sheet->dependent_children === 1 && $dc_all_ages[0] == 0)   
                  @else
                      @foreach($dc_all_ages as $key=>$age) 
                      <td style="width: 33px;">AE {{ $key+1 }}: <strong>{{ $age }}</strong></td>    
                      @endforeach 
                  @endif
                  
                  
                </tr>               
            </tbody>  
          </table>
      </div>    

      <div class="table-responsive " style="margin-top: -15px;">
          <table class="table " id="table1" style="margin-bottom: 0px;margin-top: 0px;">  
              <tbody>
              <tr> 
                <td>N° Tel : <strong>{{ $sheet->tel }}</strong> </td>  
                <td>Portable : <strong>{{ $sheet->gsm }}</strong> </td>  
                <td>Mail : <strong>{{ $sheet->email }}</strong> </td>  
              </tr>       
              </tbody>
          </table>
      </div>  
      <div class="table-responsive "  style="margin-top: -15px;">
          <table class="table " id="table1" style="margin-bottom: 0px;margin-top: 0px;">  
              <tbody>
              <tr> 
                <td style="border-top: none;" >Adresse: <strong>{{ $sheet->address }}</strong> </td>   
              </tr>       
              </tbody>
          </table>
      </div>   
      <h5 class="text-center" style="margin-top: -5px;margin-bottom: 0px;text-decoration: underline;">Observations :</h5>       	 
      <div class="row">
          <div class="col-md-12">
           <p><strong>{{ $sheet->observations }}</strong></p>  
          </div>           
      </div>
      <div class="row">
        <div class="col-md-6 pull-left"> 
        </div>
        <div class="col-md-6 pull-right">
          Modifier Par: <strong>{{ strtoupper($sheet->updatedByUser['first_name'].' '. $sheet->updatedByUser['last_name']) }}</strong>
        </div>
      </div>


    </div>
  </div>  

 
</div>
  
  
<script src="{{ asset('admin')}}/js/jquery.min.js" type="text/javascript"></script> 
<script src="{{ asset('admin') }}/js/bootstrap.min.js" type="text/javascript"></script>  

</body>

</html>
