<?php 

define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/basic.php'); 
?> 
<html>
<head>
<title>Train Master</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>
    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/jquery.imagemapster.js"></script>
<!--<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/map.js"></script>-->
<script type="text/javascript">



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


$('#RefreshPowerRelay').click(function(){ 
	//Get the status of the power refresh
	var method = 'getrelaysstatus';
	var category = 'power';
	var info = '{"category":"'+ category + '","method":"'+ method + '"}';
	var data = "service.php?info=" + info;   
	var text ="";
	data = sendMessage(data);
	// I receive a Json and remove first all backslash
	var obj = JSON.parse(data);
	data = JSON.parse(obj.data);
	var value = false;
	for (i = 0; i < data.relays.length; i++) { 
		value = false;
		if (data.relays[i].value.toUpperCase()=="HIGH"){
			value = true;
		}	
		image.mapster('set', Boolean(value), data.relays[i].id);
		text += "Setting to " + value + " to " + data.relays[i].id + "<br>";
	}
	showLog(text);

	//Refresh the image map with the values refreshed

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


//from document ready
});
</script>
<style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
	
	#fixed-top {
	    position: fixed;
	    z-index: 1;
	    margin: auto;
	    width: 100%;
	}

	#content-pos {
	position: relative;
	top: 4em;

	}

	pre {
	white-space: pre-wrap; /* CSS3 */
	white-space: -moz-pre-wrap; /* Mozilla, post millennium */
	white-space: -pre-wrap; /* Opera 4-6 */
	white-space: -o-pre-wrap; /* Opera 7 */
	word-wrap: break-word; /* Internet Explorer 5.5+ */
	}

</style>

</head>
<body>
<div id="fixed-top">
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <!-- Branding Image -->
                <a class="navbar-brand" href="/index.php">
                    Train Master
                </a>
            </div>
            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="<?php print_r($configv["home"]);?>/index.php">Home</a></li>
                    <li><a href="#inner">Inner Circuit</a></li>
                    <li><a href="#outer">Outer Circuit</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a id="ToggleLog" href="#">Hide/View Log</a></li>
                    <li><a id="RefreshPowerRelay" href="#">Refresh Power Relay</a></li>
                    <li><a id="Lallstraight" href="#">All Straight!</a></li>
                    <li><a id="Lallstart" href="#">All Start!</a></li>
                    <li><a id="Lallstop" href="#">Emergency Stop!</a></li>
                </ul>
 
             </div>
        </div>
    </nav>
</div>
<div id="content-pos">
<div class="container spark-screen">
	<div class="row">
			<div class="panel panel-default"><a name="inner"></a>
				<div class="panel-heading">Inner Circuit</div>
				<div class="panel-body" style="float: left; margin: 0px 30px 0px 0px;">
					<a id="innerIncrease" href="#"><img src="img/map_increase.png"></a>&nbsp;
					<a id="innerDecrease" href="#"><img src="img/map_decrease.png"></a>
					<br /><br />
					<img width="<?php print_r($configv["mapwidth"])?>%" src="map/train1.png" usemap="#train1" border="0" id="innermap">
					<map name="train1" id="train1_map">
					<?php
					//Display the Power relays
					foreach($vRelays['power'] as $r=>$info) {
						$jinfo = json_decode($info);
						$coord = $jinfo->{"coord"};
						$circuit = "circ".$jinfo->{"circuit"};
						$text = '<area shape="circle" coords="'.$coord.','.$configv["power_electric_light_width"].'" href="#" data-key="'.$r.',power-all,'.$circuit.'">';
						print_r($text);

					}
			
					//Display the turnout relays
					foreach($vRelays['turnout'] as $r=>$info) {
						$jinfo = json_decode($info);
						$coord = $jinfo->{"coord"};
						$text = '<area shape="circle" coords="'.$coord.','.$configv["turnout_electric_light_width"].'" href="#" data-key="'.$r.',turnout-all">';
						print_r($text);

					}					
						
					?>
					</map>

				</div>
				<div style="margin: 70px 0px 0px 70px;">
                			<ul>
                    				<li><a id="LstartA" href="#">Start A</a></li>
                    				<li><a id="LstopA" href="#">Stop A</a></li>
					</ul>   	
					<ul>
						<li><a id="LstartB" href="#">Start B</a></li>
                    				<li><a id="LstopB" href="#">Stop B</a></li>
					</ul>
					<div id="debug"><pre><div id="debugtxt"></div></pre></div>
				</div>
				<br style="clear:both" />
			</div>
			<div class="panel panel-default"><a name="outer"></a>
				<div class="panel-heading">Outer Circuit - not functional</div>
				<div class="panel-body">
					<img width="<?php print_r($configv["mapwidth"])?>%" src="map/train2.png" usemap="#train2" border="0" id="outermap">
					<map name="train2">
					</map>

				</div>
			</div>
	</div>
</div>
</div>


</body>
</html>
