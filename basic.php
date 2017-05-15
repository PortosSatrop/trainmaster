

<?php
global $configv;
global $vRelays;
$configv = parse_ini_file("config.ini", false);

$vRelays = parse_ini_file("relays.ini",true);

function url(){
    $pu = parse_url($_SERVER['REQUEST_URI']);
    return $pu["scheme"] . "://" . $pu["host"];
}


?>
