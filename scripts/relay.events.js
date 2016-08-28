
$(document).ready(function ()
{
// default colors
var default_active_color = "00FF00";
var default_inactive_color = "FF0000";
var default_color = "FFFFFF";
var turnout_active_color = "FFFF00";

//Hidden by default
$('#debug').hide();

/* USING AJAX
function Notused_toggleDevice(device){
	var info = '{"device":"'+ device + '"}';
	$.ajax({ 
		type: "GET",
		dataType: "json",
		data: { method: "hello", format: "json" },
		url: "service.php",
		success: function(info){        
		 }

	}).done(function() {
		info = JSON.stringify(info);
		return info;	
	});

}
*/

function showLog(txt){
	txt = txt.replace(/\\n/g,"<br />");
	$('#debugtxt').html(txt);
}

function getRelayCategory(relay){
	//this function could never be used
	var v = relay.split("-");
	var cat = "unknown";
	if (v[0].toUpperCase() == "P"){
		cat="power";
	}
	if (v[0].toUpperCase() == "T"){
		cat="turnout";
	}
	return cat;
	
}

function sendMessage (data){
	var xhttp;

	if (window.XMLHttpRequest) {
		xhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	data = encodeURI(data + '&format=json');
	xhttp.open("GET", data , false); //false to make it asynch
	xhttp.send();
	return xhttp.responseText;

}

function toggleDevice(device){
	var method = 'toggle';
	var info = '{"device":"'+ device + '","method":"'+ method + '"}';
	var data = "service.php?info=" + info;   
	data = sendMessage(data);
	showLog(data);
}

function allStop(){
	var method = 'allstop';	
	var info = '{"method":"'+ method + '"}';
	var data = "service.php?info=" + info;   	
	data = sendMessage(data);
	showLog(data);
}

function allStart(){
	var method = 'allstart';
	var info = '{"method":"'+ method + '"}';
	var data = "service.php?info=" + info;   	
	data = sendMessage(data);
	showLog(data);
}

function startCircuit(circuit){
	var method = 'startcircuit';
	var info = '{"method":"'+ method + '","circuit":"' + circuit + '"}';
	var data = "service.php?info=" + info;   	
	data = sendMessage(data);
	showLog(data);
}

function stopCircuit(circuit){
	var method = 'stopcircuit';
	var info = '{"method":"'+ method + '","circuit":"' + circuit + '"}';
	var data = "service.php?info=" + info;   	
	data = sendMessage(data);
	showLog(data);
}

function allStraight(){
	var method = 'allstraight';
	var info = '{"method":"'+ method + '"}';
	var data = "service.php?info=" + info;   	
	data = sendMessage(data);
	showLog(data);
}

// Shutdown Rpi
function Shutdown(){
	var method = 'shutdown';
	var info = '{"method":"'+ method + '"}';
	var data = "service.php?info=" + info;   	
	data = sendMessage(data);
	showLog(data);
}



function innerSize(size){
	var w = $('#innermap').width();
	w = w*size;
	$('#innermap').mapster('resize',w);
}

var image = $('#innermap');
image.mapster({
        
	mapKey: 'data-key',
	fillOpacity: 1,
        fillColor: default_color,  //normally never used
        //fillColor: default_inactive_color,  //normally never used
	
	render_select: {
		fillColor: default_active_color                                                        
        },

        onClick: function (e) {
		var resp = toggleDevice(e.key);
		var cat = getRelayCategory(e.key);
		if (cat=="turnout"){
			image.mapster('set_options', { 
	                areas: [{
        	            key: e.key,
                	    render_select: {fillColor: turnout_active_color}
	                    }]

        	        });
		}
        },

});

//Capture the EMERGENCY STOP and the ALL START
$('#Lallstop').click(function(){ 
	//var opts = image.mapster('get_options');
	//image.mapster(opts); //do not use, it will remove turnouts as well
	allStop();
	var neKeys = image.mapster('keys','power-all');
	image.mapster('set', false, neKeys);
	return false;
});
$('#Lallstart').click(function(){ 
	allStart();
	//image.mapster('set',true,'P-REL01');
	var neKeys = image.mapster('keys','power-all');
	image.mapster('set', true, neKeys);
	return false; 
});

//Capture start and stop events of the different circuits
$('#LstartA').click(function(){ 
	startCircuit("A");
	var neKeys = image.mapster('keys','circA');
	image.mapster('set', true, neKeys);
	return false; 
});
$('#LstopA').click(function(){ 
	stopCircuit("A");
	var neKeys = image.mapster('keys','circA');
	image.mapster('set', false, neKeys);
	return false; 
});

$('#LstartB').click(function(){ 
	startCircuit("B");
	var neKeys = image.mapster('keys','circB');
	image.mapster('set', true, neKeys);
	return false; 
});
$('#LstopB').click(function(){ 
	stopCircuit("B");
	var neKeys = image.mapster('keys','circB');
	image.mapster('set', false, neKeys);
	return false; 
});

$('#RefreshDeviceStatus').click(function(){ 
	//Get the status of the power refresh
	var method = 'getdevicestatus';
	
	// First power devices
	var category = 'power';
	var info = '{"category":"'+ category + '","method":"'+ method + '"}';
	var data = "service.php?info=" + info;   
	var text ="";
	data = sendMessage(data);
	// I receive a Json and remove first all backslash
	var obj = JSON.parse(data);
	data = JSON.parse(obj.data);
	var value = false;
	for (i = 0; i < data.devices.length; i++) { 
		value = false;
		if (data.devices[i].value.toUpperCase()=="HIGH"){
			value = true;
		}	
		image.mapster('set', Boolean(value), data.devices[i].id);
		text += "Setting " + value + " to " + data.devices[i].id + "<br>";
	}

	//Now turnouts
	var category = 'turnout';
	var info = '{"category":"'+ category + '","method":"'+ method + '"}';
	var data = "service.php?info=" + info;   
	data = sendMessage(data);
	// I receive a Json and remove first all backslash
	var obj = JSON.parse(data);
	data = JSON.parse(obj.data);
	var value = false;
	for (i = 0; i < data.devices.length; i++) { 
		value = false;
		if (data.devices[i].status.toUpperCase()=="DEVIATE"){
			value = true;
		}		
		image.mapster('set_options', { 
	                areas: [{
        	            key: data.devices[i].id,
                	    render_select: {fillColor: turnout_active_color}
	                    }]

        	        });

		image.mapster('set', Boolean(value), data.devices[i].id);
		text += "Setting " + value + " to " + data.devices[i].id + "<br>";
	}
	showLog(text);



	return false; 

});

//Capture the All Straight order
$('#Lallstraight').click(function(){ 
	allStraight();
	var neKeys = image.mapster('keys','turnout-all');
	image.mapster('set', false, neKeys);
	return false;
});
//Change maps size dynamically
$('#innerDecrease').click(function(){ innerSize(0.9); return false; });
$('#innerIncrease').click(function(){ innerSize(1.1); return false; });

//Toggle view hide log
$('#ToggleLog').click(function(){ $('#debug').toggle(); return false; });

//Shutdown Raspberry Pi
$('#Shutdown').click(function(){ 
	Shutdown();
	return false; 
});


//from document ready
});
