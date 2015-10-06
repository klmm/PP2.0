<?php


    function get_zone_prono($ID_JEU,$ID_CAL){
	//--------------------------------------FONCTIONS--------------------------------------//
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_prono.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php';
	//-------------------------------------------------------------------------------------//



	//--------------------------------------VARIABLES DE SESSION--------------------------------------//
	session_start();
	$loginjoueur = $_SESSION['LoginJoueur'];
	//------------------------------------------------------------------------------------------------//



	//--------------------------------------CALENDRIER--------------------------------------//
	$calendrier = get_calendrier($ID_CAL);
	//------------------------------------------------------------------------------------------------//


	$all_athletes = get_athletes_activite_genre($calendrier['genre']);

	//--------------------------------------PRONO--------------------------------------//
	if($loginjoueur != ""){
	    $prono = get_prono($ID_CAL,$loginjoueur);    }
	else{
	    $prono = null;
	}
	//--------------------------------------PRONO--------------------------------------//





	//-----------------------------LISTE DE PRONO----------------------//
	

      // CALCUL DES MOYENNES DES SKIEURS
	$moy_max = 0;
	foreach($all_athletes as $id => $athlete){
	    switch($calendrier['specialite']){
		case 'Slalom':
		    $moy = $athlete['note_slalom'];
		    break;
		case 'Slalom Géant':
		    $moy = $athlete['note_slalom'];
		    break;
		case 'Super G':
		    $moy = $athlete['note_slalom'];
		    break;
		case 'Descente':
		    $moy = $athlete['note_slalom'];
		    break;
		case 'Super Combiné':
		    $moy = $athlete['note_slalom'];
		    break;
		default:
		    $moy = 0;
	    }

	    if($moy > $moy_max){
		$moy_max = $moy;
	    }
	    
	    $all_athletes[$id]['moyenne'] = $moy;
	    $all_athletes[$id]['pos_prono'] = 0;
	}
	$_SESSION['ski_alpin_notes'][$ID_JEU][$ID_CAL]['etoiles_max'] = 30;
	
      // CALCUL DES ETOILES ATHLETES
	$SEUIL_3 = 10;
	$SEUIL_2 = 20;
	$SEUIL_1 = 30;
	foreach($all_athletes as $id => $athlete){
	    $moy = $athlete['moyenne'];

	    $diff = intval($moy_max - $moy);
	    switch($diff)
	    {
		case 0:
		    $nb_etoiles = 3;
		    break;
		case $diff <= $SEUIL_3 :
		    $nb_etoiles = 3;
		    break;
		case $diff > $SEUIL_3 && $diff <= $SEUIL_2 :
		    $nb_etoiles = 2;
		    break;
		case $diff > $SEUIL_2 && $diff <= $SEUIL_1 :
		    $nb_etoiles = 1;
		    break;
		default :
		     $nb_etoiles = 0;
	    }
	    
	    $all_athletes[$id]['etoiles'] = $nb_etoiles;
	     
	    $_SESSION['ski_alpin_notes'][$ID_JEU][$ID_CAL][$id] = $nb_etoiles;
	}

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
	//-----------------------------LISTE DE PRONO----------------------//
	
	$res = array(
		'calendrier' => $calendrier,
		'prono' => $prono,
		'athletes' => $all_athletes,
	    );

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