<?php

    function calcule_classements($id_jeu){
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	
	$bdd = new Connexion();
	$db = $bdd->getDB();
    
	$POINTS_CLASSEMENTS_PAR_POINTS = [0,25,20,16,12,10,7,5,3,2,1];
	$nb_classements_par_points = sizeof($POINTS_CLASSEMENTS_PAR_POINTS) - 1;
    
	$jeu = get_jeu_id($id_jeu);
	$url = $jeu['url'];
	
	$sql = "SELECT * FROM cyclisme_prono WHERE EXISTS (
				    SELECT id_cyclisme_calendrier 
				    FROM cyclisme_calendrier 
				    WHERE cyclisme_prono.id_calendrier=cyclisme_calendrier.id_cal AND id_jeu=? AND traite=1)";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	while($enregistrement = $prep->fetch()){
	    $id_prono = $enregistrement->id_cyclisme_prono;
	    $pos = $enregistrement->classement;
	    $score = $enregistrement->score_total;
	    $nb_trouves = $enregistrement->nb_trouves;
	    $joueur = $enregistrement->joueur;
	    $bonus_risque = $enregistrement->bonus_risque;
	    
	    // CLASSEMENT GENERAL
	    $tab_classements[$joueur]['joueur'] = $joueur;
	    
	    // CLASSEMENT GENERAL
	    $tab_classements[$joueur]['score_total'] += $score;
	    
	    // NOMBRE DE COUREURS TROUVES
	    $tab_classements[$joueur]['nb_trouves'] += $nb_trouves;
	    
	    // NOMBRE DE PRONOS
	    $tab_classements[$joueur]['nb_pronos'] += 1;
	    
	    // NOMBRE DE VICTOIRES
	    if($pos == 1){
		$tab_classements[$joueur]['victoires'] += 1;
	    }
	    
	    // NOMBRE DE PODIUMS
	    if($pos <= 3 && $pos > 0){
	       $tab_classements[$joueur]['podiums'] += 1;
	    }
	    
	    // NOMBRE DE TOP 10
	    if($pos <= 10 && $pos > 0){
	       $tab_classements[$joueur]['top10'] += 1;
	    }
	    
	    // CLASSEMENT PAR POINTS
	    if($pos <= $nb_classements_par_points){
	       $tab_classements[$joueur]['par_points'] += $POINTS_CLASSEMENTS_PAR_POINTS[$pos];
	    }
	    
	    // RISQUE
	    $tab_classements[$joueur]['risque'] += $bonus_risque;
	}
	
	// MOYENNES
	foreach($tab_classements as $key => $value){
	    $tab_classements[$key]['risque'] = round($tab_classements[$key]['risque']/$tab_classements[$key]['nb_pronos'],2);
	    $tab_classements[$key]['nb_trouves'] = round($tab_classements[$key]['nb_trouves']/$tab_classements[$key]['nb_pronos'],2);
	}
	
	calcule_classement_general($id_jeu,$tab_classements,$url);
	calcule_classement_par_points($tab_classements,$url);
	calcule_classement_victoires($tab_classements,$url);
	calcule_classement_podiums($tab_classements,$url);
	calcule_classement_top10($tab_classements,$url);
	calcule_classement_risque($tab_classements,$url);
	calcule_classement_regularite($tab_classements,$url);
    }
    
    function compare_score_total($a, $b)
    {
      return strnatcmp($b['score_total'], $a['score_total']);
    }
    
    function calcule_classement_general($id_jeu,$tab,$url){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_inscriptions.php');

	usort($tab, 'compare_score_total');
	
	$nom_fichier = '00-General.txt';
	
	$titre = 'Classement général';
	$descr = 'Récompense le meilleur pronostiqueur du jeu';
	$colonnes = ';;Score;Pronos';
	$taille_colonnes = '2;6;2;2';
	
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
	
	$titre = 'Classement par points';
	$descr = 'Récompense le pronostiqueur le plus régulier';
	$colonnes = ';;Score;Pronos';
	$taille_colonnes = '2;6;2;2';
	
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
	
	$titre = 'Nombre de victoires';
	$descr = 'Récompense les vainqueurs d\'étape';
	$colonnes = ';;Victoires;Pronos';
	$taille_colonnes = '2;6;2;2';
	
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
	
	$nom_fichier = '04-Podiums.txt';
	
	$titre = 'Nombre de podiums';
	$descr = 'Récompense les trusteurs de podiums';
	$colonnes = ';;Podiums;Pronos';
	$taille_colonnes = '2;6;2;2';
	
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
	
	$nom_fichier = '05-Top10.txt';
	
	$titre = 'Nombre de top 10';
	$descr = 'Récompense les habitués aux Top 10';
	$colonnes = ';;Top 10;Pronos';
	$taille_colonnes = '2;6;2;2';
	
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
      return strnatcmp($b['risque'], $a['risque']);
    }
    
    function calcule_classement_risque($tab,$url){
	usort($tab, 'compare_risque');
	
	$nom_fichier = '06-Risque.txt';
	
	$titre = 'Baroudeurs';
	$descr = 'Récompense les preneurs de risque';
	$colonnes = ';;Bonus risque moyen;Pronos';
	$taille_colonnes = '2;5;3;2';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['risque'];
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
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
    
    function compare_regularite($a, $b)
    {
      return strnatcmp($b['nb_trouves'], $a['nb_trouves']);
    }
    
    function calcule_classement_regularite($tab,$url){
	usort($tab, 'compare_regularite');
	
	$nom_fichier = '07-Regularite.txt';
	
	$titre = 'Tir groupé';
	$descr = 'Récompense les plus réguliers';
	$colonnes = ';;Moyenne corrects;Pronos';
	$taille_colonnes = '2;5;3;2';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	foreach($tab as $key => $joueur){
	    $score = $joueur['nb_trouves'];
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
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
?>