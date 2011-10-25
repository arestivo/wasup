<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

include('html/header.html');

if ($wasup->testUploadPerms()) {
	$wasup->assignment->listUploads();
	echo '<form><a class="btn" href="upload.php">Upload Assignment</a></form>';
}

include('html/footer.html');
?>
