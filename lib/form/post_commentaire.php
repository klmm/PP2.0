<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();

    session_start();
    $login = $_SESSION['LoginJoueur'];	
    $contenu = $_POST['contenu'];	
    $contenu = htmlentities(nl2br($contenu));
    $id_article = $_POST['id_article'];

    if ($login == ''){
	echo 'Joueur non connecté';
	return;
    }

    $sql = "INSERT INTO ArticlesCommentaire(IDArticle,Joueur,Contenu,DateHeurePub,NombreLikes,NombreDislikes) VALUES(?,?,?,NOW(),0,0)";

    //echo $login . ' - ' . $contenu . ' - ' . $id_article . ' - ';
    $prep = $db->prepare($sql);
    $prep->bindValue(1,$id_article,PDO::PARAM_INT);
    $prep->bindValue(2,$login,PDO::PARAM_STR);
    $prep->bindValue(3,$contenu,PDO::PARAM_STR);
    $res_req = $prep->execute();

    if( $res_req == false){
	echo 'Erreur lors de l\'envoi du commentaire...';
	return;
    }



    // Envoi de tous les commentaires + ce que le joueur like pour AJAX
    $res1 = get_commentaires_article($id_article);
    $res2 = get_likes($id_article, $login);

    $res = array(
	'result' => 'success',
	'commentaires' => $res1,
	'likes' => $res2    // !!!! 1:dislike, 2:like
    );

    echo json_encode($res);

    //echo 'success;sep;' . $contenu . ';sep;' . $login . ';sep;';
?>