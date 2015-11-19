<?php


    function get_zone_prono($ID_JEU,$ID_CAL){
	//--------------------------------------FONCTIONS--------------------------------------//
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_calendrier.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_prono.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_athlete.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_equipe.php';
	//-------------------------------------------------------------------------------------//



	//--------------------------------------VARIABLES DE SESSION--------------------------------------//
	session_start();
	$loginjoueur = $_SESSION['LoginJoueur'];
	//------------------------------------------------------------------------------------------------//

	

	//--------------------------------------CALENDRIER--------------------------------------//
	$calendrier = biathlon_get_calendrier($ID_CAL);
	$genre = $calendrier['genre'];
	
	if($calendrier['specialite'] == 'Relais'){
	    $b_relais = true;
	    $all_equipes = get_equipes_genre($genre);
	}
	else{
	    $b_relais = false;
	    $all_athletes = get_athletes_activite_genre($genre,$calendrier['date_debut']);
	}
	//------------------------------------------------------------------------------------------------//


	

	//--------------------------------------PRONO--------------------------------------//
	if($loginjoueur != ""){
	    $prono = get_prono($ID_CAL,$loginjoueur);
	}
	else{
	    $prono = null;
	}
	//--------------------------------------PRONO--------------------------------------//


      // CALCUL DES MOYENNES DES SKIEURS
	$moy_max = 0;
	if($b_relais == false){
	    $SEUIL_3 = 78;
	    $SEUIL_2 = 70;
	    $SEUIL_1 = 65;
	
	    foreach($all_athletes as $id => $athlete){
		switch($calendrier['specialite']){
		    case 'Sprint':
			$moy = ($athlete['note_couche'] + $athlete['note_debout'] + $athlete['note_fond'])/3;
			break;
		    case 'Poursuite':
			$moy = ($athlete['note_couche'] + $athlete['note_debout'] + $athlete['note_fond'])/3;
			break;
		    case 'Individuelle':
			$moy = ($athlete['note_couche'] + $athlete['note_debout'] + $athlete['note_fond'])/3;
			break;
		    case 'Mass start':
			$moy = ($athlete['note_couche'] + $athlete['note_debout'] + $athlete['note_fond'])/3;
			break;
		    case 'Relais':
			$moy = ($athlete['note_couche'] + $athlete['note_debout'] + $athlete['note_fond'])/3;
			break;
		    default:
			$moy = 0;
		}

		if($moy > $moy_max){
		    $moy_max = $moy;
		}

		$all_athletes[$id]['moyenne'] = $moy;
		$all_athletes[$id]['pos_prono'] = 0;

		switch($moy)
		{
		    case $moy > $SEUIL_2 && $moy <= $SEUIL_3 :
			$nb_etoiles = 2;
			break;
		    case $moy > $SEUIL_1 && $moy <= $SEUIL_2 :
			$nb_etoiles = 1;
			break;
		    case $moy <= $SEUIL_1 :
			$nb_etoiles = 0;
			break;
		    default :
			 $nb_etoiles = 3;
		}

		$all_athletes[$id]['etoiles'] = $nb_etoiles;

		$_SESSION['biathlon_notes'][$ID_JEU][$ID_CAL][$id] = $nb_etoiles;
	    }
	    $_SESSION['biathlon_notes'][$ID_JEU][$ID_CAL]['etoiles_max'] = 30;

	    $tab_id_athletes = explode(";", $prono['prono']);
	    $taille_prono = sizeof($tab_id_athletes);
	    if($taille_prono > 1){
		for($i=0;$i<$taille_prono;$i++){
		    if($all_athletes[$tab_id_athletes[$i]] !== null){
			$all_athletes[$tab_id_athletes[$i]]['pos_prono'] = $i+1;
			$prono['athletes_prono'][$i] = $all_athletes[$tab_id_athletes[$i]];
		    }
		}
	    }


	  // TRI DU TABLEAU
	    usort($all_athletes, 'compare_nom');
	    
	    $res = array(
		'calendrier' => $calendrier,
		'prono' => $prono,
		'athletes' => $all_athletes,
	    );
	}
	else{
	    $SEUIL_3 = 78;
	    $SEUIL_2 = 65;
	    $SEUIL_1 = 40;
	    
	    foreach($all_equipes as $id => $equipe){
		$moy = $equipe['note'];

		if($moy > $moy_max){
		    $moy_max = $moy;
		}

		$all_equipes[$id]['moyenne'] = $moy;
		$all_equipes[$id]['pos_prono'] = 0;

		switch($moy)
		{
		    case $moy > $SEUIL_2 && $moy <= $SEUIL_3 :
			$nb_etoiles = 2;
			break;
		    case $moy > $SEUIL_1 && $moy <= $SEUIL_2 :
			$nb_etoiles = 1;
			break;
		    case $moy <= $SEUIL_1 :
			$nb_etoiles = 0;
			break;
		    default :
			 $nb_etoiles = 3;
		}

		$all_equipes[$id]['etoiles'] = $nb_etoiles;

		$_SESSION['biathlon_notes'][$ID_JEU][$ID_CAL][$id] = $nb_etoiles;
	    }
	    $_SESSION['biathlon_notes'][$ID_JEU][$ID_CAL]['etoiles_max'] = 30;

	    $tab_id_equipes = explode(";", $prono['prono']);
	    $taille_prono = sizeof($tab_id_equipes);
	    if($taille_prono > 1){
		for($i=0;$i<$taille_prono;$i++){
		    if($all_equipes[$tab_id_equipes[$i]] !== null){
			$all_equipes[$tab_id_equipes[$i]]['pos_prono'] = $i+1;
			$prono['equipes_prono'][$i] = $all_equipes[$tab_id_equipes[$i]];
		    }
		}
	    }


	  // TRI DU TABLEAU
	    usort($all_equipes, 'compare_nom');
	    
	    $res = array(
		'calendrier' => $calendrier,
		'prono' => $prono,
		'equipes' => $all_equipes,
	    );
	}
	

	return $res;
    }
    
    function compare_nom($a, $b)
    {
	if($a['etoiles'] != $b['etoiles']){
	    if($a['etoiles'] > $b['etoiles']){
		return 0;
	    }
	    else{
		return 1;
	    }
	}
	else{
	    return strnatcmp($a['nom'], $b['nom']);
	}
    }
?>