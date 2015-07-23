<?php
	function get_equipes_inscrites($id_jeu){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM rugby_inscription_equipe WHERE id_jeu=?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		while( $enregistrement = $prep->fetch() )
		{
		    $id_equipe = $enregistrement->id;
		    $arr[$id_equipe]['id'] = $id_equipe;
		    $arr[$id_equipe]['id_jeu'] = $enregistrement->id_jeu;
		    $arr[$id_equipe]['id_pays'] = $enregistrement->id_pays;
		    $arr[$id_equipe]['nom'] = $enregistrement->nom;
		    $arr[$id_equipe]['force'] = $enregistrement->force;
		}
		$db = null;
		return $arr;
	}
	
	function get_equipes_match($equipe1, $equipe2){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		$chaine_id = $equipe1 . ',' . $equipe2;
		
		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT *
				FROM rugby_inscription_equipe
				WHERE id IN (" . $chaine_id . ")";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->execute();
		
		while($enregistrement = $prep->fetch()){	
		    $id_equipe = $enregistrement->id;
		    $arr[$id_equipe]['id'] = $id_equipe;
		    $arr[$id_equipe]['id_jeu'] = $enregistrement->id_jeu;
		    $arr[$id_equipe]['id_pays'] = $enregistrement->id_pays;
		    $arr[$id_equipe]['nom'] = $enregistrement->nom;
		    $arr[$id_equipe]['force'] = $enregistrement->force;
		}
		$db = null;
		return $arr;
	}
?>