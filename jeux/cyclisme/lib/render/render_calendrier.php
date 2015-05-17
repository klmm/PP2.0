<?php
    
    /* --------- INPUTS -------
    id_jeu
    id_cal
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
    $calendrier = get_calendrier($ID_JEU,$ID_CAL);
    $b_equipe = $calendrier['profil_equipe'];
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    //--------------------------------------PRONOS DES JOUEURS------------------------------------------------//
    $tab_pronos = get_pronos_cal($ID_JEU, $ID_CAL);
    //--------------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    
    
    //--------------------------------------MON PRONO------------------------------------------------//
    if($bConnected){
	$prono_joueur = get_prono($ID_JEU,$ID_CAL,$loginjoueur);
    }
    else{
	$prono_joueur = null;
    }
    //--------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    

    
    
    
    //-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
    if($b_equipe){
	$chaine_id_equipes = $prono_joueur['prono'];
	if($b_traite){
	    $chaine_id_equipes .= $calendrier['classement'];
	}

	$tab_id_equipes = array_unique(explode(";", $chaine_id_equipes));
	$tab_equipes = get_equipes_tab_id($tab_id_equipes);
	$tab_cyclistes = null;
    }
    else{
	$chaine_id_cyclistes = $prono_joueur['prono'];
	if($b_traite){
	    $chaine_id_cyclistes .= $calendrier['classement'];
	}
	
	$tab_id_cyclistes = array_unique(explode(";", $chaine_id_cyclistes));
	$tab_cyclistes = get_cyclistes_jeu_tab_id($ID_JEU,$ID_CAL,$tab_id_cyclistes);
	$tab_equipes = null;
    }
    //-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
    
  
    
    
    
    
    
    
    
    //-------------------------------OUTPUT------------------------------------//
    $res = array(   
		    'prono_joueur' => $prono_joueur,
		    'pronos' => $tab_pronos,
		    'calendrier' => $calendrier,
		    'cyclistes' => $tab_cyclistes,
		    'equipes' => $tab_equipes		    
	    );
    
    echo json_encode($res);
    //-------------------------------OUTPUT------------------------------------//
  
?>