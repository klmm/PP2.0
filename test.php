<?php
	include $_SERVER['DOCUMENT_ROOT'] . '/fonctions/fonctions.php';
	
	$date = "2015-03-15 11:00:00";
	$diff = date_to_duration($date);
	
	echo 'res: ' . $diff;
?>