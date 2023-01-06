<?php
include 'config.php';
$link->set_charset("utf8");

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=".$_POST['Software']."-".date("Y-m-s-H:i:s").".csv");
header("Pragma: no-cache");
header("Expires: 0");



$json_data = file_get_contents($serveradress);
$json_data = iconv("utf-8", "utf-8//ignore", $json_data);
$json = json_decode($json_data);




//echo '<pre>';
//var_dump($json->nodes->SK3W->qth[0]->rx);


switch ($_POST['Software'])
{

case "anytone":
    echo '"No.","Channel Name","Receive Frequency","Transmit Frequency","Channel Type","Transmit Power","Band Width","CTCSS/DCS Decode","CTCSS/DCS Encode","Contact","Contact Call Type","Radio ID","Busy Lock/TX Permit","Squelch Mode","Optional Signal","DTMF ID","2Tone ID","5Tone ID","PTT ID","Color Code","Slot","Scan List","Receive Group List","TX Prohibit","Reverse","Simplex TDMA","TDMA Adaptive","AES Digital Encryption","Digital Encryption","Call Confirmation","Talk Around","Work Alone","Custom CTCSS","2TONE Decode","Ranging","Through Mode","Digi APRS RX","Analog APRS PTT Mode","Digital APRS PTT Mode","APRS Report Type","Digital APRS Report Channel","Correct Frequency[Hz]","SMS Confirmation","Exclude channel from roaming"'."\r\n";
    break;
    
default:
    echo "Location,Name,Frequency,Duplex,Offset,Tone,rToneFreq,cToneFreq,DtcsCode,DtcsPolarity,Mode,TStep,Skip,Comment,URCALL,RPT1CALL,RPT2CALL,DVCODE\n";
    break;
 
}
 



    
    



$id=1;
if($_POST['index'])
{
    $id =$_POST['index'];
    
}


foreach($_POST['Stationexpot'] as $box)
{
    if(is_numeric(substr($box, -1))  && strpos($box, "-") === false)
    {
        $dubbelnode = substr($box, 0, -1) ;
    
        $newStation =  clone $json->nodes->$dubbelnode;
    
    
        $newStation->{"tx_index"} = substr($box, -1);
            
    
        $json->nodes->{$box} =$newStation;    
    }
    

    
}



foreach($json->nodes as $st => $station)
{
   
    if(in_array($st,$_POST['Stationexpot'])  )
    {
       
    
        $station_first_RX ="";
        $station_first_TX ="";
        $id_to_use =0;
        
        if(is_numeric($station->tx_index))
        {
            
            $id_to_use=   $station->tx_index;
        }

        
        $i=0;
        foreach($station->qth[0]->rx as $RX => $value)
        {
            if($id_to_use == $i)
            {
             $station_first_RX= $value->freq;
            }
            $i++;
       
        }
        
        $i=0;
        foreach($station->qth[0]->tx as $TX => $value)
        {
            if($id_to_use == $i)
            {
                $station_first_TX= $value->freq;
            }
            $i++;
        }
        
        $offset=0;
        
        if($station_first_TX == null)
        {
            $station_first_TX = $station_first_RX;
        }
        else
        {
            $offset =$station_first_TX-$station_first_RX;
        }
        $ofset_char ="";
        if($offset != 0)
        {
            if ($station_first_RX-$station_first_TX <0)
            {
                $ofset_char="-";
            }
            else
            {
                 $ofset_char="+";
            }
               
                
        }
        
        
        
        
        
        $offset =$station_first_TX-$station_first_RX;
    
        foreach($station->toneToTalkgroup as $tonegroup => $talkgroup)
        {
           // 0,test,146.010000,,600.000000,TSQL,88.5,88.5,023,NN,FM,5.00,,,,,,
           
            if(is_numeric(substr($st, -1)) && strpos($st, "-") === false)
            {
                $st= substr($st, 0, -1);
            }
            
            
           if($_POST['trimc'] == 1)
           {
             $st = substr($st, -3); // returns "s"
           }
           
           
            switch ($_POST['Software'])
            {

                
//                 "3","Channel 3","432.72500","434.72500","A-Analog","High","25K","Off","62.5","Contact 1","Group Call","","Off","Carrier","Off","1","1","1","Off","1","1","None","None","Off","Off","Off","Off","Normal Encryption","Off","Off","Off","Off","251.1","0","Off","Off","Off","Off","Off","Off","1","0","Off","off"
                    
                    
                    
                case "anytone":
         
                    echo '"'.$id."\",\"".$st." ".$talkgroup."\",\"".number_format($station_first_RX, 5, '.', '') ."\",\"".number_format($station_first_TX, 5, '.', '')."\",".'"A-Analog","High","25K","Off","'.$tonegroup.'","Contact 1","Group Call","","Off","Carrier","Off","1","1","1","Off","1","1","None","None","Off","Off","Off","Off","Normal Encryption","Off","Off","Off","Off","251.1","0","Off","Off","Off","Off","Off","Off","1","0","Off","off"'."\r\n";;
                    break;
    
                    
                default:
                    echo $id.",".
                        $st." ".$talkgroup.","
                            .$station_first_TX.",".$ofset_char.",".$offset.",Tone,"
                                .$tonegroup.",".$tonegroup.",023,NN,FM,5.00,,,,,,\n";
                    break;
                    
            }
            
            
            
            

        
            $id++;
            
        } 
    }
    

}




