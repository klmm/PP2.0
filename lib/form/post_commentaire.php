<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');	
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    session_start();
    $login = $_SESSION['LoginJoueur'];
    $contenu = $_POST['contenu'];
    $a_virer = array('<','>');
    $contenu = str_replace($a_virer,'',$contenu);
    $contenu = htmlentities(nl2br($contenu));
    
    $b_article = $_POST['b_article'];
    $id_art_jeu = $_POST['id_art_jeu'];
	    
    if ($login == ''){
	echo 'Joueur non connecté';
	return;
    }

    if($b_article){
	$b_article = 1;
	$id_cal = 0;
	$sql = "INSERT INTO commentaire(IDArticle,Joueur,Contenu,DateHeurePub,NombreLikes,NombreDislikes,id_jeu,id_cal) VALUES(?,?,?,NOW(),0,0,0,0)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_art_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$login,PDO::PARAM_STR);
	$prep->bindValue(3,$contenu,PDO::PARAM_STR);
	$res_req = $prep->execute();
    }
    else{
	$id_cal = $_POST['id_cal'];
	$b_article = 0;
	$sql = "INSERT INTO commentaire(IDArticle,Joueur,Contenu,DateHeurePub,NombreLikes,NombreDislikes,id_jeu,id_cal) VALUES(0,?,?,NOW(),0,0,?,?)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$login,PDO::PARAM_STR);
	$prep->bindValue(2,$contenu,PDO::PARAM_STR);
	$prep->bindValue(3,$id_art_jeu,PDO::PARAM_INT);
	$prep->bindValue(4,$id_cal,PDO::PARAM_INT);
	$res_req = $prep->execute();
    }
    
    if( $res_req == false){
	echo 'Erreur lors de l\'envoi du commentaire...';
	$db = null;
	return;
    }
    $db = null;
    echo 'success;' . $b_article . ';' . $id_art_jeu . ';' . $id_cal . ';';
?>