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
			$i++;
		}
		
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
			$i++;
		}
		
		return $arr;
	}
	
	function get_joueurs_inscrits($id_jeu){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT *
				FROM Joueurs
				WHERE EXISTS(	SELECT joueur 
                                                FROM joueurs_inscriptions
                                                WHERE (joueurs_inscriptions.joueur=joueurs.login AND id_jeu=?)
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
			$i++;
		}
		
		return $arr;
	}	
?>