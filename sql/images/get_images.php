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
	
// Prend en paramètre un tableau d'id d'images ==> Retourne un tableau d'images (avec les paramètres qui vont bien)
	function get_images_id($tab_id_image){
		//Nombre d'images
		$nb_images = sizeof($tab_id_image);
		if($nb_images == 0){
			return null;
		}
	
		// On établit la connexion avec la base de données
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On prépare la requete sur la rubrique
		$sql = "SELECT * FROM ArticlesImage WHERE IDArticleImage = ?";
		$prep = $db->prepare($sql);
				
		for ($i=0; $i<$nb_images; $i++){
			$id_image = $tab_id_image[$i];
			$prep->bindValue(1,$id_image,PDO::PARAM_INT);
			$prep->execute();
			$prep->setFetchMode(PDO::FETCH_OBJ);
				
			$enregistrement = $prep->fetch();
			
			//On fait le test si un enrengistrement a été trouvé
			if( $enregistrement ){
				$img[$i][0] = $enregistrement->IDArticleImage;
				$img[$i][1] = $enregistrement->Titre;
				$img[$i][2] = $enregistrement->Credits;
				$img[$i][3] = $enregistrement->Chemin;
			}
		}
		return $img;
	}
	
?>