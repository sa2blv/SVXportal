var filter_station = "";
function update_filter(value) {
	filter_station = value;
	$('#holder').html("");
	call_svxrefelktor();

}

var interval;
var totalSeconds = 0;
var current_talker = "";
function remove_notgouiltychar(string)
{
	string= string.replace("(", "");
	string= string.replace(")", "");
	string= string.replace(".", "");
	string= string.replace(",", "");
	string= string.replace("'", "");
	return string;
}



function setTime() {

}

function pad(val) {
	var valString = val + "";
	if (valString.length < 2) {
		return "0" + valString;
	} else {
		return valString;
	}
}

function write_log(logtext)
{
	var d = new Date();
	var textstring ="";
	textstring = d.getFullYear()+"-"+ d.getMonth()+"-"+d.getDate()+"-"+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
	textstring=textstring+="	"+logtext+"</br >";
	
	//$( "#logdiv" ).prepend( textstring );
}




