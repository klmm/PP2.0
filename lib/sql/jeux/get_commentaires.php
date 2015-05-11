<?php

    function get_commentaires_cal($id_jeu, $id_cal){

	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM jeu_commentaire WHERE id_jeu=? AND id_cal=? ORDER BY date_heure_pub DESC";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
		$arr[$i]['id_commentaire'] = $enregistrement->id_jeu_commentaire;
		$arr[$i]['id_jeu'] = $enregistrement->id_jeu;
		$arr[$i]['id_cal'] = $enregistrement->id_cal;
		$arr[$i]['joueur'] = $enregistrement->joueur;
		$arr[$i]['contenu'] = html_entity_decode($enregistrement->contenu);
		$arr[$i]['dateheurepub'] = $enregistrement->dateheurepub;
		$arr[$i]['dateheurepub_conv'] = date_to_duration($enregistrement->dateheurepub);
		$arr[$i]['dateheurepub_court'] = date_to_duration_court($enregistrement->dateheurepub);
		$arr[$i]['nblikes'] = $enregistrement->nb_likes;
		$arr[$i]['nbdislikes'] = $enregistrement->nb_dislikes;
		$i++;
	}

	return $arr;
    }
?>