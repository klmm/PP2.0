<?php
	
	function get_joueurs_tous(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/PP2.0/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Joueurs";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->Login;
			$arr[$i][1] = $enregistrement->Mail;
			$arr[$i][2] = $enregistrement->Nom;
			$arr[$i][3] = $enregistrement->Prenom;
			$arr[$i][4] = $enregistrement->Admin;
			$arr[$i][5] = $enregistrement->IDJoueur;
			$i++;
		}
		
		return $arr;
	}
	
	function get_joueurs_admins(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/PP2.0/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Joueurs WHERE Admin = 1";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->Login;
			$arr[$i][1] = $enregistrement->Mail;
			$arr[$i][2] = $enregistrement->Nom;
			$arr[$i][3] = $enregistrement->Prenom;
			$arr[$i][4] = $enregistrement->Admin;
			$arr[$i][5] = $enregistrement->IDJoueur;
			$i++;
		}
		
		return $arr;
	}
	
?>