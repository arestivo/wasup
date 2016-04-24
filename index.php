<?php

require_once('classes/Wasup.php');

$wasup = new Wasup();
$wasup->readConfiguration();

include('html/header.html');

if ($wasup->testUploadPerms()) {
	$wasup->assignment->listUploads();
  echo '<p>Delivery by: '.$wasup->assignment->date.'</p>';
  echo '<p>Time remaining: <span id="remaining"></span></p>';
	echo '<form><a class="btn" href="upload.php">Upload Assignment</a></form>';
}
?>
<script>
  updateRemaining();
  function updateRemaining() {
    var d1 = new Date('<?=$wasup->assignment->date?>');
    var d2 = new Date();
    var delta = Math.floor(d1.getTime() - d2.getTime()) / 1000;
    if (delta < 0) {
      document.getElementById('remaining').innerHTML = "Ended";
      return;
    }
    var days = Math.floor(delta / 86400);
    delta -= days * 86400;
    var hours = Math.floor(delta / 3600) % 24;
    delta -= hours * 3600;
    var minutes = Math.floor(delta / 60) % 60;
    delta -= minutes * 60;
    var seconds = Math.floor(delta % 60);

    document.getElementById('remaining').innerHTML = days + " days " + 
        (hours < 10 ? "0" + hours : hours) + ":" + 
        (minutes < 10 ? "0" + minutes : minutes) + ":" + 
        (seconds < 10 ? "0" + seconds : seconds);
    setTimeout(updateRemaining, 1000);
  }
</script>
<?php
include('html/footer.html');
?>

