<?php

    $specialites = array(   'SprintH'		=> array('id' => '00', 'specialite' => 'Sprint', 'genre' => 'H'),
			    'SprintF'		=> array('id' => '01', 'specialite' => 'Sprint', 'genre' => 'F'),
			    'PoursuiteH'	=> array('id' => '02', 'specialite' => 'Poursuite', 'genre' => 'H'),
			    'PoursuiteF'	=> array('id' => '03', 'specialite' => 'Poursuite', 'genre' => 'F'),
			    'IndividuelleH'	=> array('id' => '04', 'specialite' => 'Individuelle', 'genre' => 'H'),
			    'IndividuelleF'	=> array('id' => '05', 'specialite' => 'Individuelle', 'genre' => 'F'),
			    'Mass startH'	=> array('id' => '06', 'specialite' => 'Mass start', 'genre' => 'H'),
			    'Mass startF'	=> array('id' => '07', 'specialite' => 'Mass start', 'genre' => 'F'),
			    'RelaisH'	    => array('id' => '08', 'specialite' => 'Relais', 'genre' => 'H'),
			    'RelaisF'	    => array('id' => '09', 'specialite' => 'Relais', 'genre' => 'F'),
			    'RelaisM'	    => array('id' => '10', 'specialite' => 'Relais', 'genre' => 'M')
			);

    function calcule_classements($id_jeu,$id_cal){
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_calendrier.php';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	
	global $specialites;
	    
	// RECUPERATION DU JEU
	$jeu = get_jeu_id($id_jeu);
	$url = $jeu['url'];
	
	// REUPERATION DU CALENDRIER
	$calendrier_tous = biathlon_get_calendrier_jeu($id_jeu);
	$date = $calendrier_tous[$id_cal]['date_debut'];
	$mois = substr($date, 0, 7);
	$spec_genre = $calendrier_tous[$id_cal]['specialite'] . $calendrier_tous[$id_cal]['genre'];
	$id_weekend = $calendrier_tous[$id_cal]['id_weekend'];
	
	// OUTILS POUR CALCUL DES POINTS
	//$POINTS_CLASSEMENTS_PAR_POINTS = [0,25,20,16,12,10,7,5,3,2,1];
	$POINTS_CLASSEMENTS_PAR_POINTS = [0,100,80,60,50,45,40,36,32,29,26,24,22,20,18,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1];
	$nb_classements_par_points = sizeof($POINTS_CLASSEMENTS_PAR_POINTS) - 1;
	
	// RECUPERATION DES PRONOSTICS TERMINES
	$bdd = new Connexion();
	$db = $bdd->getDB();	
	$sql = "SELECT * FROM biathlon_prono WHERE EXISTS (
				    SELECT id_biathlon_calendrier 
				    FROM biathlon_calendrier 
				    WHERE biathlon_prono.id_calendrier=biathlon_calendrier.id_biathlon_calendrier AND id_jeu=? AND traite=1)
				AND id_jeu=?";
	
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	while($enregistrement = $prep->fetch()){
	    $id_prono = $enregistrement->id_biathlon_prono;
	    $id_cal = $enregistrement->id_calendrier;
	    $pos = $enregistrement->classement;
	    $score = $enregistrement->score_total;
	    $nb_trouves = $enregistrement->nb_trouves;
	    $joueur = $enregistrement->joueur;
	    
	    // INFOS CALENDRIER
	    $specialite = $calendrier_tous[$id_cal]['specialite'];
	    $genre = $calendrier_tous[$id_cal]['genre'];
	    $spec_genre_cal = $specialite . '' . $genre;
	    $id_weekend_cal = $calendrier_tous[$id_cal]['id_weekend'];
	    	    
	    // CLASSEMENT GENERAL
	    $tab_classements[$joueur]['général']['joueur'] = $joueur;
	    $tab_classements[$joueur]['général']['score_total'] += $score;
	    $tab_classements[$joueur]['général']['nb_pronos'] += 1;
	    if($pos == 1){
		$tab_classements[$joueur]['général']['victoires'] += 1;
	    }
	    if($pos <= $nb_classements_par_points){
	       $tab_classements[$joueur]['général']['par_points'] += $POINTS_CLASSEMENTS_PAR_POINTS[$pos];
	    }
	    
	    // SPECIALITE
	    if($spec_genre == $spec_genre_cal){
		$tab_classements[$joueur]['specialite']['joueur'] = $joueur;
		$tab_classements[$joueur]['specialite']['score_total'] += $score;
		$tab_classements[$joueur]['specialite']['nb_pronos'] += 1;
		if($pos == 1){
		    $tab_classements[$joueur]['specialite']['victoires'] += 1;
		}
	    }
	    
	    // MENSUEL
	    if(substr($calendrier_tous[$id_cal]['date_debut'],0,7) == $mois){
		$tab_classements[$joueur]['mensuel']['joueur'] = $joueur;
		$tab_classements[$joueur]['mensuel']['score_total'] += $score;
		$tab_classements[$joueur]['mensuel']['nb_pronos'] += 1;
		if($pos == 1){
		    $tab_classements[$joueur]['mensuel']['victoires'] += 1;
		}
	    }
	    
	    // WEEKEND
	    if($id_weekend == $id_weekend_cal){
		$tab_classements[$joueur]['weekend']['joueur'] = $joueur;
		$tab_classements[$joueur]['weekend']['score_total'] += $score;
		$tab_classements[$joueur]['weekend']['nb_pronos'] += 1;
		if($pos == 1){
		    $tab_classements[$joueur]['weekend']['victoires'] += 1;
		}
	    }
	}
			
	calcule_classement_general($id_jeu,$tab_classements,$url);	
	//calcule_classement_mensuel($id_jeu,$date,$tab_classements,$url);
	calcule_classement_weekend($id_weekend,$tab_classements,$url);
	calcule_classement_par_points($tab_classements,$url);
	calcule_classement_specialite($id_jeu,$spec_genre,$tab_classements,$url);
		
	$db = null;
    }
    
    function compare_score_total($a, $b)
    {
      return strnatcmp($b['général']['score_total'], $a['général']['score_total']);
    }
    
    function calcule_classement_general($id_jeu,$tab,$url){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_inscriptions.php');

	usort($tab, 'compare_score_total');
	
	$nom_fichier = '00-General.txt';
	
	$titre = 'Général';
	$descr = 'Récompense le meilleur pronostiqueur du jeu';
	$colonnes = ';;V;Pronos;Score';
	$taille_colonnes = '1;4;2;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['général']['score_total'];
	    $login = $joueur['général']['joueur'];
	    $nb_paris = $joueur['général']['nb_pronos'];
	    $nb_victoires = $joueur['général']['victoires'];
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    $line[] = $pos . ';' . $login . ';' . $nb_victoires . ';' . $nb_paris . ';' . $score;
	    $pos_cpt++;
	    $score_actuel = $score;
	    update_inscription($id_jeu, $login, $pos);
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    
    function compare_score_mensuel($a, $b)
    {
      return strnatcmp($b['mensuel']['score_total'], $a['mensuel']['score_total']);
    }
    
    function calcule_classement_mensuel($id_jeu,$date,$tab,$url){
	usort($tab, 'compare_score_mensuel');
	
	$nom_fichier = '01-Mensuel.txt';
	
	setlocale(LC_TIME, 'fr_FR');
	$date2 = strtotime($date);
	$unix = mktime(date('H',$date2),date('i',$date2),date('s',$date2),date('n',$date2),date('j',$date2),date('Y',$date2));
	
	$titre = ucfirst(strftime('%B %Y', $unix));
	
	$descr = 'Récompense le meilleur pronostiqueur du jeu';
	$colonnes = ';;V;Pronos;Score';
	$taille_colonnes = '1;4;2;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['mensuel']['score_total'];
	    $login = $joueur['mensuel']['joueur'];
	    $nb_paris = $joueur['mensuel']['nb_pronos'];
	    $nb_victoires = $joueur['mensuel']['victoires'];
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    $line[] = $pos . ';' . $login . ';' . $nb_victoires . ';' . $nb_paris . ';' . $score;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    function compare_score_weekend($a, $b)
    {
      return strnatcmp($b['weekend']['score_total'], $a['weekend']['score_total']);
    }
    
    function calcule_classement_weekend($id_weekend,$tab,$url){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_weekend.php');
	
	$weekend = get_weekend($id_weekend);
	
	usort($tab, 'compare_score_weekend');
	
	$nom_fichier = str_replace(array('é'), array('e'), '01' . $weekend['id'] . '-' . $weekend['lieu'] . '.txt');
	$nom_fichier = ltrim($nom_fichier, "' ");
	
	setlocale(LC_TIME, 'fr_FR');
	$date2 = strtotime($date);
	$unix = mktime(date('H',$date2),date('i',$date2),date('s',$date2),date('n',$date2),date('j',$date2),date('Y',$date2));
	
	$titre = $weekend['lieu'];
	
	$descr = $weekend['lieu'];
	$colonnes = ';;V;Pronos;Score';
	$taille_colonnes = '1;4;2;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['weekend']['score_total'];
	    $login = $joueur['weekend']['joueur'];
	    $nb_paris = $joueur['weekend']['nb_pronos'];
	    $nb_victoires = $joueur['weekend']['victoires'];
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    $line[] = $pos . ';' . $login . ';' . $nb_victoires . ';' . $nb_paris . ';' . $score;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    function compare_score_total_spe($a, $b)
    {
	return strnatcmp($b['specialite']['score_total'], $a['specialite']['score_total']);
    }
    
    function calcule_classement_specialite($id_jeu,$spe,$tab,$url){
	global $specialites;
	usort($tab, 'compare_score_total_spe');
	
	$spec_genre = strtr($specialites[$spe]['specialite'],'é','e') . $specialites[$spe]['genre'];
		
	$nom_fichier = str_replace(array('é', " "), array('e',""), '1' . $specialites[$spe]['id'] . '-' . $spec_genre . '.txt');
	
	$titre = $specialites[$spe]['specialite'] . ' (' . $specialites[$spe]['genre'] . ')';
	$descr = '';
	$colonnes = ';;V;Pronos;Score';
	$taille_colonnes = '1;4;2;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
		
	foreach($tab as $key => $joueur){
	    $score = $joueur['specialite']['score_total'];
	    $login = $joueur['specialite']['joueur'];
	    $nb_paris = $joueur['specialite']['nb_pronos'];
	    $nb_victoires = $joueur['specialite']['victoires'];
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    $line[] = $pos . ';' . $login . ';' . $nb_victoires . ';' . $nb_paris . ';' . $score;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	if(sizeof($line) > 0){
	    $contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	    foreach($line as $key => $ligne){
		$contenu .= $ligne . PHP_EOL;
	    }

	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
	}
    }
    
    function compare_par_points($a, $b)
    {
      return strnatcmp($b['général']['par_points'], $a['général']['par_points']);
    }
    
    function calcule_classement_par_points($tab,$url){
	usort($tab, 'compare_par_points');
	
	$nom_fichier = '02-Points.txt';
	
	$titre = 'IBU';
	$descr = 'Récompense le pronostiqueur le plus régulier';
	$colonnes = ';;Pronos;Points';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['général']['par_points'];
	    $login = $joueur['général']['joueur'];
	    $nb_paris = $joueur['général']['nb_pronos'];
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    if($score == ''){
		break;
	    }
	    $line[] = $pos . ';' . $login . ';' . $nb_paris . ';' . $score;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
?>