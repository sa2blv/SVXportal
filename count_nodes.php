<?php
include 'config.php';
$link->set_charset("utf8");
include 'function.php';
define_settings();
set_laguage();
$data_array = array();
/*
 * testfuction to calulate number of active nodese during time on reflektor 
 */

for ($i = 2020; $i <= (int)date("Y"); $i++) {
    

    for ($j = 1; $j < 13; $j++) 
    {
        
        
        $sql = "SELECT `Node` FROM `trafic_day_statistics` where `Year` = $i and Mounth = $j GROUP by `Node` ";
        $result = $link->query($sql);
        

        $data_array[$i][date("Y-m",strtotime($i."-".$j."-01"))] = $result->num_rows;
        
        
        
        
        
        
        
        
    }



}
echo json_encode($data_array);




?>
