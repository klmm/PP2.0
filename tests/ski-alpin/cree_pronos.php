<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php');

    $id_jeu = $_POST['id_jeu'];
    $id_cal = $_POST['id_cal'];
    $login_base = 'Testtest0';
    $NB_PRONOS = 500;
    
    $calendrier = get_calendrier($id_cal);
    $genre = $calendrier['genre'];
    $athletes = get_athletes_activite_genre($genre);
    $nb_athletes = sizeof($athletes);
    
    function compare_nom($a, $b)
    {
      return strnatcmp($a['nom'], $b['nom']);
    }
    usort($athletes, 'compare_nom');
    
    // ------------ CONNEXION BDD ----------//
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    // ------------ CONNEXION BDD ----------//
    
    
    for ($j=0;$j<$NB_PRONOS;$j++){
	// ------------ RECUPERATION DES PARAMETRES ----------//
	$login = $login_base . $j;
	$bonus = rand(0,30);
	$prono = '';

	for($i=0;$i<10;$i++) {
	    if($i!=0){
		$prono .= ';';
	    }
	    $prono .= $athletes[rand(0,$nb_athletes-1)]['id_ski_alpin_athlete'];
	}
	// ------------ RECUPERATION DES PARAMETRES ----------//


	// ------------ PRONO DEJA FAIT ? ----------//
	$sql = "SELECT * FROM ski_alpin_prono WHERE id_jeu=? AND id_calendrier=? AND joueur=?";
	$prep = $db->prepare($sql);

	$sql2 = "UPDATE ski_alpin_prono SET prono=?, bonus_risque=? WHERE id_ski_alpin_prono=?";
	$prep2 = $db->prepare($sql2);

	$sql3 = "INSERT INTO ski_alpin_prono(id_jeu,id_calendrier,joueur,prono,points_prono,score_base,bonus_nombre,bonus_risque,score_total,classement,nb_trouves) VALUES(?,?,?,?,'',0,0,?,0,0,0)";
	$prep3 = $db->prepare($sql3);

	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_cal,PDO::PARAM_INT);
	$prep->bindValue(3,$login,PDO::PARAM_STR);

	$res = $prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $prep->fetch();
	if($enregistrement){
	    $idpronofait = $enregistrement->id_ski_alpin_prono;
	}
	else{
	    $idpronofait = 0;
	}
	// ------------ PRONO DEJA FAIT ? ----------//
	


	// ------------ ENVOI DU PRONO ----------//
	if ($idpronofait != 0){

	    $prep2->bindValue(1,$prono,PDO::PARAM_STR);
	    $prep2->bindValue(2,$bonus,PDO::PARAM_INT);
	    $prep2->bindValue(3,$idpronofait,PDO::PARAM_INT);
	    $res = $prep2->execute();
	}
	else{

	    $prep3->bindValue(1,$id_jeu,PDO::PARAM_INT);
	    $prep3->bindValue(2,$id_cal,PDO::PARAM_INT);
	    $prep3->bindValue(3,$login,PDO::PARAM_STR);
	    $prep3->bindValue(4,$prono,PDO::PARAM_STR);
	    $prep3->bindValue(5,$bonus,PDO::PARAM_INT);
	    $res = $prep3->execute();
	}
	
	echo $res . ' - ';
    }
?>