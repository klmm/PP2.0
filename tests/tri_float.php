<?php

    $arr['0']['test'] = 1.35;
    $arr['1']['test'] = 1.85;
    $arr['2']['test'] = 1.65;
    $arr['3']['test'] = 1.5;
    $arr['4']['test'] = 1.05;
    $arr['5']['test'] = 1;
    $arr['6']['test'] = 1.9;
    
    print_r($arr);
    
    usort($arr, compare_regularite($a, $b));
    
    echo '<br/><br/';
    
    print_r($arr);
    
    function compare_regularite($a, $b)
    {
	/*if($b['test'] > $a['test']){
	    return 1;
	}
	else{
	    return -1;
	}*/
	return strnatcmp($b['test'], $a['test']);
    }

	    

?>