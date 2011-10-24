<?php

class Assignment {

	function __construct($config) {
		foreach ($config['data'] as $data) {
			$this->data[] = json_decode($data);
		}
		$this->date = $config['date'];
		$this->title = $config['title'];
		$this->course = $config['course'];
	}

	function uploadForm() {
		echo '<form action="actionupload.php" method="post" enctype="multipart/form-data">';
		echo '<fieldset>';
        echo '<legend>Assignment Upload</legend>';
		foreach ($this->data as $data) {
			echo '<div class="clearfix">';
            echo '<label>'.ucfirst($data->name).'</label>';
			if ($data->type=='text') {
				$value = @file_get_contents('uploads/' . $_SESSION['username'] . '/' . $data->name . '.txt');
				echo '<div class="input">';
		        echo '<input class="span8" type="text" value="'.$value.'" name="'.$data->name.'" />';
				echo '</div>';
			}
			if ($data->type=='file') {
				echo '<div class="input">';
		        echo '<input class="span8" type="file" name="'.$data->name.'" />';
				echo '</div>';
			}
			if ($data->type=='bigtext') {
				$value = @file_get_contents('uploads/' . $_SESSION['username'] . '/' . $data->name . '.txt');
				echo '<div class="input">';
		        echo '<textarea class="span8" name="'.$data->name.'">'.$value.'</textarea>';
				echo '</div>';
			}
			echo '</div>';
		}
		echo '<div class="actions"><input value="Update" class="btn primary" type="submit"/></div>';
		echo '</fieldset>';
		echo '</form>';
	}

	function handleUpload() {
		@mkdir('uploads/' . $_SESSION['username']);
		$file = 'uploads/' . $_SESSION['username'] . '/uploaded.txt';
		$f = fopen($file, 'w') or die("can't open file");
		fwrite($f, time());
		fclose($f);
		
		foreach ($this->data as $data) {
			if ($data->type=='text') $this->saveText($data);		
			if ($data->type=='bigtext') $this->saveText($data);		
			if ($data->type=='file') $this->saveFile($data);		
		}
	}
	
	function listUploads() {
		$files = scandir('uploads');
		echo '<h2>Uploads</h2>';
		echo '<table>';
		echo '<tr><th>Username</th>';
		foreach ($this->data as $data) echo '<th>'.ucfirst($data->name).'</th>';
		echo '<th>Date</th></tr>';
		foreach ($files as $user) 
			if ($user[0] != '.' && $user[strlen($user) - 1] != '~') 
				$this->showUserUpload($user);
		echo '</table>';
	}

	function showUserUpload($user) {
		$folder = 'uploads/' . $user . '/';
		echo "<tr><td>$user</td>";
		foreach ($this->data as $data) 
			if ($data->type == 'file')
				$this->showFile($user, $data, $folder . $data->name);
			else
				$this->showText($data, $folder . $data->name);

		$this->showDate(file_get_contents($folder . 'uploaded.txt'));
		echo '</tr>';
	}

	function showDate($date) {
		$class = (strtotime($this->date) - $date<0)?'overdue':'ontime';
		echo "<td class=\"$class\">";
		echo date('Y-m-d H:i:s', $date);
		echo '</td>';
	}

	function showText($data, $file) {	
		$file .= '.txt';
		if (file_exists($file)) $class='uploaded'; else $class='';
		echo "<td class=\"$class\">";
		if (file_exists($file) && isset($data->show) && $data->show)
			echo file_get_contents($file);
		else echo "&nbsp;";
		echo '</td>';
	}

	function showFile($user, $data, $file) {	
		$extensions = explode('|', $data->extensions);
		$class = '';
		foreach ($extensions as $ext)
			if (file_exists($file . $ext)) $class='uploaded'; 
		echo "<td class=\"$class\">";
		if (isset($_SESSION['type']) && $_SESSION['type'] == 'admin')
			echo '<a href="download.php?username='.$user.'&name=' . $data->name . '">Download</a>';
		else if (isset($_SESSION['username']) && $_SESSION['username'] == $user) 
			echo '<a href="download.php?name=' . $data->name . '">Download</a>';
		else echo "&nbsp;";
		echo '</td>';
	}

	function download($name, $username) {
		if ($username == null || $_SESSION['type'] != 'admin') 
			$username = $_SESSION['username'];
		foreach ($this->data as $data) {
			if ($data->name == $name) {
				$extensions = explode('|', $data->extensions);
				foreach ($extensions as $ext) {
					$filename = 'uploads/' . $username . '/' . $name . $ext;
					if (file_exists($filename)) {
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename='.$name.$ext);
						header('Content-Transfer-Encoding: binary');
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Pragma: public');
						header('Content-Length: ' . filesize($filename));				
						readfile($filename);
						return;
					}
				}
			}
		}
		header('HTTP/1.1 404 Not Found');
		echo '<h1>404: File not found!</h1>';
	}

	function saveText($data) {
		$file = 'uploads/' . $_SESSION['username'] . '/' . $data->name . '.txt';
		if (!$_POST[$data->name]) @unlink($file);
		else {
			$f = fopen($file, 'w') or die("can't open file");
			fwrite($f, $_POST[$data->name]);
			fclose($f);
		}
	}

	function saveFile($data) {
		$extension = $this->getExtension($_FILES[$data->name]['name']);
		if (strpos($data->extensions, $extension)===false) 
			$_SESSION['errors'][] = "Can't upload file. Extension not allowed: " . $extension;
		else {
			$file = 'uploads/' . $_SESSION['username'] . '/' . $data->name . $extension;
			move_uploaded_file($_FILES[$data->name]['tmp_name'], $file);
		}
	}

	function getExtension($filename) {
		if (!strrpos($filename, '.')) return '.unknown';
		return substr($filename,strrpos($filename, '.'));
	}
}

?>
