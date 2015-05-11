<?php

    function get_calendrier($id_jeu, $id_cal){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=? AND id_cal=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	if( $enregistrement = $prep->fetch() )
	{
	    $arr['id_cyclisme_calendrier'] = $enregistrement->id_cyclisme_calendrier;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['id_cal'] = $enregistrement->id_cal;
	    $arr['nom_complet'] = $enregistrement->nom_complet;
	    $arr['distance'] = $enregistrement->distance;
	    $arr['date_debut'] = $enregistrement->date_debut;
	    $arr['date_fin'] = $enregistrement->date_fin;
	    $arr['profil_clm'] = $enregistrement->profil_clm;
	    $arr['profil_paves'] = $enregistrement->profil_paves;
	    $arr['profil_montagne'] = $enregistrement->profil_montagne;
	    $arr['profil_sprint'] = $enregistrement->profil_sprint;
	    $arr['profil_vallons'] = $enregistrement->profil_vallons;
	    $arr['profil_baroudeurs'] = $enregistrement->profil_baroudeurs;
	    $arr['profil_equipe'] = $enregistrement->profil_equipe;
	    $arr['profil_jeunes'] = $enregistrement->profil_jeunes;
	    $arr['classement'] = $enregistrement->classement;
	    $arr['traite'] = $enregistrement->traite;
	    $arr['disponible'] = $enregistrement->disponible;
	    $arr['image'] = $enregistrement->image;
	    
	}

	return $arr;
    }
    
    function get_calendrier_jeu($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM cyclisme_calendrier WHERE id_jeu=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_cyclisme_calendrier;
	    $arr[$id_cal]['id_cyclisme_calendrier'] = $enregistrement->id_cyclisme_calendrier;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_cal'] = $enregistrement->id_cal;
	    $arr[$id_cal]['nom_complet'] = $enregistrement->nom_complet;
	    $arr[$id_cal]['distance'] = $enregistrement->distance;
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr[$id_cal]['date_fin'] = $enregistrement->date_fin;
	    $arr[$id_cal]['profil_clm'] = $enregistrement->profil_clm;
	    $arr[$id_cal]['profil_paves'] = $enregistrement->profil_paves;
	    $arr[$id_cal]['profil_montagne'] = $enregistrement->profil_montagne;
	    $arr[$id_cal]['profil_sprint'] = $enregistrement->profil_sprint;
	    $arr[$id_cal]['profil_vallons'] = $enregistrement->profil_vallons;
	    $arr[$id_cal]['profil_baroudeurs'] = $enregistrement->profil_baroudeurs;
	    $arr[$id_cal]['profil_equipe'] = $enregistrement->profil_equipe;
	    $arr[$id_cal]['profil_jeunes'] = $enregistrement->profil_jeunes;
	    $arr[$id_cal]['classement'] = $enregistrement->classement;
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    $arr[$id_cal]['image'] = $enregistrement->image;

	    $i++;
	}
	return $arr;
    }

?>