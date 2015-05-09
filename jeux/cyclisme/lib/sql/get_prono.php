<?php

    function get_pronos_joueurs_jeu($id_jeu,$joueur){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM cyclisme_prono WHERE id_jeu=? AND joueur=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$joueur,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_calendrier;
	    $arr[$id_cal]['id_cyclisme_prono'] = $enregistrement->id_cyclisme_prono;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['joueur'] = $enregistrement->joueur;
	    $arr[$id_cal]['id_cal'] = $enregistrement->id_calendrier;
	    $arr[$id_cal]['prono'] = $enregistrement->prono;
	    $arr[$id_cal]['points_prono'] = $enregistrement->points_prono;
	    $arr[$id_cal]['score_base'] = $enregistrement->score_base;
	    $arr[$id_cal]['bonus_nombre'] = $enregistrement->bonus_nombre;
	    $arr[$id_cal]['bonus_risque'] = $enregistrement->bonus_risque;
	    $arr[$id_cal]['score_total'] = $enregistrement->score_total;
	    $arr[$id_cal]['classement'] = $enregistrement->classement;

	    $i++;
	}

	return $arr;
    }
    
    function get_prono($id_jeu,$id_cal,$joueur){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM cyclisme_prono WHERE id_jeu=? AND id_calendier AND joueur=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$joueur,PDO::PARAM_INT);
	$prep->bindValue(3,$joueur,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$enregistrement = $prep->fetch();
	if($enregistrement)
	{
	    $id_cal = $enregistrement->id_calendrier;
	    $arr['id_cyclisme_prono'] = $enregistrement->id_cyclisme_prono;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['joueur'] = $enregistrement->joueur;
	    $arr['id_cal'] = $enregistrement->id_calendrier;
	    $arr['prono'] = $enregistrement->prono;
	    $arr['points_prono'] = $enregistrement->points_prono;
	    $arr['score_base'] = $enregistrement->score_base;
	    $arr['bonus_nombre'] = $enregistrement->bonus_nombre;
	    $arr['bonus_risque'] = $enregistrement->bonus_risque;
	    $arr['score_total'] = $enregistrement->score_total;
	    $arr['classement'] = $enregistrement->classement;

	    $i++;
	}

	return $arr;
    }
?>