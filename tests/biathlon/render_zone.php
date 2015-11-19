<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_calendrier.php';
    
    
    /*require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_pays.php';
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    $equipes = get_equipes_tous();
    
    $pays = get_pays_tous();
    
    
    $sql = "UPDATE biathlon_equipe SET nom=? WHERE id=?";
    $prep = $db->prepare($sql);
    
    foreach($equipes as $key => $equipe){
	$prep->bindValue(1,$pays[$equipe['id_pays']]['nom'],PDO::PARAM_STR);
	$prep->bindValue(2,$equipe['id_biathlon_equipe'],PDO::PARAM_INT);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$res = $prep->execute();
	if($res){
	    echo $pays[$equipe['id_pays']]['nom'] . ' ajouté<br/>';
	}
	else{
	    echo $pays[$equipe['id_pays']]['nom'] . ' raté<br/>';
	}
    }

    $db = null;
*/
?>