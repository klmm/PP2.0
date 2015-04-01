<?php

	session_start();
	session_unset();
	
	if (isset($_COOKIE['ParionsPotes'])) {
		//unset($_COOKIE['ParionsPotes']);
		setcookie('ParionsPotes', '', time() - 3600,'/','www.parions-potes.fr',false,true);
		echo 'ok';
	} else {
		echo 'nok';
	}

	
?>