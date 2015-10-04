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
    $ID_CAL = $_POST['id_cal'];
    $id = $_POST['id'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = get_calendrier($ID_JEU,$ID_CAL);
    $b_equipe = $calendrier['profil_equipe'];
    //------------------------------------------------------------------------------------------------//
    

    
    
    //-----------------------------LISTE DE PRONO----------------------//
    if($b_equipe){
	$equipe = get_equipe_id($id);
	$liste_coureurs = get_cyclistes_equipe($ID_JEU, $ID_CAL, $id);
    }
    else{
	$arr = array($id);
	$cycliste = get_cyclistes_jeu_tab_id($ID_JEU, $ID_CAL, $arr);
    }
    
    $res = array(
	'b_equipe' => $b_equipe,
	'cycliste' => $cycliste,
	'equipe' => $equipe,
	'liste_cyclistes' => $liste_coureurs
    );
    
    echo json_encode($res);
	
?>