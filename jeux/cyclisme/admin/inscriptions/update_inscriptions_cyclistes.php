<?php

	$id_cal = $_POST['id_cal'];
	$id_jeu = $_POST['id_jeu'];
	$annee = $_POST['annee'];
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "DELETE FROM cyclisme_inscription_athlete
			WHERE id_cal=? AND id_jeu=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$res = $prep->execute();
	
	//echo 'resultat : ' . $res . '<br/>';
	
	//On pr�pare la requ�te pour aller chercher les articles
	$sql2 = "INSERT INTO cyclisme_inscription_athlete(id_athlete,id_jeu,id_cal,forme,id_equipe) VALUES(?,?,?,?,?)";
	$prep2 = $db->prepare($sql2);
	$prep2->setFetchMode(PDO::FETCH_OBJ);
	
		
	if(!empty($_POST['cyclistes'])) {
		foreach($_POST['cyclistes'] as $id_cycliste) {
                    $forme = $_POST['forme' . $id_cycliste];
                    $cycliste=intval($id_cycliste);
		    $id_equipe = $_POST['equipe' . $id_cycliste];
			    
                    $prep2->bindValue(1,$cycliste,PDO::PARAM_INT);
                    $prep2->bindValue(2,$id_jeu,PDO::PARAM_INT);
                    $prep2->bindValue(3,$id_cal,PDO::PARAM_INT);
                    $prep2->bindValue(4,$forme,PDO::PARAM_INT);
		    $prep2->bindValue(5,$id_equipe,PDO::PARAM_INT);
                    $res = $prep2->execute();
		}
	}
	header('Location: /jeux/cyclisme/admin/inscriptions/?id_cal=' . $id_cal . '&id_jeu=' . $id_jeu . '&annee=' . $annee);
?>