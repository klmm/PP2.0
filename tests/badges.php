<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_badges.php');
    $arr = get_badges_tous();
    //echo 'ok';
    print_r($arr);
	
	
?>