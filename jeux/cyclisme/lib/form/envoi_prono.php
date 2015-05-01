<?php

    // ------------ INCLUDES ----------//
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/mail/envoi_mails.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    // ------------ INCLUDES ----------//
    
    
    
    // ------------ CONNEXION BDD ----------//
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    // ------------ CONNEXION BDD ----------//
    
    
    
    // ------------ RECUPERATION DES PARAMETRES ----------//
    session_start();
    $login = $_SESSION['LoginJoueur'];
    $id_jeu = $_POST['id_jeu'];
    $id_cal = $_POST['id_cal'];
    $bonus = $_POST['bonus'];
    $arr_prono = $_POST['prono'];
    $calendrier = get_calendrier($id_jeu, $id_cal);
    // ------------ RECUPERATION DES PARAMETRES ----------//
    
    
    
    // ------------ VERIFICATION DES PARAMETRES ----------//
    if($calendrier == null){
	echo 'Cette épreuve n\'existe pas...';
	return;
    }
    if ($login == ''){
	echo 'Joueur non connecté';
	return;
    }
    if (sizeof($arr_prono) != 10){
	if($calendrier['profil_equipe']){
	    echo 'Vous n\'avez pas sélectionné dix équipes';
	    return;
	}
	else{
	    echo 'Vous n\'avez pas sélectionné dix coureurs';
	    return;
	}	
    }
    // ------------ VERIFICATION DES PARAMETRES ----------//

    
    
    // ------------ PRONO DISPONIBLE ? ----------//
    //$dateactuelle = time
    if($calendrier['disponible'] == 0){
	echo 'Vous ne pouvez pas encore pronostiquer sur cette épreuve...';
	return;
    }
    
    if($calendrier['date_debut'] < $dateactuelle){
	echo 'Vous ne pouvez plus pronostiquer sur cette épreuve...';
	return;
    }
    // ------------ PRONO DISPONIBLE ? ----------//
    
    
    
    
    // ------------ PRONO DEJA FAIT ? ----------//
    $sql = "SELECT * FROM cyclisme_prono WHERE id_jeu=? AND id_calendrier=? AND joueur=?";

    $prep = $db->prepare($sql);
    $prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
    $prep->bindValue(2,$id_cal,PDO::PARAM_INT);
    $prep->bindValue(3,$login,PDO::PARAM_STR);
    
    $prep->execute();
    $prep->setFetchMode(PDO::FETCH_OBJ);
    $enregistrement = $prep->fetch();
    if($enregistrement){
	$idpronofait = $enregistrement->id_cyclisme_prono;
    }
    else{
	$idpronofait = 0;
    }
    // ------------ PRONO DEJA FAIT ? ----------//
    
    
    // ------------ CONSTRUCTION DU PRONO ----------//
    foreach($arr_prono as $id_cycliste) {
	$prono .= $id_cycliste . ';';
    }
    // ------------ CONSTRUCTION DU PRONO ----------//
    
    // ------------ ENVOI DU PRONO ----------//
    if ($idpronofait != 0){
	$sql = "UPDATE cyclisme_prono SET prono=?, bonus_risque=? WHERE id_cyclisme_prono=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$prono,PDO::PARAM_STR);
	$prep->bindValue(2,$bonus,PDO::PARAM_INT);
	$prep->bindValue(3,$idpronofait,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	 echo 'success;Pronostic modifié !';
    }
    else{
	$sql = "INSERT INTO cyclisme_prono(id_jeu,id_calendrier,joueur,prono,points_prono,score_base,bonus_nombre,bonus_risque,score_total,classement) VALUES(?,?,?,?,'',0,0,?,0,0)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(3,$login,PDO::PARAM_STR);
	$prep->bindValue(4,$prono,PDO::PARAM_STR);
	$prep->bindValue(5,$bonus,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	echo 'success;Pronostic enregistré !';
    }  
    // ------------ ENVOI DU PRONO ----------//
    
   
?>