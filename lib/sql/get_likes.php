<?php
    function get_likes($id_article, $joueur){
	// On établit la connexion avec la base de données
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On prépare la requête pour aller chercher les articles
	$sql = "SELECT * FROM likes WHERE IDArticle=? AND Joueur=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_article,PDO::PARAM_INT);
	$prep->bindValue(2,$joueur,PDO::PARAM_STR);
	$prep->execute();

	//On met les articles dans le tableau
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $arr[$enregistrement->IDComm] = $enregistrement->bLike + 1;
	    $i++;
	}
	$db = null;
	return $arr;
    }
      
    function get_likes_jeu($id_jeu,$id_cal,$joueur){
	// On établit la connexion avec la base de données
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On prépare la requête pour aller chercher les articles
	$sql = "SELECT * FROM likes WHERE id_jeu=? AND id_cal=? AND Joueur=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(3,$joueur,PDO::PARAM_STR);
	$prep->execute();

	//On met les articles dans le tableau
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $arr[$enregistrement->IDComm] = $enregistrement->bLike + 1;
	    $i++;
	}
	$db = null;
	return $arr;
    }
?>