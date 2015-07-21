<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');

    function get_calendrier($id_cal){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM rugby_calendrier WHERE id=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	if( $enregistrement = $prep->fetch() )
	{
	    $arr['id'] = $enregistrement->id;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    
	    $arr['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr['date_debut']);
	    $arr['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr['date_debut_fr'] = $arr_date['date'];
	    $arr['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr['traite'] = $enregistrement->traite;
	    $arr['disponible'] = $enregistrement->disponible;
	    
	    $dh_debut = $arr['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr['commence'] = "1";
	    }
	    else{
		$arr['commence'] = "0";
		$arr['temps_restant'] = dateheure_sql_to_temps_restant($arr['date_debut']);
	    }
	}
	$db = null;
	return $arr;
    }
    
    function get_calendrier_jeu($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM rugby_calendrier WHERE id_jeu=? ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_cal'] = $enregistrement->id;
	    $arr[$id_cal]['nom_complet'] = $enregistrement->nom_complet;
	    $arr[$id_cal]['distance'] = $enregistrement->distance;
	    
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_debut']);
	    $arr[$id_cal]['date_debut_fr'] = $arr_date['date'];
	    $arr[$id_cal]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['heure_debut_fr'] = $arr_date['heure'];
	    
	    
	    $arr[$id_cal]['date_fin'] = $enregistrement->date_fin;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_fin']);
	    $arr[$id_cal]['date_fin_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_fin_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['date_fin_fr'] = $arr_date['date'];
	    $arr[$id_cal]['heure_fin_fr'] = $arr_date['heure'];
	    
	    $arr[$id_cal]['profil_clm'] = $enregistrement->profil_clm;
	    $arr[$id_cal]['profil_paves'] = $enregistrement->profil_paves;
	    $arr[$id_cal]['profil_montagne'] = $enregistrement->profil_montagne;
	    $arr[$id_cal]['profil_sprint'] = $enregistrement->profil_sprint;
	    $arr[$id_cal]['profil_vallons'] = $enregistrement->profil_vallons;
	    $arr[$id_cal]['profil_baroudeurs'] = $enregistrement->profil_baroudeurs;
	    $arr[$id_cal]['profil_equipe'] = $enregistrement->profil_equipe;
	    $arr[$id_cal]['profil_jeunes'] = $enregistrement->profil_jeunes;
	    $arr[$id_cal]['classement'] = $enregistrement->classement;
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    $arr[$id_cal]['image'] = $enregistrement->image;

	    $i++;
	    
	    $dh_debut = $arr[$id_cal]['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr[$id_cal]['commence'] = "1";
		$arr[$id_cal]['date_debut'] = $arr[$id_cal]['date_fin'];
	    }
	    else{
		$arr[$id_cal]['commence'] = "0";
	    }
	    
	    $dh_fin = $arr[$id_cal]['date_fin'];
	    $dh_fin = strtotime($dh_fin);
	    
	    if($now > $dh_fin){
		$arr[$id_cal]['termine'] = "1";
	    }
	    else{
		$arr[$id_cal]['termine'] = "0";
	    }
	}
	
	usort($arr, 'compare_date_debut');
	$db = null;
	return $arr;
    }
    
    function get_calendrier_jeu_avenir($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM rugby_calendrier WHERE id_jeu=? AND date_debut>NOW() AND disponible=1 ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_cal'] = $enregistrement->id;
	    $arr[$id_cal]['nom_complet'] = $enregistrement->nom_complet;
	    $arr[$id_cal]['distance'] = $enregistrement->distance;
	    
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_debut']);
	    $arr[$id_cal]['date_debut_fr'] = $arr_date['date'];
	    $arr[$id_cal]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['heure_debut_fr'] = $arr_date['heure'];
	    
	    
	    $arr[$id_cal]['date_fin'] = $enregistrement->date_fin;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_fin']);
	    $arr[$id_cal]['date_fin_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_fin_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['date_fin_fr'] = $arr_date['date'];
	    $arr[$id_cal]['heure_fin_fr'] = $arr_date['heure'];
	    
	    $arr[$id_cal]['profil_clm'] = $enregistrement->profil_clm;
	    $arr[$id_cal]['profil_paves'] = $enregistrement->profil_paves;
	    $arr[$id_cal]['profil_montagne'] = $enregistrement->profil_montagne;
	    $arr[$id_cal]['profil_sprint'] = $enregistrement->profil_sprint;
	    $arr[$id_cal]['profil_vallons'] = $enregistrement->profil_vallons;
	    $arr[$id_cal]['profil_baroudeurs'] = $enregistrement->profil_baroudeurs;
	    $arr[$id_cal]['profil_equipe'] = $enregistrement->profil_equipe;
	    $arr[$id_cal]['profil_jeunes'] = $enregistrement->profil_jeunes;
	    $arr[$id_cal]['classement'] = $enregistrement->classement;
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    $arr[$id_cal]['image'] = $enregistrement->image;

	    $i++;
	    
	    $dh_debut = $arr[$id_cal]['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr[$id_cal]['commence'] = "1";
	    }
	    else{
		$arr[$id_cal]['commence'] = "0";
	    }
	    
	    $dh_fin = $arr[$id_cal]['date_fin'];
	    $dh_fin = strtotime($dh_fin);
	    
	    if($now > $dh_fin){
		$arr[$id_cal]['termine'] = "1";
	    }
	    else{
		$arr[$id_cal]['termine'] = "0";
	    }
	}
	
	usort($arr, 'compare_date_debut');
	$db = null;
	return $arr;
    }
    
    function compare_date_debut($a, $b)
    {
      return strnatcmp($a['date_debut'], $b['date_debut']);
    }
?>