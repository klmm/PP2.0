<?php
	
    function add_inscription($id_jeu, $joueur){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/inscriptions/get_inscriptions.php');

	$bdd = new Connexion();
	$db = $bdd->getDB();

	if (is_joueur_inscrit($id_jeu, $joueur)){
	    return true;
	}

	//On fait la requete sur le login
	$sql = "INSERT INTO joueurs_inscriptions(joueur,id_jeu,classement) VALUES(?,?,0)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$joueur,PDO::PARAM_STR);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	return $prep->execute();
    }

?>