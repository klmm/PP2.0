<?php

    // ------------ INCLUDES ----------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
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
    $score1 = $_POST['score1'];
    $score2 = $_POST['score2'];
    $essais1 = $_POST['essais1'];
    $essais2 = $_POST['essais2'];
    $calendrier = get_calendrier($id_cal);
    // ------------ RECUPERATION DES PARAMETRES ----------//

    
        
    // ------------ VERIFICATION DES PARAMETRES ----------//
    if(!is_numeric($id_jeu) || !is_numeric($id_cal) || !is_numeric($score1) || !is_numeric($score2) || !is_numeric($essais1) || !is_numeric($essais2)){
	$msg = 'Veuillez sélectionner un nombre d\'essais puis un score pour chaque équipe';
	$rafr = true;
	$res = false;
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
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
    // ------------ VERIFICATION DES PARAMETRES ----------//

    
    
    // ------------ PRONO DISPONIBLE ? ----------//
    if($calendrier['commence'] == '1'){
	$rafr = true;
	$res = false;
	$msg = 'Vous ne pouvez plus pronostiquer sur cette épreuve...';
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    
    if($calendrier['disponible'] == 0){
	$rafr = true;
	$res = false;
	$msg = 'Vous ne pouvez pas encore pronostiquer sur cette épreuve...';
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	return;
    }
    // ------------ PRONO DISPONIBLE ? ----------//
    
    
    // ------------ CONSTRUCTION PRONO ----------//
    if($score1 > $score2){
	$vainqueur = '1';
    } 
    elseif($score2 > $score1){
	$vainqueur = '2';
    }
    else{
	$vainqueur = 'N';
    }
    // ------------ CONSTRUCTION PRONO ----------//
    

    
    
    // ------------ PRONO DEJA FAIT ? ----------//
    $sql = "SELECT * FROM rugby_prono WHERE id_jeu=? AND id_calendrier=? AND joueur=?";

    $prep = $db->prepare($sql);
    $prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
    $prep->bindValue(2,$id_cal,PDO::PARAM_INT);
    $prep->bindValue(3,$login,PDO::PARAM_STR);
    
    $prep->execute();
    $prep->setFetchMode(PDO::FETCH_OBJ);
    $enregistrement = $prep->fetch();
    if($enregistrement){
	$idpronofait = $enregistrement->id;
    }
    else{
	$idpronofait = 0;
    }
    // ------------ PRONO DEJA FAIT ? ----------//

    // ------------ ENVOI DU PRONO ----------//
    if ($idpronofait != 0){
	$sql3 = "UPDATE rugby_prono SET prono_vainqueur=?,prono_essais1=?,prono_essais2=?,prono_points1=?,prono_points2=? WHERE id=?";
	$prep3 = $db->prepare($sql3);
	$prep3->bindValue(1,$vainqueur,PDO::PARAM_STR);
	$prep3->bindValue(2,$essais1,PDO::PARAM_INT);
	$prep3->bindValue(3,$essais2,PDO::PARAM_INT);
	$prep3->bindValue(4,$score1,PDO::PARAM_INT);
	$prep3->bindValue(5,$score2,PDO::PARAM_INT);
	$prep3->bindValue(6,$idpronofait,PDO::PARAM_INT);
	$prep3->execute();
	$prep3->setFetchMode(PDO::FETCH_OBJ);
	
	 $msg = 'Pronostic modifié !';
    }
    else{
	$sql2 = "INSERT INTO rugby_prono(id_jeu, id_calendrier, joueur, prono_vainqueur, prono_essais1, prono_essais2, prono_points1,
		prono_points2, score_vainqueur, score_essais1, score_essais2, score_points1, score_points2, score_ecart, score_total,
		classement) VALUES (?,?,?,?,?,?,?,?,0,0,0,0,0,0,0,0)";
	$prep2 = $db->prepare($sql2);
	$prep2->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep2->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep2->bindValue(3,$login,PDO::PARAM_STR);
	$prep2->bindValue(4,$vainqueur,PDO::PARAM_STR);
	$prep2->bindValue(5,$essais1,PDO::PARAM_INT);
	$prep2->bindValue(6,$essais2,PDO::PARAM_INT);
	$prep2->bindValue(7,$score1,PDO::PARAM_INT);
	$prep2->bindValue(8,$score2,PDO::PARAM_INT);
	$prep2->execute();
	$prep2->setFetchMode(PDO::FETCH_OBJ);
	
	$msg = 'Pronostic enregistré !';
    }

    $rafr = false;
    $res = true;
    $rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
    $db = null;
    echo json_encode($rep);
    // ------------ ENVOI DU PRONO ----------//
?>