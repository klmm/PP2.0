<?php
	session_start();

	$joueur = $_SESSION['LoginJoueur'];
	if ($joueur == ''){
	    echo 'Connectez-vous !';
	    return;
	}
	
	$id_jeu = $_POST['id_jeu'];
	$id_cal = $_POST['id_cal'];
	$id_comm = $_POST['id_comm'];
	$bLike = $_POST['type'];

	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	
	// Like déjà existant ?
	$sql = "SELECT * FROM jeu_commentaire_like WHERE id_jeu_commentaire=? AND joueur=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_comm,PDO::PARAM_INT);
	$prep->bindValue(2,$joueur,PDO::PARAM_STR);
	$prep->execute();
	
	if( $enregistrement = $prep->fetch() )
	{
	    echo 'Vous aviez déjà aimé ce commentaire...';
	    return;
	}

	
	// Ajout du like
	$sql = "INSERT INTO jeu_commentaire_like(id_jeu_commentaire,b_like,joueur,id_jeu,id_calendrier) VALUES(?,?,?,?,?)";
	$prep2 = $db->prepare($sql);
	//$prep2->setFetchMode(PDO::FETCH_OBJ);
	$prep2->bindValue(1,$id_comm,PDO::PARAM_INT);
	$prep2->bindValue(2,$bLike,PDO::PARAM_BOOL);
	$prep2->bindValue(3,$joueur,PDO::PARAM_STR);
	$prep2->bindValue(4,$id_jeu,PDO::PARAM_INT);
	$prep2->bindValue(5,$id_cal,PDO::PARAM_INT);
	
	$res = $prep2->execute();
	
	if( $res == false )
	{
	    echo 'Erreur...';
	    return;
	}
	
	// Incrémentation du nombre de like/dislike du commentaires
	if ($bLike == true){
		$sql = "UPDATE ArticlesCommentaire SET NombreLikes = NombreLikes + 1 WHERE IDCommentaire = ?";
		$prep3 = $db->prepare($sql);
		$prep3->setFetchMode(PDO::FETCH_OBJ);
		$prep3->bindValue(1,$id_comm,PDO::PARAM_INT);
		$res = $prep3->execute();
	}
	else{
		$sql = "UPDATE ArticlesCommentaire SET NombreDislikes = NombreDislikes + 1 WHERE IDCommentaire = ?";
		$prep3 = $db->prepare($sql);
		$prep3->setFetchMode(PDO::FETCH_OBJ);
		$prep3->bindValue(1,$id_comm,PDO::PARAM_INT);
		$res = $prep3->execute();
	}
	
	if( $res == false )
	{
	    echo 'Erreur incr...';
	    return;
	}
	
	echo 'success';
?>