<?php

    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    joueur
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------
    
    ------------ OUTPUTS -------*/

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_prono.php';
    //-------------------------------------------------------------------------------------//
    
    

    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    $joueur = $_POST['joueur'];
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = get_calendrier($ID_JEU,$ID_CAL);
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    if($b_commence){
	$prono = get_prono($ID_JEU,$ID_CAL,$joueur);
    }
    else{
	$prono = null;
    }
    
    $res = $prono;
    
    
    /*
     $arr['id_rugby_prono'] = $enregistrement->id;
	    $arr['id_cal'] = $enregistrement->id_calendrier;
	    $arr['id_jeu'] = $enregistrement->id_jeu;
	    $arr['joueur'] = $enregistrement->joueur;
	    $arr['prono_vainqueur'] = $enregistrement->prono_vainqueur;
	    $arr['prono_points1'] = $enregistrement->prono_points1;
	    $arr['prono_points2'] = $enregistrement->prono_points2;
	    $arr['prono_essais1'] = $enregistrement->prono_essais1;
	    $arr['prono_essais2'] = $enregistrement->prono_essais2;
	    $arr['score_vainqueur'] = $enregistrement->score_vainqueur;
	    $arr['score_points1'] = $enregistrement->score_points1;
	    $arr['score_points2'] = $enregistrement->score_points2;
	    $arr['score_essais1'] = $enregistrement->score_essais1;
	    $arr['score_essais2'] = $enregistrement->score_essais2;
	    $arr['score_ecart'] = $enregistrement->score_ecart;
	    $arr['score_total'] = $enregistrement->score_total;
	    $arr['classement'] = $enregistrement->classement;
     */
    
    
    echo $res;
	
?>