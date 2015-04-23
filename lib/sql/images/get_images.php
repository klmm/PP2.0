<?php

	function get_images_tous(){
	
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();
		
		//On fait la requete sur le login
		$sql = "SELECT * FROM ArticlesImage";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		$i = 0;
		while( $enregistrement = $prep->fetch() ){
			$arr[$i]['id_image'] = $enregistrement->IDArticleImage;
			$arr[$i]['titre'] = $enregistrement->Titre;
			$arr[$i]['credits'] = $enregistrement->Credits;
			$arr[$i]['chemin'] = $enregistrement->Chemin;
                        $arr[$i]['chemin_degrade'] = $enregistrement->chemin_degrade;
			$i++;
		}
		
		return $arr;
	}

// Prend en param�tre un tableau d'id d'images ==> Retourne un tableau d'images (avec les param�tres qui vont bien)
	function get_images_id($tab_id_image){
		//Nombre d'images
		$nb_images = sizeof($tab_id_image);
		if($nb_images == 0){
			return null;
		}
	
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requete sur la rubrique
		$sql = "SELECT * FROM ArticlesImage WHERE IDArticleImage = ?";
		$prep = $db->prepare($sql);
				
		for ($i=0; $i<$nb_images; $i++){
                    $id_image = $tab_id_image[$i];
                    $prep->bindValue(1,$id_image,PDO::PARAM_INT);
                    $prep->execute();
                    $prep->setFetchMode(PDO::FETCH_OBJ);

                    $enregistrement = $prep->fetch();

                    //On fait le test si un enrengistrement a �t� trouv�
                    if( $enregistrement ){
                        $arr[$i]['id_image'] = $enregistrement->IDArticleImage;
                        $arr[$i]['titre'] = $enregistrement->Titre;
                        $arr[$i]['credits'] = $enregistrement->Credits;
                        $arr[$i]['chemin'] = $enregistrement->Chemin;
                        $arr[$i]['chemin_degrade'] = $enregistrement->chemin_degrade;
                    }
		}
		return $arr;
	}

?>