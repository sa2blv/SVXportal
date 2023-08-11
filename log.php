<?php
include_once 'config.php';
include_once 'function.php';
set_laguage();
$last_id = $_GET['id'];

$conn = new mysqli($host, $user, $password, $db);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
mysqli_set_charset($conn,"utf8");
$logoffset = $conn->real_escape_string($_GET['offset']);
if(!$_GET['offset'])
    $logoffset=0;

    $size = 500;
    
if($_GET['size'])
{
    $size = $conn->real_escape_string($_GET['size']);
    
}
if($_GET['filter'])
{
    $filter = $conn->real_escape_string($_GET['filter']);
    
    if($filter == "1,2,3,")
    {
        $filter ="Type ='1' OR (Type = '2' AND Active = '1') OR Type= '3'";
        
    }
    else
    {
        $ceked_val = explode(",", $filter);
        $filter="";
        $i =0;
       
        foreach($ceked_val as $val) 
        {

            
            switch($val)
            {
                case "1":
                    if($i > 0 && count($ceked_val) != $i )
                    {
                        $filter .= " OR ";
                    }
                    
                    $filter .= "Type ='1'";
          

                    break;
                case "2":
                    if($i > 0 && count($ceked_val) != $i )
                    {
                        $filter .= " OR ";
                    }
                    
                    $filter.= "(Type = '2' AND Active = '1')";
 
                    break;

                case "3":
                    if($i > 0 && count($ceked_val) != $i )
                    {
                        $filter .= " OR ";
                    }
                    
                    $filter .= "Type= '3'";

                    break;

            }
            

            $i++;
        }
        
        
        
    }
    

}
else
{
    $filter ="(Type ='1' OR Type= '3')" ;
    
}
        
    
    
if($_GET['search'])
{
    $staion = $conn->real_escape_string($_GET['search']);
    $tg = $conn->real_escape_string($_GET['search']);
    if(!is_numeric($tg))
    {
        $staion_quvery ="AND Callsign like '$staion%'  ";
        
    }
    else
    {
        $staion_quvery ="AND  Talkgroup = '$tg' ";
    }
    


}
else
{
    
     if($_GET['Station'])
     {
         $staion = $conn->real_escape_string($_GET['Station']);
         $staion_quvery ="AND Callsign = '$staion' "; 
     }
     if($_GET['tg'])
     {
         $tg = $conn->real_escape_string($_GET['tg']);
         $tg_quvery ="AND Talkgroup = '$tg' ";
     }
}

$sql ="SELECT * FROM `RefletorNodeLOG` where $filter   $staion_quvery $tg_quvery  ORDER BY `RefletorNodeLOG`.`Id` DESC  limit $logoffset,$size ";



$result = $conn->query($sql);

if(!$_GET['only_table'])
{
    
    /* 
     * 
     * count nr of rows in log 
     */
    //$sql1 ="SELECT count(*) FROM `RefletorNodeLOG` where  $filter $staion_quvery $tg_quvery";
    $sql1 ="SELECT count(*) FROM `RefletorNodeLOG` where Type ='1'  $staion_quvery $tg_quvery";
    
    $result1 = $conn->query($sql1);
    
    
    $row = $result1->fetch_row();
    
    $row_cnt = $row[0];
    // setting limit for large sites 
    if($row_cnt > 16000)
    {
        $row_cnt = 16000;
    }
    
    $nrof_rows  = ((int)($row_cnt/$size));
    $tota_count = $nrof_rows;
    if($nrof_rows >20 )
    {
        $nrof_rows=20;
    }
        ?>
    
    <nav class="navbar navbar-expand-sm navbar-light  bg-light" style="background-color: #e3f2fd;">
		
		<a class="navbar-brand" href="#"><?php echo _('Log')?></a>
		
  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

       <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        <i class="fas fa-align-justify"></i>
          <?php echo _('Page size')?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

          <a class="dropdown-item" onclick="change_log_size(20)"  href="#"><i class="fas fa-grip-lines"></i> 20</a>
          <a class="dropdown-item" onclick="change_log_size(50)"  href="#"><i class="fas fa-grip-lines"></i> 50</a>
          <a class="dropdown-item" onclick="change_log_size(100)"  href="#"><i class="fas fa-grip-lines"></i> 100</a>
          <a class="dropdown-item" onclick="change_log_size(200)"  href="#"><i class="fas fa-grip-lines"></i> 200</a>
          <a class="dropdown-item" onclick="change_log_size(400)"  href="#"><i class="fas fa-grip-lines"></i> 400</a>
          <a class="dropdown-item" onclick="change_log_size(500)"  href="#"><i class="fas fa-grip-lines"></i> 500</a>
          <a class="dropdown-item" onclick="change_log_size(1000)" href="#"><i class="fas fa-grip-lines"></i> 1000</a>		
          <a class="dropdown-item" onclick="change_log_size(2000)" href="#"><i class="fas fa-grip-lines"></i> 2000</a>	
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

        <i class="fas fa-align-justify"></i>
          <?php echo _('Filter')?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

<?php  

$filter = $conn->real_escape_string($_GET['filter']);
$ceked_val_arr = explode(",", $filter);


       

?>

          <div class="form-check">
       
              <input class="form-check-input"  name="Log_filter_checkbox[]" type="checkbox" onclick="get_log_filter()" value="1" id="defaultCheck1" <?php  if (in_array("1", $ceked_val_arr)) echo "checked='checked'"; ?>:>
              <label class="form-check-label" for="defaultCheck1" >
            <?php echo _('Repeater actions');?>
              </label>
            </div>
            <div class="form-check">
      
              <input class="form-check-input" name="Log_filter_checkbox[]" onclick="get_log_filter()" type="checkbox" value="2" onclick="get_log_filter()" <?php  if (in_array("2", $ceked_val_arr)) echo "checked='checked'";?> id="defaultCheck2" >
              <label class="form-check-label" for="defaultCheck2">
              <?php echo _('S-values');?>
              </label>
			</div>

            <div class="form-check">
       
              <input class="form-check-input" name="Log_filter_checkbox[]" onclick="get_log_filter()" type="checkbox" value="3" onclick="get_log_filter()" id="defaultCheck2" <?php  if (in_array("3", $ceked_val_arr)) echo "checked='checked'";?> >
              <label class="form-check-label" for="defaultCheck2">
                <?php echo _('Reflektor');?>
              </label>
			</div>
			
			




        </div>
      </li>
      
             <li class="nav-item d-none d-lg-inline-flex ">
        
             
        <a class="nav-link  " href="#" id="navbarDropdownMenuLink" onclick="PrintElem('log_table','<?php echo _('Log print')?>')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-print"></i>
          <?php echo _('Print')?>
        </a>
             
        </li>
         <li class="nav-item d-none d-lg-inline-flex">
                <a class="nav-link  " href="#" id="navbarDropdownMenuLink" onclick="fnExcelexport('log_table_data')" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
             <i class="far fa-file-excel"></i>
          <?php echo _('Export xls')?>
        </a>
        </li>
        
 
        
        
        
   
      
      
      
    </ul>



      <form class="form-inline my-2 my-lg-0" action="#" onsubmit="return offset_log(0)">
      
          <input type="checkbox" class="form-check-input" id="log_live_update" onchange="load_live_log(this.checked)">
        <label class="form-check-label" for="log_live_update"><?php echo _('Live')?> </label>&nbsp;
        
      
      
        <input class="form-control mr-sm-2" id="logserch" type="search" placeholder="<?php echo _('Search')?>" value="<?php echo $_GET['search']?>" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><?php echo _('Search')?></button>
      </form>
      </div>

      
    </nav>
    
    
    
<nav aria-label="Page navigation">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous" onclick="offset_log_neg()">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>





  
    <?php 
    $menu_offset = $logoffset/$size;
    if($menu_offset >10)
        $start = $menu_offset-10;
    else
    {
        $start =0;
    }
    $add_class=0;
    
    for ($i = $start; $i <= ($start+$nrof_rows); $i++) {
        // break if total> pahes
        
        if($i== $tota_count)
        {
            break;   
        }
        $class="";
        
        if($add_class >= 8)
        {
            $class="d-none d-md-inline-flex";   
        }
        ?>
         <li class="page-item"><a class="page-link <?php echo $class;?>"   <?php if(($logoffset / $size) == $i ){echo "active";} ?> " href="#" onclick="offset_log(<?php echo ($i*$size)?>)"><?php if(($logoffset / $size) == $i ){?><u><?php echo $i?></u><?php } else echo ($i)?> </a> </li>
        <?php 
        $add_class++;
    }
    
    
    ?>

      <a class="page-link" href="#" aria-label="Next" onclick="offset_log_add()">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</nav>
    
    
    <div id="log_table">
<?php }?>
<table class="table   table-striped" id="log_table_data">
  <thead class="dash_header">
    <tr>
      <th scope="col"><?php echo _("Date")?></th>
      <th scope="col"><?php echo _("Station")?></th>
      <th scope="col"><?php echo _("Event")?></th>

    </tr>
  </thead>
  <tbody>

<?php 




while($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>'.$row["Time"]."</td>";
    echo '<td>'.$row["Callsign"]."</td>";
    echo '<td>';
    if($row['Type'] ==1) 
    {
    if($row['Active'] == 1)
         echo _("is Talker on tg")." ".$row['Talkgroup'];
        else
         echo _("Ended talk on tg")." ".$row['Talkgroup'];
    }
    elseif($row['Type'] ==2) 
    {
        echo _("Reciver")." ".$row['Nodename'];
        if($row['Active'] == 1)
            echo " ". _(" is active, signal level")." ".$row['Siglev'];
        else
            echo " ".  _("has signal level") ." ".$row['Siglev'];
        
        
    }
    else
    {
        echo _("Node")."  ".$row['Callsign'];
        if($row['Active'] == 1)
                echo " ". _("has dropped  from reflector")." ";
            else
                echo " ".  _("has connected to  reflector") ." ";
    }
    echo '</td>';
     echo "</tr> ";
       
}

?>
  </tbody>
</table>
<?php if(!$_GET['only_table']){?>
</div>

<nav aria-label="Page navigation">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="#" aria-label="Previous" onclick="offset_log_neg()">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>





  
    <?php 
    $menu_offset = $logoffset/$size;
    if($menu_offset >10)
        $start = $menu_offset-10;
    else
    {
        $start =0;
    }
    $add_class=0;
    for ($i = $start; $i <= ($start+$nrof_rows); $i++) {
        // break if total> pahes
        if($i== $tota_count)
        {
            break;   
        }
        $class="";
        
        if($add_class >= 8)
        {
            $class="d-none d-md-inline-flex";
        }
        
        
        ?>
         <li class="page-item"><a class="page-link <?php echo $class?>"   <?php if(($logoffset / $size) == $i ){echo "active";} ?> " href="#" onclick="offset_log(<?php echo ($i*$size)?>)"><?php if(($logoffset / $size) == $i ){?><u><?php echo $i?></u><?php } else echo ($i)?> </a> </li>
        <?php 
        $add_class++;
    }
    
    
    ?>

      <a class="page-link" href="#" aria-label="Next" onclick="offset_log_add()">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</nav>



<?php }?>
