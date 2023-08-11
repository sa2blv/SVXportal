<?php
set_time_limit(800);
include '../config.php';
$link->set_charset("utf8");
include '../function.php';
define_settings();
set_laguage();




function detect_empty()
{
    global $link;
    $nummber = $link->query("SELECT COUNT(*) as c FROM trafic_mounth_statistics ")->fetch_object()->c;
    
    
    return $nummber;
    
}
$add_filter ="";

if(detect_empty() > 0)
{
    $link->query('DELETE FROM `trafic_mounth_statistics` WHERE `Year` = "'.date("Y").'"');
    $add_filter ="WHERE Year = '".date("Y")."'"; 
    
    // the datab
    
}
else
{
    // the database is empty and no filter is neaded.
    
}





$sql_R = "SELECT sum( Total_T )as total_time,sum( `Total_N` )as Total_nn,sum( `Total_X2` )as Total_2 , `Year`,`Mounth` , `TG`, `Node` FROM `trafic_day_statistics` $add_filter GROUP BY `Year`,`Mounth`,`Node`,`TG` ";


$result = $link->query($sql_R);

$data = array();
//$data[]= array();

while($row = $result->fetch_assoc())
{

    @$data[$row['Node']][$row['Year']][$row['TG']][$row['Mounth']] ['_T']  += $row['total_time'];
    @$data[$row['Node']][$row['Year']][$row['TG']][$row['Mounth']] ['_N']  += $row['Total_nn'];
    @$data[$row['Node']][$row['Year']][$row['TG']][$row['Mounth']] ['_X2'] += $row['Total_2'];
    
    
    @$data[$row['Node']][$row['Year']][$row['TG']]['TOTAL_T']  += $row['total_time'];
    @$data[$row['Node']][$row['Year']][$row['TG']]['TOTAL_N']  += $row['Total_nn'];
    @$data[$row['Node']][$row['Year']][$row['TG']]['TOTAL_X2']  += $row['Total_2'];
    
    
    

}

foreach($data as $node => $year_data) 
{
    
    foreach($year_data as $year => $tg_data) 
    {
    
        
        foreach($tg_data as $tg => $monunts ) 
        {
            $extra='';
            $columns='';
            $values='';
            
            for ($a = 1; $a <= 12; $a++) 
            {
                if(!array_key_exists($a,$monunts))
                {
     
                    
                    $monunts[$a] ["_N"] =0;
                    $monunts[$a] ["_T"] =0;
                    $monunts[$a] ["_X2"] =0;
                    
                }
                
            }
            
            
            foreach($monunts as $i => $value) 
            {

                
                
                
                if(is_numeric($i))
                {
                    $columns .= "," . $extra . $i . "_N";
                    $columns .= "," . $extra . $i . "_T";
                    $columns .= "," . $extra . $i . "_X2";
                    
                    @$values .= ",".(float)$value["_N"].",".(float)$value["_T"].",".(float)$value["_X2"]."";
                }
                
                
                
            }
            
            
            if(is_numeric($tg))
            {
                $sql_other_col ="Node,TG,Year";
                $standard_data = '\''.$node.'\''.",".$tg.",".$year;
                $total_str =  ",TOT_N,TOT_X2, TOT_T";
                $total_val =",".$monunts['TOTAL_N'].",".$monunts['TOTAL_X2'].",".$monunts['TOTAL_T'];
                // echo $sql_other_col.$columns . "<BR />";
                // echo  $standard_data. $values . "<BR />";
                
                $mysql_quvery ="INSERT INTO `trafic_mounth_statistics`(".$sql_other_col.$columns.$total_str.") VALUES (". $standard_data. $values.$total_val .");";
               // echo $mysql_quvery."\r\n";
                
                $link->query($mysql_quvery);
                
                //echo $sql."<br />";
                
                
            }
            
            
                
                
                
            
    
    
            

        }
    
    }
    
    
    
   
}








echo "Done for today! ";

$link->close();



?>
