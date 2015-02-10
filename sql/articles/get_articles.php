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
			$arr[$i][5] = $enregistrement->IDRubrique;
			$arr[$i][6] = $enregistrement->NumRubrique;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;
			$i++;
		}
		
		return $arr;
	}
	
	function get_articles_rubrique($id_rub){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur la rubrique
		$sql = "SELECT * FROM Articles WHERE IDRubrique = ? ORDER BY NumRubrique ASC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$id_rub,PDO::PARAM_STR);
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
			$arr[$i][5] = $enregistrement->IDRubrique;
			$arr[$i][6] = $enregistrement->NumRubrique;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;
			$i++;
		}
		
		return $arr;
	}
	
	function get_articles_categorie($categ){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles WHERE Categorie = ? ORDER BY DateHeurePub DESC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$categ,PDO::PARAM_STR);
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
			$arr[$i][5] = $enregistrement->IDRubrique;
			$arr[$i][6] = $enregistrement->NumRubrique;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;
			$i++;
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
			$arr[$i][5] = $enregistrement->IDRubrique;
			$arr[$i][6] = $enregistrement->NumRubrique;
			$arr[$i][7] = $enregistrement->IDPhoto;
			$arr[$i][8] = $enregistrement->Auteur;
			$i++;
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
				$arr[$i][5] = $enregistrement2->IDRubrique;
				$arr[$i][6] = $enregistrement2->NumRubrique;
				$arr[$i][7] = $enregistrement2->IDPhoto;
				$arr[$i][8] = $enregistrement2->Auteur;
				$i++;
			}
		}
		
		return $arr;
	}	
	
	
?>