<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];

$wasup->authenticator->login($username, $password);

if (!isset($_REQUEST['from'])) die(header("Location: index.php"));
if ($_REQUEST['from'] == 'index') die(header("Location: index.php"));
if ($_REQUEST['from'] == 'upload') die(header("Location: upload.php"));

?>
