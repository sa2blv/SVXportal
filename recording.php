<?php
$svx_folder="./svxrecording/";
$site_adress="";

function scan_dir($dir) {
  if($_GET['date'])
  {
     $date = $_GET['date'];
  }
  else
  { 
     $date = date("Y-m-d");
  }
 // echo $dir."qsorec_RepeaterLogic_".$date."*.ogg";
  $filelist = glob($dir."qsorec_SimplexLogic_".$date."*");


  return $filelist;

}


$files = (scan_dir($svx_folder));
$array_json =array();
$i=0;
$array_json['length'] = sizeof($files);
foreach($files as $key => $val)
{
 $array_json[$i]['text'] = date ("F d Y H:i:s.", filemtime($val));
 $array_json[$i]['file'] = $val;
 $i++;
}


echo json_encode($array_json);


?>
