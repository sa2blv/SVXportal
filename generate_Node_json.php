<?php
header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachment; filename="node_info.json');

$json_array = [];

$json_array["nodeLocation"] = $_POST['nodeLocation'];
$json_array["hidden"] = FALSE;
$json_array["sysop"] =  $_POST['sysop'];

foreach ($_POST['toneToTalkgroup'] as $key => $value) {

    $ctcss_id =  str_replace(".","_",$value);
    $json_array["toneToTalkgroup"][$value] = intval($_POST["toneToTalkgroup_".$ctcss_id]);
    
}

$json_array["qth"][0]["name"] =        $_POST['name'];
$json_array["qth"][0]["pos"]["lat"] =  $_POST['lat'];
$json_array["qth"][0]["pos"]["long"] = $_POST['long'];
$json_array["qth"][0]["pos"]["loc"] =  $_POST['Locator'];

$json_array["qth"][0]["pos"]["loc"] =  $_POST['Locator'];


// RX section
$json_array["qth"][0]["rx"][$_POST["RxL"]]["name"] =           "Rx1";
$json_array["qth"][0]["rx"][$_POST["RxL"]]["freq"] =           floatval($_POST['RX_freq']);
$json_array["qth"][0]["rx"][$_POST["RxL"]]["sqlType"] =        implode(" , ",$_POST['SQL_TYPE']);

$json_array["qth"][0]["rx"][$_POST["RxL"]]["ant"]["comment"] = $_POST['ant_comment'];
$json_array["qth"][0]["rx"][$_POST["RxL"]]["ant"]["height"] =  $_POST['anth'];
$json_array["qth"][0]["rx"][$_POST["RxL"]]["ant"]["dir"] =     $_POST['Adir'];

// tx Section

$json_array["qth"][0]["tx"][$_POST["Txl"]]["name"] =           "Tx1";
$json_array["qth"][0]["tx"][$_POST["Txl"]]["freq"] =           floatval($_POST['TX_freq']);
$json_array["qth"][0]["tx"][$_POST["Txl"]]["pwr"] =            $_POST['power'];

$json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["comment"] = $_POST['ant_comment'];
$json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["height"] =  $_POST['anth'];
$json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["dir"] =     $_POST['Adir'];




print_r( json_encode($json_array,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));


/*
power 
sysop

*/