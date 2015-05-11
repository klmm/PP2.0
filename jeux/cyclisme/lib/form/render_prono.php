<?php
    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/breves/get_breves.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/images/get_images.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/auto_login.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/update_joueurs.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/articles/get_articles.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_prono.php';
    //-------------------------------------------------------------------------------------//
    
    
    
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];

    if($loginjoueur == ""){
        auto_login();
        $loginjoueur = $_SESSION['LoginJoueur'];
    }
    $idjoueur = $_SESSION['IDJoueur'];
    $mailjoueur = $_SESSION['MailJoueur'];
    $admin = $_SESSION['Admin'];

    if($loginjoueur != ""){
        update_derniere_visite();
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    
    $arr_pronos = get_pronos_cal($ID_JEU,$ID_CAL);
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    
    echo json_encode($res);
	
?>