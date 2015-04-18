<?php

	function get_equipes_non_inscrites($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
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
			$arr[$i]['id_cyclisme_equipe'] = $enregistrement->id_cyclisme_equipe;
			$arr[$i]['niveau'] = $enregistrement->niveau;
			$arr[$i]['nom_complet'] = $enregistrement->nom_complet;
			$arr[$i]['nom_courant'] = $enregistrement->nom_courant;
			$arr[$i]['nom_court'] = $enregistrement->nom_court;
			$arr[$i]['photo'] = $enregistrement->photo;
			$arr[$i]['saison'] = $enregistrement->saison;
			$i++;
		}
		
		return $arr;
	}
	
	function get_equipes_inscrites($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
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
			$arr[$i]['id_cyclisme_equipe'] = $enregistrement->id_cyclisme_equipe;
			$arr[$i]['niveau'] = $enregistrement->niveau;
			$arr[$i]['nom_complet'] = $enregistrement->nom_complet;
			$arr[$i]['nom_courant'] = $enregistrement->nom_courant;
			$arr[$i]['nom_court'] = $enregistrement->nom_court;
			$arr[$i]['photo'] = $enregistrement->photo;
			$arr[$i]['saison'] = $enregistrement->saison;
			$i++;
		}
		
		return $arr;
	}
	
	function get_equipe_id($id_equipe){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_equipe
				WHERE id_cyclisme_equipe = ?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_equipe,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		$enregistrement = $prep->fetch();

		$arr['id_cyclisme_equipe'] = $enregistrement->id_cyclisme_equipe;
                $arr['niveau'] = $enregistrement->niveau;
                $arr['nom_complet'] = $enregistrement->nom_complet;
                $arr['nom_courant'] = $enregistrement->nom_courant;
                $arr['nom_court'] = $enregistrement->nom_court;
                $arr['photo'] = $enregistrement->photo;
                $arr['saison'] = $enregistrement->saison;
		$i++;
		
		return $arr;
	}
?>