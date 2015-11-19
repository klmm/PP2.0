<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');

    function biathlon_get_calendrier($id_cal){
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On pr�pare la requ�te pour aller chercher les articles
	$sql = "SELECT * FROM biathlon_calendrier WHERE id_biathlon_calendrier=?";
	$prep = $db->prepare($sql);
	$prep->setFetchMode(PDO::FETCH_OBJ);
	$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
	$prep->execute();

	//On met les articles dans le tableau
	if( $enregistrement = $prep->fetch() )
	{
	    $arr['id_biathlon_calendrier'] = $enregistrement->id_biathlon_calendrier;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['id_weekend'] = $enregistrement->id_weekend;
	    $arr['specialite'] = $enregistrement->specialite;
	    $arr['genre'] = $enregistrement->genre;
	    if($arr['genre'] == 'H'){
		$arr['genre_fr'] = 'Hommes';
	    }
	    elseif($arr['genre'] == 'F'){
		$arr['genre_fr'] = 'Femmes';
	    }
	    else{
		$arr['genre_fr'] = 'Mixte';
	    }
	    
	    $arr['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr['date_debut']);
	    $arr['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr['date_debut_fr'] = $arr_date['date'];
	    $arr['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr['classement'] = $enregistrement->classement;
	    $arr['traite'] = $enregistrement->traite;
	    $arr['disponible'] = $enregistrement->disponible;
	    $arr['annule'] = $enregistrement->annule;
	    
	    $arr['image'] = $enregistrement->image;
	    
	    $dh_debut = $arr['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr['commence'] = "1";
	    }
	    else{
		$arr['commence'] = "0";
		$arr['temps_restant'] = dateheure_sql_to_temps_restant($arr['date_debut']);
	    }
	}
	$db = null;
	return $arr;
    }
    
    function biathlon_get_calendrier_jeu($id_jeu){
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT * FROM biathlon_calendrier WHERE id_jeu=? ORDER BY date_debut ASC";
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_biathlon_calendrier;
	    $arr[$id_cal]['id_biathlon_calendrier'] = $id_cal;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_weekend'] = $enregistrement->id_weekend;
	    $arr[$id_cal]['specialite'] = $enregistrement->specialite;
	    $arr[$id_cal]['genre'] = $enregistrement->genre;
	    if($arr[$id_cal]['genre'] == 'H'){
		$arr[$id_cal]['genre_fr'] = 'Hommes';
	    }
	    elseif($arr[$id_cal]['genre'] == 'F'){
		$arr[$id_cal]['genre_fr'] = 'Femmes';
	    }
	    else{
		$arr[$id_cal]['genre_fr'] = 'Mixte';
	    }
	    
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_debut']);
	    $arr[$id_cal]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['date_debut_fr'] = $arr_date['date'];
	    $arr[$id_cal]['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr[$id_cal]['classement'] = $enregistrement->classement;
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    $arr[$id_cal]['annule'] = $enregistrement->annule;
	    
	    $arr[$id_cal]['image'] = $enregistrement->image;
	    
	    $dh_debut = $arr[$id_cal]['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr[$id_cal]['commence'] = "1";
	    }
	    else{
		$arr[$id_cal]['commence'] = "0";
		$arr[$id_cal]['temps_restant'] = dateheure_sql_to_temps_restant($arr[$id_cal]['date_debut']);
	    }
	    $i++;
	}
	
	$db = null;
	return $arr;
    }
    
    function biathlon_get_calendrier_jeu_filtre($id_jeu,$filtre){
	$epreuves = filtre_to_epreuves_biathlon($filtre);
	$sql_epreuve = '';
	
	for($i=0; $i<sizeof($epreuves); $i++){
	    if($epreuves[$i]['inscrit']){
		$sql_epreuve .= '(genre="' . $epreuves[$i]['genre'] . '" AND specialite="' . $epreuves[$i]['discipline'] . '") OR ';
	    }
	}
	
	// Enlever le dernier ' OR '
	$sql_epreuve = substr($sql_epreuve, 0, strlen($sql_epreuve)-4);
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = 'SELECT * FROM biathlon_calendrier WHERE id_jeu=? AND (' . $sql_epreuve . ') ORDER BY date_debut ASC';
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$j = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_biathlon_calendrier;
	    $arr[$id_cal]['id_biathlon_calendrier'] = $id_cal;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_weekend'] = $enregistrement->id_weekend;
	    $arr[$id_cal]['specialite'] = $enregistrement->specialite;
	    $arr[$id_cal]['genre'] = $enregistrement->genre;
	    if($arr[$id_cal]['genre'] == 'H'){
		$arr[$id_cal]['genre_fr'] = 'Hommes';
	    }
	    elseif($arr[$id_cal]['genre'] == 'F'){
		$arr[$id_cal]['genre_fr'] = 'Femmes';
	    }
	    else{
		$arr[$id_cal]['genre_fr'] = 'Mixte';
	    }
	    
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_debut']);
	    $arr[$id_cal]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['date_debut_fr'] = $arr_date['date'];
	    $arr[$id_cal]['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr[$id_cal]['classement'] = $enregistrement->classement;
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    $arr[$id_cal]['annule'] = $enregistrement->annule;
	    
	    $arr[$id_cal]['image'] = $enregistrement->image;
	    
	    $dh_debut = $arr[$id_cal]['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr[$id_cal]['commence'] = "1";
	    }
	    else{
		$arr[$id_cal]['commence'] = "0";
		$arr[$id_cal]['temps_restant'] = dateheure_sql_to_temps_restant($arr[$id_cal]['date_debut']);
	    }
	    
	    if($arr[$id_cal]['traite'] && dateheure_sql_to_jours_passes($arr[$id_cal]['date_debut']) > 15){
		$arr[$id_cal]['tri'] = 1000 - $j;
	    }
	    else{
		$arr[$id_cal]['tri'] = $j;
	    }
	    
	    $j++;
	}
	
	usort($arr,'biathlon_compare_tri');
	$db = null;
	return $arr;
    }
    
    function biathlon_get_calendrier_jeu_avenir($id_jeu,$filtre){
	$epreuves = filtre_to_epreuves_biathlon($filtre);
	
	$sql_epreuve = '';
	for($i=0; $i<sizeof($epreuves); $i++){
	    if($epreuves[$i]['inscrit']){
		$sql_epreuve .= '(genre="' . $epreuves[$i]['genre'] . '" AND specialite="' . $epreuves[$i]['discipline'] . '") OR ';
	    }
	}
	
	// Enlever le dernier ' OR '
	$sql_epreuve = substr($sql_epreuve, 0, strlen($sql_epreuve)-4);
	// On �tablit la connexion avec la base de donn�es
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = 'SELECT * FROM biathlon_calendrier WHERE id_jeu=? AND date_debut>NOW() AND disponible=1 AND (' . $sql_epreuve . ') ORDER BY date_debut ASC LIMIT 20';
	$prep = $db->prepare($sql);
	$prep->bindValue(1,$id_jeu,PDO::PARAM_INT);
	$prep->execute();
	$prep->setFetchMode(PDO::FETCH_OBJ);

	//On fait le test si un enrengistrement a �t� trouv�
	$i = 0;
	while( $enregistrement = $prep->fetch() )
	{
	    $id_cal = $enregistrement->id_biathlon_calendrier;
	    $arr[$id_cal]['id_biathlon_calendrier'] = $id_cal;
	    $arr[$id_cal]['id_jeu'] = $enregistrement->id_jeu;
	    $arr[$id_cal]['id_weekend'] = $enregistrement->id_weekend;
	    $arr[$id_cal]['specialite'] = $enregistrement->specialite;
	    $arr[$id_cal]['genre'] = $enregistrement->genre;
	    if($arr[$id_cal]['genre'] == 'H'){
		$arr[$id_cal]['genre_fr'] = 'Hommes';
	    }
	    elseif($arr[$id_cal]['genre'] == 'F'){
		$arr[$id_cal]['genre_fr'] = 'Femmes';
	    }
	    else{
		$arr[$id_cal]['genre_fr'] = 'Mixte';
	    }
	    
	    $arr[$id_cal]['date_debut'] = $enregistrement->date_debut;
	    $arr_date = dateheure_sql_to_fr($arr[$id_cal]['date_debut']);
	    $arr[$id_cal]['date_debut_fr_court'] = $arr_date['date_court'];
	    $arr[$id_cal]['date_debut_fr_tcourt'] = substr($arr_date['date_court'], 0, 5);
	    $arr[$id_cal]['date_debut_fr'] = $arr_date['date'];
	    $arr[$id_cal]['heure_debut_fr'] = $arr_date['heure'];
	    
	    $arr[$id_cal]['classement'] = $enregistrement->classement;
	    $arr[$id_cal]['traite'] = $enregistrement->traite;
	    $arr[$id_cal]['disponible'] = $enregistrement->disponible;
	    $arr[$id_cal]['annule'] = $enregistrement->annule;
	    
	    $arr[$id_cal]['image'] = $enregistrement->image;
	    
	    $dh_debut = $arr[$id_cal]['date_debut'];
	    $now   = time();
	    $dh_debut = strtotime($dh_debut);
	    
	    if($now > $dh_debut){
		$arr[$id_cal]['commence'] = "1";
	    }
	    else{
		$arr[$id_cal]['commence'] = "0";
		$arr[$id_cal]['temps_restant'] = dateheure_sql_to_temps_restant($arr[$id_cal]['date_debut']);
	    }
	}
	
	usort($arr, 'biathlon_compare_date_debut');
	$db = null;
	return $arr;
    }
    
    function biathlon_compare_date_debut($a, $b)
    {
      return strnatcmp($a['date_debut'], $b['date_debut']);
    }
    
    function biathlon_get_id_calendrier_actuel($ID_JEU,$filtre){
	$epreuves = filtre_to_epreuves_biathlon($filtre);
	
	$sql_epreuve = '';
	for($i=0; $i<sizeof($epreuves); $i++){
	    if($epreuves[$i]['inscrit']){
		$sql_epreuve .= '(genre="' . $epreuves[$i]['genre'] . '" AND specialite="' . $epreuves[$i]['discipline'] . '") OR ';
	    }
	}
	
	// Enlever le dernier ' OR '
	$sql_epreuve = substr($sql_epreuve, 0, strlen($sql_epreuve)-4);
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();
	
	$now = time();
	$unix = mktime(date('H',$now),date('i',$now),date('s',$now),date('n',$now),date('j',$now),date('Y',$now));

	$date_heure = strftime('%Y-%m-%d %H:%M:%S', $unix);
	$date_seule = strftime('%Y-%m-%d', $unix);
	
	//On fait la requete sur le login
	$sql4 = "SELECT * FROM biathlon_calendrier WHERE id_jeu=? AND CAST(date_debut AS DATE)=? AND date_debut>NOW() AND (' . $sql_epreuve . ') ORDER BY date_debut ASC LIMIT 1"; // ETAPE DU JOUR A VENIR
	$prep4 = $db->prepare($sql4);
	$prep4->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	$prep4->bindValue(2,$date_seule,PDO::PARAM_STR);
	$prep4->bindValue(3,$date_seule,PDO::PARAM_STR);
	$prep4->execute();
	$prep4->setFetchMode(PDO::FETCH_OBJ);

	$enregistrement4 = $prep4->fetch();
	
	if( $enregistrement4 ){
	    $db = null;
	    return $enregistrement4->id_biathlon_calendrier; // ETAPE DU JOUR A VENIR
	}
	else{
	    $sql = 'SELECT * FROM biathlon_calendrier WHERE id_jeu=? AND CAST(date_debut AS DATE)=? AND (' . $sql_epreuve . ') ORDER BY date_debut DESC LIMIT 1'; // DERNIERE ETAPE DU JOUR
	    $prep = $db->prepare($sql);
	    $prep->bindValue(1,$ID_JEU,PDO::PARAM_INT);
	    $prep->bindValue(2,$date_seule,PDO::PARAM_STR);
	    $prep->execute();
	    $prep->setFetchMode(PDO::FETCH_OBJ);
	    
	    $enregistrement = $prep->fetch();
	
	    if( $enregistrement ){
		$db = null;
		return $enregistrement->id_biathlon_calendrier; // DERNIERE ETAPE DU JOUR
	    }
	    else{
		$sql2 = 'SELECT * FROM biathlon_calendrier WHERE id_jeu=? AND date_debut>NOW() AND disponible=1 AND (' . $sql_epreuve . ') ORDER BY date_debut ASC LIMIT 1'; // PROCHAINE DISPO
		$prep2 = $db->prepare($sql2);
		$prep2->bindValue(1,$ID_JEU,PDO::PARAM_INT);
		$prep2->execute();
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		$enregistrement2 = $prep2->fetch();

		if( $enregistrement2 ){
		    $db = null;
		    return $enregistrement2->id_biathlon_calendrier; // PROCHAINE DISPO
		}
		else{
		    $sql3 = 'SELECT * FROM biathlon_calendrier WHERE id_jeu=? AND traite=1 AND (' . $sql_epreuve . ') ORDER BY date_debut DESC LIMIT 1'; // DERNIERE TRAITEE
		    $prep3 = $db->prepare($sql3);
		    $prep3->bindValue(1,$ID_JEU,PDO::PARAM_INT);
		    $prep3->execute();
		    $prep3->setFetchMode(PDO::FETCH_OBJ);

		    $enregistrement3 = $prep3->fetch();

		    if( $enregistrement3 ){
			$db = null;
			return $enregistrement3->id_biathlon_calendrier; // DERNIERE TRAITEE
		    }
		    else{
			$sql4 = 'SELECT * FROM biathlon_calendrier WHERE id_jeu=? AND (' . $sql_epreuve . ') ORDER BY date_debut ASC LIMIT 1'; // PROCHAINE
			$prep4 = $db->prepare($sql4);
			$prep4->bindValue(1,$ID_JEU,PDO::PARAM_INT);
			$prep4->execute();
			$prep4->setFetchMode(PDO::FETCH_OBJ);

			$enregistrement4 = $prep4->fetch();

			if( $enregistrement4 ){
			    $db = null;
			    return $enregistrement4->id_biathlon_calendrier; // PROCHAINE
			}
			else{
			    $db = null;
			    return 1;
			}
		    }
		}
	    }
	}
    }
    
    function filtre_to_epreuves_biathlon($filtre){
	// 1 : SPRINT FEMMES
	// 2 : SPRINT HOMMES
	// 4 : POURSUITE FEMMES
	// 8 : POURSUITE HOMMES
	// 16 : INDIV FEMMES
	// 32 : INDIV HOMMES
	// 64 : MASS START FEMMES
	// 128 : MASS START HOMMES
	// 256 : RELAIS FEMMES
	// 512 : RELAIS HOMMES
	// 1024 : RELAIS MIXTE
	
	if($filtre == 0 || $filtre == null){
	    $filtre = 8191;
	}
	
	$epreuves[0]['discipline'] = 'Sprint';
	$epreuves[0]['genre'] = 'F';
	$epreuves[1]['discipline'] = 'Sprint';
	$epreuves[1]['genre'] = 'H';
	$epreuves[2]['discipline'] = 'Poursuite';
	$epreuves[2]['genre'] = 'F';
	$epreuves[3]['discipline'] = 'Poursuite';
	$epreuves[3]['genre'] = 'H';
	$epreuves[4]['discipline'] = 'Individuelle';
	$epreuves[4]['genre'] = 'F';
	$epreuves[5]['discipline'] = 'Individuelle';
	$epreuves[5]['genre'] = 'H';
	$epreuves[6]['discipline'] = 'Mass start';
	$epreuves[6]['genre'] = 'F';
	$epreuves[7]['discipline'] = 'Mass start';
	$epreuves[7]['genre'] = 'H';
	$epreuves[8]['discipline'] = 'Relais';
	$epreuves[8]['genre'] = 'F';
	$epreuves[9]['discipline'] = 'Relais';
	$epreuves[9]['genre'] = 'H';
	$epreuves[10]['discipline'] = 'Relais';
	$epreuves[10]['genre'] = 'M';
	
	foreach($epreuves as $key => $value){
	    if($value['genre'] == 'H'){
		$epreuves[$key]['genre_long'] = 'Hommes';
	    }
	    elseif($value['genre'] == 'F'){
		$epreuves[$key]['genre_long'] = 'Femmes';
	    }
	    else{
		$epreuves[$key]['genre_long'] = 'Mixte';
	    }
	}
	
	$binary = decbin($filtre);
	
	for($i=0; $i<sizeof($epreuves); $i++){
	    $epreuves[$i]['inscrit'] = $binary[strlen($binary) - ($i+1)];
	}
	return $epreuves;
    }
    
    function biathlon_compare_tri($a, $b)
    {
	if (floatval($b['tri']) < floatval($a['tri'])){
	    return 1;
	}
	else{
	    return -1;
	}
    }
?>