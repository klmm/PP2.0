<?php

 	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$login = $_POST['username'];
	$motdepasse = $_POST['password'];

	// Hash du mot de passe + login
	$motdepasse = hash('sha512', $motdepasse . $login);
	
	// Login ?
	$sql = "SELECT * FROM Joueurs WHERE Login=?";
	
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$login,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $prep->fetch();

	if( $enregistrement ){
		$vrai_mdp = $enregistrement->Mdp;
		
		if ($vrai_mdp == $motdepasse){
			session_start();
			$_SESSION['IDJoueur'] = $enregistrement->IDJoueur;
			$_SESSION['LoginJoueur'] = $login;
			$_SESSION['MailJoueur'] = $enregistrement->Mail;
			echo "Connexion OK";
			return;
		}
		else{
			echo "Mot de passe incorrect";
			return;
		}
	}
	else{
		echo "Ce login n'existe pas... Êtes-vous inscrit ?";
		return;
	}

?>