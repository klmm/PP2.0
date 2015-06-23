<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');

    function get_calendrier($id_jeu, $id_cal){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=? AND id_cal=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	if( $enregistrement = $prep->fetch() )
	{
	    $arr['id_cyclisme_calendrier'] = $enregistrement->id_cyclisme_calendrier;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['id_cal'] = $enregistrement->id_cal;
	    $arr['nom_complet'] = $enregistrement->nom_complet;
	    $arr['distance'] = $enregistrement->distance;
	    
	    $arr['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr['date_debut']);
	    $arr['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr['date_debut_fr'] = $arr_date['date'];
	    $arr['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr['date_fin'] = $enregistrement->date_fin;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_fin']);
	    $arr['date_fin_fr_court'] = $arr_date['date_court'];
	    $arr['date_fin_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr['date_fin_fr'] = $arr_date['date'];
	    $arr['heure_fin_fr'] = $arr_date['heure'];
	    
	    $arr['profil_clm'] = $enregistrement->profil_clm;
	    $arr['profil_paves'] = $enregistrement->profil_paves;
	    $arr['profil_montagne'] = $enregistrement->profil_montagne;
	    $arr['profil_sprint'] = $enregistrement->profil_sprint;
	    $arr['profil_vallons'] = $enregistrement->profil_vallons;
	    $arr['profil_baroudeurs'] = $enregistrement->profil_baroudeurs;
	    $arr['profil_equipe'] = $enregistrement->profil_equipe;
	    $arr['profil_jeunes'] = $enregistrement->profil_jeunes;
	    $arr['classement'] = $enregistrement->classement;
	    $arr['traite'] = $enregistrement->traite;
	    $arr['disponible'] = $enregistrement->disponible;
	    $arr['image'] = $enregistrement->image;
	    
	    $dh_debut = $arr['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr['commence'] = "1";
	    }
	    else{
		$arr['commence'] = "0";
	    }
	    
	    $dh_fin = $arr['date_fin'];
	    $dh_fin = strtotime($dh_fin);
	    
	    if($now > $dh_fin){
		$arr['termine'] = "1";
	    }
	    else{
		$arr['termine'] = "0";
	    }
	}

	return $arr;
    }
    
    function get_calendrier_jeu($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=? ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_cal;
	    $arr[$id_cal]['id_cyclisme_calendrier'] = $enregistrement->id_cyclisme_calendrier;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_cal'] = $enregistrement->id_cal;
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
	return $arr;
    }
    
    function get_calendrier_jeu_avenir($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=? AND date_debut>NOW() AND disponible=1 ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_cal;
	    $arr[$id_cal]['id_cyclisme_calendrier'] = $enregistrement->id_cyclisme_calendrier;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_cal'] = $enregistrement->id_cal;
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
	return $arr;
    }
    
    function compare_date_debut($a, $b)
    {
      return strnatcmp($a['date_debut'], $b['date_debut']);
    }
    
    function get_id_calendrier_actuel($ID_JEU){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$now = time();
	$unix = mktime(date('H',$now),date('i',$now),date('s',$now),date('n',$now),date('j',$now),date('Y',$now));

	$date_heure = strftime('%Y-%m-%d %H:%M:%S', $unix);
	$date_seule = strftime('%Y-%m-%d', $unix);
	
	//On fait la requete sur le login
	$sql = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=? AND CAST(date_debut AS DATE)=? ORDER BY date_debut ASC LIMIT 1";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	$prep->bindValue(2,$date_seule,PDO::PARAM_STR);
	$prep->bindValue(3,$date_seule,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	$enregistrement = $prep->fetch();

	
	if( $enregistrement ){
	    return $enregistrement->id_cal; // ETAPE DU JOUR
	}
	else{
	    
	    $sql2 = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=? AND date_debut>? AND disponible=1 ORDER BY date_debut ASC LIMIT 1";
	    $prep2 = $db->prepare($sql2);
	    $prep2->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	    $prep2->bindValue(2,$date_heure,PDO::PARAM_STR);
	    $prep2->execute();
	    $prep2->setFetchMode(PDO::FETCH_OBJ);
	    $enregistrement2 = $prep2->fetch();

	    if( $enregistrement2 ){
		return $enregistrement2->id_cal; // PROCHAINE DISPO
	    }
	    else{
		$sql3 = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=? AND traite=1 ORDER BY date_fin DESC LIMIT 1";
		$prep3 = $db->prepare($sql3);
		$prep3->bindValue(1,$ID_JEU,PDO::PARAM_INT);
		$prep3->execute();
		$prep3->setFetchMode(PDO::FETCH_OBJ);

		$enregistrement3 = $prep3->fetch();

		if( $enregistrement3 ){
		    return $enregistrement3->id_cal; // DERNIERE TRAITEE
		}
		else{
		    return 1;
		}
	    }
	}
    }

?>