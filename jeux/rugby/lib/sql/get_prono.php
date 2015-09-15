<?php

    function get_pronos_joueurs_jeu($id_jeu,$joueur){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM rugby_prono WHERE id_jeu=? AND joueur=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$joueur,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_calendrier;
	    $arr[$id_cal]['id_rugby_prono'] = $enregistrement->id;
	    $arr[$id_cal]['id_cal'] = $enregistrement->id_calendrier;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['joueur'] = $enregistrement->joueur;
	    $arr[$id_cal]['prono_vainqueur'] = $enregistrement->prono_vainqueur;
	    $arr[$id_cal]['prono_points1'] = $enregistrement->prono_points1;
	    $arr[$id_cal]['prono_points2'] = $enregistrement->prono_points2;
	    $arr[$id_cal]['prono_essais1'] = $enregistrement->prono_essais1;
	    $arr[$id_cal]['prono_essais2'] = $enregistrement->prono_essais2;
	    $arr[$id_cal]['score_vainqueur'] = $enregistrement->score_vainqueur;
	    $arr[$id_cal]['score_points1'] = $enregistrement->score_points1;
	    $arr[$id_cal]['score_points2'] = $enregistrement->score_points2;
	    $arr[$id_cal]['score_essais1'] = $enregistrement->score_essais1;
	    $arr[$id_cal]['score_essais2'] = $enregistrement->score_essais2;
	    $arr[$id_cal]['score_ecart'] = $enregistrement->score_ecart;
	    $arr[$id_cal]['score_total'] = $enregistrement->score_total;
	    $arr[$id_cal]['classement'] = $enregistrement->classement;
	}
	$db = null;
	return $arr;
    }
    
    function get_prono($id_cal,$joueur){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM rugby_prono WHERE id_calendrier=? AND joueur=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(2,$joueur,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	//On fait le test si un enrengistrement a �t� trouv�
	$enregistrement = $prep->fetch();
	if($enregistrement)
	{ 
	    $id_cal = $enregistrement->id_calendrier;
	    $arr['id_rugby_prono'] = $enregistrement->id;
	    $arr['id_cal'] = $enregistrement->id_calendrier;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['joueur'] = $enregistrement->joueur;
	    $arr['prono_vainqueur'] = $enregistrement->prono_vainqueur;
	    $arr['prono_points1'] = $enregistrement->prono_points1;
	    $arr['prono_points2'] = $enregistrement->prono_points2;
	    $arr['prono_essais1'] = $enregistrement->prono_essais1;
	    $arr['prono_essais2'] = $enregistrement->prono_essais2;
	    $arr['score_vainqueur'] = $enregistrement->score_vainqueur;
	    $arr['score_points1'] = $enregistrement->score_points1;
	    $arr['score_points2'] = $enregistrement->score_points2;
	    $arr['score_essais1'] = $enregistrement->score_essais1;
	    $arr['score_essais2'] = $enregistrement->score_essais2;
	    $arr['score_ecart'] = $enregistrement->score_ecart;
	    $arr['score_total'] = $enregistrement->score_total;
	    $arr['classement'] = $enregistrement->classement;
	}
	$db = null;
	return $arr;
    }
    
    function get_pronos_cal($id_cal){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM rugby_prono WHERE id_calendrier=? ORDER BY classement ASC, score_total DESC, joueur ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	while ($enregistrement = $prep->fetch())
	{
	    $joueur = $enregistrement->joueur;
	    $arr[$joueur]['id_rugby_prono'] = $enregistrement->id;
	    $arr[$joueur]['id_cal'] = $enregistrement->id_calendrier;
	    $arr[$joueur]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$joueur]['joueur'] = $enregistrement->joueur;
	    $arr[$joueur]['prono_vainqueur'] = $enregistrement->prono_vainqueur;
	    $arr[$joueur]['prono_points1'] = $enregistrement->prono_points1;
	    $arr[$joueur]['prono_points2'] = $enregistrement->prono_points2;
	    $arr[$joueur]['prono_essais1'] = $enregistrement->prono_essais1;
	    $arr[$joueur]['prono_essais2'] = $enregistrement->prono_essais2;
	    $arr[$joueur]['score_vainqueur'] = $enregistrement->score_vainqueur;
	    $arr[$joueur]['score_points1'] = $enregistrement->score_points1;
	    $arr[$joueur]['score_points2'] = $enregistrement->score_points2;
	    $arr[$joueur]['score_essais1'] = $enregistrement->score_essais1;
	    $arr[$joueur]['score_essais2'] = $enregistrement->score_essais2;
	    $arr[$joueur]['score_ecart'] = $enregistrement->score_ecart;
	    $arr[$joueur]['score_total'] = $enregistrement->score_total;
	    $arr[$joueur]['classement'] = $enregistrement->classement;
	}
	$db = null;
	return $arr;
    }
?>