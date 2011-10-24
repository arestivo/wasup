<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

if (!$wasup->authenticator->isLoggedIn()) die(header('Location: login.php?from=upload'));

include('html/header.html');

$wasup->testUploadPerms();
$wasup->assignment->uploadForm();

include('html/footer.html');
?>
