<?php
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
?>