<?php

    // ------------ INCLUDES ----------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/admin/calcule_classements.php';
    // ------------ INCLUDES ----------//
    
    
    
    // ------------ CONNEXION BDD ----------//
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    // ------------ CONNEXION BDD ----------//
    
   
    
    // ------------ RECUPERATION DES PARAMETRES ----------//
    session_start();
    $admin = $_SESSION['Admin'];
    $id_jeu = $_POST['id_jeu'];
    $id_cal = $_POST['id_cal'];
    $score1 = $_POST['score1'];
    $score2 = $_POST['score2'];
    $essais1 = $_POST['essais1'];
    $essais2 = $_POST['essais2'];
    $calendrier = get_calendrier($id_cal);
    // ------------ RECUPERATION DES PARAMETRES ----------//
    
    
    
    // ------------ VERIFICATION DES PARAMETRES ----------//
    if(!is_numeric($id_cal) || !is_numeric($score1) || !is_numeric($score2) || !is_numeric($essais1) || !is_numeric($essais2)){
	$msg = 'Paramètre non numérique';
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
	$db = null;
	return;
    }
    
    if ($admin == false){
	$msg = 'Vous n\'êtes pas admin !!!';
	$rafr = false;
	$res = false;
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	$db = null;
	return;
    }
    // ------------ VERIFICATION DES PARAMETRES ----------//

    
    
    // ------------ PRONO DISPONIBLE ? ----------//
    if($calendrier['commence'] == '0'){
	$rafr = true;
	$res = false;
	$msg = 'Le pronostic n\'a pas encore commencé !!!';
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	$db = null;
	return;
    }
    // ------------ PRONO DISPONIBLE ? ----------//
    
    
    // ------------ CONSTRUCTION DU RESULTAT ----------//
    if($score1 > $score2){
	$vainqueur = '1';
    } 
    elseif($score2 > $score1){
	$vainqueur = '2';
    }
    else{
	$vainqueur = 'N';
    }
    // ------------ CONSTRUCTION DU RESULTAT ----------//
    
    
    // ---------- POINTS ATTRIBUES -------------//
    $POINTS_VAINQUEUR = 100;
    $POINTS_ESSAIS = 10;
    $POINTS_MAX_ECART = 40;
    $DELTA_ECART = 1;
    $POINTS_MAX_POINTS = 20;
    $DELTA_POINTS = 1;
    // ---------- POINTS ATTRIBUES -------------//
    
    
    
    
    
    // ------------ MAJ PRONOS ----------//
    $arr_pronos = get_pronos_cal($id_cal);
    
    $sql2 = "UPDATE rugby_prono SET score_vainqueur=?, score_essais1=?, score_essais2=?, score_points1=?, score_points2=?, score_ecart=?, score_total=? WHERE id=?";
    $prep2 = $db->prepare($sql2);
    
    $score_max = 0;
    $nb_trouves_max = 0;
    
    foreach($arr_pronos as $key => $prono){
	
	$id_prono = $prono['id_rugby_prono'];
	$score_vainqueur = 0;
	$score_essais1 = 0;
	$score_essais2 = 0;
	
	// VAINQUEUR
	if($prono['prono_vainqueur'] == $vainqueur){
	    $score_vainqueur = $POINTS_VAINQUEUR;
	}
	
	// ESSAIS 1
	if($prono['prono_essais1'] == $essais1){
	    $score_essais1 = $POINTS_ESSAIS;
	}
	
	// ESSAIS 2
	if($prono['prono_essais2'] == $essais2){
	    $score_essais2 = $POINTS_ESSAIS;
	}
	
	
	
	// POINTS 1
	$score_points1 = max($POINTS_MAX_POINTS - $DELTA_POINTS*(abs($prono['prono_points1'] - $score1)),0);
	
	// POINTS 2
	$score_points2 = max($POINTS_MAX_POINTS - $DELTA_POINTS*(abs($prono['prono_points2'] - $score2)),0);
	
	// ECART
	$score_ecart = max($POINTS_MAX_ECART - $DELTA_ECART*(abs(($prono['prono_points1'] - $prono['prono_points2']) - ($score1 - $score2))),0);
	
	// TOTAL
	$score_total = $score_vainqueur + $score_essais1 + $score_essais2 + $score_points1 + $score_points2 + $score_ecart;
	
	$prep2->bindValue(1,$score_vainqueur,PDO::PARAM_INT);
	$prep2->bindValue(2,$score_essais1,PDO::PARAM_INT);
	$prep2->bindValue(3,$score_essais2,PDO::PARAM_INT);
	$prep2->bindValue(4,$score_points1,PDO::PARAM_INT);
	$prep2->bindValue(5,$score_points2,PDO::PARAM_INT);
	$prep2->bindValue(6,$score_ecart,PDO::PARAM_INT);
	$prep2->bindValue(7,$score_total,PDO::PARAM_INT);
	$prep2->bindValue(8,$id_prono,PDO::PARAM_INT);
	$prep2->execute();
	$prep2->setFetchMode(PDO::FETCH_OBJ);
	
    }
       
    $arr_pronos = get_pronos_cal($id_cal);
    
    $sql7 = "UPDATE rugby_prono SET classement=? WHERE id=?";
    $prep7 = $db->prepare($sql7);
    
    $place_actuelle = 1;
    $place_cpt = 1;
    $score_actuel = -1;
    
    foreach($arr_pronos as $key => $prono){
	$id_prono = $prono['id_rugby_prono'];
	$score_total_joueur = $prono['score_total'];

	if($score_actuel != $score_total_joueur){
	    $place_actuelle = $place_cpt;
	    $place_joueur = $place_cpt;
	}
	else{
	    $place_joueur = $place_actuelle;
	}
	    
	$prep7->bindValue(1,$place_joueur,PDO::PARAM_INT);
	$prep7->bindValue(2,$id_prono,PDO::PARAM_INT);
	$prep7->execute();
	$prep7->setFetchMode(PDO::FETCH_OBJ);
	
	$score_actuel = $score_total_joueur;
	$place_cpt++;
    }
    // ------------ MAJ PRONOS ----------//
    

    
     
    
    
    
    // ------------ MAJ CALENDRIER ----------//
    $sql5 = "UPDATE rugby_calendrier SET score1=?, score2=?, essais1=?, essais2=?, traite=1, disponible=0 WHERE id=?";
    $prep5 = $db->prepare($sql5);
    $prep5->bindValue(1,$score1,PDO::PARAM_INT);
    $prep5->bindValue(2,$score2,PDO::PARAM_INT);
    $prep5->bindValue(3,$essais1,PDO::PARAM_INT);
    $prep5->bindValue(4,$essais2,PDO::PARAM_INT);
    $prep5->bindValue(5,$id_cal,PDO::PARAM_INT);
    $prep5->execute();
    // ------------ MAJ CALENDRIER ----------//
    
    
    
    
    
    
    
    
    // ------------ MAJ CLASSEMENTS ----------//
    calcule_classements($id_jeu);
    // ------------ MAJ CLASSEMENTS ----------//
    

    
    
    
    $msg = 'Ok !';
    $rafr = true;
    $res = true;
    $rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
    $db = null;
    echo json_encode($rep);
    // ------------ ENVOI DU PRONO ----------//
 
?>