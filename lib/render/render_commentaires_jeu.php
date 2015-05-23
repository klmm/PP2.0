<?php
    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------

    ------------ OUTPUTS -------*/
   

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_commentaires.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_likes.php';
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
    
    
    
    
    //----------------------COMMENTAIRES & LIKES -----------------------------------//
    $tab_commentaires = get_commentaires_calendrier($ID_JEU, $ID_CAL);
    if($bConnected){
	$tab_likes = get_likes_jeu($ID_JEU,$ID_CAL,$loginjoueur);
    }
    else{
	$tab_likes = null;
    }
    //----------------------COMMENTAIRES & LIKES -----------------------------------//

    
    
   
    
    
    //-------------------------------OUTPUT------------------------------------//
    $res = array(
		    'connecte' => $bConnected,
		    'commentaires' => $tab_commentaires,
		    'likes' => $tab_likes
		    
	    );
    
    echo json_encode($res);
    //-------------------------------OUTPUT------------------------------------//
  
?>