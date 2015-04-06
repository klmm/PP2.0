<?php

	function get_articles_tous(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles ORDER BY DateHeurePub DESC";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDArticle;
			$arr[$i][1] = $enregistrement->DateHeurePub;
			$arr[$i][2] = $enregistrement->Categorie;
			$arr[$i][3] = $enregistrement->SousCategorie;
			$arr[$i][4] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j][9] = $tab_img[$j][1];	//titre
			$arr[$j][10] = $tab_img[$j][2]; //crédits
			$arr[$j][11] = $tab_img[$j][3];	//chemin
		}
		
		return $arr;
	}
	
	function get_nombre_total_articles(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT COUNT(*) FROM Articles";
		$nRows = $db->query($sql)->fetchColumn();
		
		return $nRows;
	}
	
	function get_articles_tranche($nombre, $decalage){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles ORDER BY DateHeurePub DESC LIMIT " . $nombre . " OFFSET " . $decalage;
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDArticle;
			$arr[$i][1] = $enregistrement->DateHeurePub;
			$arr[$i][2] = $enregistrement->Categorie;
			$arr[$i][3] = $enregistrement->SousCategorie;
			$arr[$i][4] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j][9] = $tab_img[$j][1];	//titre
			$arr[$j][10] = $tab_img[$j][2]; //crédits
			$arr[$j][11] = $tab_img[$j][3];	//chemin
		}
		
		return $arr;
	}
	
	function get_articles_categorie($categ){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles WHERE Categorie = ? ORDER BY DateHeurePub DESC LIMIT 5";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$categ,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch())
		{
			$arr[$i][0] = $enregistrement->IDArticle;
			$arr[$i][1] = $enregistrement->DateHeurePub;
			$arr[$i][2] = $enregistrement->Categorie;
			$arr[$i][3] = $enregistrement->SousCategorie;
			$arr[$i][4] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;

			$i++;
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j][9] = $tab_img[$j][1];	//titre
			$arr[$j][10] = $tab_img[$j][2]; //crédits
			$arr[$j][11] = $tab_img[$j][3];	//chemin
		}
		
		return $arr;
	}

	function get_articles_souscategorie($categ, $souscat){
		// On établit la connexion avec la base de données
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

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDArticle;
			$arr[$i][1] = $enregistrement->DateHeurePub;
			$arr[$i][2] = $enregistrement->Categorie;
			$arr[$i][3] = $enregistrement->SousCategorie;
			$arr[$i][4] = $enregistrement->Titre;
			$tab_id_photo[$i] = $enregistrement->IDPhoto;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;
			
			$i++;
		}
					
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j][9] = $tab_img[$j][1];	//titre
			$arr[$j][10] = $tab_img[$j][2]; //crédits
			$arr[$j][11] = $tab_img[$j][3];	//chemin
		}
		
		return $arr;
	}
	
	function get_articles_unes(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On récupère les ID des articles
		$sql = "SELECT * FROM ArticlesUnes ORDER BY IDArticleUne ASC";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);
		
		//On prépare la requête pour aller chercher les articles
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
				
				$arr[$i][0] = $enregistrement2->IDArticle;
				$arr[$i][1] = $enregistrement2->DateHeurePub;
				$arr[$i][2] = $enregistrement2->Categorie;
				$arr[$i][3] = $enregistrement2->SousCategorie;
				$arr[$i][4] = $enregistrement2->Titre;
				$tab_id_photo[$i] = $enregistrement2->IDPhoto;
				$arr[$i][7] = $enregistrement2->IDPhoto;
				$arr[$i][8] = $enregistrement2->Auteur;

				$i++;
			}
		}
		
		$tab_img = get_images_id($tab_id_photo);
		
		for($j=0; $j<$i; $j++){
			$arr[$j][9] = $tab_img[$j][1];	//titre
			$arr[$j][10] = $tab_img[$j][2]; //crédits
			$arr[$j][11] = $tab_img[$j][3];	//chemin
		}
		
		return $arr;
	}
	
	function get_article_infos($id){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requête pour aller chercher les articles
		$sql = "SELECT * FROM Articles WHERE IDArticle = ?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		if( $enregistrement = $prep->fetch() )
		{
			$arr[0] = $enregistrement->IDArticle;
			$arr[1] = $enregistrement->DateHeurePub;
			$arr[2] = $enregistrement->Categorie;
			$arr[3] = $enregistrement->SousCategorie;
			$arr[4] = $enregistrement->Titre;
			$tab_id_photo[0] = $enregistrement->IDPhoto;
			$arr[7] = $enregistrement->IDPhoto;
			$arr[8] = $enregistrement->Auteur;
			
			$tab_img = get_images_id($tab_id_photo);
		
			$arr[9] = $tab_img[0][1];	//titre
			$arr[10] = $tab_img[0][2]; //crédits
			$arr[11] = $tab_img[0][3];	//chemin
		}
		
		return $arr;
	}
	
?>