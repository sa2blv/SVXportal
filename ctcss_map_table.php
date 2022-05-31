<?php
include_once 'config.php';

include_once 'function.php';

$link->set_charset("utf8");


$talkgroup_array= array();
$station_array= array();
$json_data ="";

function Get_station_from_json() 
{
    global $serveradress;
    global $json_data;


    //$context = stream_context_create(array('http' => array('header'=>'Connection: close\r\n')));

    $json_data = file_get_contents($serveradress,false,$context);
    

    
    //$json_data = file_get_contents($serveradress);
    $json_data = iconv("utf-8", "utf-8//ignore", $json_data);
    
    $json = json_decode($json_data);
    global $talkgroup_array;
    global $station_array;

    

    foreach($json->nodes as $st => $station)
    {
       
        foreach ($station->qth as $qth => $qthdata)
        {
 
            
       
            $station_first_RX =array();
            $station_first_TX =array();
            $a=0;
            foreach($qthdata->rx as $RX => $value)
            {
                $station_first_RX[ $a]= $value->freq;
                $a++;
                
            }
            
            $b=0;
            foreach($qthdata->tx as $TX => $value)
            {
                $station_first_TX[$b]= $value->freq;
                $b++;
                
            }
      
            
            
            foreach($station_first_TX as $int => $data)
            {
                
          
            
                $offset=0;
                
                if($station_first_TX[$int] == null)
                {
                    $station_first_TX[$int] = $station_first_RX[$int];
                }
                else
                {
                    $offset = floatval($station_first_TX[$int])-floatval($station_first_RX[$int]);
                }
                
                $ofset_char ="";
                
                if($offset != 0)
                {
                    if ((floatval($station_first_RX[$int])-floatval($station_first_TX[$int])) <0)
                    {
                        $ofset_char="-";
                    }
                    else
                    {
                        $ofset_char="+";
                    }
                    
                    
                }
                
       
                
                
                
                
                
                $offset =floatval($station_first_TX[$int])-floatval($station_first_RX[$int]);
                
                foreach($station->toneToTalkgroup as $tonegroup => $talkgroup)
                {
                 
                    if($int ==0 )
                    {
                        $talkgroup_array[$talkgroup] =$talkgroup;
                        $station_array[$st][$talkgroup] = $tonegroup;
                        $station_array[$st]["tx_freq"] =$station_first_TX[$int];
                        $station_array[$st]["Location"] =$station->NodeLocation;
                        if($station->NodeLocation == null)
                        {
                            $station_array[$st]["Location"] =$station->nodeLocation;
                            
                        }
                    }
                    else
                    {
                        $talkgroup_array[$talkgroup] =$talkgroup;
                        $station_array[$st.$int][$talkgroup] = $tonegroup;
                        $station_array[$st.$int]["tx_freq"] =$station_first_TX[$int];
                        $station_array[$st.$int]["Location"] =$station->NodeLocation;
                        if($station->NodeLocation == null)
                        {
                            $station_array[$st.$int]["Location"] =$station->nodeLocation;
                            
                        }
                        
                    }
                    
                    
                    
                    
            
                }
                
            }
        }
    }
    
}

Get_station_from_json();
sort($talkgroup_array);

/*
echo '<pre>';
var_dump($talkgroup_array);
echo '<pre>';
var_dump($station_array);
*/


?>

<?php 
set_laguage();

$noheader =$_GET['NOHEAD'];

if($noheader != 1){?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>CTCSS table</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<?php }?>

<div class="container-fluid">

<script type="text/javascript">

 function validate_export() 
 {

		var checked=false;
		var elements = document.getElementsByName("Stationexpot[]");
		for(var i=0; i < elements.length; i++){
			if(elements[i].checked) {
				checked = true;
			}
		}
		if (!checked) {
			alert('You nead to select a station for export');
			return false;
		}
		return true;
		
	
}


</script>

  
  
      <nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		<a class="navbar-brand" href="#"><?php echo _('CTCSS Map table')?></a>
		
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">




           <li class="nav-item  d-none d-lg-inline-flex">
        
             
        <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('print_export_log','<?php echo _('CTSS map table')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-print"></i>
          <?php echo _('Print')?>
        </a>
             
        </li>
        <li class=" d-none d-lg-inline-flex">
                <a class="nav-link " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('ctcss_data_table')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="far fa-file-excel"></i>
          <?php echo _('Export xls')?>
        </a>
        </li>
        

  
        

        
        
        
   
      
      
      
    </ul>



      
    </nav>
    
    
  
  
  
  <form action="Get_Node_CSV.php" method="POST" onsubmit="return validate_export()"  target="_blank">
  <div id="print_export_log" >

 <div style="overflow-x:auto;overflow-y: hidden;" >
 
  <table class="table table-hover table-sm" id="ctcss_data_table"  >
    <thead class="dash_header">
      <tr>
        <th> </th>
        <th><?php echo _("Callsign")?></th>
		<th><?php echo _("Frequency")?></th>
		<th><?php echo _("Location")?></th>
        <?php 
        foreach(  $talkgroup_array  as $tonegroup => $talkgroup)
        {
            if($talkgroup == 0)
            {
                echo '<th>'._('Local').'</th>';
            }
            else
            {
                echo '<th>'.$talkgroup.'</th>';
            }
           
           
        }

        ?>

      </tr>
    </thead>
    <tbody>
   

     
        <?php 

        
        
        foreach(  $station_array  as $station => $st_object)
        {
            if(!$_GET['hiddeexport'])
                $chechbox ="<input type='checkbox' name='Stationexpot[]' value='".$station."'>";
            else
                $chechbox ="";
            
                if(is_numeric(substr($station, -1)) && strpos($station, "-") === false)
                {
                    $station= substr($station, 0, -1);
                }
                
            echo '<tr>
            '.'<td>'.$chechbox
            .'<td>'.$station.'</td>'
            .'<td>'.number_format($st_object['tx_freq'], 4, '.', '').'</td>'
            .'<td>'.$st_object['Location'].'</td>';
            
            foreach(  $talkgroup_array  as $tonegroup => $talkgroup)
            {
                
                echo '<td>'.$st_object[$talkgroup].'</td>';
                
            }
            echo '</tr>';

        }

        ?>



    </tbody>
  </table>
  </div>
  <br />
  
  </div>
  <?php if(!$_GET['hiddeexport']){?>
  
    <div class="row">
        <div class="col-sm">
  <div class="form-group">

    <fieldset class="form-group row">
              <label for="Softwareinput" class="col-2 col-form-label"><?php echo _("Software")?></label>
  		<div class="col-10">  
            <select class="form-control " name="Software" id="Softwareinput">
              <option value="anytone" > AnyTone AT</option>
              <option value="chirp">Chirp</option>
    		</select>
		</div>
		

          <label for="numberinpuet" class="col-2 col-form-label"><?php echo _('CSV index')?></label>
          <div class="col-10">
            <input class="form-control" type="number" value="0" name="index" id="numberinpuet">
          </div>
          
          <label for="countrypre" class="col-2 col-form-label"><?php echo _("Use three last char in call")?></label>
          <div class="col-10">
            <input class="form-control" type="checkbox" value="1" name="trimc" id="countrypre">
          </div>
  	<div class="col-sm-10">
         	<input class="btn btn-primary" type="submit" value="<?php echo _("Export")?>">

	</div>         
      </fieldset>

    </div>
    </div>
  </div>
  
  

    <?php }?>
  </form>

</div>


	


