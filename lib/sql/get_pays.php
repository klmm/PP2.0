<?php	
	
	function get_pays_tous(){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On fait la requete sur le login
		$sql = "SELECT * FROM pays";
		$prep = $db->prepare($sql);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);

		//On fait le test si un enrengistrement a �t� trouv�
		while( $enregistrement = $prep->fetch() )
		{
		    $id_pays = $enregistrement->Id_Pays;
		    $arr[$id_pays]['Abreviation'] = $enregistrement->Abreviation;
		    $arr[$id_pays]['nom'] = $enregistrement->Nom;
		    $arr[$id_pays]['drapeau_icone'] = $enregistrement->drapeau_icone;
		    $arr[$id_pays]['drapeau_petit'] = $enregistrement->drapeau_petit;
		    $arr[$id_pays]['drapeau_moyen'] = $enregistrement->drapeau_moyen;
		    $arr[$id_pays]['drapeau_grand'] = $enregistrement->drapeau_grand;    
		}
		$db = null;
		return $arr;
	}
?>