<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

if (!$wasup->authenticator->isLoggedIn()) die(header('Location: login.php'));

if (isset($_GET['username'])) 
	$username = $_GET['username']; else $username = null;

$wasup->assignment->download($_GET['name'], $username);
?>
