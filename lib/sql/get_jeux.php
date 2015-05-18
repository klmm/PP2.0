<?php	
	
	function get_jeux_tous(){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM jeu ORDER BY date_debut DESC";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_jeu'] = $enregistrement->id_jeu;
			$arr[$i]['date_debut'] = $enregistrement->date_debut;
			$arr[$i]['date_fin'] = $enregistrement->date_fin;
			$arr[$i]['sport'] = $enregistrement->sport;
			$arr[$i]['competition'] = $enregistrement->competition;
			$arr[$i]['url'] = $enregistrement->url;
			$arr[$i]['image'] = $enregistrement->image;
			$arr[$i]['description'] = $enregistrement->description;

			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_finis(){
		// On �tablit la connexion avec la base de donn�es
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

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_jeu'] = $enregistrement->id_jeu;
			$arr[$i]['date_debut'] = $enregistrement->date_debut;
			$arr[$i]['date_fin'] = $enregistrement->date_fin;
			$arr[$i]['sport'] = $enregistrement->sport;
			$arr[$i]['competition'] = $enregistrement->competition;
			$arr[$i]['url'] = $enregistrement->url;
			$arr[$i]['image'] = $enregistrement->image;
			$arr[$i]['description'] = $enregistrement->description;
			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_avenir(){
		// On �tablit la connexion avec la base de donn�es
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

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_jeu'] = $enregistrement->id_jeu;
			$arr[$i]['date_debut'] = $enregistrement->date_debut;
			$arr[$i]['date_fin'] = $enregistrement->date_fin;
			$arr[$i]['sport'] = $enregistrement->sport;
			$arr[$i]['competition'] = $enregistrement->competition;
			$arr[$i]['url'] = $enregistrement->url;
			$arr[$i]['image'] = $enregistrement->image;
			$arr[$i]['description'] = $enregistrement->description;
			$i++;
		}
		
		return $arr;
	}
	
	function get_jeux_encours(){
		// On �tablit la connexion avec la base de donn�es
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

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$arr[$i]['id_jeu'] = $enregistrement->id_jeu;
			$arr[$i]['date_debut'] = $enregistrement->date_debut;
			$arr[$i]['date_fin'] = $enregistrement->date_fin;
			$arr[$i]['sport'] = $enregistrement->sport;
			$arr[$i]['competition'] = $enregistrement->competition;
			$arr[$i]['url'] = $enregistrement->url;
			$arr[$i]['image'] = $enregistrement->image;
			$arr[$i]['description'] = $enregistrement->description;
			$i++;
		}
		
		return $arr;
	}
	
?>