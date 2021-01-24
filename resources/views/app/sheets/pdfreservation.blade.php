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
  <br>  <br>  <br>  <br>
  <div class="row" style="margin-top: -15px;">
    <div class="col-md-12 ">   

        <div class="row">
          <div class="col-md-3 col-md-offset-4">
            <h4 class="text-center " style="text-decoration: underline;">FORMULAIRE DE RESERVATION</h4>
          </div>
        </div>
 
      </div>    
      <h6 style="margin-top: 0px;margin-bottom: 0px;">MADAME :</h6>  
      <div class="table-responsive" >
          <table class="table" id="table1"  style="margin-bottom: 0px;margin-top: -1px;"> 
              <tbody>  
              <tr>
                  <td>Nom :&nbsp;<strong>{{ $sheet->w_last_name }}</strong> </td>
                  <td>Prenom :&nbsp;<strong>{{ $sheet->w_first_name  }}</strong> </td> 
              </tr>
              <tr> 
                  <td>Profession :&nbsp;<strong>{{ $sheet->w_profession }}</strong> </td>
                  <td>Age :&nbsp;<strong>{{ $sheet->w_age }}</strong>  &nbsp;</td> 
              </tr>
              </tbody>
          </table>
      </div>    
      <h6 style="margin-top: 0px;margin-bottom: 0px;">MONSIEUR :</h6>  
      <div class="table-responsive" >
          <table class="table" id="table1"  style="margin-bottom: 0px;margin-top: -1px;"> 
              <tbody> 
              <tr>
                  <td>Nom :&nbsp;<strong>{{ $sheet->m_last_name  }}</strong> </td>
                  <td>Prenom :&nbsp;<strong>{{ $sheet->m_first_name }}</strong> </td> 
              </tr>
              <tr> 
                  <td>Profession :&nbsp;<strong>{{ $sheet->m_profession }}</strong> </td>
                  <td>Age :&nbsp;<strong>{{ $sheet->m_age }}</strong>  &nbsp;</td> 
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
                <td>E-mail : <strong>{{ $sheet->email }}</strong> </td>  
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
      <div class="row">
        <div class="col-md-3 col-md-offset-4">
          <h6 class="text-center ">Nombre de PAX : <strong  >  {{ $sheet->nbr_pax }}</strong></h6>
        </div>
      </div> 
      <div class="table-responsive" >
          <table class="table" id="table1"  style="margin-bottom: 0px;margin-top: -1px;"> 
              <tbody> 
              <tr>
                  <td>La date d'arrivée </td>
                  <td>la date de départ </strong> </td> 
              </tr>
              <tr> 
                  <td><strong>{{ date('d-m-Y', strtotime($sheet->date_arrived))  }}</strong> </td>
                  <td><strong>{{ date('d-m-Y', strtotime($sheet->date_departure))  }}</strong> </td> 
              </tr>  
              </tbody>
          </table>
      </div>

      <div class="table-responsive"   style="margin-top: -15px;">
          <table class="table" id="table1"  style="margin-bottom: 0px;margin-top: -1px;"> 
              <tbody> 
              <tr>
                  <td>Numéro de vol d'arrivée </td>
                  <td>Numéro de vol de départ</strong> </td> 
              </tr>
              <tr> 
                  <td><strong>{{  $sheet->da_flight_number  }}</strong> </td>
                  <td><strong>{{  $sheet->dd_flight_number  }}</strong> </td> 
              </tr>  
              </tbody>
          </table>
      </div>

      <div class="table-responsive" >
          <table class="table" id="table1"  style="margin-bottom: 0px;margin-top: -1px;"> 
              <tbody> 
              <tr>
                  <td>2 Choix d'établissements Obligatoires:</td> 
              </tr>
              <tr> 
                  <td><strong>{{  $sheet->establishment  }}</strong> </td> 
              </tr>  
              </tbody>
          </table>
      </div>

      <div class="table-responsive" >
          <table class="table" id="table1"  style="margin-bottom: 0px;margin-top: -1px;"> 
              <tbody> 
              <tr>
                  <td>Supplements : <strong>{{  $sheet->supplements  }}</strong></td> 
                  <td>Montant à payer sur place : <strong>{{ $sheet->amount }}</strong></td> 
              </tr>  
              </tbody>
          </table>
      </div>

      <div class="row">
        <h5 class="text-center" style="margin-top: -5px;margin-bottom: 0px;text-decoration: underline;">Commentaires :</h5>          
      </div>
      <div class="row">
          <div class="col-md-12">
           <p style="padding: 12px;line-height: 17px;">{{ $sheet->comments }}</p>  
          </div>           
      </div> 

    </div>
  </div>  

 
</div>
  
  
<script src="{{ asset('admin')}}/js/jquery.min.js" type="text/javascript"></script> 
<script src="{{ asset('admin')}}/js/bootstrap.min.js" type="text/javascript"></script>  

</body>

</html>
