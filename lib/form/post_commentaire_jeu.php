<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	
    $bdd = new Connexion();
    $db = $bdd->getDB();

    session_start();
    $login = $_SESSION['LoginJoueur'];
    $contenu = $_POST['contenu'];	
    $contenu = htmlentities(nl2br($contenu));
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
	
    if ($login == ''){
	echo 'Joueur non connecté';
	return;
    }

    $sql = "INSERT INTO jeu_commentaire (id_jeu, id_cal, joueur, contenu, date_heure_pub, nb_likes, nb_dislikes) VALUES (?, ?, ?, ?, NOW(), 0, 0)";

    //echo $login . ' - ' . $contenu . ' - ' . $id_article . ' - ';
    $prep = $db->prepare($sql);
    $prep->bindValue(1,$ID_JEU,PDO::PARAM_INT);
    $prep->bindValue(1,$ID_CAL,PDO::PARAM_INT);
    $prep->bindValue(3,$login,PDO::PARAM_STR);
    $prep->bindValue(4,$contenu,PDO::PARAM_STR);
    $res_req = $prep->execute();

    if( $res_req == false){
	echo 'Erreur lors de l\'envoi du commentaire...';
	return;
    }

    echo 'success;sep;' . $contenu . ';sep;' . $login . ';sep;';
?>