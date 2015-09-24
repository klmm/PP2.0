<?php

    function date_naissance_sql_to_fr($date_naissance){
	$arr = explode('-',$date_naissance);
	
	return $arr[2] . '/' . $arr[1] . '/' . $arr[0];
    }
    
    function dateheure_sql_to_temps_restant($date){
	$now   = time();
	$date2 = strtotime($date);
	$diff  = abs($date2 - $now);

	//X minutes
	if($diff < 3600){
	    return floor($diff/60) . ' minutes';
	}

	//1 heure
	if($diff < 7200){
	    return '1 heure';
	}

	//X heures
	if($diff < 86400){
	    return floor($diff/3600) . ' heures';
	}
	
	$date = substr($date,0,10) . ' 23:59:59';
	$date2 = strtotime($date);
	$diff  = abs($date2 - $now);
	return floor($diff/86400) . ' jours';
    }
    
    function dateheure_sql_to_jours_passes($date){
	$now   = time();
	$date2 = strtotime($date);
	$diff  = abs($date2 - $now);

	return floor($diff/86400);
    }


    function dateheure_sql_to_fr($date){
	setlocale(LC_TIME, 'fr_FR');
	$date2 = strtotime($date);
	$unix = mktime(date('H',$date2),date('i',$date2),date('s',$date2),date('n',$date2),date('j',$date2),date('Y',$date2));
	
	$arr = array(
	    'date_court' => strftime('%d/%m/%Y', $unix),
	    'date' => utf8_encode(strftime('%A %d %B %Y', $unix)),
	    'heure' => strftime('%Hh%M', $unix)
	);
	
	return $arr;
    }
    
    function date_to_duration($date){
	$now   = time();
	$date2 = strtotime($date);
	$diff  = abs($now - $date2);

	//� l'instant
	if($diff < 60){
	    return 'A l\'instant';
	}

	//1 minutes
	if($diff < 120){
	    return 'il y a 1 minute';
	}

	//X minutes
	if($diff < 3600){
	    return 'il y a ' . floor($diff/60) . ' minutes';
	}

	//1 heure
	if($diff < 7200){
	    return 'il y a 1 heure';
	}

	//X heures
	if($diff < 86400){
	    return 'il y a ' . floor($diff/3600) . ' heures';
	}

	setlocale(LC_TIME, 'fr_FR');
	$unix = mktime(date('H',$date2),date('i',$date2),date('s',$date2),date('n',$date2),date('j',$date2),date('Y',$date2));

	return utf8_encode(strftime('%A %d %B %Y, %Hh%M', $unix));
    }
    
    function date_to_duration_court($date){
	$now   = time();
	$date2 = strtotime($date);
	$diff  = abs($now - $date2);

	//� l'instant
	if($diff < 60){
	    return 'A l\'instant';
	}

	//1 minutes
	if($diff < 120){
	    return 'il y a 1 min';
	}

	//X minutes
	if($diff < 3600){
	    return 'il y a ' . floor($diff/60) . ' min';
	}

	//1 heure
	if($diff < 7200){
	    return 'il y a 1 heure';
	}

	//X heures
	if($diff < 86400){
	    return 'il y a ' . floor($diff/3600) . ' h';
	}

	setlocale(LC_TIME, 'fr_FR');
	$unix = mktime(date('H',$date2),date('i',$date2),date('s',$date2),date('n',$date2),date('j',$date2),date('Y',$date2));

	return strftime('%d/%m/%y, à %Hh%M', $unix);
    }		
?>