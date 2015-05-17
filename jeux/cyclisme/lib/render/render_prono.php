<?php

    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    joueur
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------
    
    ------------ OUTPUTS -------*/

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_equipe.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_cycliste.php';
    //-------------------------------------------------------------------------------------//
    
    
    
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    $joueur = $_POST['joueur'];
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = get_calendrier($ID_JEU,$ID_CAL);
    $b_equipe = $calendrier['profil_equipe'];
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    if($b_commence || $joueur == $loginjoueur){
	//-------------------------PRONO DU JOUEUR-----------------------------------//
	$prono = get_prono($ID_JEU,$ID_CAL,$joueur);
	//----------------------------------------------------------------------------//

	//-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
	if($b_equipe){
	    $chaine_id_equipes = $prono['prono'];

	    $tab_id_equipes = array_unique(explode(";", $chaine_id_equipes));
	    $tab_equipes = get_equipes_tab_id($tab_id_equipes);
	    $tab_cyclistes = null;
	}
	else{
	    $chaine_id_cyclistes = $prono['prono'];

	    $tab_id_cyclistes = array_unique(explode(";", $chaine_id_cyclistes));
	    $tab_cyclistes = get_cyclistes_jeu_tab_id($ID_JEU,$ID_CAL,$tab_id_cyclistes);
	    $tab_equipes = null;
	}
	//-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
    }
    else{
	$prono = null;
	$tab_cyclistes = null;
	$tab_equipes = null;
    }
    
    $res = array(
		'calendrier' => $calendrier,
		'prono' => $prono,
		'cyclistes' => $tab_cyclistes,
		'equipes' => $tab_equipes
	);
    
    
    echo json_encode($res);
	
?>