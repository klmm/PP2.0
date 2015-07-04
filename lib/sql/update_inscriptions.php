<?php
	
    function update_inscription($id_jeu, $joueur,$classement){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_inscriptions.php');

	$bdd = new Connexion();
	$db = $bdd->getDB();

	$id_inscr = is_joueur_inscrit($id_jeu, $joueur);

	if($id_inscr == 0){
	    $sql = "INSERT INTO joueurs_inscriptions(joueur,id_jeu,classement) VALUES(?,?,?)";
	    $prep = $db->prepare($sql);
	    $prep->bindValue(1,$joueur,PDO::PARAM_STR);
	    $prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	    $prep->bindValue(3,$classement,PDO::PARAM_INT);
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    $prep->execute();
	}
	else{
	    $sql = "UPDATE joueurs_inscriptions SET classement=? WHERE id_joueurs_inscriptions=?";
	    $prep = $db->prepare($sql);
	    $prep->bindValue(1,$classement,PDO::PARAM_INT);
	    $prep->bindValue(2,$id_inscr,PDO::PARAM_INT);
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    $prep->execute();
	}
	
	$db = null;
    }

?>