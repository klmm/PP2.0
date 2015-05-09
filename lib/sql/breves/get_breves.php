<?php

    function get_breves_jeu($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM jeu_breve WHERE id_jeu=? ORDER BY dateheurepub";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $arr[$i]['id_jeu_breve'] = $enregistrement->id_jeu_breve;
	    $arr[$i]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$i]['dateheurepub'] = $enregistrement->dateheurepub;
	    $arr[$i]['contenu'] = $enregistrement->contenu;
	    $arr[$i]['titre'] = $enregistrement->titre;

	    $i++;
	}

	return $arr;
    }

?>