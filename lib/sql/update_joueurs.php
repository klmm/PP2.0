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
		$prep->bindValue(1,$joueur,PDO::PARAM_STR);
		$prep->execute();
		
		$db = null;
		
		return true;
	}
	
	function update_joueur_infos($joueur,$nom,$prenom,$mail,$avatar,$slogan){
	    // On �tablit la connexion avec la base de donn�es
	    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	    $bdd = new Connexion();
	    $db = $bdd->getDB();

	    if($avatar != null){
		$sql = "UPDATE Joueurs SET nom=?, prenom=?, avatar=?, slogan=?, mail=? WHERE Login = ?";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$nom,PDO::PARAM_STR);
		$prep->bindValue(2,$prenom,PDO::PARAM_STR);
		$prep->bindValue(3,$avatar,PDO::PARAM_STR);
		$prep->bindValue(4,$slogan,PDO::PARAM_STR);
		$prep->bindValue(5,$mail,PDO::PARAM_STR);
		$prep->bindValue(6,$joueur,PDO::PARAM_STR);
	    }
	    else{
		$sql = "UPDATE Joueurs SET nom=?, prenom=?, slogan=?, mail=? WHERE Login = ?";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$nom,PDO::PARAM_STR);
		$prep->bindValue(2,$prenom,PDO::PARAM_STR);
		$prep->bindValue(3,$slogan,PDO::PARAM_STR);
		$prep->bindValue(4,$mail,PDO::PARAM_STR);
		$prep->bindValue(5,$joueur,PDO::PARAM_STR);
	    }	    
	    
	    $res = $prep->execute();

	    $db = null;

	    return $res;
	}
	
?>