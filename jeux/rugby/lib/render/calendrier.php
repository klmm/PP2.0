<?php
    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------
    
    ------------ OUTPUTS -------*/
   

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_equipe.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_pays.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/clean_url.php';
    //-------------------------------------------------------------------------------------//
  
   
    
    
 
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];

    if($loginjoueur != ""){
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    //------------------------------------------------------------------------------------------------//
    
    
  
    
    
    
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    
    
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = get_calendrier($ID_CAL);
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    //--------------------------------------PRONOS DES JOUEURS------------------------------------------------//
    $tab_pronos = get_pronos_cal($ID_JEU, $ID_CAL);
    $nb_pronos = sizeof($tab_pronos);
    //--------------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    
    
    //--------------------------------------MON PRONO------------------------------------------------//
    if($bConnected){
	$prono_joueur = get_prono($ID_JEU,$ID_CAL,$loginjoueur);
    }
    else{
	$prono_joueur = null;
    }
    //--------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    

    
    
    
    //-------------------------------PAYS/EQUIPES------------------------------------//
    $equipes = get_equipes_inscrites($ID_JEU, $ID_CAL);
    $pays = get_pays_tous();
    //-------------------------------PAYS/EQUIPES------------------------------------//
    
    
    // AFFICHAGE PARTIE CALENDRIER
    
    
    // Terminé
    if($calendrier['commence'] && $calendrier['traite']){
	$tmp = 'disabled';
	$txt = 'Terminé';
    }
    // En cours
    elseif($calendrier['commence'] && !$calendrier['traite']){
	$tmp = 'disabled';
	$txt = 'En cours';
    }
    // Dispo
    elseif($calendrier['disponible']){
	$tmp = '';
	$txt = 'Parier';
    }
    // Pas dispo
    else{
	$tmp = 'disabled';
	$txt = 'Bientôt';
    }
    
    $id_equipe1 = $calendrier['id_equipe1'];
    $nom_equipe1 = $equipes[$id_equipe1]['nom'];
    $id_pays1 = $equipes[$id_equipe1]['id_pays'];
    $drapeau_equipe1 = $pays[$id_pays1]['drapeau_icone'];

    $id_equipe2 = $calendrier['id_equipe2'];
    $nom_equipe2 = $equipes[$id_equipe2]['nom'];
    $id_pays2 = $equipes[$id_equipe2]['id_pays'];
    $drapeau_equipe2 = $pays[$id_pays2]['drapeau_icone'];
    
    
    
    // PRESENTATION MATCH
    
    $res = '	    
		<div class="pres-panel clearfix">
		    <img class="item-flag hidden-xs" src="' . $pays[$id_pays1]['drapeau_icone'] . '"/>
		    <p class="name section-highlight">' . $nom_equipe1 . ' - ' . $nom_equipe2 . '</p>
		    <img class="item-flag hidden-xs" src="' . $pays[$id_pays2]['drapeau_icone'] . '"/>
		    <p class="date">' . $calendrier['date_debut_fr'] . ' - ' . $calendrier['heure_debut_fr'] . '</p>
		    <div class="pres-button col-md-12 col-sm-12 col-xs-12">
			<a class="btn btn-primary btn-lg ' . $tmp . '" href="' . $calendrier['url'] . '">' . $txt . '</a>
		    </div>
		</div>
		<div class="result-panel">';
    
    
    
    
    
    // MATCH COMMENCE        
    if($calendrier['commence']){
	
	if($prono_joueur == null){
	    // PAS DE PRONO
	}
	else{
	    if($calendrier['traite']){
		// MATCH TERMINE
	    }
	    else{
		// MATCH EN COURS
	    }
	}
	
	// AUTRES JOUEURS
	$count = 0;
	$premier = '';
	foreach($tab_pronos as $key => $value){
	    if($count == 0){
		$count++;
		$premier = $key;
	    }
	    if($key == $loginjoueur){
		$class_surlign_joueur = 'goodbet';
	    }
	    else{
		$class_surlign_joueur = '';
	    }
	    
	    if($calendrier['traite']){
		$pos = $value['classement'];
		$pts_joueur = $value['score_total'];
	    }
	    else{
		$pos = '';
		$pts_joueur = '';
	    }
	}
    }
    // MATCH NON COMMENCE
    else{
	// PRONO JOUEUR UNIQUEMENT
	
    }
        
    
    $envoi = array(
	    'premier' => $premier,
	    'html' => $res);
    
    echo json_encode($envoi);
?>