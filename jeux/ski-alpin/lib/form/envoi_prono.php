<?php

    // ------------ INCLUDES ----------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php';
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
    $arr_prono = $_POST['prono'];
    $calendrier = get_calendrier($id_cal);
    // ------------ RECUPERATION DES PARAMETRES ----------//    
    
    // ------------ VERIFICATION DES PARAMETRES ----------//
    if(!is_numeric($id_jeu) || !is_numeric($id_cal)){
	$msg = 'Paramètre non numérique';
	$rafr = true;
	$res = false;
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    
    foreach($arr_prono as $key => $value){
	if (!is_numeric($value)){
	    $msg = 'Paramètre non numérique';
	    $rafr = true;
	    $res = false;
	    $rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	    echo json_encode($rep);
	    return;
	}
    }
    
    if($calendrier == null){
	$msg = 'Cette épreuve n\'existe pas...';
	$rafr = true;
	$res = false;
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    
    if ($login == ''){
	$msg = 'Vous n\'êtes pas connecté !';
	$rafr = false;
	$res = false;
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    
    if (sizeof($arr_prono) != 10){
	$msg =  'Vous n\'avez pas sélectionné dix athlètes !';
	$rafr = false;
	$res = false;
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    // ------------ VERIFICATION DES PARAMETRES ----------//

    
    
    // ------------ PRONO DISPONIBLE ? ----------//
    //$dateactuelle = time
    if($calendrier['disponible'] == 0){
	$rafr = true;
	$res = false;
	$msg = 'Vous ne pouvez pas encore pronostiquer sur cette épreuve...';
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    
    if($calendrier['commence'] == '1'){
	$rafr = true;
	$res = false;
	$msg = 'Vous ne pouvez plus pronostiquer sur cette épreuve...';
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    // ------------ PRONO DISPONIBLE ? ----------//
    
    
    
    
    // ------------ PRONO DEJA FAIT ? ----------//
    $sql = "SELECT * FROM ski_alpin_prono WHERE id_calendrier=? AND joueur=?";

    $prep = $db->prepare($sql);
    $prep->bindValue(1,$id_cal,PDO::PARAM_INT);
    $prep->bindValue(2,$login,PDO::PARAM_STR);
    
    $prep->execute();
    $prep->setFetchMode(PDO::FETCH_OBJ);
    $enregistrement = $prep->fetch();
    if($enregistrement){
	$idpronofait = $enregistrement->id_ski_alpin_prono;
    }
    else{
	$idpronofait = 0;
    }
    // ------------ PRONO DEJA FAIT ? ----------//
    
    
    // ------------ CONSTRUCTION DU PRONO ----------//
    $taille_prono = sizeof($arr_prono);
    $nb_etoiles_prono = $_SESSION['ski_alpin_notes'][$id_jeu][$id_cal]['etoiles_max'];;
    for($i=0;$i<$taille_prono;$i++) {
	if($i!=0){
	    $prono .= ';';
	}
	$id_coureur_prono = $arr_prono[$i];
	$prono .= $id_coureur_prono;
	
	$etoiles_coureur = $_SESSION['ski_alpin_notes'][$id_jeu][$id_cal][$id_coureur_prono];
	if($etoiles_coureur === null){
	    $rafr = true;
	    $res = false;
	    $msg = 'Problème dans le pronostic...';
	    $rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	    echo json_encode($rep);
	    return;
	}
	else{
	    $nb_etoiles_prono -= $etoiles_coureur;
	}
    }
    $bonus = $nb_etoiles_prono*2;
    
    // ------------ CONSTRUCTION DU PRONO ----------//
    
    // ------------ ENVOI DU PRONO ----------//
    if ($idpronofait != 0){
	$sql = "UPDATE ski_alpin_prono SET prono=?, bonus_risque=? WHERE id_ski_alpin_prono=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$prono,PDO::PARAM_STR);
	$prep->bindValue(2,$bonus,PDO::PARAM_INT);
	$prep->bindValue(3,$idpronofait,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	 $msg = 'Pronostic modifié !';
    }
    else{
	$sql = "INSERT INTO ski_alpin_prono(id_jeu,id_calendrier,joueur,prono,points_prono,score_base,bonus_nombre,bonus_risque,score_total,classement,nb_trouves) VALUES(?,?,?,?,'',0,0,?,0,0,0)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(3,$login,PDO::PARAM_STR);
	$prep->bindValue(4,$prono,PDO::PARAM_STR);
	$prep->bindValue(5,$bonus,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	$msg = 'Pronostic enregistré !';
    }  
    $rafr = false;
    $res = true;
    $rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
    $db = null;
    echo json_encode($rep);
    // ------------ ENVOI DU PRONO ----------//
    
   
?>