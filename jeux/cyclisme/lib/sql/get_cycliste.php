<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');
    
	function get_cyclistes_inscriptions($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
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
				WHERE id_athlete=? AND (id_cal=? OR id_cal=0) AND id_jeu=?";
					
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		
		
		// Parcours des cyclistes dont l'�quipe est inscrite
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$id_cycliste = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_athlete'] = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_equipe'] = $enregistrement->id_cyclisme_equipe;
			$arr[$id_cycliste]['nom'] = $enregistrement->nom;
			$arr[$id_cycliste]['prenom'] = $enregistrement->prenom;
			$arr[$id_cycliste]['date_naissance'] = $enregistrement->date_naissance;
			$arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
			$arr[$id_cycliste]['note_paves'] = $enregistrement->note_paves;
			$arr[$id_cycliste]['note_vallons'] = $enregistrement->note_vallons;
			$arr[$id_cycliste]['note_montagne'] = $enregistrement->note_montagne;
			$arr[$id_cycliste]['note_sprint'] = $enregistrement->note_sprint;
			$arr[$id_cycliste]['note_clm'] = $enregistrement->note_clm;
			$arr[$id_cycliste]['photo'] = $enregistrement->photo;
			$arr[$id_cycliste]['id_pays'] = $enregistrement->id_pays;
			$arr[$id_cycliste]['note_baroudeur'] = $enregistrement->note_baroudeur;
			
			//Cycliste inscrit ?
			$prep2->bindValue(1,$id_cycliste,PDO::PARAM_INT);
			$prep2->bindValue(2,$id_cal,PDO::PARAM_INT);
			$prep2->bindValue(3,$id_jeu,PDO::PARAM_INT);
			$prep2->execute();
			$enregistrement2 = $prep2->fetch();
			if($enregistrement2){
				$arr[$id_cycliste]['inscrit'] = 1;
				$arr[$id_cycliste]['forme'] = $enregistrement2->forme;
				$arr[$id_cycliste]['id_equipe_course'] = $enregistrement2->id_equipe;
				$arr[$id_cycliste]['abandon'] = $enregistrement2->abandon;
			}
			else{
				$arr[$id_cycliste]['inscrit'] = 0;
			}
			
			$i++;
		}
		$db = null;
		return $arr;
	}
	
	function get_cyclistes_inscrits($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
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
		
		$sql2 = "SELECT *
				FROM cyclisme_inscription_athlete
				WHERE id_athlete=? AND (id_cal=? or id_cal=0) AND id_jeu=?";
					
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$id_cycliste = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_athlete'] = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_equipe'] = $enregistrement->id_cyclisme_equipe;
			$arr[$id_cycliste]['nom'] = $enregistrement->nom;
			$arr[$id_cycliste]['prenom'] = $enregistrement->prenom;
			$arr[$id_cycliste]['date_naissance'] = $enregistrement->date_naissance;
			$arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
			$arr[$id_cycliste]['note_paves'] = $enregistrement->note_paves;
			$arr[$id_cycliste]['note_vallons'] = $enregistrement->note_vallons;
			$arr[$id_cycliste]['note_montagne'] = $enregistrement->note_montagne;
			$arr[$id_cycliste]['note_sprint'] = $enregistrement->note_sprint;
			$arr[$id_cycliste]['note_clm'] = $enregistrement->note_clm;
			$arr[$id_cycliste]['photo'] = $enregistrement->photo;
			$arr[$id_cycliste]['id_pays'] = $enregistrement->id_pays;
			$arr[$id_cycliste]['note_baroudeur'] = $enregistrement->note_baroudeur;
			$i++;
			
			$prep2->bindValue(1,$id_cycliste,PDO::PARAM_INT);
			$prep2->bindValue(2,$id_cal,PDO::PARAM_INT);
			$prep2->bindValue(3,$id_jeu,PDO::PARAM_INT);
			$prep2->execute();
			$enregistrement2 = $prep2->fetch();
			
			$arr[$id_cycliste]['inscrit'] = 1;
			$arr[$id_cycliste]['forme'] = $enregistrement2->forme;
			$arr[$id_cycliste]['id_equipe_course'] = $enregistrement2->id_equipe;
			$arr[$id_cycliste]['abandon'] = $enregistrement2->abandon;
		}
		$db = null;
		return $arr;
	}
	
	function get_cyclistes_jeu_tab_id($id_jeu, $id_cal, $chaine_id){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		if ($chaine_id != ''){
		    $chaine_id = str_replace(";",",",$chaine_id);

		    //On pr�pare la requ�te pour aller chercher les articles
		    $sql = "SELECT *
				    FROM cyclisme_athlete
				    WHERE id_cyclisme_athlete IN (" . $chaine_id . ")";
		    $prep = $db->prepare($sql);
		    $prep->setFetchMode(PDO::FETCH_OBJ);

		    //On met les articles dans le tableau	
		    $prep->execute();
		    		
		    while($enregistrement = $prep->fetch()){
			$id_cycliste = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_athlete'] = $id_cycliste;
			$arr[$id_cycliste]['id_equipe_actuelle'] = $enregistrement->id_cyclisme_equipe;
			$arr[$id_cycliste]['nom'] = $enregistrement->nom;
			$arr[$id_cycliste]['prenom'] = $enregistrement->prenom;
			$arr[$id_cycliste]['date_naissance'] = $enregistrement->date_naissance;
			$arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
			$arr[$id_cycliste]['note_paves'] = $enregistrement->note_paves;
			$arr[$id_cycliste]['note_vallons'] = $enregistrement->note_vallons;
			$arr[$id_cycliste]['note_montagne'] = $enregistrement->note_montagne;
			$arr[$id_cycliste]['note_sprint'] = $enregistrement->note_sprint;
			$arr[$id_cycliste]['note_clm'] = $enregistrement->note_clm;
			$arr[$id_cycliste]['photo'] = $enregistrement->photo;
			$id_pays = $enregistrement->id_pays;
			$arr[$id_cycliste]['id_pays'] = $id_pays;
			$arr[$id_cycliste]['note_baroudeur'] = $enregistrement->note_baroudeur;
		    }
		    
		    $sql2 = "SELECT *
				    FROM cyclisme_inscription_athlete
				    WHERE (id_cal=? or id_cal=0) AND id_jeu=? AND id_athlete IN (" . $chaine_id . ")";
		    
		    $prep2 = $db->prepare($sql2);
		    $prep2->setFetchMode(PDO::FETCH_OBJ);
		    $prep2->bindValue(1,$id_cal,PDO::PARAM_INT);
		    $prep2->bindValue(2,$id_jeu,PDO::PARAM_INT);

		    $prep2->execute();
			
		    
		    while($enregistrement2 = $prep2->fetch()){
			$id_cycliste = $enregistrement2->id_athlete;
			$id_equipe_actuelle = $enregistrement2->id_equipe;
			$arr[$id_cycliste]['id_equipe_course'] = $id_equipe_actuelle;
			$arr[$id_cycliste]['abandon'] = $enregistrement2->abandon;
			$arr[$id_cycliste]['inscrit'] = 1;
			$arr[$id_cycliste]['forme'] = $enregistrement2->forme;
		    }
		    $db = null;
		return $arr;
	    }
	    else{
		$db = null;
		return null;
	    }
	}
	
	
	function get_cyclistes_jeu($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();


		$sql = "SELECT *
				FROM cyclisme_inscription_athlete
				WHERE (abandon=0 AND (id_cal=? or id_cal=0) AND id_jeu=?)";
					
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
		
		//On pr�pare la requ�te pour aller chercher les articles
		$sql2 = "SELECT *
			    FROM cyclisme_athlete
			    WHERE EXISTS (
					SELECT id_athlete
					FROM cyclisme_inscription_athlete
					WHERE (abandon=0 AND id_cyclisme_athlete=id_athlete AND (id_cal=0 OR id_cal=?) AND id_jeu=?)
				    )";
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		$prep2->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep2->bindValue(2,$id_jeu,PDO::PARAM_INT);
		
		$prep->execute();
		
		while($enregistrement = $prep->fetch()){
		    $id_cycliste = $enregistrement->id_athlete;	    
 
		    $id_equipe_actuelle = $enregistrement->id_equipe;
		    $arr[$id_cycliste]['id_equipe_course'] = $id_equipe_actuelle;
		    $arr[$id_cycliste]['abandon'] = $enregistrement->abandon;
		    $arr[$id_cycliste]['inscrit'] = 1;
		    $arr[$id_cycliste]['forme'] = $enregistrement->forme;
		}
		
		$prep2->execute();
		
		while($enregistrement2 = $prep2->fetch()){
		    $id_cycliste = $enregistrement2->id_cyclisme_athlete;
		    
		    $arr[$id_cycliste]['id_cyclisme_athlete'] = $enregistrement2->id_cyclisme_athlete;
		    $arr[$id_cycliste]['id_equipe_actuelle'] = $enregistrement2->id_cyclisme_equipe;
		    $arr[$id_cycliste]['nom'] = $enregistrement2->nom;
		    $arr[$id_cycliste]['prenom'] = $enregistrement2->prenom;
		    $arr[$id_cycliste]['date_naissance'] = $enregistrement2->date_naissance;
		    $arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
		    $arr[$id_cycliste]['note_paves'] = $enregistrement2->note_paves;
		    $arr[$id_cycliste]['note_vallons'] = $enregistrement2->note_vallons;
		    $arr[$id_cycliste]['note_montagne'] = $enregistrement2->note_montagne;
		    $arr[$id_cycliste]['note_sprint'] = $enregistrement2->note_sprint;
		    $arr[$id_cycliste]['note_clm'] = $enregistrement2->note_clm;
		    $arr[$id_cycliste]['photo'] = $enregistrement2->photo;
		    $id_pays = $enregistrement2->id_pays;
		    $arr[$id_cycliste]['id_pays'] = $id_pays;
		    $arr[$id_cycliste]['note_baroudeur'] = $enregistrement2->note_baroudeur;
		    
		    $arr[$id_cycliste]['pos_prono'] = 0;
		}
		$db = null;
		return $arr;
	}
	
	function get_cyclistes_equipe($ID_JEU, $ID_CAL, $id_equipe){
	    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	    $bdd = new Connexion();
	    $db = $bdd->getDB();

	    //On pr�pare la requ�te pour aller chercher les articles
	    $sql = "SELECT *
			    FROM cyclisme_inscription_athlete
			    WHERE id_jeu=? AND (id_cal=0 OR id_cal=?) AND id_equipe=? AND abandon=0";

	    $prep = $db->prepare($sql);
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    $prep->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	    $prep->bindValue(2,$ID_CAL,PDO::PARAM_INT);
	    $prep->bindValue(3,$id_equipe,PDO::PARAM_INT);
	    $prep->execute();

	    $sql2 = "SELECT *
			    FROM cyclisme_athlete
			    WHERE id_cyclisme_athlete=?";

	    $prep2 = $db->prepare($sql2);
	    $prep2->setFetchMode(PDO::FETCH_OBJ);


	    // Parcours des cyclistes dont l'�quipe est inscrite
	    while( $enregistrement = $prep->fetch() )
	    {
		    $id_cycliste = $enregistrement->id_athlete;

		    $prep2->bindValue(1,$id_cycliste,PDO::PARAM_INT);
		    $prep2->execute();
		    $enregistrement2 = $prep2->fetch();
		    
		    $arr[$id_cycliste]['forme'] = $enregistrement->forme;

		if($enregistrement2){
		    $arr[$id_cycliste]['id_cyclisme_athlete'] = $enregistrement2->id_cyclisme_athlete;
		    $arr[$id_cycliste]['id_cyclisme_equipe'] = $enregistrement2->id_cyclisme_equipe;
		    $arr[$id_cycliste]['nom'] = $enregistrement2->nom;
		    $arr[$id_cycliste]['prenom'] = $enregistrement2->prenom;
		    $arr[$id_cycliste]['date_naissance'] = $enregistrement2->date_naissance;
		    $arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
		    $arr[$id_cycliste]['note_paves'] = $enregistrement2->note_paves;
		    $arr[$id_cycliste]['note_vallons'] = $enregistrement2->note_vallons;
		    $arr[$id_cycliste]['note_montagne'] = $enregistrement2->note_montagne;
		    $arr[$id_cycliste]['note_sprint'] = $enregistrement2->note_sprint;
		    $arr[$id_cycliste]['note_clm'] = $enregistrement2->note_clm;
		    $arr[$id_cycliste]['photo'] = $enregistrement2->photo;
		    $arr[$id_cycliste]['id_pays'] = $enregistrement2->id_pays;
		    $arr[$id_cycliste]['note_baroudeur'] = $enregistrement2->note_baroudeur;
		}
	    }
	    $db = null;
	    return $arr;
	}
?>