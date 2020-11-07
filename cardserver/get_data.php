<?php


$Server_data = array();
$Server_data["IO"][0] =1;
$Server_data["IO"][1] =1;
$Server_data["IO"][2] =1;
$Server_data["IO"][3] =1;

$Server_data["DATA"][0] =1;
$Server_data["DATA"][1] =1;
$Server_data["DATA"][2] =0;
$Server_data["DATA"][3] =1;

echo json_encode($Server_data,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);