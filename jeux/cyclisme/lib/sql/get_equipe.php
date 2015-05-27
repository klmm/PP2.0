<?php

	function get_equipes_non_inscrites($id_jeu, $id_cal, $annee){
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
					) AND saison=" . $annee . " 
				ORDER BY nom_complet ASC";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->execute();
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$id_equipe = $enregistrement->id_cyclisme_equipe;
			$arr[$id_equipe]['id_cyclisme_equipe'] = $id_equipe;
			$arr[$id_equipe]['niveau'] = $enregistrement->niveau;
			$arr[$id_equipe]['nom_complet'] = $enregistrement->nom_complet;
			$arr[$id_equipe]['nom_courant'] = $enregistrement->nom_courant;
			$arr[$id_equipe]['nom_court'] = $enregistrement->nom_court;
			$arr[$id_equipe]['photo'] = $enregistrement->photo;
			$arr[$id_equipe]['saison'] = $enregistrement->saison;
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
		    $id_equipe = $enregistrement->id_cyclisme_equipe;
		    $arr[$id_equipe]['id_cyclisme_equipe'] = $id_equipe;
		    $arr[$id_equipe]['niveau'] = $enregistrement->niveau;
		    $arr[$id_equipe]['nom_complet'] = $enregistrement->nom_complet;
		    $arr[$id_equipe]['nom_courant'] = $enregistrement->nom_courant;
		    $arr[$id_equipe]['nom_court'] = $enregistrement->nom_court;
		    $arr[$id_equipe]['photo'] = $enregistrement->photo;
		    $arr[$id_equipe]['saison'] = $enregistrement->saison;
		    $arr[$id_equipe]['pos_prono'] = 0;
		    $arr[$id_equipe]['nb_coureurs'] = 0;
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
	
	function get_equipes_tab_id($tab_id){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_equipe
				WHERE id_cyclisme_equipe=?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		
		$nb_equipes = sizeof($tab_id);

		for( $i=0; $i<$nb_equipes; $i++ )
		{   
		   
			$id_equipe = $tab_id[$i];
			$prep->bindValue(1,$id_equipe,PDO::PARAM_INT);
			$prep->execute();

			$enregistrement = $prep->fetch();
			
			if($enregistrement){
			    $arr[$id_equipe]['id_cyclisme_equipe'] = $id_equipe;
			    $arr[$id_equipe]['niveau'] = $enregistrement->niveau;
			    $arr[$id_equipe]['nom_complet'] = $enregistrement->nom_complet;
			    $arr[$id_equipe]['nom_courant'] = $enregistrement->nom_courant;
			    $arr[$id_equipe]['nom_court'] = $enregistrement->nom_court;
			    $arr[$id_equipe]['photo'] = $enregistrement->photo;
			    $arr[$id_equipe]['saison'] = $enregistrement->saison;
			}
		}
		
		return $arr;
	}
?>