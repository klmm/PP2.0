<?php

    $specialites = array(   'SlalomH'		=> array('id' => '00', 'specialite' => 'Slalom', 'genre' => 'H'),
			    'SlalomF'		=> array('id' => '01', 'specialite' => 'Slalom', 'genre' => 'F'),
			    'Slalom GéantH'	=> array('id' => '02', 'specialite' => 'Slalom Géant', 'genre' => 'H'),
			    'Slalom GéantF'	=> array('id' => '03', 'specialite' => 'Slalom Géant', 'genre' => 'F'),
			    'Super GH'		=> array('id' => '04', 'specialite' => 'Super G', 'genre' => 'H'),
			    'Super GF'		=> array('id' => '05', 'specialite' => 'Super G', 'genre' => 'F'),
			    'DescenteH'		=> array('id' => '06', 'specialite' => 'Descente', 'genre' => 'H'),
			    'DescenteF'		=> array('id' => '07', 'specialite' => 'Descente', 'genre' => 'F'),
			    'Super CombinéH'	=> array('id' => '08', 'specialite' => 'Super Combiné', 'genre' => 'H'),
			    'Super CombinéF'	=> array('id' => '09', 'specialite' => 'Super Combiné', 'genre' => 'F'));

    function calcule_classements($id_jeu,$id_cal){
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	
	global $specialites;
	    
	// RECUPERATION DU JEU
	$jeu = get_jeu_id($id_jeu);
	$url = $jeu['url'];
	
	// REUPERATION DU CALENDRIER
	$calendrier_tous = get_calendrier_jeu($id_jeu);
	$date = $calendrier_tous[$id_cal]['date_debut'];
	$mois = substr($date, 0, 7);
	$spec_genre = $calendrier_tous[$id_cal]['specialite'] . $calendrier_tous[$id_cal]['genre'];
	
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
	    $spec_genre_cal = $specialite . '' . $genre;
	    	    
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
	}
			
	calcule_classement_general($id_jeu,$tab_classements,$url);	
	calcule_classement_mensuel($id_jeu,$date,$tab_classements,$url);
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
    
    function compare_score_total_spe($a, $b)
    {
	return strnatcmp($b['specialite']['score_total'], $a['specialite']['score_total']);
    }
    
    function calcule_classement_specialite($id_jeu,$spe,$tab,$url){
	global $specialites;
	usort($tab, 'compare_score_total_spe');
	
	$spec_genre = strtr($specialites[$spe]['specialite'],'é','e') . $specialites[$spe]['genre'];
		
	$nom_fichier = '1' . $specialites[$spe]['id'] . '-' . $spec_genre . '.txt';
	
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
	
	$nom_fichier = '02-Par points.txt';
	
	$titre = 'FIS';
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