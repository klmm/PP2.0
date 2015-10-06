<?php

    // ------------ INCLUDES ----------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/admin/calcule_classements.php';
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
    $arr_resultat = $_POST['prono'];
    $calendrier = get_calendrier($id_cal);
    // ------------ RECUPERATION DES PARAMETRES ----------//
    
    
    
    // ------------ VERIFICATION DES PARAMETRES ----------//
    if(!is_numeric($id_jeu) || !is_numeric($id_cal)){
	$msg = 'Paramètre non numérique ' . $id_jeu . ' - ' . $id_cal;
	$rafr = true;
	$res = false;
	$rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	echo json_encode($rep);
	$db = null;
	return;
    }
    
    foreach($arr_resultat as $key => $value){
	if (!is_numeric($value)){
	    $msg = 'Id non numérique' . $key;
	    $rafr = true;
	    $res = false;
	    $rep = array('resultat' => $res, 'rafr' => $rafr, 'msg' => $msg);
	    echo json_encode($rep);
	    $db = null;
	    return;
	}
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
    
    if (sizeof($arr_resultat) != 10){
	if($calendrier['profil_equipe']){
	    $msg = 'Vous n\'avez pas sélectionné dix équipes';
	}
	else{
	    $msg =  'Vous n\'avez pas sélectionné dix coureurs';
	}	
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
    $taille_res = sizeof($arr_resultat);
    for($i=0;$i<$taille_res;$i++) {
	if($i!=0){
	    $resultat .= ';';
	}
	$resultat .= $arr_resultat[$i];
    }
    // ------------ CONSTRUCTION DU RESULTAT ----------//
    
    
    // ---------- POINTS ATTRIBUES -------------//
    $COEFF_EXACT = 1.6;
    $TABLEAU_POINTS = [25,20,16,12,10,7,5,3,2,1];
    $TABLEAU_BONUS_REGULARITE = [1000,600,400,250,100,0,0,0,0,0,0];
    // ---------- POINTS ATTRIBUES -------------//
    
    
    
    
    
    // ------------ MAJ PRONOS ----------//
    $sql = "SELECT * FROM ski_alpin_prono WHERE id_calendrier=?";
    $prep = $db->prepare($sql);
    $prep->bindValue(1,$id_cal,PDO::PARAM_INT);
    $prep->execute();
    $prep->setFetchMode(PDO::FETCH_OBJ);
    
    $sql2 = "UPDATE ski_alpin_prono SET points_prono=?, score_base=?, nb_trouves=? WHERE id_ski_alpin_prono=?";
    $prep2 = $db->prepare($sql2);
    
    $score_max = 0;
    $nb_trouves_max = 0;
    
    while($enregistrement = $prep->fetch()){
	$id_prono = $enregistrement->id_ski_alpin_prono;
	$prono = $enregistrement->prono;
	
	$tab_prono = explode(';',$prono);
		
	$taille_prono = sizeof($tab_prono);
	$nb_trouves = 0;
	$score_joueur = 0;
	$points_prono = '';
	for ($i=0; $i<$taille_prono; $i++){    
	    $score_tmp = 0;
	    for($j=0; $j<$taille_prono; $j++){
		if($tab_prono[$i] == $arr_resultat[$j]){
		    $nb_trouves++;
		    $score_tmp = $TABLEAU_POINTS[$i]*$TABLEAU_POINTS[$j];
		    if($i == $j){
			$score_tmp = intval($score_tmp*$COEFF_EXACT);
		    }
		    $score_joueur = $score_joueur+$score_tmp;
		}
	    }
	    if($i != 0){
		$points_prono .= ';';
	    }
	    $points_prono .= $score_tmp;
	}
	
	if($score_joueur > $score_max){
	    $score_max = $score_joueur;
	}
	
	if($nb_trouves > $nb_trouves_max){
	    $nb_trouves_max = $nb_trouves;
	}
		
	$prep2->bindValue(1,$points_prono,PDO::PARAM_STR);
	$prep2->bindValue(2,$score_joueur,PDO::PARAM_INT);
	$prep2->bindValue(3,$nb_trouves,PDO::PARAM_INT);
	$prep2->bindValue(4,$id_prono,PDO::PARAM_INT);
	$prep2->execute();
	$prep2->setFetchMode(PDO::FETCH_OBJ);
    }

    $sql4 = "SELECT * FROM ski_alpin_prono WHERE id_calendrier=?";
    $prep4 = $db->prepare($sql4);
    $prep4->bindValue(1,$id_cal,PDO::PARAM_INT);
    $prep4->execute();
    $prep4->setFetchMode(PDO::FETCH_OBJ);
    
    $sql3 = "UPDATE ski_alpin_prono SET bonus_nombre=?, score_total=? WHERE id_ski_alpin_prono=?";
    $prep3 = $db->prepare($sql3);
    
    while($enregistrement4 = $prep4->fetch()){
	$id_prono = $enregistrement4->id_ski_alpin_prono;
	$nb_trouves_joueur = $enregistrement4->nb_trouves;
	$score_base_joueur = $enregistrement4->score_base;
	$bonus_risque_joueur = $enregistrement4->bonus_risque;
	$bonus_regularite_joueur = 0;
	
	if($nb_trouves_joueur > 0){
	    if ($nb_trouves_max == 1){
		$bonus_regularite_joueur = $TABLEAU_BONUS_REGULARITE[1];
	    }
	    else{
		$bonus_regularite_joueur = $TABLEAU_BONUS_REGULARITE[$nb_trouves_max - $nb_trouves_joueur];
	    }
	}
	
	$score_total_joueur = ($score_base_joueur + $bonus_regularite_joueur)*(1+$bonus_risque_joueur/100);
	    
	$prep3->bindValue(1,$bonus_regularite_joueur,PDO::PARAM_INT);
	$prep3->bindValue(2,$score_total_joueur,PDO::PARAM_INT);
	$prep3->bindValue(3,$id_prono,PDO::PARAM_INT);
	$prep3->execute();
	$prep3->setFetchMode(PDO::FETCH_OBJ);
    }
    
    
    
    
    $sql6 = "SELECT * FROM ski_alpin_prono WHERE id_calendrier=? ORDER BY score_total DESC";
    $prep6 = $db->prepare($sql6);
    $prep6->bindValue(1,$id_cal,PDO::PARAM_INT);
    $prep6->execute();
    $prep6->setFetchMode(PDO::FETCH_OBJ);
    
    $sql7 = "UPDATE ski_alpin_prono SET classement=? WHERE id_ski_alpin_prono=?";
    $prep7 = $db->prepare($sql7);
    
    $place_actuelle = 1;
    $place_cpt = 1;
    $score_actuel = -1;
    while($enregistrement6 = $prep6->fetch()){
	$id_prono = $enregistrement6->id_ski_alpin_prono;
	$score_total_joueur = $enregistrement6->score_total;

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
    $sql5 = "UPDATE ski_alpin_calendrier SET classement=?, traite=1, disponible=0 WHERE id_ski_alpin_calendrier=?";
    $prep5 = $db->prepare($sql5);
    $prep5->bindValue(1,$resultat,PDO::PARAM_STR);
    $prep5->bindValue(2,$id_cal,PDO::PARAM_INT);
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