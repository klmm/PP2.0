<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On prépare la requête pour aller chercher les articles
	$sql = "SELECT * FROM cyclisme_athlete";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->execute();
	
	//On met les articles dans le tableau
	$sql2 = "SELECT * FROM cyclisme_equipe WHERE nom_complet = ?";
	$prep2 = $db->prepare($sql2);
	$prep2->setFetchMode(PDO::FETCH_OBJ);
	
	$sql3 = "UPDATE cyclisme_athlete SET id_equipe = ? WHERE id_cyclisme_athlete = ?";
	$prep3 = $db->prepare($sql3);
	$prep3->setFetchMode(PDO::FETCH_OBJ);
			
	while( $enregistrement = $prep->fetch() )
	{
		$id_cycliste = $enregistrement->id_cyclisme_athlete;
		$equipe = $enregistrement->Equipe;
		
		echo $id_cycliste . ' - ' . $equipe . '<br/>';
		
		$prep2->bindValue(1,$equipe,PDO::PARAM_STR);
		$prep2->execute();
		
		$res = $enregistrement2 = $prep2->fetch();
		
		if ( $res ){
			$id_equipe = $enregistrement2->id_cyclisme_equipe;
			$prep3->bindValue(1,$id_equipe,PDO::PARAM_INT);
			$prep3->bindValue(2,$id_cycliste,PDO::PARAM_INT);
			$prep3->execute();
		}
	}
?>