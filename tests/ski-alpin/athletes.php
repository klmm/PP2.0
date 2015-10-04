<?php

    require_once($_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_athlete.php');
    
    $arr_tous = get_athletes_tous();
    $arr_en_activite_hommes = get_athletes_activite_genre('H');
    $arr_en_activite_femmes = get_athletes_activite_genre('F');
    $chaine_id = '2;159;40;78;23;99';
    $arr_id = get_athletes_tab_id($chaine_id);
    
    echo '<h3>Tous les athletes (' . sizeof($arr_tous) .')</h3>';
    print_r($arr_tous);
    
    echo '<h3>Tous les hommes en activite (' . sizeof($arr_en_activite_hommes) .')</h3>';
    print_r($arr_en_activite_hommes);
    
    echo '<h3>Toutes les femmes en activite (' . sizeof($arr_en_activite_femmes) .')</h3>';
    print_r($arr_en_activite_femmes);
        
    echo '<h3>Athletes avec ids : ' . $chaine_id . '</h3>';
    print_r($arr_id);
    
?>