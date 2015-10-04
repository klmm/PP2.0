<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');
    
	function get_athletes_tab_id($chaine_id){
	    // On �tablit la connexion avec la base de donn�es
	    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	    $bdd = new Connexion();
	    $db = $bdd->getDB();

	    if ($chaine_id != ''){
		$chaine_id = str_replace(";",",",$chaine_id);

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM ski_alpin_athlete WHERE id IN (" . $chaine_id . ")";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
	
		$prep->execute();

		while($enregistrement = $prep->fetch()){
		    $id_athlete = $enregistrement->id;
		    $arr[$id_athlete]['id_ski_alpin_athlete'] = $id_athlete;
		    $arr[$id_athlete]['nom'] = $enregistrement->nom;
		    $arr[$id_athlete]['prenom'] = $enregistrement->prenom;
		    $arr[$id_athlete]['date_naissance'] = $enregistrement->date_naissance;
		    $arr[$id_athlete]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_naissance']);
		    $arr[$id_athlete]['genre'] = $enregistrement->genre;
		    $arr[$id_athlete]['note_slalom'] = $enregistrement->note_slalom;
		    $arr[$id_athlete]['note_geant'] = $enregistrement->note_geant;
		    $arr[$id_athlete]['note_superg'] = $enregistrement->note_superg;
		    $arr[$id_athlete]['note_descente'] = $enregistrement->note_descente;
		    $arr[$id_athlete]['note_combine'] = $enregistrement->note_combine;
		    $arr[$id_athlete]['retraite'] = $enregistrement->retraite;
		    $arr[$id_athlete]['date_blessure'] = $enregistrement->date_blessure;
		    $arr[$id_athlete]['date_blessure_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_blessure']);
		    $id_pays = $enregistrement->id_pays;
		    $arr[$id_athlete]['id_pays'] = $id_pays;
		}

		$db = null;
		return $arr;
	    }
	    else{
		$db = null;
		return null;
	    }
	}
	
	function get_athletes_tous(){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM ski_alpin_athlete";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->execute();
		
		while($enregistrement = $prep->fetch()){
		    $id_athlete = $enregistrement->id;
		    $arr[$id_athlete]['id_ski_alpin_athlete'] = $id_athlete;
		    $arr[$id_athlete]['nom'] = $enregistrement->nom;
		    $arr[$id_athlete]['prenom'] = $enregistrement->prenom;
		    $arr[$id_athlete]['date_naissance'] = $enregistrement->date_naissance;
		    $arr[$id_athlete]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_naissance']);
		    $arr[$id_athlete]['genre'] = $enregistrement->genre;
		    $arr[$id_athlete]['note_slalom'] = $enregistrement->note_slalom;
		    $arr[$id_athlete]['note_geant'] = $enregistrement->note_geant;
		    $arr[$id_athlete]['note_superg'] = $enregistrement->note_superg;
		    $arr[$id_athlete]['note_descente'] = $enregistrement->note_descente;
		    $arr[$id_athlete]['note_combine'] = $enregistrement->note_combine;
		    $arr[$id_athlete]['retraite'] = $enregistrement->retraite;
		    $arr[$id_athlete]['date_blessure'] = $enregistrement->date_blessure;
		    $arr[$id_athlete]['date_blessure_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_blessure']);
		    $id_pays = $enregistrement->id_pays;
		    $arr[$id_athlete]['id_pays'] = $id_pays;
		}

		$db = null;
		return $arr;
	}
			
	function get_athletes_activite_genre($genre){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT * FROM ski_alpin_athlete WHERE genre=? AND retraite=0 AND (date_blessure IS NULL OR date_blessure<NOW())";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$genre,PDO::PARAM_STR);		
		$prep->execute();
		
		while($enregistrement = $prep->fetch()){
		    $id_athlete = $enregistrement->id;
		    $arr[$id_athlete]['id_ski_alpin_athlete'] = $id_athlete;
		    $arr[$id_athlete]['nom'] = $enregistrement->nom;
		    $arr[$id_athlete]['prenom'] = $enregistrement->prenom;
		    $arr[$id_athlete]['date_naissance'] = $enregistrement->date_naissance;
		    $arr[$id_athlete]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_naissance']);
		    $arr[$id_athlete]['genre'] = $enregistrement->genre;
		    $arr[$id_athlete]['note_slalom'] = $enregistrement->note_slalom;
		    $arr[$id_athlete]['note_geant'] = $enregistrement->note_geant;
		    $arr[$id_athlete]['note_superg'] = $enregistrement->note_superg;
		    $arr[$id_athlete]['note_descente'] = $enregistrement->note_descente;
		    $arr[$id_athlete]['note_combine'] = $enregistrement->note_combine;
		    $arr[$id_athlete]['retraite'] = $enregistrement->retraite;
		    $arr[$id_athlete]['date_blessure'] = $enregistrement->date_blessure;
		    $arr[$id_athlete]['date_blessure_fr'] = date_naissance_sql_to_fr($arr[$id_athlete]['date_blessure']);
		    $id_pays = $enregistrement->id_pays;
		    $arr[$id_athlete]['id_pays'] = $id_pays;
		}

		$db = null;
		return $arr;
	}
?>