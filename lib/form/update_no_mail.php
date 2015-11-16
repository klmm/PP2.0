<?php

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
    //------------------------------------------------------------------------------------------------//
    
    $sql = "UPDATE Joueurs SET no_mail=? WHERE Login=?";
	
    $prep = $db->prepare($sql);
    $prep->bindValue(1,$no_mail,PDO::PARAM_INT);
    $prep->bindValue(2,$loginjoueur,PDO::PARAM_STR);
    $success = $prep->execute();

    echo json_encode(array('success' => $success));
    return;

?>