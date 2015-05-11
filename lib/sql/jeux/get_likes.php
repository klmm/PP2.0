<?php
    function get_likes($id_jeu, $id_cal, $joueur){
	// On établit la connexion avec la base de données
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On prépare la requête pour aller chercher les articles
	$sql = "SELECT * FROM jeu_commentaire_like WHERE id_jeu=? AND id_calendrier=? AND joueur=?";
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
	    $arr[$enregistrement->id_jeu_commentaire] = $enregistrement->b_like + 1;
	    $i++;
	}

	return $arr;
    }
?>