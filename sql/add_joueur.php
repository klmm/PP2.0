<?php

 	require_once('../admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$login = $_POST['login'];
	$motdepasse = $_POST['motdepasse'];
	$confmotdepasse = $_POST['confmotdepasse'];
	$mail = $_POST['mail'];
	$confmail = $_POST['confmail'];
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	
	if ($motdepasse != $confmotdepasse)
		{ echo '1';}
		
		if ($mail != $confmail)
		{ echo '2';}
		
		$sql = "SELECT * FROM Joueurs WHERE Login=?";
		
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$login,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$enregistrement = $prep->fetch();

		if( $enregistrement )
		{echo '3';}

		$sql = "SELECT * FROM Joueurs WHERE Mail=?";
		
		$prep2 = $db->prepare($sql);
		$prep2->bindValue(1,$mail,PDO::PARAM_STR);
		$prep2->execute();
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		$enregistrement = $prep2->fetch();

		if( $enregistrement )
		{ echo '4';}

		// Hash du mot de passe + login
		$motdepasse = hash('sha512', $motdepasse . $login);
		
		$sql = "INSERT INTO Joueurs(Nom,Prenom,Mail,Login,Mdp,Admin) VALUES(?,?,?,?,?,0)";
		
		$prep3 = $db->prepare($sql);
		$prep3->bindValue(1,$nom,PDO::PARAM_STR);
		$prep3->bindValue(2,$prenom,PDO::PARAM_STR);
		$prep3->bindValue(3,$mail,PDO::PARAM_STR);
		$prep3->bindValue(4,$login,PDO::PARAM_STR);
		$prep3->bindValue(5,$motdepasse,PDO::PARAM_STR);
		$prep3->execute();

		echo '0';		
?>