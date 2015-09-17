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
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_equipe.php';
    //-------------------------------------------------------------------------------------//
    
    

    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    $joueur = $_POST['joueur'];
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $equipes = get_equipes_inscrites($ID_JEU);
    $calendrier = get_calendrier($ID_CAL);
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    $id_equipe1 = $calendrier['id_equipe1'];
    $nom_equipe1 = $equipes[$id_equipe1]['nom'];
    $id_pays1 = $equipes[$id_equipe1]['id_pays'];

    $id_equipe2 = $calendrier['id_equipe2'];
    $nom_equipe2 = $equipes[$id_equipe2]['nom'];
    $id_pays2 = $equipes[$id_equipe2]['id_pays'];
    
    $prono_joueur = get_prono($ID_CAL,$joueur);
 	    
    if($prono_joueur == null){
	// PAS DE PRONO
	$prono_points_equipe1 = '-';
	$prono_points_equipe2 = '-';
	$prono_essais_equipe1 = '-';
	$prono_essais_equipe2 = '-';
	$score_points = '-';
	$score_essais = '-';
	$score_vainqueur = '-';
	$score_total = '-';
	$score_ecart = '-';
    }
    else{
	$prono_points_equipe1 = $prono_joueur['prono_points1'];
	$prono_points_equipe2 = $prono_joueur['prono_points2'];
	$prono_essais_equipe1 = $prono_joueur['prono_essais1'];
	$prono_essais_equipe2 = $prono_joueur['prono_essais2'];
	
	if($calendrier['traite']){
	    $score_points = $prono_joueur['score_points1'] + $prono_joueur['score_points2'] . ' pts';
	    $score_essais = $prono_joueur['score_essais1'] + $prono_joueur['score_essais2'] . ' pts';
	    $score_vainqueur = $prono_joueur['score_vainqueur'] . ' pts';
	    $score_total = $prono_joueur['score_total'] . ' pts';
	    $score_ecart = $prono_joueur['score_ecart'] . ' pts';
	}
	else{
	    $score_points = '-';
	    $score_essais = '-';
	    $score_vainqueur = '-';
	    $score_total = '-';
	    $score_ecart = '-';
	}
    }
    
    
    
    $res = '
	    <div class="sectionSide">
		<p class="section-highlight">Le pari de ' . $joueur . '</p>
	    </div>
	    <div class="stat-box col-md-9 col-sm-9 col-xs-9">
		<div class="team-side col-md-12 col-sm-12 col-xs-12">
		    <div class="team-name col-md-6 col-sm-6 col-xs-6">
			<span>' . $nom_equipe1 . '</span>
		    </div>
		    <div class="col-md-3 col-sm-3 col-xs-3 team-tries">
			<span>' . $prono_essais_equipe1 . '</span>
		    </div>
		    <div class="col-md-3 col-sm-3  col-xs-3 team-points">
			<span>' . $prono_points_equipe1 . '</span>
		    </div>
		</div>
		<div class="team-side col-md-12 col-sm-12 col-xs-12">
		    <div class="team-name col-md-6 col-sm-6 col-xs-6">
			<span>' . $nom_equipe2 . '</span>
		    </div>
		    <div class="col-md-3 col-sm-3 col-xs-3 team-tries">
			<span>' . $prono_essais_equipe2 . '</span>
		    </div>
		    <div class="col-md-3 col-sm-3  col-xs-3 team-points">
			<span>' . $prono_points_equipe2 . '</span>
		    </div>
		</div>
		<div class="score col-md-12 col-sm-12 col-xs-12">
		    <p class="stat-item-big">Score total</p>
		    <p class="stat-value-big">' . $score_total . '</p>
		</div>
	    </div>
	    <div class="stat-box col-md-3 col-sm-3 col-xs-3">
		<ul>	
		    <li class="winners">
			    <p class="stat-item">Vainqueur</p>
			    <p class="stat-value">' . $score_vainqueur . '</p>
		    </li>
		    <li class="tries">
			    <p class="stat-item">Essais</p>
			    <p class="stat-value">' . $score_essais . '</p>
		    </li>
		    <li class="gap">
			    <p class="stat-item">Ecart</p>
			    <p class="stat-value">' . $score_ecart . '</p>
		    </li>	
		    <li class="points">
			    <p class="stat-item">Points</p>
			    <p class="stat-value">' . $score_points . '</p>
		    </li>
		</ul>
	    </div>';    
   
    echo $res;
	
?>