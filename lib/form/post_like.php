<?php
	session_start();
	
	$joueur = $_SESSION['LoginJoueur'];
	if ($joueur == ''){
	    echo 'Connectez-vous !';
	    return;
	}
	
	$b_article = $_POST['b_article'];
	$id_art_jeu = $_POST['id_art_jeu'];
	
	$id_comm = $_POST['id_comm'];
	$bLike = $_POST['type'];

	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	
	// Like déjà existant ?
	$sql = "SELECT * FROM likes WHERE IDComm = ? AND Joueur = ?";
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

	if($b_article){
	    $sql = "INSERT INTO likes(IDComm,bLike,Joueur,IDArticle,id_cal,id_jeu) VALUES(?,?,?,?,0,0)";
	    $prep2 = $db->prepare($sql);
	    $prep2->setFetchMode(PDO::FETCH_OBJ);
	    $prep2->bindValue(1,$id_comm,PDO::PARAM_INT);
	    $prep2->bindValue(2,$bLike,PDO::PARAM_BOOL);
	    $prep2->bindValue(3,$joueur,PDO::PARAM_STR);
	    $prep2->bindValue(4,$id_art_jeu,PDO::PARAM_INT);

	    $res = $prep2->execute();
	}
	else{
	    $id_cal = $_POST['id_cal'];
	    
	    $sql = "INSERT INTO likes(IDComm,bLike,Joueur,IDArticle,id_jeu,id_cal) VALUES(?,?,?,0,?,?)";
	    $prep2 = $db->prepare($sql);
	    $prep2->bindValue(1,$id_comm,PDO::PARAM_INT);
	    $prep2->bindValue(2,$bLike,PDO::PARAM_BOOL);
	    $prep2->bindValue(3,$joueur,PDO::PARAM_STR);
	    $prep2->bindValue(4,$id_art_jeu,PDO::PARAM_INT);
	    $prep2->bindValue(5,$id_cal,PDO::PARAM_INT);
	    
	    $res = $prep2->execute();
	}
	
	if( $res == false )
	{
	    echo 'Erreur...';
	    return;
	}
	
	// Incrémentation du nombre de like/dislike du commentaires
	if ($bLike == true){
		$sql = "UPDATE commentaire SET NombreLikes = NombreLikes + 1 WHERE IDCommentaire = ?";
		$prep3 = $db->prepare($sql);
		$prep3->setFetchMode(PDO::FETCH_OBJ);
		$prep3->bindValue(1,$id_comm,PDO::PARAM_INT);
		$res = $prep3->execute();
	}
	else{
		$sql = "UPDATE commentaire SET NombreDislikes = NombreDislikes + 1 WHERE IDCommentaire = ?";
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