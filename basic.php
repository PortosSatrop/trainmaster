

<?php
global $configv;
global $vRelays;
$configv = parse_ini_file("config.ini", false);

$vRelays = parse_ini_file("relays.ini",true);

?>
