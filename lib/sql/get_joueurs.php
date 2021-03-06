<?php	
    function get_joueurs_tous(){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM Joueurs";
	$prep = $db->prepare($sql);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
		$arr[$i]['login'] = $enregistrement->Login;
		$arr[$i]['mail'] = $enregistrement->Mail;
		$arr[$i]['nom'] = $enregistrement->Nom;
		$arr[$i]['prenom'] = $enregistrement->Prenom;
		$arr[$i]['admin'] = $enregistrement->Admin;
		$arr[$i]['id_joueur'] = $enregistrement->IDJoueur;
		$arr[$i]['no_mail'] = $enregistrement->no_mail;
		$arr[$i]['slogan'] = $enregistrement->slogan;
		$arr[$i]['avatar'] = $enregistrement->avatar;
		$i++;
	}
	$db = null;
	return $arr;
    }
    
    function get_joueur($login){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM Joueurs WHERE login = ?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$login,PDO::PARAM_STR);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$enregistrement = $prep->fetch();
	if( $enregistrement )
	{
	    $arr['login'] = $enregistrement->Login;
	    $arr['mail'] = $enregistrement->Mail;
	    $arr['nom'] = $enregistrement->Nom;
	    $arr['prenom'] = $enregistrement->Prenom;
	    $arr['admin'] = $enregistrement->Admin;
	    $arr['id_joueur'] = $enregistrement->IDJoueur;
	    $arr['no_mail'] = $enregistrement->no_mail;
	    $arr['slogan'] = $enregistrement->slogan;
	    $arr['avatar'] = $enregistrement->avatar;
	}
	$db = null;
	return $arr;
    }
	
    function get_joueurs_admins(){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM Joueurs WHERE Admin = 1";
	$prep = $db->prepare($sql);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
		$arr[$i]['login'] = $enregistrement->Login;
		$arr[$i]['mail'] = $enregistrement->Mail;
		$arr[$i]['nom'] = $enregistrement->Nom;
		$arr[$i]['prenom'] = $enregistrement->Prenom;
		$arr[$i]['admin'] = $enregistrement->Admin;
		$arr[$i]['id_joueur'] = $enregistrement->IDJoueur;
		$arr[$i]['no_mail'] = $enregistrement->no_mail;
		$arr[$i]['slogan'] = $enregistrement->slogan;
		$arr[$i]['avatar'] = $enregistrement->avatar;
		$i++;
	}
	$db = null;
	return $arr;
    }
	
    function get_joueurs_inscrits($id_jeu,$no_mail){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$req_mail = '';
	if($no_mail){
	    $req_mail = ' AND no_mail=0';
	}

	//On fait la requete sur le login
	$sql = "SELECT *
			FROM Joueurs
			WHERE EXISTS(	SELECT * 
					FROM joueurs_inscriptions
					WHERE (joueurs_inscriptions.joueur=Joueurs.Login AND id_jeu=?" . $req_mail . ")
				    )";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
		$arr[$i]['login'] = $enregistrement->Login;
		$arr[$i]['mail'] = $enregistrement->Mail;
		$arr[$i]['nom'] = $enregistrement->Nom;
		$arr[$i]['prenom'] = $enregistrement->Prenom;
		$arr[$i]['admin'] = $enregistrement->Admin;
		$arr[$i]['id_joueur'] = $enregistrement->IDJoueur;
		$arr[$i]['no_mail'] = $enregistrement->no_mail;
		$arr[$i]['slogan'] = $enregistrement->slogan;
		$arr[$i]['avatar'] = $enregistrement->avatar;
		$i++;
	}
	$db = null;
	return $arr;
    }
    
    function get_joueurs_oubli_paris($id_jeu, $id_cal, $id_discipline){
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_inscriptions.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$inscrits = get_joueurs_inscriptions_jeu($id_jeu);
	$jeu = get_jeu_id($id_jeu);
	$sport = $jeu['sport'];
	
	switch($sport){
	    case 'Cyclisme':
		$table_prono = 'cyclisme_prono';
	    break;
	
	    case 'Rugby':
		$table_prono = 'rugby_prono';
	    break;
	
	    case 'Ski alpin':
		$table_prono = 'ski_alpin_prono';
	    break;
	
	    default:
		return null;
	}

	//On fait la requete sur le login
	$sql = "SELECT * FROM Joueurs WHERE NOT EXISTS (
							    SELECT * FROM " . $table_prono . " WHERE Joueurs.Login = " . $table_prono . ".joueur AND id_calendrier=? AND id_jeu=?
							)
							AND EXISTS (
							    SELECT * FROM joueurs_inscriptions WHERE (joueurs_inscriptions.joueur=Joueurs.Login AND id_jeu=? AND no_mail=0)
							)
							AND no_mail=0";

	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(3,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $login = $enregistrement->Login;
	    $filtre = $inscrits[$login]['filtre'];
	    
	    if($filtre != 0){
		$binary = decbin($filtre);
		if($binary[strlen($binary)-$id_discipline] == 0){
		    continue;
		}
	    }
	    
	    $arr[$i]['login'] = $login;
	    $arr[$i]['mail'] = $enregistrement->Mail;
	    $arr[$i]['nom'] = $enregistrement->Nom;
	    $arr[$i]['prenom'] = $enregistrement->Prenom;
	    $arr[$i]['admin'] = $enregistrement->Admin;
	    $arr[$i]['id_joueur'] = $enregistrement->IDJoueur;
	    $arr[$i]['no_mail'] = $enregistrement->no_mail;
	    $arr[$i]['slogan'] = $enregistrement->slogan;
	    $arr[$i]['avatar'] = $enregistrement->avatar;
	    $i++;
	}
	
	$db = null;
	return $arr;
    }
    
    function get_joueurs_oubli_premier_pari($id_jeu, $id_cal){
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$jeu = get_jeu_id($id_jeu);
	$sport = $jeu['sport'];
	
	switch($sport){
	    case 'Cyclisme':
		$table_prono = 'cyclisme_prono';
	    break;
	
	    case 'Rugby':
		$table_prono = 'rugby_prono';
	    break;
	
	    default:
		return null;
	}

	//On fait la requete sur le login
	$sql = "SELECT * FROM Joueurs WHERE NOT EXISTS (
			    SELECT * FROM " . $table_prono . " WHERE Joueurs.Login = " . $table_prono . ".joueur AND id_calendrier=? AND id_jeu=?)
				AND no_mail=0";

	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $arr[$i]['login'] = $enregistrement->Login;
	    $arr[$i]['mail'] = $enregistrement->Mail;
	    $arr[$i]['nom'] = $enregistrement->Nom;
	    $arr[$i]['prenom'] = $enregistrement->Prenom;
	    $arr[$i]['admin'] = $enregistrement->Admin;
	    $arr[$i]['id_joueur'] = $enregistrement->IDJoueur;
	    $arr[$i]['no_mail'] = $enregistrement->no_mail;
	    $arr[$i]['slogan'] = $enregistrement->slogan;
	    $arr[$i]['avatar'] = $enregistrement->avatar;
	    $i++;
	}
	
	$db = null;
	return $arr;
    }
?>