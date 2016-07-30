<?php
print_r("HOLA");
$result = shell_exec("sudo python relay.py allstop dummy");
print_r($result);
?>
