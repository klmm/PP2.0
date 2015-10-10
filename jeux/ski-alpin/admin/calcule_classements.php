<?php

    $ite_spe = 0;
    $specialites = array(   0 => array('spec_genre' => 'SlalomH', 'specialite' => 'Slalom', 'genre' => 'H'),
			    1 => array('spec_genre' => 'SlalomF', 'specialite' => 'Slalom', 'genre' => 'F'),
			    2 => array('spec_genre' => 'Slalom GéantH', 'specialite' => 'Slalom Géant', 'genre' => 'H'),
			    3 => array('spec_genre' => 'Slalom GéantF', 'specialite' => 'Slalom Géant', 'genre' => 'F'),
			    4 => array('spec_genre' => 'Super GH', 'specialite' => 'Super G', 'genre' => 'H'),
			    5 => array('spec_genre' => 'Super GF', 'specialite' => 'Super G', 'genre' => 'F'),
			    6 => array('spec_genre' => 'DescenteH', 'specialite' => 'Descente', 'genre' => 'H'),
			    7 => array('spec_genre' => 'DescenteF', 'specialite' => 'Descente', 'genre' => 'F'),
			    8 => array('spec_genre' => 'Super CombinéH', 'specialite' => 'Super Combiné', 'genre' => 'H'),
			    9 => array('spec_genre' => 'Super CombinéF', 'specialite' => 'Super Combiné', 'genre' => 'F'));

    function calcule_classements($id_jeu,$date){
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	
	global $ite_spe;
	global $specialites;
    
	// RECUPERATION DU JEU
	$jeu = get_jeu_id($id_jeu);
	$url = $jeu['url'];
	
	// REUPERATION DU CALENDRIER
	$calendrier_tous = get_calendrier_jeu($id_jeu);
	$mois = substr($date, 0, 7);
	
	// OUTILS POUR CALCUL DES POINTS
	//$POINTS_CLASSEMENTS_PAR_POINTS = [0,25,20,16,12,10,7,5,3,2,1];
	$POINTS_CLASSEMENTS_PAR_POINTS = [0,100,80,60,50,45,40,36,32,29,26,24,22,20,18,16,15,14,13,12,11,10,9,8,7,6,5,4,3,2,1];
	$nb_classements_par_points = sizeof($POINTS_CLASSEMENTS_PAR_POINTS) - 1;
		
	// RECUPERATION DES PRONOSTICS TERMINES
	$bdd = new Connexion();
	$db = $bdd->getDB();	
	$sql = "SELECT * FROM ski_alpin_prono WHERE EXISTS (
				    SELECT id_ski_alpin_calendrier 
				    FROM ski_alpin_calendrier 
				    WHERE ski_alpin_prono.id_calendrier=ski_alpin_calendrier.id_ski_alpin_calendrier AND id_jeu=? AND traite=1)
				AND id_jeu=?";
	
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	while($enregistrement = $prep->fetch()){
	    $id_prono = $enregistrement->id_ski_alpin_prono;
	    $id_cal = $enregistrement->id_calendrier;
	    $pos = $enregistrement->classement;
	    $score = $enregistrement->score_total;
	    $nb_trouves = $enregistrement->nb_trouves;
	    $joueur = $enregistrement->joueur;
	    
	    // INFOS CALENDRIER
	    $specialite = $calendrier_tous[$id_cal]['specialite'];
	    $genre = $calendrier_tous[$id_cal]['genre'];
	    $spec_genre = $specialite . '' . $genre;
	    	    
	    // CLASSEMENT GENERAL
	    $tab_classements[$joueur]['général']['joueur'] = $joueur;
	    $tab_classements[$joueur]['général']['score_total'] += $score;
	    $tab_classements[$joueur]['général']['nb_trouves'] += $nb_trouves;
	    $tab_classements[$joueur]['général']['nb_pronos'] += 1;
	    if($pos == 1){
		$tab_classements[$joueur]['général']['victoires'] += 1;
	    }
	    if($pos <= $nb_classements_par_points){
	       $tab_classements[$joueur]['général']['par_points'] += $POINTS_CLASSEMENTS_PAR_POINTS[$pos];
	    }
	    
	    // SPECIALITE
	    $tab_classements[$joueur][$spec_genre]['joueur'] = $joueur;
	    $tab_classements[$joueur][$spec_genre]['specialite'] = $specialite;
	    $tab_classements[$joueur][$spec_genre]['genre'] = $genre;
	    $tab_classements[$joueur][$spec_genre]['score_total'] += $score;
	    $tab_classements[$joueur][$spec_genre]['nb_trouves'] += $nb_trouves;
	    $tab_classements[$joueur][$spec_genre]['nb_pronos'] += 1;
	    if($pos == 1){
		$tab_classements[$joueur][$spec_genre]['victoires'] += 1;
	    }
	    
	    // MENSUEL
	    if(substr($calendrier_tous[$id_cal]['date_debut'],0,7) == $mois){
		$tab_classements[$joueur]['mensuel']['joueur'] = $joueur;
		$tab_classements[$joueur]['mensuel']['specialite'] = $specialite;
		$tab_classements[$joueur]['mensuel']['genre'] = $genre;
		$tab_classements[$joueur]['mensuel']['score_total'] += $score;
		$tab_classements[$joueur]['mensuel']['nb_trouves'] += $nb_trouves;
		$tab_classements[$joueur]['mensuel']['nb_pronos'] += 1;
		if($pos == 1){
		    $tab_classements[$joueur]['mensuel']['victoires'] += 1;
		}
	    }
	}
	
	
	
	calcule_classement_general($id_jeu,$tab_classements,$url);
	calcule_classement_mensuel($id_jeu,$date,$tab_classements,$url);
	calcule_classement_par_points($tab_classements,$url);
	foreach ($specialites as $key => $spe){
	    calcule_classement_specialite($id_jeu,$spe,$tab_classements,$url);
	    $ite_spe++;
	}
	
	
	
	$db = null;
    }
    
    function compare_score_total($a, $b)
    {
      return strnatcmp($b['général']['score_total'], $a['général']['score_total']);
    }
    
    function calcule_classement_general($id_jeu,$tab,$url){
	usort($tab, 'compare_score_total');
	
	$nom_fichier = '00-General.txt';
	
	$titre = 'Général';
	$descr = 'Récompense le meilleur pronostiqueur du jeu';
	$colonnes = ';;V;J;Score';
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
	
	$titre = strftime('%B %Y', $unix);
	
	$descr = 'Récompense le meilleur pronostiqueur du jeu';
	$colonnes = ';;V;J;Score';
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
    
    function compare_score_total_spe($a, $b)
    {
	global $specialites;
	global $ite_spe;
	return strnatcmp($b[$specialites[$ite_spe]['spec_genre']]['score_total'], $a[$specialites[$ite_spe]['spec_genre']]['score_total']);
    }
    
    function calcule_classement_specialite($id_jeu,$spe,$tab,$url){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_inscriptions.php');
	global $specialites;
	global $ite_spe;
	usort($tab, 'compare_score_total_spe');
	
	$spec_genre = $specialites[$ite_spe]['spec_genre'];
		
	$nom_fichier = '1' . ($ite_spe) . '-' . $spec_genre . '.txt';
	
	$titre = $specialites[$ite_spe]['specialite'] . ' (' . $specialites[$ite_spe]['genre'] . ')';
	$descr = '';
	$colonnes = ';;V;J;Score';
	$taille_colonnes = '1;4;2;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur[$spec_genre]['score_total'];
	    $login = $joueur[$spec_genre]['joueur'];
	    $nb_paris = $joueur[$spec_genre]['nb_pronos'];
	    $nb_victoires = $joueur[$spec_genre]['victoires'];
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
	    update_inscription($id_jeu,$login,$pos);
	}
	
	if(sizeof($line)){
	    $contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	    foreach($line as $key => $ligne){
		$contenu .= $ligne . PHP_EOL;
	    }

	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
	}
    }
    
    function compare_par_points($a, $b)
    {
      return strnatcmp($b['par_points'], $a['par_points']);
    }
    
    function calcule_classement_par_points($tab,$url){
	usort($tab, 'compare_par_points');
	
	$nom_fichier = '02-Par points.txt';
	
	$titre = 'Points';
	$descr = 'Récompense le pronostiqueur le plus régulier';
	$colonnes = ';;Pronos;Points';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['par_points'];
	    $login = $joueur['joueur'];
	    $nb_paris = $joueur['nb_pronos'];
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