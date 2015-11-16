<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_inscriptions.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
	
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];

    if($loginjoueur == ""){
        auto_login();
        $loginjoueur = $_SESSION['LoginJoueur'];
    }

    if($loginjoueur == ""){
	$success = false;
	
        echo json_encode(array('filtre' => 0, 'success' => $success));
	return;
    }
    //------------------------------------------------------------------------------------------------//
    
    $id_jeu = $_POST['id_jeu'];
    if($id_jeu == 0 || id_jeu == null){
	echo json_encode(array('filtre' => 'id jeu nul', 'success' => false));
	return;
    }
    
    $races = $_POST['races'];
    
    $tmp = '';
    $filtre = 0;
    foreach($races as $key => $race){
	$tmp .= $race["inscr"] . ' - ';
	if($race["inscr"] == "true"){
	    $filtre += pow(2,$race["id"]);
	}
    }
    
    if($filtre == 0){
	$filtre = 8191;
    }
    
    $success = update_inscription_filtre($id_jeu, $loginjoueur, $filtre);
	
    echo json_encode(array('filtre' => $filtre, 'success' => $success));
    return;
?>