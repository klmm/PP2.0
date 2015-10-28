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
	    return $enr->id_joueurs_inscriptions;
	}
	else{
	    return 0;
	}
    }
    
    function get_joueur_inscription($id_jeu, $joueur){
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
	    $arr['id_joueurs_inscriptions'] = $enr->id_joueurs_inscriptions;
	    $arr['id_jeu'] = $enr->id_joueurs_inscriptions;
	    $arr['joueur'] = $enr->id_joueurs_inscriptions;
	    $arr['classement'] = $enr->id_joueurs_inscriptions;
	    $arr['no_mail'] = $enr->id_joueurs_inscriptions;
	    $arr['filtre'] = $enr->filtre;
	    return $arr;
	}
	else{
	    return null;
	}
    }
    
    function get_joueurs_inscriptions_jeu($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM joueurs_inscriptions WHERE (id_jeu=?)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->execute();
		
	while ($enr = $prep->fetch()){
	    $joueur = $enr->joueur;
	    
	    $arr[$joueur]['id_joueurs_inscriptions'] = $enr->id_joueurs_inscriptions;
	    $arr[$joueur]['id_jeu'] = $id_jeu;
	    $arr[$joueur]['joueur'] = $joueur;
	    $arr[$joueur]['classement'] = $enr->classement;
	    $arr[$joueur]['no_mail'] = $enr->no_mail;
	    $arr[$joueur]['filtre'] = $enr->filtre;
	}
	return $arr;
    }
?>