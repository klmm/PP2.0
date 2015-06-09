<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_commentaires.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_likes.php');

    session_start();
    $login = $_SESSION['LoginJoueur'];
    
    if($login != ""){
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    
    $barticle = $_POST['b_article'];
    if($barticle){
	$id_article = $_POST['id_article'];
	
	$tab_commentaires = get_commentaires_article($id_article,0);
	
	if($bConnected){
	    $tab_likes = get_likes($id_article, $login);
	}
	else{
	    $tab_likes = null;
	}
    }
    else{
	$ID_JEU = $_POST['id_jeu'];
	$ID_CAL = $_POST['id_cal'];
	
	$tab_commentaires = get_commentaires_calendrier($ID_JEU, $ID_CAL, 0);
	
	if($bConnected){
	    $tab_likes = get_likes_jeu($ID_JEU,$ID_CAL,$login);
	}
	else{
	    $tab_likes = null;
	}
    }
    
    
    $res = array(
	'connecte' => $bConnected,
	'b_article' => $barticle,
	'commentaires' => $tab_commentaires,
	'likes' => $tab_likes    // !!!! 1:dislike, 2:like
    );

    echo json_encode($res);
	
?>