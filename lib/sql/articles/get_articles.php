<?php

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
			$arr[$i]['categorie'] = $enregistrement->Categorie;
			$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
			$arr[$i]['auteur'] = $enregistrement->Auteur;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j]['photo_titre'] = $tab_img[$j]['titre'];	//titre
			$arr[$j]['photo_credits'] = $tab_img[$j]['credits']; //credits
			$arr[$j]['photo_chemin'] = $tab_img[$j]['chemin'];	//chemin
		}
		
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
			$arr[$i]['categorie'] = $enregistrement->Categorie;
			$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
			$arr[$i]['auteur'] = $enregistrement->Auteur;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j]['photo_titre'] = $tab_img[$j]['titre'];	//titre
			$arr[$j]['photo_credits'] = $tab_img[$j]['credits']; //credits
			$arr[$j]['photo_chemin'] = $tab_img[$j]['chemin'];	//chemin
		}
		
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
			$arr[$i]['categorie'] = $enregistrement->Categorie;
			$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
			$arr[$i]['auteur'] = $enregistrement->Auteur;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j]['photo_titre'] = $tab_img[$j]['titre'];	//titre
			$arr[$j]['photo_credits'] = $tab_img[$j]['credits']; //credits
			$arr[$j]['photo_chemin'] = $tab_img[$j]['chemin'];	//chemin
		}
		
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
			$arr[$i]['categorie'] = $enregistrement->Categorie;
			$arr[$i]['souscategorie'] = $enregistrement->SousCategorie;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i]['photo_id'] = $enregistrement->IDPhoto;
			$arr[$i]['auteur'] = $enregistrement->Auteur;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j]['photo_titre'] = $tab_img[$j]['titre'];	//titre
			$arr[$j]['photo_credits'] = $tab_img[$j]['credits']; //credits
			$arr[$j]['photo_chemin'] = $tab_img[$j]['chemin'];	//chemin
		}
		
		return $arr;
	}
	
	function get_articles_unes(){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On r�cup�re les ID des articles
		$sql = "SELECT * FROM ArticlesUnes ORDER BY IDArticleUne ASC";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);
		
		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM Articles WHERE IDArticle = ?";
		$prep2 = $db->prepare($sql);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$id_article = $enregistrement->IDArticle;
			$prep2->bindValue(1,$id_article,PDO::PARAM_INT);
			$prep2->execute();
			
			if ($enregistrement2 = $prep2->fetch()){
				
                            $arr[$i]['id_article'] = $enregistrement2->IDArticle;
                            $arr[$i]['dateheurepub'] = $enregistrement2->DateHeurePub;
                            $arr[$i]['categorie'] = $enregistrement2->Categorie;
                            $arr[$i]['souscategorie'] = $enregistrement2->SousCategorie;
                            $arr[$i]['titre'] = $enregistrement2->Titre;
                            $tab_id_photo[$i] = $enregistrement2->IDPhoto;
                            $arr[$i]['photo_id'] = $enregistrement2->IDPhoto;
                            $arr[$i]['auteur'] = $enregistrement2->Auteur;

                            $i++;
			}
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
                    $arr[$j]['photo_titre'] = $tab_img[$j]['titre'];	//titre
                    $arr[$j]['photo_credits'] = $tab_img[$j]['credits']; //cr�dits
                    $arr[$j]['photo_chemin'] = $tab_img[$j]['chemin'];	//chemin
		}
		
		return $arr;
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
			$arr['categorie'] = $enregistrement->Categorie;
			$arr['souscategorie'] = $enregistrement->SousCategorie;
			$arr['titre'] = $enregistrement->Titre;
			$tab_id_photo[0] = $enregistrement->IDPhoto;
			$arr['photo_id'] = $enregistrement->IDPhoto;
			$arr['auteur'] = $enregistrement->Auteur;
			
			$tab_img = get_images_id($tab_id_photo);
		
			$arr['photo_titre'] = $tab_img[0]['titre'];	//titre
			$arr['photo_credits'] = $tab_img[0]['credits']; //cr�dits
			$arr['photo_chemin'] = $tab_img[0]['chemin'];	//chemin
		}
		
		return $arr;
	}
	
?>