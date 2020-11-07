<?php
include '../config.php';
include '../function.php';
define_settings();



echo '<pre>';


?>

[ReflectorLogic]
TYPE=Reflector
HOST=<?php echo REFLEKTOR_SERVER_ADRESS?> 
PORT=<?php echo REFLEKTOR_SERVER_PORT?> 
CALLSIGN=<?php echo $_POST['Callsign1a']?> 
AUTH_KEY="<?php echo $_POST["password"]?>" 
AUDIO_CODEC=OPUS
MONITOR_TGS=<?php echo  join(",",$_POST['tg']);?> 
EVENT_HANDLER=/usr/share/svxlink/events.tcl
NODE_INFO_FILE=/etc/svxlink/node_info.json

[ReflectorLink]
CONNECT_LOGICS=SimplexLogic:9:REF,ReflectorLogic
DEFAULT_ACTIVE=1
#TIMEOUT=3600
#AUTOACTIVATE_ON_SQL=LinkTRX
#NO_DEACTIVATE=1

[GLOBAL]
LOGICS=SimplexLogic,ReflectorLogic
LINKS=ReflectorLink


<?php echo '</pre>'?>