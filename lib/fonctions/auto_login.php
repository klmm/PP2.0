<?php

function auto_login(){
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
	
    // Récupération de la valeur du cookie
    $key = $_COOKIE['ParionsPotes'];
    
    if (isset($key)){
	    $sql = "SELECT * FROM Joueurs WHERE SHA1(CONCAT('SEL1-df299', `Login`, `IDJoueur`, 'SEL2-ef144'))='" . $key. "'";
	    $prep = $db->prepare($sql);
	    $prep->execute();
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    $enregistrement = $prep->fetch();

	    if($enregistrement) {
		    $_SESSION['IDJoueur'] = $enregistrement->IDJoueur;
		    $_SESSION['LoginJoueur'] = $enregistrement->Login;
		    $_SESSION['MailJoueur'] = $enregistrement->Mail;
		    $_SESSION['Admin'] = $enregistrement->Admin;

		    setcookie('ParionsPotes', $key, time() + 3600 * 24 * 30, '/', 'www.parions-potes.fr', false, true);

		    return true;
	    }
	    else{
		    // Mauvais cookie !
		    return false;
	    }
    }
    else{
	    // Mauvais cookie !
	    return false;
    }

}

?>