<?php

	$id_cal = $_POST['id_cal'];
	$id_jeu = $_POST['id_jeu'];
	$annee = $_POST['annee'];
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "DELETE FROM cyclisme_inscription_equipe
			WHERE id_cal=? AND id_jeu=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$res = $prep->execute();
	
	//echo 'resultat : ' . $res . '<br/>';
	
	//On pr�pare la requ�te pour aller chercher les articles
	$sql2 = "INSERT INTO cyclisme_inscription_equipe(id_equipe,id_jeu,id_cal) VALUES(?,?,?)";
	$prep2 = $db->prepare($sql2);
	$prep2->setFetchMode(PDO::FETCH_OBJ);
	
		
	if(!empty($_POST['equipes'])) {
		foreach($_POST['equipes'] as $id_equipe) {
				
				$equipe=intval($id_equipe);
				
				$prep2->bindValue(1,$equipe,PDO::PARAM_INT);
				$prep2->bindValue(2,$id_jeu,PDO::PARAM_INT);
				$prep2->bindValue(3,$id_cal,PDO::PARAM_INT);

				$res = $prep2->execute();
				
				//echo $res . ' : ' . $cycliste . ' - ' . $id_jeu . ' - ' . $id_cal . '<br/>';
		}
	}
	
	header('Location: /jeux/cyclisme/admin/inscriptions/?id_cal=' . $id_cal . '&id_jeu=' . $id_jeu . '&annee=' . $annee);
?>