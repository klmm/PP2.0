<?php

    include($_SERVER['DOCUMENT_ROOT'] . '/mail/envoi_mails.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    
    $mail = $_POST["mail"];

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

    // Génération d'un mot de passe al�atoire de 8 caract�res
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
    $res = $prep3->execute();


    if ($res == false){
	echo 'Erreur lors de la réinitialisation du mot de passe...';
	return;
    }


    // Envoi du mail
    $sujet = "Parions Potes - Nouveau mot de passe";
    $contenu = 'Votre nouveau de passe est ' . $newpass . '
	
	    Connectez-vous avec ce mot de passe sur www.parions-potes.fr, puis changez immédiatement votre mot de passe.';
    
    
    //envoi_mail($login, $mail, $sujet, $contenu);
	    
    echo('success;Un mail vient de vous être envoyé avec un nouveau de mot de passe.');
?>