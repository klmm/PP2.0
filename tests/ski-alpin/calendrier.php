<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php');
    
    //$arr_jeu = get_calendrier_jeu(4);
    //$arr_deux = get_calendrier(2);
    $arr_jeu_filtre = get_calendrier_jeu_mois_filtre(4,'2015-12',7);
    //$arr_a_venir_filtre = get_calendrier_jeu_avenir(4,7);
    //$cal_avenir = get_id_calendrier_actuel(4,1023);
    
    echo '<h3>Jeu 4</h3>';
    print_r($arr_jeu);
    
    echo '<h3>2</h3>';
    print_r($arr_deux);
    
    echo '<h3>Filtres</h3>';
    print_r($arr_jeu_filtre);
        
    echo '<h3>Jeu 4, A venir</h3>';
    print_r($arr_a_venir_filtre);
    
    echo '<h3>Jeu 4, cal a venir : ' . $cal_avenir . '</h3>';
    
?>