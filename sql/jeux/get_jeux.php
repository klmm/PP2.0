<?php	

	function get_jeux_tous(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/PP2.0/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM Jeu ORDER BY DateHeureDebut DESC";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDJeu;
			$arr[$i][1] = $enregistrement->DateHeureDebut;
			$arr[$i][2] = $enregistrement->DateHeureFin;
			$arr[$i][3] = $enregistrement->Sport;
			$arr[$i][4] = $enregistrement->Competition;
			$arr[$i][5] = $enregistrement->URL;
			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_finis(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/PP2.0/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		$date_actu = date('Y-m-d H:i:s');

		//On fait la requete sur le login
		$sql = "SELECT * FROM Jeu WHERE DateHeureFin < ? ORDER BY DateHeureFin DESC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$date_actu,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDJeu;
			$arr[$i][1] = $enregistrement->DateHeureDebut;
			$arr[$i][2] = $enregistrement->DateHeureFin;
			$arr[$i][3] = $enregistrement->Sport;
			$arr[$i][4] = $enregistrement->Competition;
			$arr[$i][5] = $enregistrement->URL;
			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_avenir(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/PP2.0/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		$date_actu = date('Y-m-d H:i:s');

		//On fait la requete sur le login
		$sql = "SELECT * FROM Jeu WHERE DateHeureDebut > ? ORDER BY DateHeureDebut ASC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$date_actu,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDJeu;
			$arr[$i][1] = $enregistrement->DateHeureDebut;
			$arr[$i][2] = $enregistrement->DateHeureFin;
			$arr[$i][3] = $enregistrement->Sport;
			$arr[$i][4] = $enregistrement->Competition;
			$arr[$i][5] = $enregistrement->URL;
			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_encours(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/PP2.0/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		$date_actu = date('Y-m-d H:i:s');

		//On fait la requete sur le login
		$sql = "SELECT * FROM Jeu WHERE (DateHeureDebut < ? AND DateHeureFin > ?) ORDER BY DateHeureDebut DESC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$date_actu,PDO::PARAM_STR);
		$prep->bindValue(2,$date_actu,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->IDJeu;
			$arr[$i][1] = $enregistrement->DateHeureDebut;
			$arr[$i][2] = $enregistrement->DateHeureFin;
			$arr[$i][3] = $enregistrement->Sport;
			$arr[$i][4] = $enregistrement->Competition;
			$arr[$i][5] = $enregistrement->URL;
			$i++;
		}
		
		return $arr;
	}
	
?>