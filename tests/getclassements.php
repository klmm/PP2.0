<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/get_classements.php';

    $folder_name = '/jeux/cyclisme/2015/tour-de-france/classements/';
    /*$folder_name2 = 'jeux/cyclisme/2015/tour-de-france/classements';
    $file_name = 'test3.txt';
    
    $sep = PHP_EOL;
    $titre = 'titre ' . $file_name;
    $description = 'description ' . $file_name;
    $titres_colonnes = 'col1;col2;col3';
    $largeurs_colones = '40;50;10';
    
    $line[] = 'pos1;nom1;points1';
    $line[] = 'pos2;nom2;points2';
    $line[] = 'pos3;nom3;points3';
    $line[] = 'pos4;nom4;points4';
    
    $contenu = $titre . $sep . $description . $sep . $titres_colonnes . $sep . $largeurs_colones . $sep;
    
    foreach($line as $key => $value){
	$contenu .= $value . $sep;
    }
    
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . $folder_name . $file_name , $contenu);*/
      
    $res = get_classements($folder_name);
    
    echo json_encode($res);
?>