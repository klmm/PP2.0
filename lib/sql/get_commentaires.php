<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');

    function get_commentaires_article($id,$tri){
	
	// On �tablit la connexion avec la base de donn�es
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	switch($tri){
	    case 0: // plus récents
		$order = 'ORDER BY DateHeurePub DESC';
		break;
	    
	    case 1: // plus de likes
		$order = 'ORDER BY NombreLikes DESC, DateHeurePub DESC';
		break;
	    
	    default:
		$order = 'ORDER BY DateHeurePub DESC';
	}
	
	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM commentaire WHERE IDArticle = ? " . $order;
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
		$arr[$i]['id_commentaire'] = $enregistrement->IDCommentaire;
		$arr[$i]['id_article'] = $enregistrement->IDArticle;
		$arr[$i]['id_jeu'] = $enregistrement->id_jeu;
		$arr[$i]['id_cal'] = $enregistrement->id_cal;
		$arr[$i]['joueur'] = $enregistrement->Joueur;
		$arr[$i]['contenu'] = html_entity_decode($enregistrement->Contenu);
		$arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
		$arr[$i]['dateheurepub_conv'] = date_to_duration($enregistrement->DateHeurePub);
		$arr[$i]['dateheurepub_court'] = date_to_duration_court($enregistrement->DateHeurePub);
		$arr[$i]['nblikes'] = $enregistrement->NombreLikes;
		$arr[$i]['nbdislikes'] = $enregistrement->NombreDislikes;
		$i++;

	}

	return $arr;
    }
    
    function get_commentaires_calendrier($id_jeu,$id_cal,$tri){
	// On �tablit la connexion avec la base de donn�es
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	switch($tri){
	    case 0: // plus récents
		$order = 'ORDER BY DateHeurePub DESC';
		break;
	    
	    case 1: // plus de likes
		$order = 'ORDER BY NombreLikes DESC, DateHeurePub DESC';
		break;
	}

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM commentaire WHERE id_jeu=? AND id_cal=? " . $order;
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
		$arr[$i]['id_commentaire'] = $enregistrement->IDCommentaire;
		$arr[$i]['id_article'] = $enregistrement->IDArticle;
		$arr[$i]['id_jeu'] = $enregistrement->id_jeu;
		$arr[$i]['id_cal'] = $enregistrement->id_cal;
		$arr[$i]['joueur'] = $enregistrement->Joueur;
		$arr[$i]['contenu'] = html_entity_decode($enregistrement->Contenu);
		$arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
		$arr[$i]['dateheurepub_conv'] = date_to_duration($enregistrement->DateHeurePub);
		$arr[$i]['dateheurepub_court'] = date_to_duration_court($enregistrement->DateHeurePub);
		$arr[$i]['nblikes'] = $enregistrement->NombreLikes;
		$arr[$i]['nbdislikes'] = $enregistrement->NombreDislikes;
		$i++;

	}

	return $arr;
    }
    
    function get_nb_commentaires_article($id){
	
	// On �tablit la connexion avec la base de donn�es
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT COUNT(*) FROM commentaire WHERE IDArticle = ?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id,PDO::PARAM_INT);
	$prep->execute();
	
	return $prep->fetchColumn();
    }
    
    function get_nb_commentaires_calendrier($id_jeu,$id_cal){
	// On �tablit la connexion avec la base de donn�es
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT COUNT(*) FROM commentaire WHERE id_jeu=? AND id_cal=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->execute();
	
	return $prep->fetchColumn();
    }
?>