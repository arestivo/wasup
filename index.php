<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

include('html/header.html');

if ($wasup->testUploadPerms()) {
	echo '<form><a class="btn" href="upload.php">Upload</a></form>';
	$wasup->assignment->listUploads();
}
include('html/footer.html');
?>
