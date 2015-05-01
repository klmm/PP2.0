<?php
    $ID_JEU = 4;
    
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
    $arr_breves = get_breves_jeu($ID_JEU);
    $nb_breves = sizeof($arr_breves);
    
    $arr_articles = get_articles_jeu($ID_JEU);
    $nb_articles = sizeof($arr_articles);
    
    $arr_calendrier = get_calendrier_jeu($ID_JEU);
    $nb_calendier = sizeof($arr_calendrier);
     
    if($bConnected){
	$arr_pronos = get_pronos_joueurs_jeu($ID_JEU,$loginjoueur);
    }
    else{
	$arr_pronos = null;
    }
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    
    
    print_r($arr_breves);
    echo $nb_breves;
    
    print_r($arr_articles);
    echo $nb_articles;
    
    print_r($arr_calendrier);
    echo $nb_calendier;
    
    print_r($arr_pronos);
    
?>