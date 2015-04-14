<?php

	function get_equipes_non_inscrites($id_jeu, $id_cal){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requête pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_equipe
				WHERE NOT EXISTS (
					SELECT id_equipe
					FROM cyclisme_inscription_equipe
					WHERE (id_cyclisme_equipe=id_equipe AND (id_cal=0 OR id_cal=" . $id_cal . ") AND id_jeu=" . $id_jeu . ")
					)
				ORDER BY nom_complet ASC";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_cyclisme_equipe;
			$arr[$i][1] = $enregistrement->niveau;
			$arr[$i][2] = $enregistrement->nom_complet;
			$arr[$i][3] = $enregistrement->nom_courant;
			$arr[$i][4] = $enregistrement->nom_court;
			$arr[$i][5] = $enregistrement->photo;
			$arr[$i][7] = $enregistrement->saison;
			$i++;
		}
		
		return $arr;
	}
	
	function get_equipes_inscrites($id_jeu, $id_cal){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requête pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_equipe
				WHERE EXISTS (
					SELECT id_equipe
					FROM cyclisme_inscription_equipe
					WHERE (id_cyclisme_equipe=id_equipe AND (id_cal=0 OR id_cal=" . $id_cal . ") AND id_jeu=" . $id_jeu . ")
					)
				ORDER BY nom_complet ASC";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_cyclisme_equipe;
			$arr[$i][1] = $enregistrement->niveau;
			$arr[$i][2] = $enregistrement->nom_complet;
			$arr[$i][3] = $enregistrement->nom_courant;
			$arr[$i][4] = $enregistrement->nom_court;
			$arr[$i][5] = $enregistrement->photo;
			$arr[$i][7] = $enregistrement->saison;
			$i++;
		}
		
		return $arr;
	}
	
	function get_equipe_id($id_equipe){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requête pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_equipe
				WHERE id_cyclisme_equipe = ?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_equipe,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		$enregistrement = $prep->fetch();

		$arr[0] = $enregistrement->id_cyclisme_equipe;
		$arr[1] = $enregistrement->niveau;
		$arr[2] = $enregistrement->nom_complet;
		$arr[3] = $enregistrement->nom_courant;
		$arr[4] = $enregistrement->nom_court;
		$arr[5] = $enregistrement->photo;
		$arr[7] = $enregistrement->saison;
		$i++;
		
		return $arr;
	}
?>