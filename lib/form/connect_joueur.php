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
            // IP du client
 
            $id = $enregistrement->IDJoueur;
            $_SESSION['IDJoueur'] = $id;
            $_SESSION['LoginJoueur'] = $login;
            $_SESSION['MailJoueur'] = $enregistrement->Mail;
            $_SESSION['Admin'] = $enregistrement->Admin;
            echo "success";

            $auto_connect = true;
            if($auto_connect) {
                // Cryptage/Salage des éléments
                $key = sha1('SEL1-df299'.$login.$id.'SEL2-ef144');

                // Création du cookie
                setcookie('ParionsPotes', $key, time() + 3600 * 24 * 30, '/', 'www.parions-potes.fr', false, true);
            }

            // MAJ dernière connexion
            require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_joueurs.php');
            update_derniere_visite();
	    $db = null;
            return;
        }
        else{
            echo "Mot de passe incorrect";
	    $db = null;
            return;
        }
    }
    else{
        echo "Ce login n'existe pas... Êtes-vous inscrit ?";
	$db = null;
        return;
    }
?>