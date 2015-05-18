<?php
    function is_joueur_inscrit($id_jeu, $joueur){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM joueurs_inscriptions WHERE (id_jeu=? AND joueur=?)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$joueur,PDO::PARAM_STR);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->execute();
	
	$enr = $prep->fetch();
	
	if ($enr){
	    return true;
	}
	else{
	    return false;
	}
    }
?>