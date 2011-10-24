<?php

require_once('classes/Authenticator.php');

class PostgreSQLAuthenticator extends Authenticator {

	function __construct($config) {
		parent::__construct($config);
		$this->host = $config['host'];
	}

	function login($user, $pass) {
		if (!$this->isAllowedUser($user)) {
			$_SESSION['errors'][] = 'User not allowed.';
			return false;
		}
		if (!pg_connect("host=" . $this->host . " dbname=template1 user=$user password=$pass")) {
			$_SESSION['errors'][] = 'Authentication failed.';
			return false;
		}

		$_SESSION['username'] = $user;
		if ($this->isAdmin($user)) $_SESSION['type'] = 'admin'; 
		else $_SESSION['type'] = 'user';

		return true;
	}
}

?>
