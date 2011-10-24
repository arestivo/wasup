<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

include('html/header.html');

$wasup->testUploadPerms();
$wasup->authenticator->loginForm($_GET['from']);

include('html/footer.html');
?>
