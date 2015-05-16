<?php

// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM cyclisme_athlete";
	$prep = $db->prepare($sql);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	$sql2 = "UPDATE cyclisme_athlete SET photo='/jeux/cyclisme/img/cyclistes/nobody_photo.jpg' WHERE id_cyclisme_athlete=?";
	$prep2 = $db->prepare($sql2);
	
	while( $enregistrement = $prep->fetch() )
	{
		//echo 'id : ' . $enregistrement->id_cyclisme_athlete;
		echo $enregistrement->prenom . ' ' . $enregistrement->nom . ' : ' . '<img src="' . $enregistrement->photo . '" alt="' . $enregistrement->photo . '"></img><br/>';
	
	    /*if (file_exists($_SERVER['DOCUMENT_ROOT'] . $enregistrement->photo)) {
		echo "Le fichier " . $enregistrement->photo . "existe.";
	    } else {
		$id = $enregistrement->id_cyclisme_athlete;
		$prep2->bindValue(1,$id);
		$prep2->execute();
		echo "Le fichier " . $enregistrement->photo . "n'existe pas.";
	    }*/
	    
	}
?>
