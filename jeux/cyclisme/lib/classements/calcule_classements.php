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
	       $tab_classements[$joueur]['par_points'] = $POINTS_CLASSEMENTS_PAR_POINTS[$pos];
	    }
	}
	
	calcule_clasement_general($tab_classements,$url);
    }
    
    function compare_score_total($a, $b)
    {
      return strnatcmp($b['score_total'], $a['score_total']);
    }
    
    function calcule_clasement_general($tab,$url){
	usort($tab, 'compare_score_total');
	
	$nom_fichier = '00-General.txt';
	
	$titre = 'Classement général';
	$descr = 'Récompense le meilleur pronostiqueur du jeu';
	$colonnes = ';;Score;Pronos';
	$taille_colonnes = '2;8;2;2';
	
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
	}
	
	$contenu = $titre . PHP_EOL . $descr . PHP_EOL . $colonnes . PHP_EOL . $taille_colonnes . PHP_EOL;
	foreach($line as $key => $ligne){
	    $contenu .= $ligne . PHP_EOL;
	}
	
	echo $_SERVER['DOCUMENT_ROOT'] . $url . '/classements/' . $nom_fichier;
	echo PHP_EOL;
	echo $contenu;
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $url . '/classements/' . $nom_fichier, $contenu);
    }
?>