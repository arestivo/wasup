<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

session_destroy();

header("Location: index.php");

?>
