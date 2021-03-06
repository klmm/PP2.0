<?php

    $nb_paris_max = 0;

    function calcule_classements($id_jeu){
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	global $nb_paris_max;
	
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$POINTS_CLASSEMENTS_PAR_POINTS = [0,25,20,16,12,10,7,5,3,2,1];
	$nb_classements_par_points = sizeof($POINTS_CLASSEMENTS_PAR_POINTS) - 1;
      
	$cal = get_calendrier_jeu($id_jeu);
	
	$jeu = get_jeu_id($id_jeu);
	$url = $jeu['url'];
	
	$sql = "SELECT * FROM rugby_prono WHERE EXISTS (
						SELECT id
						FROM rugby_calendrier 
						WHERE rugby_prono.id_calendrier=rugby_calendrier.id AND id_jeu=? AND traite=1)
					    AND id_jeu=?";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);
	
	foreach($cal as $key => $value){
	    $cal_trie[$value['id']] = $value;
	}
	
	print_r($cal_trie);

	while($enregistrement = $prep->fetch()){
	    $id_prono = $enregistrement->id;
	    $id_cal = $enregistrement->id_calendrier;
	    $pos = $enregistrement->classement;
	    $score = $enregistrement->score_total;
	    $joueur = $enregistrement->joueur;
	    $score_vainqueur = $enregistrement->score_vainqueur;
	    $score_essais1 = $enregistrement->score_essais1;
	    $score_essais2 = $enregistrement->score_essais2;   
	    
	    $tour = $cal_trie[$id_cal]['tour'];
	    
	    // JOUEUR
	    $tab_classements[$joueur]['joueur'] = $joueur;
	    
	    // NOMBRE DE PRONOS
	    $tab_classements[$joueur]['nb_pronos'] += 1;
	    
	    // COEFFICIENT DU MATCH
	    $coeff_match = $cal_trie[$id_cal]['coefficient'];
	    	    
	    // CLASSEMENT GENERAL
	    $tab_classements[$joueur]['score_total'] += $score*$coeff_match;
	    
	    // PHASE FINALE
	    if(substr($tour,0,5) != 'Poule'){
		$tab_classements[$joueur]['nb_pronos_phase_finale'] += 1;
		$tab_classements[$joueur]['phase_finale'] += $score*$coeff_match;
	    }
	    
	    // FRANCE
	    if($cal_trie[$id_cal]['id_equipe1'] == 13 || $cal_trie[$id_cal]['id_equipe2'] == 13){
		$tab_classements[$joueur]['nb_pronos_france'] += 1;
		$tab_classements[$joueur]['score_france'] += $score*$coeff_match;
	    }
	    
	    // CLASSEMENT PAR POINTS
	    if($pos <= $nb_classements_par_points){
	       $tab_classements[$joueur]['par_points'] += $POINTS_CLASSEMENTS_PAR_POINTS[$pos];
	    }
	    
	    // REUSSITE
	    if($score_vainqueur != 0){
		$tab_classements[$joueur]['vainqueur'] += 1;
	    }
	    
	    // MARQUEUR D'ESSAIS
	    if($score_essais1 != 0){
		$tab_classements[$joueur]['essais'] += 1;
	    }
	    if($score_essais2 != 0){
		$tab_classements[$joueur]['essais'] += 1;
	    }
	    
	    // HOMME DU MATCH
	    if($pos == 1){
		$tab_classements[$joueur]['victoires'] += 1;
	    }
	}
	
	$db = null;
	
	print_r($tab_classements);
	
	calcule_classement_general($id_jeu,$tab_classements,$url);
	calcule_classement_par_points($tab_classements,$url);
	calcule_classement_phase_finale($tab_classements,$url);
	calcule_classement_france($tab_classements,$url);
	calcule_classement_reussite($tab_classements,$url);
	calcule_classement_essais($tab_classements,$url);
	calcule_classement_homme_match($tab_classements,$url);
    }
    
    
    
    
    
    
    
    
    // 00 GENERAL
    
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
    
    
       
    
    
    // 02 PHASE FINALE
    
    function compare_phase_finale($a, $b)
    {
      return strnatcmp($b['phase_finale'], $a['phase_finale']);
    }
    
    function calcule_classement_phase_finale($tab,$url){
	usort($tab, 'compare_phase_finale');
	
	$nom_fichier = '02-Phase finale.txt';
	
	$titre = 'Phase finale';
	$descr = 'Récompense les hommes du match';
	$colonnes = ';;Score;Pronos';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	
	if(sizeof($tab) > 0){	
	    foreach($tab as $key => $joueur){
		$score = $joueur['phase_finale'];
		$login = $joueur['joueur'];
		$nb_paris = $joueur['nb_pronos_phase_finale'];
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
	}
    
	if(sizeof($line) > 0){
	    $contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	    foreach($line as $key => $ligne){
		$contenu .= $ligne . PHP_EOL;
	    }

	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
	}
    }
    
    
    // 03 PAR POINTS
    
    function compare_par_points($a, $b)
    {
      return strnatcmp($b['par_points'], $a['par_points']);
    }
    
    function calcule_classement_par_points($tab,$url){
	usort($tab, 'compare_par_points');
	
	$nom_fichier = '03-Par points.txt';
	
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
    
    
    
    // 04 REUSSITE
    
    function compare_reussite($a, $b)
    {
      return strnatcmp($b['vainqueur'], $a['vainqueur']);
    }
    
    function calcule_classement_reussite($tab,$url){
	usort($tab, 'compare_reussite');
	
	$nom_fichier = '04-Reussite.txt';
	
	$titre = 'Réussite';
	$descr = 'Récompense les hommes du match';
	$colonnes = ';;V;Pronos';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	
	if(sizeof($tab) > 0){	
	    foreach($tab as $key => $joueur){
		$score = $joueur['vainqueur'];
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
	}
	
	if(sizeof($line) > 0){
	    $contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	    foreach($line as $key => $ligne){
		$contenu .= $ligne . PHP_EOL;
	    }

	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
	}
    }
    
    
    
    
    // 06 HOMME DU MATCH
    
    function compare_victoires($a, $b)
    {
      return strnatcmp($b['victoires'], $a['victoires']);
    }
    
    function calcule_classement_homme_match($tab,$url){
	usort($tab, 'compare_victoires');
	
	$nom_fichier = '06-Homme du match.txt';
	
	$titre = 'Homme du match';
	$descr = 'Récompense les hommes du match';
	$colonnes = ';;HM;Pronos';
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
	
	if(sizeof($line) > 0){
	    $contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	    foreach($line as $key => $ligne){
		$contenu .= $ligne . PHP_EOL;
	    }

	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
	}
    }
    
    // 07 FRANCE
    
    function compare_france($a, $b)
    {
      return strnatcmp($b['score_france'], $a['score_france']);
    }
    
    function calcule_classement_france($tab,$url){
	usort($tab, 'compare_france');
	
	$nom_fichier = '07-France.txt';
	
	$titre = 'Bleus';
	$descr = 'Matches de l\'équipe de France';
	$colonnes = ';;Score;Pronos';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	
	if(sizeof($tab) > 0){	
	    foreach($tab as $key => $joueur){
		$score = $joueur['score_france'];
		$login = $joueur['joueur'];
		$nb_paris = $joueur['nb_pronos_france'];
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
	}
	if(sizeof($line) > 0){
	    $contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	    foreach($line as $key => $ligne){
		$contenu .= $ligne . PHP_EOL;
	    }

	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
	}
    }
    
 
    
    
    
    // 08 MARQUEUR D'ESSAIS
    
    function compare_essais($a, $b)
    {
      return strnatcmp($b['essais'], $a['essais']);
    }
    
    function calcule_classement_essais($tab,$url){
	usort($tab, 'compare_essais');
	
	$nom_fichier = '08-Essais.txt';
	
	$titre = 'Essais';
	$descr = 'Récompense les hommes du match';
	$colonnes = ';;Essais;Pronos';
	$taille_colonnes = '2;5;2;3';
	
	$score_actuel = -1;
	$pos_actuel = 1;
	$pos_cpt = 1;
	
	if(sizeof($tab) > 0){	
	    foreach($tab as $key => $joueur){
		$score = $joueur['essais'];
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
	}
	if(sizeof($line) > 0){
	    $contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	    foreach($line as $key => $ligne){
		$contenu .= $ligne . PHP_EOL;
	    }

	    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
	}
    }
    
 
?>