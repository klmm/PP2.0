<?php
    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    ------------ INPUTS -------*/
    
    /* --------- OUTPUTS -------
    calendrier
	"1": {
            "id_cyclisme_calendrier": "1",
            "id_jeu": "4",
            "id_cal": "1",
            "nom_complet": "Etape 1 : Utrecht - Utrecht (CLM individuel)",
            "distance": "14.0",
            "date_debut": "2015-07-04 10:00:00",
            "date_fin": "2015-07-04 18:30:00",
            "profil_clm": "60",
            "profil_paves": "0",
            "profil_montagne": "0",
            "profil_sprint": "30",
            "profil_vallons": "0",
            "profil_baroudeurs": "0",
            "profil_equipe": "0",
            "profil_jeunes": "0",
            "classement": "",
            "traite": "0",
            "disponible": "1",
            "image": ""
        },
    
    prono_joueur
	"id_cyclisme_prono": "3",
        "id_jeu": "4",
        "joueur": "Toto",
        "id_cal": "1",
        "prono": "8;2;3;4;5;6;7;8;9;12;",
        "points_prono": "",
        "score_base": "0",
        "bonus_nombre": "0",
        "bonus_risque": "50",
        "score_total": "0",
        "classement": "0"
    
    pronos
	"Toto": {
            "id_cyclisme_prono": "3",
            "id_jeu": "4",
            "joueur": "Toto",
            "id_cal": "1",
            "prono": "8;2;3;4;5;6;7;8;9;12;",
            "points_prono": "",
            "score_base": "0",
            "bonus_nombre": "0",
            "bonus_risque": "50",
            "score_total": "0",
            "classement": "0"
        },
    
    cyclistes
	"125": {
            "id_cyclisme_athlete": "125",
            "id_cyclisme_equipe": "6",
            "nom": "Cherel",
            "prenom": "Mikael",
            "date_naissance": "1986-03-17",
            "note_paves": "52",
            "note_vallons": "67",
            "note_montagne": "65",
            "note_sprint": "65",
            "note_clm": "59",
            "photo": "mcherel",
            "id_pays": "9",
            "note_baroudeur": "60",
            "inscrit": 1,
            "forme": "80"
        },
    
    equipes
	"25": {
            "id_cyclisme_calendrier": "25",
            "id_jeu": "4",
            "id_cal": "25",
            "nom_complet": "Classement des jeunes",
            "distance": "0.0",
            "date_debut": "2015-07-10 11:00:00",
            "date_fin": "2015-07-27 19:15:00",
            "profil_clm": "7",
            "profil_paves": "3",
            "profil_montagne": "7",
            "profil_sprint": "0",
            "profil_vallons": "0",
            "profil_baroudeurs": "10",
            "profil_equipe": "0",
            "profil_jeunes": "1",
            "classement": "",
            "traite": "0",
            "disponible": "0",
            "image": ""
        },
    
    commentaires
 
    likes 
    
    ------------ OUTPUTS -------*/



    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/breves/get_breves.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/images/get_images.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/auto_login.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/update_joueurs.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/articles/get_articles.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_equipe.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_cycliste.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/jeux/get_commentaires.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/jeux/get_likes.php';
    //-------------------------------------------------------------------------------------//
  
    
    
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];

    if($loginjoueur == ""){
        auto_login();
        $loginjoueur = $_SESSION['LoginJoueur'];
    }
    $idjoueur = $_SESSION['IDJoueur'];
    $mailjoueur = $_SESSION['MailJoueur'];
    $admin = $_SESSION['Admin'];

    if($loginjoueur != ""){
        update_derniere_visite();
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    
    $arr_calendrier = get_calendrier_jeu($ID_JEU);
    
    $arr_pronos = get_pronos_cal($ID_JEU, $ID_CAL);
    
    if($bConnected){
	$arr_prono_joueur = get_prono($ID_JEU,$ID_CAL,$loginjoueur);
	$chaine_id_cyclistes = $arr_prono_joueur['prono'];
    }
    else{
	$arr_prono_joueur = null;
	$chaine_id_cyclistes = '';
    }
    
    $chaine_id_cyclistes .= $arr_calendrier['classement'];
    
    $tab_id_cyclistes = explode(";", $chaine_id_cyclistes);
    $tab_id_cyclistes = array_unique($tab_id_cyclistes);
    echo $chaine_id_cyclistes;
    print_r($tab_id_cyclistes);
    $arr_cyclistes = get_cyclistes_tab_id($ID_JEU, $ID_CAL, $tab_id_cyclistes);
    
    $arr_equipes = get_equipes_inscrites($ID_JEU, $ID_CAL);
    
    $arr_commentaires = get_commentaires_cal($ID_JEU, $ID_CAL);
    
    $arr_likes = get_likes($ID_JEU,$ID_CAL,$loginjoueur);
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    
    
    
    $res = array(   
		    'prono_joueur' => $arr_prono_joueur,
		    'pronos' => $arr_pronos,
		    'calendrier' => $arr_calendrier,
		    'cyclistes' => $arr_cyclistes,
		    'equipes' => $arr_equipes,
		    'commentaires' => $arr_commentaires,
		    'likes' => $arr_likes
		    
	    );
    
    echo json_encode($res);
	
?>