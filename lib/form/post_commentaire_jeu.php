<?php

        require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	
    $bdd = new Connexion();
    $db = $bdd->getDB();

    session_start();
    $login = $_SESSION['LoginJoueur'];
    $contenu = $_POST['contenu'];	
    $contenu = htmlentities(nl2br($contenu));
    $id_jeu = $_POST['id_jeu'];
    $id_cal = $_POST['id_cal'];
	
    if ($login == ''){
	echo 'Joueur non connecté';
	return;
    }

    $sql = "INSERT INTO commentaire(IDArticle,Joueur,Contenu,DateHeurePub,NombreLikes,NombreDislikes,id_jeu,id_cal) VALUES(0,?,?,NOW(),0,0,?,?)";

    //echo $login . ' - ' . $contenu . ' - ' . $id_article . ' - ';
    $prep = $db->prepare($sql);
    
    $prep->bindValue(1,$login,PDO::PARAM_STR);
    $prep->bindValue(2,$contenu,PDO::PARAM_STR);
    $prep->bindValue(3,$id_jeu,PDO::PARAM_INT);
    $prep->bindValue(4,$id_cal,PDO::PARAM_INT);
    $res_req = $prep->execute();

    if( $res_req == false){
	echo 'Erreur lors de l\'envoi du commentaire...';
	return;
    }
    
    echo 'success;sep;' . $contenu . ';sep;' . $login . ';sep;';
?>