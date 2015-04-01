<?php

	function get_commentaires_article($id){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requête pour aller chercher les articles
		$sql = "SELECT * FROM ArticlesCommentaire WHERE IDArticle = ? ORDER BY DateHeurePub DESC";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDCommentaire;
			$arr[$i][1] = $enregistrement->IDArticle;
			$arr[$i][2] = $enregistrement->Joueur;
			$arr[$i][3] = $enregistrement->Contenu;
			$arr[$i][4] = $enregistrement->DateHeurePub;
			$arr[$i][5] = $enregistrement->NombreLikes;
			$arr[$i][6] = $enregistrement->NombreDislikes;
			$i++;
		}
		
		return $arr;
	}
?>