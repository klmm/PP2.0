<?php

 	require_once(_SERVER['DOCUMENT_ROOT'] . '/PP2.0/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$login = $_POST['login'];
	$motdepasse = $_POST['motdepasse'];
	$confmotdepasse = $_POST['confmotdepasse'];
	$mail = $_POST['email'];
	$nom = $_POST['nom'];
	$prenom = $_POST['prenom'];
	
	
	// Taille du login entre 3 et 12 caractères
	$taille = strlen($login);
	if ($taille < 3 or $taille > 12){
		echo 'Le login doit contenir entre 3 et 12 caractères.';
		return;
	}
	
	// Taille du mot de passse entre 8 et 20 caractères
	$taille = strlen($motdepasse);
	if ($taille < 7 or $taille > 20){
		echo 'Le mot de passe doit contenir entre 8 et 20 caractères.';
		return;
	}
	
	// Les deux mots de passe identiques
	if ($motdepasse != $confmotdepasse){ 
		echo 'Les mots de passe sont différents !';
		return;
	}
	
	
	// Login constitué de lettres et de chiffres
	$new_string = ereg_replace("[^A-Za-z0-9]", "", $login);
	if ( $new_string != $login){
		echo 'Le login ne doit contenir que des lettres et des chiffres !';
		return;
	}
	
	// Mdp constitué de lettres et de chiffres
	$new_string = ereg_replace("[^A-Za-z0-9]", "", $motdepasse);
	if ( $new_string != $motdepasse){
		echo 'Le mot de passe ne doit contenir que des lettres et des chiffres !';
		return;
	}
	
	// Login unique
	$sql = "SELECT * FROM Joueurs WHERE Login=?";
	
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$login,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $prep->fetch();

	if( $enregistrement ){
		echo 'Ce login est déjà utilisé par un autre joueur...';
		return;
	}
	
	
	// Mail unique
	$sql = "SELECT * FROM Joueurs WHERE Mail=?";
	
	$prep2 = $db->prepare($sql);
	$prep2->bindValue(1,$mail,PDO::PARAM_STR);
	$prep2->execute();
	$prep2->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $prep2->fetch();

	if( $enregistrement ){
		echo 'Cette adresse mail a déjà été utilisée par un autre joueur...';
		return;
	}
	

	// Hash du mot de passe + login
	$motdepasse = hash('sha512', $motdepasse . $login);
	
	
	// Ajout à la base
	$sql = "INSERT INTO Joueurs(Nom,Prenom,Mail,Login,Mdp,Admin) VALUES(?,?,?,?,?,0)";
	
	$prep3 = $db->prepare($sql);
	$prep3->bindValue(1,$nom,PDO::PARAM_STR);
	$prep3->bindValue(2,$prenom,PDO::PARAM_STR);
	$prep3->bindValue(3,$mail,PDO::PARAM_STR);
	$prep3->bindValue(4,$login,PDO::PARAM_STR);
	$prep3->bindValue(5,$motdepasse,PDO::PARAM_STR);
	$prep3->execute();
	
	echo ('Félicitations, vous êtes maintenant inscrit sur Parions Potes !
	Vous pouvez maintenant vous connecter en haut de la page afin de laisser vos commentaires et participer aux jeux !');
		
?>