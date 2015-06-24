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
			
	function get_jeu_id($id_jeu){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		$date_actu = date('Y-m-d H:i:s');

		//On fait la requete sur le login
		$sql = "SELECT * FROM jeu WHERE id_jeu=?";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$id_jeu,PDO::PARAM_STR);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		$enregistrement = $prep->fetch();

		if( $enregistrement ){
			$arr['id_jeu'] = $enregistrement->id_jeu;
			$arr['date_debut'] = $enregistrement->date_debut;
			$now   = time();
			$dh_debut = strtotime($arr['date_debut']);

			if($now > $dh_debut){
			    $arr['commence'] = "1";
			}
			else{
			    $arr['commence'] = "0";
			}
			
			$arr['date_fin'] = $enregistrement->date_fin;
			$dh_fin = strtotime($arr['date_fin']);
			if($now > $dh_fin){
			    $arr['termine'] = "1";
			}
			else{
			    $arr['termine'] = "0";
			}
			
			$arr['sport'] = $enregistrement->sport;
			$arr['competition'] = $enregistrement->competition;
			$arr['url'] = $enregistrement->url;
			$arr['image'] = $enregistrement->image;
			$arr['description'] = $enregistrement->description;
		}
		
		return $arr;
	}
?>