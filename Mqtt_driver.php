<?php
class MQtt_Driver {
    // Properties
    public $name ="MQTT";
    public $username;
    public $password;
    private $server_adress;
    private $server_port;
    private $server_USE_TLS;
    private $MSG_Java_sctipt_func;
    private $enable;
    private $message_recivers = array();
    
    // Methods
    public function Set_broker($server,$port,$TLS) {
        
        $this->server_adress    =   $server;
        $this->server_port      =   $port;
        $this->server_USE_TLS   =   $TLS;
        $this->enable = True;
        
  
    }
    public function Set_Msg_Function($func)
    {

        array_push($this->message_recivers,$func);
        
    }
    public function get_enable()
    {
        
        return $this->enable;
    }

 
    
    /*
     *  Javacipt code for connct to broker
     *
     *
     */
    
    public function javascipt()
    {
        echo "";
        
?>



            var mqtt_station_array = new Array();
            
            function onConnect() 
            {
            // Once a connection has been made, make a subscription and send a message.
            
                console.log("Connected ");
                mqtt.subscribe("#");
            }
      

            
            	
            
            
            function MQTTconnect()
            {
            	var number = Math.random() // 0.9394456857981651
            	number.toString(36); // '0.xtis06h6'
            	var id = number.toString(36).substr(2, 9); // 'xtis06h6'
            	id.length >= 9; // false
                
            	mqtt = new Paho.MQTT.Client("<?php echo $this->server_adress?>",<?php echo $this->server_port?>,("portal"+id));
            	//document.write("connecting to "+ host);
            	var options = {
            	<?php if($this->server_USE_TLS == true){?>
            		useSSL:true,
            	<?php }?>
            		timeout: 10,
            		onSuccess: onConnect,
            		onFailure: onFailure,
            		
            	  
            	 };
            	
            	mqtt.onMessageArrived = on_mqtt_MessageArrived;
            		
            
            	 
            	mqtt.connect(options); //connect
            	
            	}
            
            function onFailure(message) {
            	console.log("Connection Attempt to Host "+host+"Failed");
            	setTimeout(MQTTconnect, reconnectTimeout);
            }
            
            <?php $this->Print_mqtt_mesasage_notification()?>
            

      
    
        
        
<?php 
    }
    
    public function print_message_recived()
    {
        ?>

        function on_mqtt_MessageArrived(msg)
        {


        }

        
       <?php 
        
        
    }
    
    public function Print_hock_on_message()
    {
        ?>
        var mqtt_message_recive_function = new Array();
		mqtt_message_recive_function[1] = Mqtt_sys_msg;
		
        function on_mqtt_MessageArrived(msg)
        {
        
            for(var k in mqtt_message_recive_function)
            {

            	mqtt_message_recive_function[k](msg);
            
            }
        
   

        }

        
       <?php 
        
        
    }
    public function Print_mqtt_mesasage_notification()
    {
        ?>
        
        
        function Mqtt_sys_msg(msg)
        {
       		var payload_topic =  msg.destinationName.split("/"); 
       		
       		
       		
       		
       		
       		if(payload_topic[0] == "Sys_message")
       			create_message_toast(msg.payloadString,payload_topic[1],0,"red","true");
       		if(payload_topic[0] == "Sys_message_important")
   				create_message_toast(msg.payloadString,payload_topic[1],0,"red","false");
       			
       		
       		

        //  create_message_toast(message,title,type,color)
        
      
        
        }
        <?php         
        
    }
    
}