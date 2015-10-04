<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');

    function get_calendrier($id_jeu, $id_cal){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM ski_alpin_calendrier WHERE id_jeu=? AND id_cal=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	if( $enregistrement = $prep->fetch() )
	{
	    $arr['id_ski_alpin_calendrier'] = $enregistrement->id_ski_alpin_calendrier;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['id_cal'] = $enregistrement->id_cal;
	    
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
	    
	    $arr['classement'] = $enregistrement->classement;
	    $arr['traite'] = $enregistrement->traite;
	    $arr['disponible'] = $enregistrement->disponible;
	    $arr['annule'] = $enregistrement->annule;
	    
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
	$sql = "SELECT * FROM ski_alpin_calendrier WHERE id_jeu=? ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $i++;
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
	$sql = "SELECT * FROM ski_alpin_calendrier WHERE id_jeu=? AND date_debut>NOW() AND disponible=1 ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    
	}
	
	usort($arr, 'compare_date_debut');
	$db = null;
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
	$sql4 = "SELECT * FROM ski_alpin_calendrier WHERE id_jeu=? AND CAST(date_debut AS DATE)=? AND CAST(date_fin AS DATE)=? AND date_debut<NOW()";
	$prep4 = $db->prepare($sql4);
	$prep4->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	$prep4->bindValue(2,$date_seule,PDO::PARAM_STR);
	$prep4->bindValue(3,$date_seule,PDO::PARAM_STR);
	$prep4->execute();
	$prep4->setFetchMode(PDO::FETCH_OBJ);

	$enregistrement4 = $prep4->fetch();
	
	if( $enregistrement4 ){
	    $db = null;
	    return $enregistrement4->id_cal; // ETAPE EN COURS
	}
	else{
	    $sql = "SELECT * FROM ski_alpin_calendrier WHERE id_jeu=? AND CAST(date_debut AS DATE)=? ORDER BY date_debut ASC LIMIT 1";
	    $prep = $db->prepare($sql);
	    $prep->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	    $prep->bindValue(2,$date_seule,PDO::PARAM_STR);
	    $prep->execute();
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    
	    $enregistrement = $prep->fetch();
	
	    if( $enregistrement ){
		$db = null;
		return $enregistrement->id_cal; // ETAPE DU JOUR
	    }
	    else{
		$sql2 = "SELECT * FROM ski_alpin_calendrier WHERE id_jeu=? AND date_debut>? AND disponible=1 ORDER BY date_debut ASC LIMIT 1";
		$prep2 = $db->prepare($sql2);
		$prep2->bindValue(1,$ID_JEU,PDO::PARAM_INT);
		$prep2->bindValue(2,$date_heure,PDO::PARAM_STR);
		$prep2->execute();
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		$enregistrement2 = $prep2->fetch();

		if( $enregistrement2 ){
		    $db = null;
		    return $enregistrement2->id_cal; // PROCHAINE DISPO
		}
		else{
		    $sql3 = "SELECT * FROM ski_alpin_calendrier WHERE id_jeu=? AND traite=1 ORDER BY date_fin DESC LIMIT 1";
		    $prep3 = $db->prepare($sql3);
		    $prep3->bindValue(1,$ID_JEU,PDO::PARAM_INT);
		    $prep3->execute();
		    $prep3->setFetchMode(PDO::FETCH_OBJ);

		    $enregistrement3 = $prep3->fetch();

		    if( $enregistrement3 ){
			$db = null;
			return $enregistrement3->id_cal; // DERNIERE TRAITEE
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