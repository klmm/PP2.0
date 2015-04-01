<?php
	
	function update_derniere_visite(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		session_start();
		$id = $_SESSION["IDJoueur"];
		
		//On fait la requete sur le login
		$sql = "UPDATE `Joueurs` SET `DerniereVisite` = NOW() WHERE `IDJoueur` = ?";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$id,PDO::PARAM_INT);
		$prep->execute();
		
		return true;
	}
	
?>