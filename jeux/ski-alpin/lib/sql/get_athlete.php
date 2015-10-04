<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');
    
	function get_athletes_jeu_tab_id($id_jeu, $id_cal, $chaine_id){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		if ($chaine_id != ''){
		    $chaine_id = str_replace(";",",",$chaine_id);

		    //On pr�pare la requ�te pour aller chercher les articles
		    $sql = "SELECT *
				    FROM ski_alpin_athlete
				    WHERE id_ski_alpin_athlete IN (" . $chaine_id . ")";
		    $prep = $db->prepare($sql);
		    $prep->setFetchMode(PDO::FETCH_OBJ);

		    //On met les articles dans le tableau	
		    $prep->execute();
		    		
		    while($enregistrement = $prep->fetch()){
			$id_athlete = $enregistrement->id_ski_alpin_athlete;
			$arr[$id_athlete]['id_ski_alpin_athlete'] = $id_athlete;
			$arr[$id_athlete]['id_equipe_actuelle'] = $enregistrement->id_ski_alpin_equipe;
			$arr[$id_athlete]['nom'] = $enregistrement->nom;
			$arr[$id_athlete]['prenom'] = $enregistrement->prenom;
			$arr[$id_athlete]['date_naissance'] = $enregistrement->date_naissance;
			$arr[$id_athlete]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_naissance']);
			$arr[$id_athlete]['note_paves'] = $enregistrement->note_paves;
			$arr[$id_athlete]['note_vallons'] = $enregistrement->note_vallons;
			$arr[$id_athlete]['note_montagne'] = $enregistrement->note_montagne;
			$arr[$id_athlete]['note_sprint'] = $enregistrement->note_sprint;
			$arr[$id_athlete]['note_clm'] = $enregistrement->note_clm;
			$arr[$id_athlete]['photo'] = $enregistrement->photo;
			$id_pays = $enregistrement->id_pays;
			$arr[$id_athlete]['id_pays'] = $id_pays;
		    }
		    
		    $sql2 = "SELECT *
				    FROM ski_alpin_inscription_athlete
				    WHERE (id_cal=? or id_cal=0) AND id_jeu=? AND id_athlete IN (" . $chaine_id . ")";
		    
		    $prep2 = $db->prepare($sql2);
		    $prep2->setFetchMode(PDO::FETCH_OBJ);
		    $prep2->bindValue(1,$id_cal,PDO::PARAM_INT);
		    $prep2->bindValue(2,$id_jeu,PDO::PARAM_INT);

		    $prep2->execute();
			
		    
		    while($enregistrement2 = $prep2->fetch()){
			$id_athlete = $enregistrement2->id_athlete;
			$id_equipe_actuelle = $enregistrement2->id_equipe;
			$arr[$id_athlete]['id_equipe_course'] = $id_equipe_actuelle;
			$arr[$id_athlete]['abandon'] = $enregistrement2->abandon;
			$arr[$id_athlete]['inscrit'] = 1;
			$arr[$id_athlete]['forme'] = $enregistrement2->forme;
		    }
		    $db = null;
		return $arr;
	    }
	    else{
		$db = null;
		return null;
	    }
	}
	
	
	function get_athletes_jeu($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();


		$sql = "SELECT *
				FROM ski_alpin_inscription_athlete
				WHERE (abandon=0 AND (id_cal=? or id_cal=0) AND id_jeu=?)";
					
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
		
		//On pr�pare la requ�te pour aller chercher les articles
		$sql2 = "SELECT *
			    FROM ski_alpin_athlete
			    WHERE EXISTS (
					SELECT id_athlete
					FROM ski_alpin_inscription_athlete
					WHERE (abandon=0 AND id_ski_alpin_athlete=id_athlete AND (id_cal=0 OR id_cal=?) AND id_jeu=?)
				    )";
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		$prep2->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep2->bindValue(2,$id_jeu,PDO::PARAM_INT);
		
		$prep->execute();
		
		while($enregistrement = $prep->fetch()){
		    $id_athlete = $enregistrement->id_athlete;	    
 
		    $id_equipe_actuelle = $enregistrement->id_equipe;
		    $arr[$id_athlete]['id_equipe_course'] = $id_equipe_actuelle;
		    $arr[$id_athlete]['abandon'] = $enregistrement->abandon;
		    $arr[$id_athlete]['inscrit'] = 1;
		    $arr[$id_athlete]['forme'] = $enregistrement->forme;
		}
		
		$prep2->execute();
		
		while($enregistrement2 = $prep2->fetch()){
		    $id_athlete = $enregistrement2->id_ski_alpin_athlete;
		    
		    $arr[$id_athlete]['id_ski_alpin_athlete'] = $enregistrement2->id_ski_alpin_athlete;
		    $arr[$id_athlete]['id_equipe_actuelle'] = $enregistrement2->id_ski_alpin_equipe;
		    $arr[$id_athlete]['nom'] = $enregistrement2->nom;
		    $arr[$id_athlete]['prenom'] = $enregistrement2->prenom;
		    $arr[$id_athlete]['date_naissance'] = $enregistrement2->date_naissance;
		    $arr[$id_athlete]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_naissance']);
		    $arr[$id_athlete]['note_paves'] = $enregistrement2->note_paves;
		    $arr[$id_athlete]['note_vallons'] = $enregistrement2->note_vallons;
		    $arr[$id_athlete]['note_montagne'] = $enregistrement2->note_montagne;
		    $arr[$id_athlete]['note_sprint'] = $enregistrement2->note_sprint;
		    $arr[$id_athlete]['note_clm'] = $enregistrement2->note_clm;
		    $arr[$id_athlete]['photo'] = $enregistrement2->photo;
		    $id_pays = $enregistrement2->id_pays;
		    $arr[$id_athlete]['id_pays'] = $id_pays;
		    $arr[$id_athlete]['note_baroudeur'] = $enregistrement2->note_baroudeur;
		    
		    $arr[$id_athlete]['pos_prono'] = 0;
		}
		$db = null;
		return $arr;
	}
	
	function get_athletes_equipe($ID_JEU, $ID_CAL, $id_equipe){
	    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	    $bdd = new Connexion();
	    $db = $bdd->getDB();

	    //On pr�pare la requ�te pour aller chercher les articles
	    $sql = "SELECT *
			    FROM ski_alpin_inscription_athlete
			    WHERE id_jeu=? AND (id_cal=0 OR id_cal=?) AND id_equipe=? AND abandon=0";

	    $prep = $db->prepare($sql);
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    $prep->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	    $prep->bindValue(2,$ID_CAL,PDO::PARAM_INT);
	    $prep->bindValue(3,$id_equipe,PDO::PARAM_INT);
	    $prep->execute();

	    $sql2 = "SELECT *
			    FROM ski_alpin_athlete
			    WHERE id_ski_alpin_athlete=?";

	    $prep2 = $db->prepare($sql2);
	    $prep2->setFetchMode(PDO::FETCH_OBJ);


	    // Parcours des athletes dont l'�quipe est inscrite
	    while( $enregistrement = $prep->fetch() )
	    {
		    $id_athlete = $enregistrement->id_athlete;

		    $prep2->bindValue(1,$id_athlete,PDO::PARAM_INT);
		    $prep2->execute();
		    $enregistrement2 = $prep2->fetch();
		    
		    $arr[$id_athlete]['forme'] = $enregistrement->forme;

		if($enregistrement2){
		    $arr[$id_athlete]['id_ski_alpin_athlete'] = $enregistrement2->id_ski_alpin_athlete;
		    $arr[$id_athlete]['id_ski_alpin_equipe'] = $enregistrement2->id_ski_alpin_equipe;
		    $arr[$id_athlete]['nom'] = $enregistrement2->nom;
		    $arr[$id_athlete]['prenom'] = $enregistrement2->prenom;
		    $arr[$id_athlete]['date_naissance'] = $enregistrement2->date_naissance;
		    $arr[$id_athlete]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_naissance']);
		    $arr[$id_athlete]['note_paves'] = $enregistrement2->note_paves;
		    $arr[$id_athlete]['note_vallons'] = $enregistrement2->note_vallons;
		    $arr[$id_athlete]['note_montagne'] = $enregistrement2->note_montagne;
		    $arr[$id_athlete]['note_sprint'] = $enregistrement2->note_sprint;
		    $arr[$id_athlete]['note_clm'] = $enregistrement2->note_clm;
		    $arr[$id_athlete]['photo'] = $enregistrement2->photo;
		    $arr[$id_athlete]['id_pays'] = $enregistrement2->id_pays;
		    $arr[$id_athlete]['note_baroudeur'] = $enregistrement2->note_baroudeur;
		}
	    }
	    $db = null;
	    return $arr;
	}
?>