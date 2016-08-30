<?php 

define('__ROOT__', dirname(__FILE__)); 
require_once(__ROOT__.'/basic.php'); 
?> 
<html>
<head>
<title>Train Master</title>



<!-- Fonts -->
<link rel="stylesheet" href="<?php print_r($configv["home"]);?>/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
<link rel="stylesheet" href="<?php print_r($configv["home"]);?>/css/font.family.lato">
<!-- Styles -->
<link rel="stylesheet" href="<?php print_r($configv["home"]);?>/css/bootstrap.min.css">
    
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
               <button type="button" class="btn btn-link navbar-brand" data-toggle="modal" data-target="#aboutModal">
                    Train Master
                </button>
            </div>
            <div class="collapse navbar-collapse" id="spark-navbar-collapse">
		<img style="vertical-align:middle" src="img/marklin.png" width="100">
                <ul class="nav navbar-nav">
                    <li><a href="<?php print_r($configv["home"]);?>/index.php">Home</a></li>
                    <li><a href="#inner">Inner Circuit</a></li>
                    <li><a href="#outer">Outer Circuit</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a id="Lallstraight" href="#">All Straight!</a></li>
                    <li><a id="Lallstart" href="#">All Start!</a></li>
                    <li><a id="Lallstop" href="#">Emergency Stop!</a></li>
            	    <li class="dropdown">
	                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Admin<span class="caret"></span></a>
                      <ul class="dropdown-menu">
                          <li><a id="RefreshDeviceStatus" href="#">Refresh Device Status</a></li>
                          <li><a id="ToggleLog" href="#">Hide/View Log</a></li>
                          <li role="separator" class="divider"></li>
                          <li><a id="LRPiInfo" data-toggle="modal" data-target="#RPiInfoModal" href="#">Raspberry Pi Info</a></li>
                          <li><a id="Lshutdown" href="#" data-toggle="modal" data-target="#shutdownModal">Shutdown!</a></li>
                      </ul>
            	    </li>
		
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

 <!-- About Modal -->
  <div class="modal fade" id="aboutModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Train Master</h4>
        </div>
        <div class="modal-body">
          <p>Relay Control for model train. Get project here:
          <br />
          <a href="https://github.com/chapunazar/trainmaster">https://github.com/chapunazar/trainmaster</a>
          </p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  
 <!-- RPiInfo Modal -->
  <div class="modal fade" id="RPiInfoModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Train Master - Raspberry Pi Info</h4>
        </div>
        <div class="modal-body" id="RPiInfoText">
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-default" data-dismiss="modal">Close</a>
        </div>
      </div>
    </div>
  </div>
 
 <!-- Shutdown Modal -->
  <div class="modal fade" id="shutdownModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Train Master</h4>
        </div>
        <div class="modal-body" id="shutdownText">
          <p>Are you sure you want to shutdown the Raspberry Pi?
          <br />
	   TrainMaster will stop to respond to your commands.
          </p>
        </div>
        <div class="modal-footer">
          <a type="button" class="btn btn-danger" data-dismiss="modal" id="Shutdown" href="#">Yes</a>
          <a type="button" class="btn btn-default" data-dismiss="modal">No</a>
        </div>
      </div>
    </div>
  </div>
 


<!-- JavaScripts -->
<!-- Latest compiled and minified Bootstrap JavaScript -->

<!-- lOCAL lIBS -->
<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/jquery.min.js"></script>
<script src="<?php print_r($configv["home"]);?>/scripts/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/jquery.imagemapster.js"></script>
<script type="text/javascript" src="<?php print_r($configv["home"]);?>/scripts/relay.events.js"></script>
 
</body>
</html>
