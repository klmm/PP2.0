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
	    $arr['tour'] = $enregistrement->tour;
	    $arr['id_equipe1'] = $enregistrement->id_equipe1;
	    $arr['id_equipe2'] = $enregistrement->id_equipe2;
	    $arr['ville'] = $enregistrement->ville;
	    $arr['stade'] = $enregistrement->stade;
	    $arr['score1'] = $enregistrement->score1;
	    $arr['score2'] = $enregistrement->score2;
	    $arr['essais1'] = $enregistrement->essais1;
	    $arr['essais2'] = $enregistrement->essais2;
	    $arr['coefficient'] = $enregistrement->coefficient;
	    
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
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id;
	    $arr[$id_cal]['id'] = $enregistrement->id;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['tour'] = $enregistrement->tour;
	    $arr[$id_cal]['id_equipe1'] = $enregistrement->id_equipe1;
	    $arr[$id_cal]['id_equipe2'] = $enregistrement->id_equipe2;
	    $arr[$id_cal]['ville'] = $enregistrement->ville;
	    $arr[$id_cal]['stade'] = $enregistrement->stade;
	    $arr[$id_cal]['score1'] = $enregistrement->score1;
	    $arr[$id_cal]['score2'] = $enregistrement->score2;
	    $arr[$id_cal]['essais1'] = $enregistrement->essais1;
	    $arr[$id_cal]['essais2'] = $enregistrement->essais2;
	    $arr[$id_cal]['coefficient'] = $enregistrement->coefficient;
	    
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_debut']);
	    $arr[$id_cal]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['date_debut_fr'] = $arr_date['date'];
	    $arr[$id_cal]['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    
	    $dh_debut = $arr[$id_cal]['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr[$id_cal]['commence'] = "1";
	    }
	    else{
		$arr[$id_cal]['commence'] = "0";
		$arr[$id_cal]['temps_restant'] = dateheure_sql_to_temps_restant($arr[$id_cal]['date_debut']);
	    }
	}
	
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
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id;
	    $arr[$id_cal]['id'] = $enregistrement->id;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['tour'] = $enregistrement->tour;
	    $arr[$id_cal]['id_equipe1'] = $enregistrement->id_equipe1;
	    $arr[$id_cal]['id_equipe2'] = $enregistrement->id_equipe2;
	    $arr[$id_cal]['ville'] = $enregistrement->ville;
	    $arr[$id_cal]['stade'] = $enregistrement->stade;
	    $arr[$id_cal]['score1'] = $enregistrement->score1;
	    $arr[$id_cal]['score2'] = $enregistrement->score2;
	    $arr[$id_cal]['essais1'] = $enregistrement->essais1;
	    $arr[$id_cal]['essais2'] = $enregistrement->essais2;
	    $arr[$id_cal]['coefficient'] = $enregistrement->coefficient;
	    
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_debut']);
	    $arr[$id_cal]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['date_debut_fr'] = $arr_date['date'];
	    $arr[$id_cal]['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    
	    $dh_debut = $arr[$id_cal]['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr[$id_cal]['commence'] = "1";
	    }
	    else{
		$arr[$id_cal]['commence'] = "0";
		$arr[$id_cal]['temps_restant'] = dateheure_sql_to_temps_restant($arr[$id_cal]['date_debut']);
	    }
	}
	
	$db = null;
	return $arr;
    }
    
    function get_id_calendrier_actuel($ID_JEU){
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$now = time();
	$unix = mktime(date('H',$now),date('i',$now),date('s',$now),date('n',$now),date('j',$now),date('Y',$now));

	$date_seule = strftime('%Y-%m-%d', $unix);
	
	$sql = "SELECT * FROM rugby_calendrier WHERE id_jeu=? AND CAST(date_debut AS DATE)=? AND date_debut>NOW() ORDER BY date_debut ASC LIMIT 1";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	$prep->bindValue(2,$date_seule,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	$enregistrement = $prep->fetch();
		
	if( $enregistrement ){
	    $db = null;
	    return $enregistrement->id; // PROCHAIN PRONO DU JOUR
	}
	else{
	    $sql2 = "SELECT * FROM rugby_calendrier WHERE id_jeu=? AND CAST(date_debut AS DATE)=? AND date_debut<NOW() ORDER BY date_debut DESC LIMIT 1";
	    $prep2 = $db->prepare($sql2);
	    $prep2->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	    $prep2->bindValue(2,$date_seule,PDO::PARAM_STR);
	    $prep2->execute();
	    $prep2->setFetchMode(PDO::FETCH_OBJ);
	    $enregistrement2 = $prep2->fetch();

	    if( $enregistrement2 ){
		$db = null;
		return $enregistrement2->id; // DERNIER RESULTAT DU JOUE
	    }
	    else{
		$sql3 = "SELECT * FROM rugby_calendrier WHERE id_jeu=? AND date_debut>NOW() ORDER BY date_debut ASC LIMIT 1";
		$prep3 = $db->prepare($sql3);
		$prep3->bindValue(1,$ID_JEU,PDO::PARAM_INT);
		$prep3->execute();
		$prep3->setFetchMode(PDO::FETCH_OBJ);

		$enregistrement3 = $prep3->fetch();

		if( $enregistrement3 ){
		    $db = null;
		    return $enregistrement3->id; // PROCHAIN PRONO
		}
		else{
		    $sql4 = "SELECT * FROM rugby_calendrier WHERE id_jeu=? AND traite=1 ORDER BY date_debut DESC LIMIT 1";
		    $prep4 = $db->prepare($sql4);
		    $prep4->bindValue(1,$ID_JEU,PDO::PARAM_INT);
		    $prep4->execute();
		    $prep4->setFetchMode(PDO::FETCH_OBJ);

		    $enregistrement4 = $prep4->fetch();

		    if( $enregistrement4 ){
			$db = null;
			return $enregistrement4->id; // DERNIER RESULTAT (QUAND FINI)
		    }
		    else{
			$db = null;
			return 1;
		    }
		}
	    }
	}
    }
?>