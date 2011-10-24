<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

$wasup->assignment->handleUpload();

header("Location: index.php");
?>
