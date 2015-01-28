<?php

 	require_once('../../admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	// Récupération des paramètres POST
	$dhpub = $_POST['dhpub'];
	$categ = $_POST['categ'];
	$sous_cat = $_POST['sous_cat'];
	$titre = $_POST['titre'];
	$idRub = $_POST['idRub'];
	$numRub = $_POST['numRub'];
	$photo = $_POST['photo'];
	$contenu = $_POST['contenu'];
	
	
	// Ajout de l'article à la base
	$sql = "INSERT INTO Articles(DateHeurePub,Categorie,SousCategorie,Titre,IDRubrique,NumRubrique,Photo) VALUES(?,?,?,?,?,?,?)";
	
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$dhpub,PDO::PARAM_STR);
	$prep->bindValue(2,$categ,PDO::PARAM_STR);
	$prep->bindValue(3,$sous_cat,PDO::PARAM_STR);
	$prep->bindValue(4,$titre,PDO::PARAM_STR);
	$prep->bindValue(5,$idRub,PDO::PARAM_INT);
	$prep->bindValue(6,$numRub,PDO::PARAM_INT);
	$prep->bindValue(7,$photo,PDO::PARAM_STR);
	$prep->execute();
	
	$id_nouveau = $db->lastInsertId();
	
	
	
	// Ajout du fichier html contenant l'article
	
	
	
	// Ajout de l'image au serveur
	
	echo $id_nouveau;		
?>