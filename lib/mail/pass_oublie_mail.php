<?php

	$mail = $_POST["mail"];
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	
	
	// Mail renseigné dans la base ?
	$sql = "SELECT * FROM Joueurs WHERE Mail=?";
	
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$mail,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $prep->fetch();
		
	if (!$enregistrement ){
		echo 'Ce mail n\'a jamais été utilisé pour une inscription sur Parions Potes...';
		return;
	}
	$login = $enregistrement->Login;
	$idjoueur = $enregistrement->IDJoueur;
	
	
	
	
	// Génération d'un mot de passe aléatoire de 8 caractères
	$characters = '2345679abcdefghijkmnopqrstuvwxyzACDEFGHJKLMNPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
	$length = rand(8,12);
    $newpass = '';
    for ($i = 0; $i < $length; $i++) {
        $newpass .= $characters[rand(0, $charactersLength - 1)];
    }
	
	
	
	
	// Modification du mot de passe dans la base
	$newpass_h = hash('sha512', $newpass . $login);
	
	$sql = "UPDATE Joueurs SET Mdp = ? WHERE IDJoueur = ?";
	
	$prep3 = $db->prepare($sql);
	$prep3->bindValue(1,$newpass_h,PDO::PARAM_STR);
	$prep3->bindValue(2,$idjoueur,PDO::PARAM_INT);
	$prep3->execute();
	
	
	
	

	// Envoi du mail
	$sujet = "Parions Potes - Nouveau mot de passe";

	$passage_ligne = "\r\n";
	$contenu = "Bonjour " . $login . "," . $passage_ligne . $passage_ligne . "Votre nouveau de passe est " . $newpass . $passage_ligne . $passage_ligne;
	$contenu .= "Connectez-vous avec ce mot de passe sur www.parions-potes.fr, puis changez immédiatement votre mot de passe." . $passage_ligne . $passage_ligne;
	$contenu .= "Sportivement," . $passage_ligne . $passage_ligne;
	$contenu .= "L'équipe Parions Potes". $passage_ligne . "www.parions-potes.fr";
	
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	
	//=====Création du header de l'e-mail.
	$header = 'From: "Parions Potes"<parionsp@20gp.ovh.net>'.$passage_ligne;
	$header.= 'Reply-to: "Parions Potes" <contact@parions-potes.fr>'.$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
	//==========
	 
	//=====Création du message.
	$message = $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format texte.
	$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$contenu.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	//=====Ajout du message au format HTML
	$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$contenu.$passage_ligne;
	//==========
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
	
	//==========
	
	//=====Envoi de l'e-mail.
	mail($mail,$sujet,$message,$header);
	//==========
	
	echo('success;Un mail vient de vous être envoyé avec un nouveau de mot de passe réinitialisé.');
?>