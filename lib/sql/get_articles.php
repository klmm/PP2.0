<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_images.php';
    
	function get_articles_tous(){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles ORDER BY DateHeurePub DESC";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
		    $arr[$i]['id_article'] = $enregistrement->IDArticle;
		    $arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
		    $arr[$i]['dateheurepub_fr'] = date_to_duration($arr[$i]['dateheurepub']);
		    $arr[$i]['categorie'] = $enregistrement->Categorie;
		    $arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
		    $arr[$i]['titre'] = $enregistrement->Titre;
		    $tab_id_photo[$i] = $enregistrement->IDPhoto;
		    $arr[$i]['photo_id'] = $enregistrement->IDPhoto;
		    $arr[$i]['auteur'] = $enregistrement->Auteur;
		    $arr[$i]['debut'] = $enregistrement->debut;

		    $i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		
		for($j=0; $j<$i; $j++){
		    $id_img = $arr[$j]['photo_id'];
		    		    
		    $arr[$j]['photo_titre'] = $tab_img[$id_img]['titre'];	//titre
		    $arr[$j]['photo_credits'] = $tab_img[$id_img]['credits']; //credits
		    $arr[$j]['photo_chemin'] = $tab_img[$id_img]['chemin'];	//chemin
		    $arr[$j]['photo_chemin_deg'] = $tab_img[$id_img]['chemin_degrade'];
		}
		
		$db = null;
		
		return $arr;
	}
	
	function get_nombre_total_articles(){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT COUNT(*) FROM Articles";
		$nRows = $db->query($sql)->fetchColumn();
		
		$db = null;
		
		return $nRows;
	}
	
	function get_articles_tranche($nombre, $decalage){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles ORDER BY DateHeurePub DESC LIMIT " . $nombre . " OFFSET " . $decalage;
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_article'] = $enregistrement->IDArticle;
			$arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
			$arr[$i]['dateheurepub_fr'] = date_to_duration($arr[$i]['dateheurepub']);
			$arr[$i]['categorie'] = $enregistrement->Categorie;
			$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
			$arr[$i]['auteur'] = $enregistrement->Auteur;
			$arr[$i]['debut'] = $enregistrement->debut;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
		    $id_img = $arr[$j]['photo_id'];
		    
		    $arr[$j]['photo_titre'] = $tab_img[$id_img]['titre'];	//titre
		    $arr[$j]['photo_credits'] = $tab_img[$id_img]['credits']; //credits
		    $arr[$j]['photo_chemin'] = $tab_img[$id_img]['chemin'];	//chemin
		    $arr[$j]['photo_chemin_deg'] = $tab_img[$id_img]['chemin_degrade'];
		}
		
		$db = null;
		
		return $arr;
	}
	
	function get_articles_categorie($categ){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles WHERE Categorie = ? ORDER BY DateHeurePub DESC LIMIT 5";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$categ,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_article'] = $enregistrement->IDArticle;
			$arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
			$arr[$i]['dateheurepub_fr'] = date_to_duration($arr[$i]['dateheurepub']);
			$arr[$i]['categorie'] = $enregistrement->Categorie;
			$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
			$arr[$i]['auteur'] = $enregistrement->Auteur;
			$arr[$i]['debut'] = $enregistrement->debut;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
		    $id_img = $arr[$j]['photo_id'];
		    
		    $arr[$j]['photo_titre'] = $tab_img[$id_img]['titre'];	//titre
		    $arr[$j]['photo_credits'] = $tab_img[$id_img]['credits']; //credits
		    $arr[$j]['photo_chemin'] = $tab_img[$id_img]['chemin'];	//chemin
		    $arr[$j]['photo_chemin_deg'] = $tab_img[$id_img]['chemin_degrade'];
		}
		
		$db = null;
		return $arr;
	}

	function get_articles_souscategorie($categ, $souscat){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles WHERE (Categorie = ? AND SousCategorie = ?) ORDER BY DateHeurePub DESC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$categ,PDO::PARAM_STR);
		$prep->bindValue(2,$souscat,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_article'] = $enregistrement->IDArticle;
			$arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
			$arr[$i]['dateheurepub_fr'] = date_to_duration($arr[$i]['dateheurepub']);
			$arr[$i]['categorie'] = $enregistrement->Categorie;
			$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
			$arr[$i]['auteur'] = $enregistrement->Auteur;
			$arr[$i]['debut'] = $enregistrement->debut;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
		    $id_img = $arr[$j]['photo_id'];
		    
		    $arr[$j]['photo_titre'] = $tab_img[$id_img]['titre'];	//titre
		    $arr[$j]['photo_credits'] = $tab_img[$id_img]['credits']; //credits
		    $arr[$j]['photo_chemin'] = $tab_img[$id_img]['chemin'];	//chemin
		    $arr[$j]['photo_chemin_deg'] = $tab_img[$id_img]['chemin_degrade'];
		}
		$db = null;
		return $arr;
	}
	
	function get_articles_unes(){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM Articles WHERE EXISTS (
				SELECT IDArticle
				FROM ArticlesUnes
				WHERE (Articles.IDArticle = ArticlesUnes.IDArticle))";
		
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{	
		    $arr[$i]['id_article'] = $enregistrement->IDArticle;
		    $arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
		    $arr[$i]['dateheurepub_fr'] = date_to_duration($arr[$i]['dateheurepub']);
		    $arr[$i]['categorie'] = $enregistrement->Categorie;
		    $arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
		    $arr[$i]['titre'] = $enregistrement->Titre;
		    $tab_id_photo[$i] = $enregistrement->IDPhoto;
		    $arr[$i]['photo_id'] = $enregistrement->IDPhoto;
		    $arr[$i]['auteur'] = $enregistrement->Auteur;
		    $arr[$i]['debut'] = $enregistrement->debut;

		    $i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
		    $id_img = $arr[$j]['photo_id'];
		    
		    $arr[$j]['photo_titre'] = $tab_img[$id_img]['titre'];	//titre
		    $arr[$j]['photo_credits'] = $tab_img[$id_img]['credits']; //credits
		    $arr[$j]['photo_chemin'] = $tab_img[$id_img]['chemin'];	//chemin
		    $arr[$j]['photo_chemin_deg'] = $tab_img[$id_img]['chemin_degrade'];
		}
		$db = null;
		
		usort($arr,'compare_dates');
		return $arr;
	}
	
	function compare_dates($a, $b)
	{
	  return strnatcmp($b['dateheurepub'], $a['dateheurepub']);
	}
	
	function get_article_infos($id){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM Articles WHERE IDArticle = ?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		if( $enregistrement = $prep->fetch() )
		{
		    $arr['id_article'] = $enregistrement->IDArticle;
		    $arr['dateheurepub'] = $enregistrement->DateHeurePub;
		    $arr['dateheurepub_fr'] = date_to_duration($arr['dateheurepub']);
		    $arr['categorie'] = $enregistrement->Categorie;
		    $arr['souscategorie'] = $enregistrement->SousCategorie;
		    $arr['titre'] = $enregistrement->Titre;
		    $id_img = $enregistrement->IDPhoto;
		    $tab_id_photo[0] = $id_img;
		    $arr['photo_id'] = $id_img;
		    $arr['auteur'] = $enregistrement->Auteur;
		    $arr['debut'] = $enregistrement->debut;

		    $tab_img = get_images_id($tab_id_photo);
		    
		    
		    $arr['photo_titre'] = $tab_img[$id_img]['titre'];	//titre
		    $arr['photo_credits'] = $tab_img[$id_img]['credits']; //cr�dits
		    $arr['photo_chemin'] = $tab_img[$id_img]['chemin'];	//chemin
		    $arr['photo_chemin_deg'] = $tab_img[$id_img]['chemin_degrade'];
		}
		$db = null;
		return $arr;
	}
		
	function get_articles_jeu($id_jeu){
	    // On �tablit la connexion avec la base de donn�es
	    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	    $bdd = new Connexion();
	    $db = $bdd->getDB();

	    //On pr�pare la requ�te pour aller chercher les articles
	    $sql = "SELECT * FROM Articles WHERE EXISTS (
				SELECT IDArticle
				FROM ArticlesJeu
				WHERE (Articles.IDArticle = ArticlesJeu.IDArticle AND ArticlesJeu.IDJeu=?))";
	    
	    $prep = $db->prepare($sql);
	    $prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	    $prep->execute();
	    $prep->setFetchMode(PDO::FETCH_OBJ);

	    //On met les articles dans le tableau
	    $i = 0;
	    while( $enregistrement = $prep->fetch() )
	    {
		$arr[$i]['id_article'] = $enregistrement->IDArticle;
		$arr[$i]['dateheurepub'] = $enregistrement->DateHeurePub;
		$arr[$i]['dateheurepub_fr'] = date_to_duration($arr[$i]['dateheurepub']);
		$arr[$i]['categorie'] = $enregistrement->Categorie;
		$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
		$arr[$i]['titre'] = $enregistrement->Titre;
		$tab_id_photo[$i] = $enregistrement->IDPhoto;
		$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
		$arr[$i]['auteur'] = $enregistrement->Auteur;
		$arr[$i]['debut'] = $enregistrement->debut;

		$i++;
	    }

	    $tab_img = get_images_id($tab_id_photo);

	    for($j=0; $j<$i; $j++){
		    $id_img = $arr[$j]['photo_id'];
		    
		    $arr[$j]['photo_titre'] = $tab_img[$id_img]['titre'];	//titre
		    $arr[$j]['photo_credits'] = $tab_img[$id_img]['credits']; //credits
		    $arr[$j]['photo_chemin'] = $tab_img[$id_img]['chemin'];	//chemin
		    $arr[$j]['photo_chemin_deg'] = $tab_img[$id_img]['chemin_degrade'];
	    }
	    $db = null;
	    return $arr;
	}
	
?>