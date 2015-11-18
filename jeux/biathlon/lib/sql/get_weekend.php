<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');

    function get_weekend($id_weekend){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM biathlon_weekend WHERE id=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_weekend,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	if( $enregistrement = $prep->fetch() )
	{
	    $arr['id'] = $enregistrement->id;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['lieu'] = $enregistrement->lieu;
	    $arr['id_pays'] = $enregistrement->id_pays;
	    $arr['competition'] = $enregistrement->competition;
	    
	    $arr['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr['date_debut']);
	    $arr['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr['date_debut_fr'] = $arr_date['date'];
	    
	    $arr['date_fin'] = $enregistrement->date_fin;
	    $arr_date = dateheure_sql_to_fr($arr['date_fin']);
	    $arr['date_fin_fr_court'] = $arr_date['date_court'];
	    $arr['date_fin_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr['date_fin_fr'] = $arr_date['date'];
	}
	$db = null;
	return $arr;
    }
    
    function get_weekend_jeu($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM biathlon_weekend WHERE id_jeu=? ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id = $enregistrement->id;
	    $arr[$id]['id'] = $enregistrement->id;
	    $arr[$id]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id]['lieu'] = $enregistrement->lieu;
	    $arr[$id]['id_pays'] = $enregistrement->id_pays;
	    $arr[$id]['competition'] = $enregistrement->competition;
	    
	    $arr[$id]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id]['date_debut']);
	    $arr[$id]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id]['date_debut_fr'] = $arr_date['date'];
	    
	    $arr[$id]['date_fin'] = $enregistrement->date_fin;
	    $arr_date = dateheure_sql_to_fr($arr[$id]['date_fin']);
	    $arr[$id]['date_fin_fr_court'] = $arr_date['date_court'];
	    $arr[$id]['date_fin_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id]['date_fin_fr'] = $arr_date['date'];
	}
	
	$db = null;
	return $arr;
    }
?>