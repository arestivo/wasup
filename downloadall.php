<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

if (!$wasup->authenticator->isLoggedIn()) die(header('Location: login.php'));

$wasup->assignment->downloadAll();
?>
