<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/commentaires/get_commentaires.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/likes/get_likes.php');

    session_start();
    $login = $_SESSION['LoginJoueur'];
    $id_article = $_POST['id_article'];
	
    if ($login != ''){
	$res2 = get_likes($id_article, $login);
    }
    else
    {
	$res2 = null;
    }
	
     // Envoi de tous les commentaires + ce que le joueur like pour AJAX
    $res1 = get_commentaires_article($id_article);
    
    $res = array(
	'result' => 'success',
	'commentaires' => $res1,
	'likes' => $res2    // !!!! 1:dislike, 2:like
    );

    echo json_encode($res);
	
?>