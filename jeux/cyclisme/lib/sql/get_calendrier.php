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
	    $arr[$i]['id_cyclisme_calendrier'] = $enregistrement->id_cyclisme_calendrier;
	    $arr[$i]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$i]['id_cal'] = $enregistrement->id_cal;
	    $arr[$i]['nom_complet'] = $enregistrement->nom_complet;
	    $arr[$i]['distance'] = $enregistrement->distance;
	    $arr[$i]['date_debut'] = $enregistrement->date_debut;
	    $arr[$i]['date_fin'] = $enregistrement->date_fin;
	    $arr[$i]['profil_clm'] = $enregistrement->profil_clm;
	    $arr[$i]['profil_paves'] = $enregistrement->profil_paves;
	    $arr[$i]['profil_montagne'] = $enregistrement->profil_montagne;
	    $arr[$i]['profil_sprint'] = $enregistrement->profil_sprint;
	    $arr[$i]['profil_vallons'] = $enregistrement->profil_vallons;
	    $arr[$i]['profil_baroudeurs'] = $enregistrement->profil_baroudeurs;
	    $arr[$i]['profil_equipe'] = $enregistrement->profil_equipe;
	    $arr[$i]['profil_jeunes'] = $enregistrement->profil_jeunes;
	    $arr[$i]['classement'] = $enregistrement->classement;
	    $arr[$i]['traite'] = $enregistrement->traite;
	    $arr[$i]['disponible'] = $enregistrement->disponible;
	    $arr[$i]['image'] = $enregistrement->image;

	    $i++;
	}
	return $arr;
    }

?>