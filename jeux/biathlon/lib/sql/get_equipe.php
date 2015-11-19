<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');
    
    function get_equipes_tous(){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM biathlon_equipe";
	$prep = $db->prepare($sql);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	while($enregistrement = $prep->fetch()){
	    $id_equipe = $enregistrement->id;
	    $arr[$id_equipe]['id_biathlon_equipe'] = $id_equipe;
	    $arr[$id_equipe]['nom'] = $enregistrement->nom;
	    $arr[$id_equipe]['genre'] = $enregistrement->genre;
	    $arr[$id_equipe]['note'] = $enregistrement->note;
	    $arr[$id_equipe]['id_pays'] = $enregistrement->id_pays;
	}

	$db = null;
	return $arr;
    }

    function get_equipes_genre($genre){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM biathlon_equipe WHERE genre=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$genre,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	while($enregistrement = $prep->fetch()){
	    $id_equipe = $enregistrement->id;
	    $arr[$id_equipe]['id_biathlon_equipe'] = $id_equipe;
	    $arr[$id_equipe]['nom'] = $enregistrement->nom;
	    $arr[$id_equipe]['genre'] = $enregistrement->genre;
	    $arr[$id_equipe]['note'] = $enregistrement->note;
	    $arr[$id_equipe]['id_pays'] = $enregistrement->id_pays;
	}

	$db = null;
	return $arr;
    }
?>