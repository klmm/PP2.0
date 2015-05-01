<?php

 	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	session_start();
	$login = $_SESSION['LoginJoueur'];
	
	$old_pass = $_POST['oldpassword'];
	$new_pass = $_POST['newpassword'];
	$new_pass2 = $_POST['newpassword2'];

	// Taille du mot de passse entre 6 et 20 caractères
	$taille = strlen($new_pass);
	if ($taille < 5 or $taille > 20){
		echo 'Le mot de passe doit contenir entre 6 et 20 caractères.';
		return;
	}
	
	// Les deux mots de passe identiques
	if ($new_pass != $new_pass2){ 
		echo 'Les mots de passe sont différents !';
		return;
	}
	
	// Mdp constitué de lettres et de chiffres
	$new_string = ereg_replace("[^A-Za-z0-9]", "", $new_pass);
	if ( $new_string != $new_pass){
		echo 'Le mot de passe doit uniquement contenir : des lettres et des chiffres !';
		return;
	}
	
	
	// Hash du mot de passe + login
	$old_pass = hash('sha512', $old_pass . $login);
	
	
	// Login ?
	$sql = "SELECT * FROM Joueurs WHERE Login=?";
	
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$login,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $prep->fetch();

	if( $enregistrement ){
		$vrai_mdp = $enregistrement->Mdp;
		
		if ($vrai_mdp == $old_pass){
		}
		else{
			echo "Le mot de passe actuel indiqué est incorrect";
			return;
		}
	}
	else{
		echo "Problème, joueur inconnu. Déconnexion.";
		return;
	}
	
		
	// Hash du mot de passe + login
	$new_pass = hash('sha512', $new_pass . $login);
	
	
	// Ajout à la base
	$sql = "UPDATE Joueurs SET Mdp=?, PassChanged=1 WHERE Login = ?";
	
	$prep3 = $db->prepare($sql);
	$prep3->bindValue(1,$new_pass,PDO::PARAM_STR);
	$prep3->bindValue(2,$login,PDO::PARAM_STR);
	$prep3->execute();
	
	echo ('success;Mot de passe changé avec succès. Veuillez vous reconnecter.');

?>