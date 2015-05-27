<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_commentaires.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_likes.php');

    session_start();
    $login = $_SESSION['LoginJoueur'];
    $id_article = $_POST['id_article'];
    
    $res1 = get_commentaires_article($id_article,0);
    
    if($login==''){
	$res2 = null;
    }
    else{
	$res2 = get_likes($id_article, $login);
    }
    
    $res = array(
	'result' => 'success',
	'commentaires' => $res1,
	'likes' => $res2    // !!!! 1:dislike, 2:like
    );

    echo json_encode($res);
	
?>