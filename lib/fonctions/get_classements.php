<?php

    function get_classement($url){
	$handle = fopen($url, "r");
	if ($handle) {
	    $titre = preg_replace( "/\r|\n/", "", fgets($handle));
	    $description = preg_replace( "/\r|\n/", "", fgets($handle));
	    $titre_colonnes = preg_replace( "/\r|\n/", "", fgets($handle));
	    $arr_titre_colonnes = explode(";",$titre_colonnes);
	    
	    $largeur_colonnes = preg_replace( "/\r|\n/", "", fgets($handle));
	    $arr_largeur_colonnes = explode(";",$largeur_colonnes);
	    
	    $line = preg_replace( "/\r|\n/", "", fgets($handle));
	    while ($line != '') {
		$classement[] = explode(";",$line);
		$line = preg_replace( "/\r|\n/", "", fgets($handle));
	    }

	    fclose($handle);
	    
	    $res = array(
		'titre' => $titre,
		'description' => $description,
		'titre_colonnes' => $arr_titre_colonnes,
		'largeur_colonnes' => $arr_largeur_colonnes,
		'classement' => $classement);
	    return $res;
	}
	else {
	    return null;
	} 
    }
     
    function get_classements($url){	
	$first_char = substr($url, 0, 1);
	$last_char = substr($url, strlen($url)-1, 1);
 
	if ($last_char != '/') {
	    $url .= '/';
	}
	
	if ($first_char != '/') {
	    $url = '/' . $url;
	}
	$url = $_SERVER['DOCUMENT_ROOT'] . $url;
    
	$file = array_diff(scandir($url), array('..', '.'));
	
	foreach ($file as $key => $dir) 
	{
	    $arr_classements[] = get_classement($url . $dir);
	}
	
	return $arr_classements;
    }
   
?>