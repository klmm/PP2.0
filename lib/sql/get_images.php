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
		$db = null;
		return $arr;
	}

// Prend en param�tre un tableau d'id d'images ==> Retourne un tableau d'images (avec les param�tres qui vont bien)
	function get_images_id($tab_id_image){
	    //Nombre d'images
	    $tab_id_image = array_unique($tab_id_image);
	    $nb_images = sizeof($tab_id_image);
	    if($nb_images == 0){
		return null;
	    }
	    $list_id_image = '';
	    $i = 0;
	    foreach($tab_id_image as $key => $value){
		if($i > 0){
		    $list_id_image .= ',';
		}
		$list_id_image .= $value;
		$i++;
	    }

	    // On �tablit la connexion avec la base de donn�es
	    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	    $bdd = new Connexion();
	    $db = $bdd->getDB();

	    //On pr�pare la requete sur la rubrique
	    $sql = "SELECT * FROM ArticlesImage WHERE IDArticleImage IN(" . $list_id_image . ")";
	    $prep = $db->prepare($sql);
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    $prep->execute();

	    while($enregistrement = $prep->fetch()){
		$id_image = $enregistrement->IDArticleImage;
		$arr[$id_image]['id_image'] = $enregistrement->IDArticleImage;
		$arr[$id_image]['titre'] = $enregistrement->Titre;
		$arr[$id_image]['credits'] = $enregistrement->Credits;
		$arr[$id_image]['chemin'] = $enregistrement->Chemin;
		$arr[$id_image]['chemin_degrade'] = $enregistrement->chemin_degrade;
	    }
	    $db = null;
	    return $arr;
	}

?>