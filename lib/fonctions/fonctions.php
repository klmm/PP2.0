<?php

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

	return strftime('%A %d %B %Y, à %Hh%M', $unix);
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

	return strftime('%d/%m/%Y, à %Hh%M', $unix);
    }		
?>