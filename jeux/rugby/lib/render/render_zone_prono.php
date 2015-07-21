<?php


    function get_zone_prono($ID_JEU,$ID_CAL){
	//--------------------------------------FONCTIONS--------------------------------------//
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_prono.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_equipe.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_cycliste.php';
	//-------------------------------------------------------------------------------------//



	//--------------------------------------VARIABLES DE SESSION--------------------------------------//
	session_start();
	$loginjoueur = $_SESSION['LoginJoueur'];
	//------------------------------------------------------------------------------------------------//




	//--------------------------------------RECUPERATIONS DES INFOS (CAS RENDER)--------------------------------------//
	//$ID_JEU = $_POST['id_jeu'];
	//$ID_CAL = $_POST['id_cal'];
	//------------------------------------------------------------------------------------------------//




	//--------------------------------------CALENDRIER--------------------------------------//
	$calendrier = get_calendrier($ID_JEU,$ID_CAL);
	$b_equipe = $calendrier['profil_equipe'];
	$b_jeunes = $calendrier['profil_jeunes'];
	$annee_course = intval(substr($calendrier['date_debut'],0,4));
	//------------------------------------------------------------------------------------------------//





	//--------------------------------------PRONO--------------------------------------//
	if($loginjoueur != ""){
	    $prono = get_prono($ID_JEU,$ID_CAL,$loginjoueur);

	    if($b_equipe){
		$chaine_id_equipes = $prono['prono'];

		$tab_id_equipes = array_unique(explode(";", $chaine_id_equipes));
		$prono = get_equipes_tab_id($tab_id_equipes);
	    }
	    else{
		$chaine_id_cyclistes = $prono['prono'];

		$tab_id_cyclistes = array_unique(explode(";", $chaine_id_cyclistes));
	    }
	}
	else{
	    $prono = null;
	}
	//--------------------------------------PRONO--------------------------------------//





	//-----------------------------LISTE DE PRONO----------------------//
	$all_cyclistes = get_cyclistes_jeu($ID_JEU,$ID_CAL);
	$all_equipes = get_equipes_inscrites($ID_JEU,$ID_CAL);

      // CALCUL DES MOYENNES DES CYCLISTES ET DES EQUIPES
	$moy_max = 0;
	foreach($all_cyclistes as $id => $cycliste){
	    if($b_jeunes == false || intval(substr($cycliste['date_naissance'],0,4)) >= $annee_course-25){
		$moy = ($cycliste['note_paves']*$calendrier['profil_paves'] + $cycliste['note_vallons']*$calendrier['profil_vallons'] + 
			$cycliste['note_montagne']*$calendrier['profil_montagne'] + $cycliste['note_sprint']*$calendrier['profil_sprint'] +
			$cycliste['note_baroudeur']*$calendrier['profil_baroudeurs'] +
			$cycliste['note_clm']*$calendrier['profil_clm'] + 10*$cycliste['forme'])/100;
		$all_cyclistes[$id]['moyenne'] = round($moy,1);

		if($moy > $moy_max){
		    $moy_max = $moy;
		}

		if($b_equipe){
		    $id_equipe = $cycliste['id_equipe_course'];
		    $all_equipes[$id_equipe]['moyenne'] += $moy;
		    $all_equipes[$id_equipe]['nb_coureurs'] += 1;
		    $all_equipes[$id_equipe]['liste_coureurs'][] = $id;
		}
	    }
	    else{
		unset($all_cyclistes[$id]);
	    }
	}

	$moy_max_equipe = 0;
	foreach($all_equipes as $id => $equipe){
	    if($equipe['nb_coureurs'] > 0){
		$moy = round($equipe['moyenne']/$equipe['nb_coureurs'],1);
		$all_equipes[$id]['moyenne'] = $moy;

		if($moy > $moy_max_equipe){
		    $moy_max_equipe = $moy;
		}
	    }
	    else{
		unset($all_equipes[$id]);
	    }
	}
	
	if($b_jeunes || $b_equipe){
	    $_SESSION['cyclisme_notes'][$ID_JEU][$ID_CAL]['etoiles_max'] = 30;
	}
	else{
	    $_SESSION['cyclisme_notes'][$ID_JEU][$ID_CAL]['etoiles_max'] = 30;
	}
	
      // CALCUL DES ETOILES CYCLISTES
	$SEUIL_3 = 5;
	$SEUIL_2 = 8;
	$SEUIL_1 = 13;
	foreach($all_cyclistes as $id => $cycliste){
	    $moy = $cycliste['moyenne'];

	    $diff = intval($moy_max - $moy);
	    
	    if($b_jeunes){
		switch($diff)
		{
		    case 0:
			$nb_etoiles = 3;
			break;
		    case $diff <= 6 :
			$nb_etoiles = 3;
			break;
		    case $diff > 6 && $diff <= 12 :
			$nb_etoiles = 2;
			break;
		    default :
			 $nb_etoiles = 1;
		}
	    }
	    else{
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
	    }
	    
	    $all_cyclistes[$id]['etoiles'] = $nb_etoiles;
	    
	    if($b_equipe == false){
		$_SESSION['cyclisme_notes'][$ID_JEU][$ID_CAL][$id] = $nb_etoiles;
	    }
	}

	if($b_equipe){
	    foreach($all_equipes as $id => $equipe){
		$moy = $equipe['moyenne'];

		$diff = intval($moy_max_equipe - $moy);

		switch($diff)
		{
		    case 0:
			$nb_etoiles = 3;
			break;
		    case $diff <= 5 :
			$nb_etoiles = 3;
			break;
		    case $diff > 5 && $diff <= 8 :
			$nb_etoiles = 2;
			break;
		    default :
			$nb_etoiles = 1;
		}
		
		$all_equipes[$id]['etoiles'] = $nb_etoiles;
		
		$_SESSION['cyclisme_notes'][$ID_JEU][$ID_CAL][$id] = $nb_etoiles;
	    }
	}
	
       // MON PRONO
	if($b_equipe){
	    $taille_prono = sizeof($tab_id_equipes);
	    if($taille_prono > 1){
		for($i=0;$i<$taille_prono;$i++){
		    $all_equipes[$tab_id_equipes[$i]]['pos_prono'] = $i+1;
		    $prono['cyclistes_prono'][$i] = $all_equipes[$tab_id_equipes[$i]];
		}
	    }
	}
	else{
	    $taille_prono = sizeof($tab_id_cyclistes);
	    if($taille_prono > 1){
		for($i=0;$i<$taille_prono;$i++){
		    if($all_cyclistes[$tab_id_cyclistes[$i]] !== null){
			$all_cyclistes[$tab_id_cyclistes[$i]]['pos_prono'] = $i+1;
			$prono['cyclistes_prono'][$i] = $all_cyclistes[$tab_id_cyclistes[$i]];
		    }
		}
	    }
	}


      // TRI DU TABLEAU
	if($b_equipe){
	    usort($all_equipes, 'compare_nom_courant');
	}
	else{
	    usort($all_cyclistes, 'compare_nom');
	}
	//-----------------------------LISTE DE PRONO----------------------//
	
	$res = array(
		'calendrier' => $calendrier,
		'prono' => $prono,
		'cyclistes' => $all_cyclistes,
		'equipes' => $all_equipes
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

    function compare_nom_courant($a, $b)
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
	    return strnatcmp($a['nom_complet'], $b['nom_complet']);
	}
    }
?>