<?php
	
	function update_derniere_visite($joueur){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		session_start();
		
		//On fait la requete sur le login
		$sql = "UPDATE `Joueurs` SET `DerniereVisite` = NOW() WHERE `Login` = ?";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$joueur,PDO::PARAM_INT);
		$prep->execute();
		
		return true;
	}
	
?>