<?php

	function get_articles_tous(){
		// On établit la connexion avec la base de données
		require_once('../../admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Articles";
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
			$arr[$i][7] = $enregistrement->Photo;
			$i++;
		}
		
		return $arr;
	}
	
	function get_articles_rubrique($id_rub){
		// On établit la connexion avec la base de données
		require_once('../../admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Joueurs WHERE IDRubrique = ?";
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
			$arr[$i][7] = $enregistrement->Photo;
			$i++;
		}
		
		return $arr;
	}
	
	function get_articles_categorie($categ){
		// On établit la connexion avec la base de données
		require_once('../../admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Joueurs WHERE Categorie = ?";
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
			$arr[$i][7] = $enregistrement->Photo;
			$i++;
		}
		
		return $arr;
	}

	function get_articles_souscategorie($categ, $souscat){
		// On établit la connexion avec la base de données
		require_once('../../admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Joueurs WHERE (Categorie = ? AND SousCategorie = ?)";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$categ,PDO::PARAM_STR);
		$prep->bindValue(1,$souscat,PDO::PARAM_STR);
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
			$arr[$i][7] = $enregistrement->Photo;
			$i++;
		}
		
		return $arr;
	}	
	
	
?>