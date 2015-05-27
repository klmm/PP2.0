<?php

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_equipe.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_cycliste.php';
    //-------------------------------------------------------------------------------------//
    
    
    
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $id_equipe = $_POST['id_equipe'];
    $id_equipe = $_POST['id_equipe'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = get_calendrier($ID_JEU,$ID_CAL);
    $b_equipe = $calendrier['profil_equipe'];
    //------------------------------------------------------------------------------------------------//
    

    
    
    //-----------------------------LISTE DE PRONO----------------------//
    $all_cyclistes = get_cyclistes_jeu($ID_JEU,$ID_CAL);
    if($b_equipe){
	$all_equipes = get_equipes_inscrites($ID_JEU,$ID_CAL);
    }
	    
  // CALCUL DES MOYENNES DES CYCLISTES ET DES EQUIPES
    $moy_max = 0;
    foreach($all_cyclistes as $id => $cycliste){
	$moy = ($cycliste['note_paves']*$calendrier['profil_paves'] + $cycliste['note_vallons']*$calendrier['profil_vallons'] + 
		$cycliste['note_montagne']*$calendrier['profil_montagne'] + $cycliste['note_sprint']*$calendrier['profil_sprint'] + 
		$cycliste['note_clm']*$calendrier['profil_clm'] + 10*$cycliste['forme'])/100;
	$all_cyclistes[$id]['moyenne'] = $moy;
	
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
    
    $moy_max_equipe = 0;
    foreach($all_equipes as $id => $equipe){
	if($equipe['nb_coureurs'] > 0){
	    $moy = $equipe['moyenne']/$equipe['nb_coureurs'];
	    $all_equipes[$id]['moyenne'] = $moy;
	    
	    if($moy > $moy_max_equipe){
		$moy_max_equipe = $moy;
	    }
	}
	else{
	    unset($all_equipes[$id]);
	}
    }
    
    
    
  // CALCUL DES ETOILES CYCLISTES
    foreach($all_cyclistes as $id => $cycliste){
	$moy = $cycliste['moyenne'];
	
	$diff = $moy_max - $moy;

	switch($diff)
	{
	    case $diff >= 0 && $diff <= 5 :
		$all_cyclistes[$id]['etoiles'] = 3;
		break;
	    case $diff > 5 && $diff <= 8 :
		$all_cyclistes[$id]['etoiles'] = 2;
		break;
	    case $diff > 8 && $diff <= 15 :
		$all_cyclistes[$id]['etoiles'] = 1;
		break;
	    default :
		 $all_cyclistes[$id]['etoiles'] = 0;
	}
    }
    
    foreach($all_equipes as $id => $equipe){
	$moy = $equipe['moyenne'];
	
	$diff = $moy_max_equipe - $moy;

	switch($diff)
	{
	    case $diff >= 0 && $diff <= 3 :
		$all_equipes[$id]['etoiles'] = 2;
		break;
	    case $diff > 3 && $diff <= 5 :
		$all_equipes[$id]['etoiles'] = 1;
		break;
	    default :
		 $all_equipes[$id]['etoiles'] = 0;
	}
    }
    
    
   // MON PRONO
    if($b_equipe){	
	$taille_prono = sizeof($tab_id_equipes);
	if($taille_prono > 0){
	    for($i=0;$i<$taille_prono;$i++){
		$all_equipes[$tab_id_equipes[$i]]['pos_prono'] = $i+1;
	    }
	}
    }
    else{
	$taille_prono = sizeof($tab_id_cyclistes);
	if($taille_prono > 0){
	    for($i=0;$i<$taille_prono;$i++){
		$all_cyclistes[$tab_id_cyclistes[$i]]['pos_prono'] = $i+1;
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
    
    function compare_nom_courant($a, $b)
    {
      return strnatcmp($a['nom_complet'], $b['nom_complet']);
    }
    
    function compare_nom($a, $b)
    {
      return strnatcmp($a['nom'], $b['nom']);
    }
    
    $res = array(
		'calendrier' => $calendrier,
		'prono' => $prono,
		'cyclistes' => $all_cyclistes,
		'equipes' => $all_equipes
	);
    
    
    echo json_encode($res);
	
?>