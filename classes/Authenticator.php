<?php

class Authenticator {

	function __construct($config) {
		foreach ($config['users'] as $user) {
			$this->users[] = json_decode($user);
		}
	}

	function login($user, $pass) {
	}

	function isAllowedUser($user) {
		foreach ($this->users as $au) {
			if ($au->type == 'single' && $this->isAllowedSingle($user, $au)) return true;
			if ($au->type == 'group' && $this->isAllowedGroup($user, $au)) return true;
		}
		return false;
	}

	function isAdmin($user) {
		foreach ($this->users as $au) {
			if ($au->type == 'single' && $this->isAllowedSingle($user, $au)) 
				return $au->admin;
			if ($au->type == 'group' && $this->isAllowedGroup($user, $au)) 
				return $au->admin;
		}
		return false;
	}

	function isAllowedSingle($user, $allowed) {
		return ($user == $allowed->username);
	}

	function isAllowedGroup($user, $allowed) {
		if (strpos($user, $allowed->start) !== 0) return false;
		$number = substr($user, strlen($allowed->start));
		if ((0 + $number) < $allowed->first) return false;
		if ((0 + $number) > $allowed->last) return false;
		return true;
	}

	function loginForm($referer) {
		echo '<form action="actionlogin.php" method="post">';
		echo '<fieldset>';

		echo '<input type="hidden" name="from" value="'.$referer.'"/>';

        echo '<legend>Login</legend>';

		echo '<div class="clearfix">';
        echo '<label>Login</label>';
		echo '<div class="input">';
	    echo '<input class="" type="text" name="username" />';
		echo '</div>';
		echo '</div>';

		echo '<div class="clearfix">';
        echo '<label>Password</label>';
		echo '<div class="input">';
	    echo '<input class="" type="password" name="password" />';
		echo '</div>';
		echo '</div>';
		

		echo '<div class="actions"><input value="Login" class="btn primary" type="submit"/></div>';
		echo '</fieldset>';
		echo '</form>';
	}

	function isLoggedIn() {
		if (!isset($_SESSION['username'])) return false;
		return $_SESSION['username'] !== '';
	}

}

?>
