<?php	
	
	function get_jeux_tous(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM jeu ORDER BY date_debut DESC";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_jeu;
			$arr[$i][1] = $enregistrement->date_debut;
			$arr[$i][2] = $enregistrement->date_fin;
			$arr[$i][3] = $enregistrement->sport;
			$arr[$i][4] = $enregistrement->competition;
			$arr[$i][5] = $enregistrement->url;
			$arr[$i][6] = $enregistrement->image;
			$arr[$i][7] = $enregistrement->description;

			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_finis(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		$date_actu = date('Y-m-d H:i:s');

		//On fait la requete sur le login
		$sql = "SELECT * FROM jeu WHERE date_fin < ? ORDER BY date_fin DESC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$date_actu,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_jeu;
			$arr[$i][1] = $enregistrement->date_debut;
			$arr[$i][2] = $enregistrement->date_fin;
			$arr[$i][3] = $enregistrement->sport;
			$arr[$i][4] = $enregistrement->competition;
			$arr[$i][5] = $enregistrement->url;
			$arr[$i][6] = $enregistrement->image;
			$arr[$i][7] = $enregistrement->description;
			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_avenir(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		$date_actu = date('Y-m-d H:i:s');

		//On fait la requete sur le login
		$sql = "SELECT * FROM jeu WHERE date_debut > ? ORDER BY date_debut ASC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$date_actu,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_jeu;
			$arr[$i][1] = $enregistrement->date_debut;
			$arr[$i][2] = $enregistrement->date_fin;
			$arr[$i][3] = $enregistrement->sport;
			$arr[$i][4] = $enregistrement->competition;
			$arr[$i][5] = $enregistrement->url;
			$arr[$i][6] = $enregistrement->image;
			$arr[$i][7] = $enregistrement->description;
			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_encours(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		$date_actu = date('Y-m-d H:i:s');

		//On fait la requete sur le login
		$sql = "SELECT * FROM jeu WHERE (date_debut < ? AND date_fin > ?) ORDER BY date_debut DESC";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$date_actu,PDO::PARAM_STR);
		$prep->bindValue(2,$date_actu,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i][0] = $enregistrement->id_jeu;
			$arr[$i][1] = $enregistrement->date_debut;
			$arr[$i][2] = $enregistrement->date_fin;
			$arr[$i][3] = $enregistrement->sport;
			$arr[$i][4] = $enregistrement->competition;
			$arr[$i][5] = $enregistrement->url;
			$arr[$i][6] = $enregistrement->image;
			$arr[$i][7] = $enregistrement->description;
			$i++;
		}
		
		return $arr;
	}
	
?>