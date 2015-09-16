<?php
    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------
    
    ------------ OUTPUTS -------*/
   

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_equipe.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_pays.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/clean_url.php';
    //-------------------------------------------------------------------------------------//
  
   
    
    
 
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];

    if($loginjoueur != ""){
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    //------------------------------------------------------------------------------------------------//
    
    
  
    
    
    
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    
    
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = get_calendrier($ID_CAL);
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    //--------------------------------------PRONOS DES JOUEURS------------------------------------------------//
    $tab_pronos = get_pronos_cal($ID_JEU, $ID_CAL);
    $nb_pronos = sizeof($tab_pronos);
    //--------------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    
    
    //--------------------------------------MON PRONO------------------------------------------------//
    if($bConnected){
	$prono_joueur = get_prono($ID_CAL,$loginjoueur);
	if($prono_joueur == null){
	    $prono_joueur['prono_essais1'] = '-';
	    $prono_joueur['prono_essais2'] = '-';
	    $prono_joueur['prono_points1'] = '-';
	    $prono_joueur['prono_points2'] = '-';
	}
    }
    else{
	$prono_joueur = null;
	$prono_joueur['prono_essais1'] = '-';
	$prono_joueur['prono_essais2'] = '-';
	$prono_joueur['prono_points1'] = '-';
	$prono_joueur['prono_points2'] = '-';
    }
    //--------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    

    
    
    
    //-------------------------------PAYS/EQUIPES------------------------------------//
    $equipes = get_equipes_inscrites($ID_JEU);
    $pays = get_pays_tous();
    //-------------------------------PAYS/EQUIPES------------------------------------//
    
    
    // AFFICHAGE PARTIE CALENDRIER
    
    
    // Terminé
    if($calendrier['commence'] && $calendrier['traite']){
	$tmp = 'disabled';
	$txt = 'Terminé';
    }
    // En cours
    elseif($calendrier['commence'] && !$calendrier['traite']){
	$tmp = 'disabled';
	$txt = 'En cours';
    }
    // Dispo
    elseif($calendrier['disponible']){
	$tmp = '';
	$txt = 'Valider';
    }
    // Pas dispo
    else{
	$tmp = 'disabled';
	$txt = 'Bientôt';
    }
    
    $id_equipe1 = $calendrier['id_equipe1'];
    $nom_equipe1 = $equipes[$id_equipe1]['nom'];
    $id_pays1 = $equipes[$id_equipe1]['id_pays'];

    $id_equipe2 = $calendrier['id_equipe2'];
    $nom_equipe2 = $equipes[$id_equipe2]['nom'];
    $id_pays2 = $equipes[$id_equipe2]['id_pays'];
    
    $res = '';
    
    // PRESENTATION MATCH
    
    $res .= '	    
		<div class="pres-panel clearfix">
		    <img class="item-flag col-md-2 col-sm-2 hidden-xs" src="' . $pays[$id_pays1]['drapeau_grand'] . '"/>
		    <p class="name section-highlight col-md-8 col-sm-8 col-xs-12">' . $nom_equipe1 . ' - ' . $nom_equipe2 . '</p>
		    <img class="item-flag col-md-2 col-sm-2 hidden-xs" src="' . $pays[$id_pays2]['drapeau_grand'] . '"/>
		    <p class="date col-md-6 col-sm-6 col-xs-6">' . $calendrier['tour'] . '</p>
			<p class="date col-md-6 col-sm-6 col-xs-6">Coefficient x' . $calendrier['coefficient'] . '</p>
		    <p class="date col-md-6 col-sm-6 col-xs-6">' . $calendrier['stade'] . ' (' . $calendrier['ville'] . ')</p>
		    <p class="date col-md-6 col-sm-6 col-xs-6">' . $calendrier['date_debut_fr'] . ' - ' . $calendrier['heure_debut_fr'] . '</p>
		</div>
		<div class="result-panel">';
    
    
    
    
    
    // MATCH COMMENCE        
    if($calendrier['commence']){
	
	if($prono_joueur == null){
	    // PAS DE PRONO
	}
	else{
	    if($calendrier['traite']){
		// MATCH TERMINE
	    }
	    else{
		// MATCH EN COURS
	    }
	}
	
	// AUTRES JOUEURS
	$count = 0;
	$premier = '';
	foreach($tab_pronos as $key => $value){
	    if($count == 0){
		$count++;
		$premier = $key;
	    }
	    if($key == $loginjoueur){
		$class_surlign_joueur = 'goodbet';
	    }
	    else{
		$class_surlign_joueur = '';
	    }
	    
	    if($calendrier['traite']){
		$pos = $value['classement'];
		$pts_joueur = $value['score_total'];
	    }
	    else{
		$pos = '';
		$pts_joueur = '';
	    }
	}
    }
    // MATCH NON COMMENCE
    else{
	// PRONO JOUEUR UNIQUEMENT
	$res .= '
			    <div class="row later">
					<form id="pari-form" role="form" class="row" method="POST">
						<input name="id_cal" id="id_cal" type="text" class="hidden" required="" value="' . $ID_CAL . '"/>
						<div class="label-side col-md-6 col-sm-12 col-xs-12">
							<div class="col-md-6 col-sm-6 col-xs-6"></div>
							<div class="tries-combo col-md-3 col-sm-3 col-xs-3">
								<span class="">Essais</span>
							</div>
							<div class="points-combo col-md-3 col-sm-3 col-xs-3">
								<span class="">Points</span>
							</div>
						</div>
						<div class="label-mirror col-md-6 col-sm-12 col-xs-12">
							<div class="col-md-6 col-sm-6 col-xs-6"></div>
							<div class="tries-combo col-md-3 col-sm-3 col-xs-3">
								<span class="">Essais</span>
							</div>
							<div class="points-combo col-md-3 col-sm-3 col-xs-3">
								<span class="">Points</span>
							</div>
						</div>
						<div class="team-side col-md-6 col-sm-12 col-xs-12">
							<div class="team-flag col-md-2 col-sm-2 col-xs-2">
								<img class="item-flag" src="' . $pays[$id_pays1]['drapeau_moyen'] . '" alt=""/>
							</div>
							<div class="team-name col-md-4 col-sm-4 col-xs-4">
								<span class="name section-highlight">' . $nom_equipe1 . '</span>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 btn-group tries-combo">
								<button type="button" id="essais1" name="essais1" class="btn btn-md btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_joueur['prono_essais1'] . '</button>
								<button type="button" class="btn btn-md btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu tries-list" role="menu">
									<li>-</li>
									<li>0</li>
									<li>1</li>
									<li>2</li>
									<li>3</li>
									<li>4</li>
									<li>5</li>
									<li>6</li>
									<li>7</li>
									<li>8</li>
									<li>9</li>
								</ul>
							</div>
							<div id="combo-points-1" class="col-md-3 col-sm-3 col-xs-3 btn-group points-combo">
								<button type="button" id="score1" name="score1" class="btn btn-lg btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_joueur['prono_points1'] . '</button>
								<button type="button" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu points-list" role="menu">
								</ul>
							</div>
						</div>

						<div class="team-mirror col-md-6 col-sm-12 col-xs-12">
							<div class="team-flag col-md-2 col-sm-2 col-xs-2">
								<img class="item-flag" src="' . $pays[$id_pays2]['drapeau_moyen'] . '" alt=""/>
							</div>
							<div class="team-name col-md-4 col-sm-4 col-xs-4">
								<span class="name section-highlight">' . $nom_equipe2 . '</span>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 btn-group tries-combo">
								<button type="button" id="essais2" name="essais2" class="btn btn-md btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_joueur['prono_essais2'] . '</button>
								<button type="button" class="btn btn-md btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu tries-list" role="menu">
									<li>-</li>
									<li>0</li>
									<li>1</li>
									<li>2</li>
									<li>3</li>
									<li>4</li>
									<li>5</li>
									<li>6</li>
									<li>7</li>
									<li>8</li>
									<li>9</li>
								</ul>
							</div>
							<div id="combo-points-2" class="col-md-3 col-sm-3 col-xs-3 btn-group points-combo">
								<button type="button" id="score2" name="score2" class="btn btn-lg btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_joueur['prono_points2'] . '</button>
								<button type="button" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu points-list" role="menu">
								</ul>
							</div>
						</div>
						<div id="send-pari" class="pres-button col-md-12 col-sm-12 col-xs-12">
							<button type="button" id="envoi_pari" class="btn btn-primary btn-lg ' . $tmp . '" >' . $txt . '</button>
						</div>
						<div class="alert-msg-prono col-md-12 col-sm-12 col-xs-12">
						
						</div>
					</div>';
	
    }
        
    $res .= '
			</div>';
    
    
    $envoi = array(
	    'essais1' => $prono_joueur['prono_essais1'],
	    'essais2' => $prono_joueur['prono_essais2'],
	    'premier' => $premier,
	    'html' => $res);
    
    echo json_encode($envoi);
?>