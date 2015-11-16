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
	
        echo json_encode(array('success' => $success));
	return;
    }
    //------------------------------------------------------------------------------------------------//
    
    
    //--------------------------------------PARAMETRES--------------------------------------//
    $no_mail = $_POST['no_mail'];
    $id_jeu = $_POST['id_jeu'];
    //------------------------------------------------------------------------------------------------//
    
    $success = update_inscription_mail($id_jeu, $loginjoueur, $no_mail);

    echo json_encode(array('success' => $success));
    return;

?>