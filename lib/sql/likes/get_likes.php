<?php
	function get_likes($id, $joueur){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM ArticlesLike WHERE IDArticle = ? AND Joueur = ?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id,PDO::PARAM_INT);
		$prep->bindValue(2,$joueur,PDO::PARAM_STR);
		$prep->execute();
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_comm'] = $enregistrement->IDComm;
			$arr[$i]['bLike'] = $enregistrement->bLike;
			$i++;
		}
		
		return $arr;
	}
?>