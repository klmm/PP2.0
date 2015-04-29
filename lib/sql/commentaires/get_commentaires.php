<?php

	function get_commentaires_article($id){
		
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/fonctions.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM ArticlesCommentaire WHERE IDArticle = ? ORDER BY DateHeurePub DESC";
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
?>