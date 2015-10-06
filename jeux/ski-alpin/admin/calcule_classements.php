<?php

    $nb_paris_max = 0;

    function calcule_classements($id_jeu){
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	global $nb_paris_max;
	
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$calendrier_tous = get_calendrier_jeu($id_jeu);
    
	$POINTS_CLASSEMENTS_PAR_POINTS = [0,25,20,16,12,10,7,5,3,2,1];
	$nb_classements_par_points = sizeof($POINTS_CLASSEMENTS_PAR_POINTS) - 1;
    
	$jeu = get_jeu_id($id_jeu);
	$url = $jeu['url'];
	
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
	    $bonus_risque = $enregistrement->bonus_risque;
	    
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
	    if($pos <= 3 && $pos > 0){
	       $tab_classements[$joueur]['général']['podiums'] += 1;
	    }
	    if($pos <= 10 && $pos > 0){
	       $tab_classements[$joueur]['général']['top10'] += 1;
	    }
	    if($pos <= $nb_classements_par_points){
	       $tab_classements[$joueur]['général']['par_points'] += $POINTS_CLASSEMENTS_PAR_POINTS[$pos];
	    }
	    $tab_classements[$joueur]['général']['risque'] += $bonus_risque;
	    if($tab_classements[$joueur]['général']['score_min'] == null || $score < $tab_classements[$joueur]['score_min']){
		$tab_classements[$joueur]['général']['score_min'] = $score;
	    }
	    
	    // SPECIALITE
	    $tab_classements[$joueur][$spec_genre]['genre'] = $genre;
	    $tab_classements[$joueur][$spec_genre]['score_total'] += $score;
	    $tab_classements[$joueur][$spec_genre]['nb_trouves'] += $nb_trouves;
	    $tab_classements[$joueur][$spec_genre]['nb_pronos'] += 1;
	    if($pos == 1){
		$tab_classements[$joueur][$spec_genre]['victoires'] += 1;
	    }
	    if($pos <= 3 && $pos > 0){
	       $tab_classements[$joueur][$spec_genre]['podiums'] += 1;
	    }
	    if($pos <= 10 && $pos > 0){
	       $tab_classements[$joueur][$spec_genre]['top10'] += 1;
	    }
	    if($pos <= $nb_classements_par_points){
	       $tab_classements[$joueur][$spec_genre]['par_points'] += $POINTS_CLASSEMENTS_PAR_POINTS[$pos];
	    }
	    $tab_classements[$joueur][$spec_genre]['risque'] += $bonus_risque;
	    if($tab_classements[$joueur][$spec_genre]['score_min'] == null || $score < $tab_classements[$joueur]['score_min']){
		$tab_classements[$joueur][$spec_genre]['score_min'] = $score;
	    }
	}
	
	$db = null;
	
	// MOYENNES
	foreach($tab_classements as $key => $value){
	    $tab_classements[$key]['risque'] = round($tab_classements[$key]['risque']/$tab_classements[$key]['nb_pronos'],2);
	    $tab_classements[$key]['nb_trouves'] = round($tab_classements[$key]['nb_trouves']/$tab_classements[$key]['nb_pronos'],2);
	    
	    $nb_paris = $value['nb_pronos'];
	    if($nb_paris > $nb_paris_max){
		$nb_paris_max = $nb_paris;
	    }
	}
	
	calcule_classement_general($id_jeu,$tab_classements,$url);
	calcule_classement_par_points($tab_classements,$url);
	calcule_classement_victoires($tab_classements,$url);
	calcule_classement_podiums($tab_classements,$url);
	calcule_classement_top10($tab_classements,$url);
	calcule_classement_risque($tab_classements,$url);
	calcule_classement_regularite($tab_classements,$url);
	

	// ------------ ARCHIVE CLASSEMENTS ----------//
	/*$old_path = getcwd();
	chdir($_SERVER['DOCUMENT_ROOT'] . '/admin/scripts/');
	$message=shell_exec('./archive_classements.sh ' . $_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements' . ' etape');
	chdir($old_path);*/
	// ------------ ARCHIVE CLASSEMENTS ----------//
    }
    
    function compare_score_total($a, $b)
    {
      return strnatcmp($b['score_total'], $a['score_total']);
    }
    
    function calcule_classement_general($id_jeu,$tab,$url){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_inscriptions.php');

	usort($tab, 'compare_score_total');
	
	$nom_fichier = '00-General.txt';
	
	$titre = 'Général';
	$descr = 'Récompense le meilleur pronostiqueur du jeu';
	$colonnes = ';;Score;Pronos';
	$taille_colonnes = '2;5;3;2';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['score_total'];
	    $login = $joueur['joueur'];
	    $nb_paris = $joueur['nb_pronos'];
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    $line[] = $pos . ';' . $login . ';' . $score . ';' . $nb_paris;
	    $pos_cpt++;
	    $score_actuel = $score;
	    update_inscription($id_jeu,$login,$pos);
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    
    function compare_par_points($a, $b)
    {
      return strnatcmp($b['par_points'], $a['par_points']);
    }
    
    function calcule_classement_par_points($tab,$url){
	usort($tab, 'compare_par_points');
	
	$nom_fichier = '01-Par points.txt';
	
	$titre = 'Points';
	$descr = 'Récompense le pronostiqueur le plus régulier';
	$colonnes = ';;Score;Pronos';
	$taille_colonnes = '2;5;3;2';
	
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
	    $line[] = $pos . ';' . $login . ';' . $score . ';' . $nb_paris;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    function compare_victoires($a, $b)
    {
      return strnatcmp($b['victoires'], $a['victoires']);
    }
    
    function calcule_classement_victoires($tab,$url){
	usort($tab, 'compare_victoires');
	
	$nom_fichier = '03-Victoires.txt';
	
	$titre = 'Victoires';
	$descr = 'Récompense les vainqueurs d\'étape';
	$colonnes = ';;V;Pronos';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['victoires'];
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
	    $line[] = $pos . ';' . $login . ';' . $score . ';' . $nb_paris;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
 
    function compare_podiums($a, $b)
    {
      return strnatcmp($b['podiums'], $a['podiums']);
    }
    
    function calcule_classement_podiums($tab,$url){
	usort($tab, 'compare_podiums');
	
	$nom_fichier = '05-Podiums.txt';
	
	$titre = 'Podiums';
	$descr = 'Récompense les trusteurs de podiums';
	$colonnes = ';;Pod.;Pronos';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['podiums'];
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
	    $line[] = $pos . ';' . $login . ';' . $score . ';' . $nb_paris;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    function compare_top10($a, $b)
    {
      return strnatcmp($b['top10'], $a['top10']);
    }
    
    function calcule_classement_top10($tab,$url){
	usort($tab, 'compare_top10');
	
	$nom_fichier = '06-Top10.txt';
	
	$titre = 'Top 10';
	$descr = 'Récompense les habitués aux Top 10';
	$colonnes = ';;Top10;Pronos';
	$taille_colonnes = '2;5;3;2';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['top10'];
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
	    $line[] = $pos . ';' . $login . ';' . $score . ';' . $nb_paris;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    function compare_risque($a, $b)
    {
	if (floatval($b['risque']) < floatval($a['risque'])){
	    return -1;
	}
	else{
	    return 1;
	}
    }
    
    function calcule_classement_risque($tab,$url){
	global $nb_paris_max;
	usort($tab, 'compare_risque');
	
	$nom_fichier = '07-Risque.txt';
	
	$titre = 'Risque';
	$descr = 'Récompense les preneurs de risque';
	$colonnes = ';;Moy;Pronos';
	$taille_colonnes = '2;5;3;2';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['risque'];
	    $login = $joueur['joueur'];
	    $nb_paris = $joueur['nb_pronos'];
	    
	    if($nb_paris < 0.5*$nb_paris_max){
		continue;
	    }
	    
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    $line[] = $pos . ';' . $login . ';' . $score . '%;' . $nb_paris;
	    $pos_cpt++;
	    $score_actuel = $score;
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    function compare_regularite($a, $b)
    {
	if (floatval($b['nb_trouves']) < floatval($a['nb_trouves'])){
	    return -1;
	}
	else{
	    return 1;
	}
	//return strnatcmp($b['nb_trouves'], $a['nb_trouves']);
    }
    
    function calcule_classement_regularite($tab,$url){
	global $nb_paris_max;
	usort($tab, 'compare_regularite');
	
	$nom_fichier = '08-Regularite.txt';
	
	$titre = 'Régularité';
	$descr = 'Récompense les plus réguliers';
	$colonnes = ';;Moy;Pronos';
	$taille_colonnes = '2;5;3;2';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['nb_trouves'];
	    $login = $joueur['joueur'];
	    $nb_paris = $joueur['nb_pronos'];
	    if($nb_paris < 0.5*$nb_paris_max){
		continue;
	    }
	    
	    if($score != $score_actuel){
		$pos_actuel = $pos_cpt;
		$pos = $pos_cpt;
	    }
	    else{
		$pos = $pos_actuel;
	    }
	    $line[] = $pos . ';' . $login . ';' . $score . ';' . $nb_paris;
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