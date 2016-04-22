<?php
session_name('wasup_session_' . basename(dirname($_SERVER['REQUEST_URI'])));
session_start();

class Wasup {

	function readConfiguration() {
		$files = scandir('config');
		foreach ($files as $file) 
			if ($file[0] != '.' && substr($file,strlen($file) - 4) == '.ini') 
				$this->readConfigFile($file);
	}

	function readConfigFile($file) {
		$data = parse_ini_file('config/' . $file, true);
		foreach ($data as $section => $content) {
			if ($section == 'authentication') $this->initAuthentication($content);
			if ($section == 'assignment') $this->initAssignment($content);
			if ($section == 'global') $this->initGlobal($content);
		}
	}

	function initGlobal($config) {
		date_default_timezone_set($config['timezone']);
	}

	function initAuthentication($config) {
		if ($config['type'] == 'postgresql') {
			include_once('classes/PostgreSQLAuthenticator.php');
			$this->authenticator = new PostgreSQLAuthenticator($config);
		}
	}

	function initAssignment($config) {
		include_once('classes/Assignment.php');
		$this->assignment = new Assignment($config);
	}

	function testUploadPerms() {
		if (!@fopen("uploads/test.txt", 'w')) {
			echo '<div class="alert-message error">
				    <p><strong>Cannot write to uploads folder</p>
			     </div>';
			return false;
		} 
		unlink("uploads/test.txt");
		return true;	
	}

}

?>
