<?php

	function get_cyclistes_inscriptions($id_jeu, $id_cal){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requête pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_athlete
				WHERE EXISTS (
					SELECT id_equipe
					FROM cyclisme_inscription_equipe
					WHERE (cyclisme_inscription_equipe.id_equipe=cyclisme_athlete.id_cyclisme_equipe AND (id_cal=0 OR id_cal=?) AND id_jeu=?)
					)
				ORDER BY id_cyclisme_equipe ASC, nom ASC";
					
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
		$prep->execute();

		$sql2 = "SELECT *
				FROM cyclisme_inscription_athlete
				WHERE id_athlete=? AND (id_cal=? or id_cal=0) AND id_jeu=?";
					
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		
		
		// Parcours des cyclistes dont l'équipe est inscrite
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_cyclisme_athlete;
			$arr[$i][1] = $enregistrement->id_cyclisme_equipe;
			$arr[$i][2] = $enregistrement->nom;
			$arr[$i][3] = $enregistrement->prenom;
			$arr[$i][4] = $enregistrement->date_naissance;
			$arr[$i][5] = $enregistrement->note_paves;
			$arr[$i][7] = $enregistrement->note_vallons;
			$arr[$i][8] = $enregistrement->note_montagne;
			$arr[$i][9] = $enregistrement->note_sprint;
			$arr[$i][10] = $enregistrement->note_clm;
			$arr[$i][11] = $enregistrement->photo;
			$arr[$i][12] = $enregistrement->id_pays;
			
			//Cycliste inscrit ?
			$id_cycliste = $enregistrement->id_cyclisme_athlete;

			$prep2->bindValue(1,$id_cycliste,PDO::PARAM_INT);
			$prep2->bindValue(2,$id_cal,PDO::PARAM_INT);
			$prep2->bindValue(3,$id_jeu,PDO::PARAM_INT);
			$prep2->execute();
			$enregistrement2 = $prep2->fetch();
			if($enregistrement2){
				$arr[$i][13] = 1;
				$arr[$i][14] = $enregistrement2->forme;
			}
			else{
				$arr[$i][13] = 0;
			}
			
			$i++;
		}
		
		return $arr;
	}
	
	function get_cyclistes_inscrits($id_jeu, $id_cal){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requête pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_athlete
				WHERE EXISTS (
					SELECT id_athlete
					FROM cyclisme_inscription_athlete
					WHERE (id_cyclisme_athlete=id_athlete AND (id_cal=0 OR id_cal=?) AND id_jeu=?)
					)
				ORDER BY id_cyclisme_equipe ASC, nom ASC";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
		$prep->execute();
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_cyclisme_athlete;
			$arr[$i][1] = $enregistrement->id_cyclisme_equipe;
			$arr[$i][2] = $enregistrement->nom;
			$arr[$i][3] = $enregistrement->prenom;
			$arr[$i][4] = $enregistrement->date_naissance;
			$arr[$i][5] = $enregistrement->note_paves;
			$arr[$i][7] = $enregistrement->note_vallons;
			$arr[$i][8] = $enregistrement->note_montagne;
			$arr[$i][9] = $enregistrement->note_sprint;
			$arr[$i][10] = $enregistrement->note_clm;
			$arr[$i][11] = $enregistrement->photo;
			$arr[$i][12] = $enregistrement->id_pays;
			$i++;
		}
		
		return $arr;
	}
?>