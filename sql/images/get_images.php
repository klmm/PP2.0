<?php

	function get_images_tous(){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM ArticlesImage";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a été trouvé
		$i = 0;
		while( $enregistrement = $prep->fetch() ){
			$arr[$i][0] = $enregistrement->IDArticleImage;
			$arr[$i][1] = $enregistrement->Titre;
			$arr[$i][2] = $enregistrement->Credits;
			$arr[$i][3] = $enregistrement->Chemin;
			$i++;
		}
		
		return $arr;
	}
	
	function get_images_id($id_image){
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur la rubrique
		$sql = "SELECT * FROM ArticlesImage WHERE IDArticleImage = ?";
		$prep = $db->prepare($sql);
		$prep->bindValue(1,$id_image,PDO::PARAM_INT);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);
			
		$enregistrement = $prep->fetch();
		
		//On fait le test si un enrengistrement a été trouvé
		if( $enregistrement ){
			$img[0] = $enregistrement->IDArticleImage;
			$img[1] = $enregistrement->Titre;
			$img[2] = $enregistrement->Credits;
			$img[3] = $enregistrement->Chemin;
		}
		
		return $img;
	}
	
?>