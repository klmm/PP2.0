<?php

    $delimiter = ' ';
	
    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_athlete.php');    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
    
    $bdd = new Connexion();
    $db = $bdd->getDB();
    
    // RECUPERATION DES PARAMETRES
    $type = $_POST['type'];
    $genre = $_POST['genre'];
    $url = $_POST['url'];
    
    // RECUPERATION DU CLASSEMENT
    $html = file_get_contents($url);
    $html = str_replace('<', '', $html);
    echo 'XX';
    echo $html;
    
    switch($type){
	case "startlist":
	    $debut = "<th data-hide='phone'>FIS Points</th>";
	    $fin = "footer ";
	    $debut_ath = 'type=result">';
	    $fin_ath = "</a>";
	    break;
	
	case "course":
	    $debut = '<tbody data-bind="foreach: itemsOnCurrentPage"><tr';
	    $fin = "schedule_race_reports";
	    $debut_ath = 'data-bind="text: Name">';
	    $fin_ath = "</td>";
	    break;
	
	case "classement":
	    $debut = '<div class="bloc-tab">';
	    $fin = "footer ";
	    $debut_ath = 'type=st-WC">';
	    $fin_ath = "</a>";
	    break;
	
	default:
	    echo 'KO';
	    return;
	    break;
    }
    
    
    // MISE EN FORME DU CLASSEMENT
    $html = explode($debut, $html);
    $html = explode($fin, $html[1]);
    $html = $html[0];
    
    $athletes_tmp = explode($debut_ath, $html);
    foreach($athletes_tmp as $key => $athlete){
	if($key != 0 && $key != sizeof($athletes_tmp)){
	    $tmp = explode($fin_ath,$athlete);
	    $athletes[] = $tmp[0];
	}
    }

    
    echo 'ATHLETES DU CLASSEMENT ET LEUR NOTE DANS LA SPECIALITE<br/>';

    $sql = "SELECT * FROM biathlon_athlete WHERE genre=? AND nom LIKE ? AND prenom LIKE ? LIMIT 1";
    $prep = $db->prepare($sql);   
    $prep->setFetchMode(PDO::FETCH_OBJ);

    foreach ($athletes as $athlete){
	$nom = explode(' ', $athlete);
	$prep->bindValue(1,$genre);
	$prep->bindValue(2,'%' . $nom[0] . '%',PDO::PARAM_STR);
	$prep->bindValue(3,'%' . $nom[sizeof($nom) - 1] . '%',PDO::PARAM_STR);
	$prep->execute();
	$ath = $prep->fetch();

	if($ath){
	    if($ath->retraite == 0){
		echo $nom[0] . ' ' . $nom[sizeof($nom) - 1] . ' : ' . $ath->nom . ' ' . $ath->prenom . ' - ' . ($ath->note_couche+$ath->note_debout+$ath->note_fond)/3 . '<br/>';
		$arr_id[] = $ath->id;
	    }
	}
	else{
	    echo '>>> A CREER >>>' . $nom[0] . ' ' . $nom[sizeof($nom) - 1] . '<br/>';
	}
    }

    echo '<br/>';
    echo 'ATHLETES A PLUS DE 65 DANS LA BASE, NON RECENSES DANS CE CLASSEMENT<br/>';

    $ids = implode(',',$arr_id);

    $sql2 = "SELECT * FROM biathlon_athlete WHERE retraite=0 AND genre=? AND (note_couche+note_debout+note_fond)/3>= 65 AND id NOT IN ($ids)";
    $prep2 = $db->prepare($sql2);
    $prep2->bindValue(1,$genre);
    $prep2->setFetchMode(PDO::FETCH_OBJ);
    $prep2->execute();


    while($ath = $prep2->fetch()){
	echo $ath->nom . ' ' . $ath->prenom . '<br/>';
    }
    
?>