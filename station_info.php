<?php
include_once "config.php";
include_once 'function.php';
include_once "Mqtt_driver.php";
$no_header = $_GET['no_header'];
$callsign =$_GET['callsign'];

$callsign = $link->real_escape_string($callsign);

mysqli_set_charset($link,"utf8");
    
$result_station = mysqli_query($link, "SELECT * FROM `Infotmation_page`  where Station_Name = '$callsign'");



?>

<?php
if($no_header != true)
{
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">



    <title>Station information</title>
    
    
    
<script type="text/javascript" src="lib/jquery.min.js"></script>

    <!-- Bootstrap core CSS -->
<script src="./lib/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="lib/css/bootstrap.min.css">


<link rel="stylesheet" href="jquery-ui.css">
<script src="jquery-ui.js"></script>



  </head>
  
  
  <nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		<a class="navbar-brand" href="#"><?php  echo _('Station infromation')?> <span style="color: red;">Beta</span></a>
		

      
</nav>

<?php }?>


    
    
<div class="container-fluid">


    
  <div class="row">
  
    <div class=" col-lg-9" id="print_div">
    
    
    <nav>
      <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-page-tab" data-toggle="tab" href="#nav-page" role="tab" aria-controls="nav-page" aria-selected="true"><?php echo _('Information')?></a>
         <a class="nav-item nav-link" id="Hardware-tab" data-toggle="tab" href="#Hardware" role="tab" aria-controls="Hardware" aria-selected="false"><?php echo _('Hardware')?></a>
         <a class="nav-item nav-link" id="Status-tab" data-toggle="tab" href="#Status" role="tab" aria-controls="Status" aria-selected="false"><?php echo _('Operating information')?></a>
        <a class="nav-item nav-link" id="DTMF-tab" data-toggle="tab" href="#DTMF" role="tab" aria-controls="DTMF" aria-selected="false"><?php echo _('DTMF')?></a>
       
      </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
      <div class="tab-pane fade show active" id="nav-page" role="tabpanel" aria-labelledby="nav-page-tab">



    

    <br />    
    
    <?php
    
    
    


// Associative array
$image ="";
$module ="";
$hardware ="";
$staton_idnr ="";
while ($row = mysqli_fetch_array($result_station, MYSQLI_ASSOC)) {
    
    echo html_entity_decode ($row['Html']);
    $image = $row['Image'];
    $module = $row['Module'];
    $hardware = html_entity_decode ($row['Hardware_page']);
    $staton_idnr = $row['Station_id'];
    
    
}
    ?>
    
    </div>
      <div class="tab-pane fade" id="DTMF" role="tabpanel" aria-labelledby="DTMF-tab">
 
 
<div class="table-responsive">
    
      

           
    <?php 
    $dtmf_array = DTMF_Catgory();
    
    
    foreach ($dtmf_array as $key => $value) 
    {
        $result = mysqli_query($link, "SELECT * FROM `Dtmf_command` where Station_Name = '$callsign' AND Category='$key'");
        $rowcount=mysqli_num_rows($result);
        if($rowcount >0)
        {
    ?>  
      	 <br />
         <h2><?php echo $value?></h2>
          <br />
    
         <table class="table table-striped ">
          <thead class=" thead-dark">
            <tr>
              <th style="width:20%"  ><?php echo _('DTMF Command')?></th>
              <th style="width:80%"   ><?php echo _('Description')?></th>
            </tr>
          </thead>
          <tbody>
        
        <?php
    
        
        
        // Associative array
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
        {
        
           ?>
           
           <tr>
           <td class="" >
            <?php echo $row['Command'];?>
           </td>
           <td class="">
           <?php echo $row['Description'];?>
           </td>
           </tr>
            
        <?php   
        }
        ?>	
        
          </tbody>
        </table>
    
    
    <?php
        }
    }?>

 </div>     
      
      </div>
      <?php // FADA STATUS?>
      
      <div class="tab-pane fade show " id="Status" role="tabpanel" aria-labelledby="Status-tab">
      
       <table class="table table-striped ">
          <thead class=" thead-dark">
            <tr>
              <th style="width:30%"  ><?php echo _('Date')?></th>
              <th style="width:50%"   ><?php echo _('Message')?></th>
              <th style="width:20%"   ><?php echo _('Category')?></th>
            </tr>
          </thead>
          <tbody>
        
        <?php
    
        $result = mysqli_query($link, "SELECT * FROM `Operation_log` where Station_id = '$staton_idnr' ORDER BY  Date  DESC LIMIT 30 ");
        $Operation_message_type = Operation_message_type();
        // Associative array
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
        {
        
           ?>
           
           <tr>
           <td class="" >
            <?php echo $row['Date'];?>
           </td>
           <td class="">
           <?php echo $row['Message'];?>
           </td>
           <td>
           <?php echo $Operation_message_type[$row['Type']]?>
           </td>
           </tr>
            
        <?php   
        }
        ?>	
        
          </tbody>
        </table>
        
        
        

      </div>
      
      <div class="tab-pane fade" id="Hardware" role="tabpanel" aria-labelledby="Hardware-tab"><br /><?php echo $hardware;?></div>
      
    
    </div>
    
    
    
    
    
    </div>
    <div class="col-lg-3">
<?php  if($image != ""){?>
    <div class="card card_margin shadow mb-4" style="">
      <div class="card-header text-white bg-dark " >
        <?php echo _('Radio')?>
    
    
    
    
      </div>
      <div class="bg-white">
      
     	 <img class="card-img-top" src="driver/images/<?php echo $image;?>" alt="Card image cap">
      
     </div>
    </div>
<?php }?>
 
 
 <?php if($module != ""){?>
 

    
    <div class="card shadow mb-4" style="">
      <div class="card-header text-white bg-dark " >
        <?php echo _('Logic status')?>
      </div>
       <div class="bg-white">
      
   <?php 
 __autoload_driver($module);

 $driver = new $module();
 $driver->Init($callsign);
 $driver->status_bar();
 
 
 
 ?>
      </div>
    




  
  
  
  
     
</div>
  <?php }?>  


      




</div>
    

</div>

<?php
if($no_header != true)

    
 echo '</html>';
?>

    