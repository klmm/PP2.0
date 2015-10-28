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
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_badges.php';
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
    
    
  
    //--------------------------------------BADGES--------------------------------------//
    $badges = get_badges_tous();
    //----------------------------------------------------------------------------------//
    
    
    
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
    $tab_pronos = get_pronos_cal($ID_CAL);
    $nb_pronos = sizeof($tab_pronos);
    //--------------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    
    
    //--------------------------------------MON PRONO------------------------------------------------//
    if($bConnected){
	$prono_joueur = get_prono($ID_CAL,$loginjoueur);
    }
    else{
	$prono_joueur = null;
    }
    //--------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    

    
    
    
    //-------------------------------PAYS/EQUIPES------------------------------------//
    $equipes = get_equipes_inscrites($ID_JEU);
    $pays = get_pays_tous();
    //-------------------------------PAYS/EQUIPES------------------------------------//
    
 
        
    $id_equipe1 = $calendrier['id_equipe1'];
    $nom_equipe1 = $equipes[$id_equipe1]['nom'];
    $id_pays1 = $equipes[$id_equipe1]['id_pays'];

    $id_equipe2 = $calendrier['id_equipe2'];
    $nom_equipe2 = $equipes[$id_equipe2]['nom'];
    $id_pays2 = $equipes[$id_equipe2]['id_pays'];
    
    
    
    
    $txt_score = $nom_equipe1 . ' - ' . $nom_equipe2;
    
    if($calendrier['commence']){
	if($calendrier['traite']){
	    // MATCH TERMINE
	    $points_equipe1 = $calendrier['score1'];
	    $points_equipe2 = $calendrier['score2'];
	    $essais_equipe1 = $calendrier['essais1'];
	    $essais_equipe2 = $calendrier['essais2'];

	    $txt_score = $nom_equipe1 . ' ' . $points_equipe1 . ' - ' . $points_equipe2 . ' ' . $nom_equipe2;
	}
	else{
	    // MATCH EN COURS
	    $points_equipe1 = '-';
	    $points_equipe2 = '-';
	    $essais_equipe1 = '-';
	    $essais_equipe2 = '-';
	}
    }
    else{
	if($calendrier['disponible']){
	    $txt_button = 'Valider';
	    $state_button = '';
	}
	else{
	    $txt_button = 'Bientôt';
	    $state_button = 'disabled';
	}
    }
    
    
    
    
    
    

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
	$classement = '';
    }
    else{
	$prono_points_equipe1 = $prono_joueur['prono_points1'];
	$prono_points_equipe2 = $prono_joueur['prono_points2'];
	$prono_essais_equipe1 = $prono_joueur['prono_essais1'];
	$prono_essais_equipe2 = $prono_joueur['prono_essais2'];
	
	if($calendrier['traite']){
	    $score_points = ($prono_joueur['score_points1'] + $prono_joueur['score_points2'])*$calendrier['coefficient'] . ' pts';
	    $score_essais = ($prono_joueur['score_essais1'] + $prono_joueur['score_essais2'])*$calendrier['coefficient'] . ' pts';
	    $score_vainqueur = $prono_joueur['score_vainqueur']*$calendrier['coefficient'] . ' pts';
	    $score_total = $prono_joueur['score_total']*$calendrier['coefficient'] . ' pts';
	    $score_ecart = $prono_joueur['score_ecart']*$calendrier['coefficient'] . ' pts';
	    $classement = ' - ' . $prono_joueur['classement'];

	    if($prono_joueur['classement'] == 1){
		$classement .= 'er';
	    }
	    else{
		$classement .= 'ème';
	    }
	}
	else{
	    $score_points = '-';
	    $score_essais = '-';
	    $score_vainqueur = '-';
	    $score_total = '-';
	    $score_ecart = '-';
	    $classement = '';
	}
	
	
    }
 
    $res = '';  
    
    
    
    
    // PRESENTATION MATCH
    $res .= '	    
	    <div class="pres-panel clearfix">
		<img class="item-flag col-md-2 col-sm-2 hidden-xs" src="' . $pays[$id_pays1]['drapeau_grand'] . '"/>
		<p class="name section-highlight col-md-8 col-sm-8 col-xs-12">' . $txt_score . '</p>
		<img class="item-flag col-md-2 col-sm-2 hidden-xs" src="' . $pays[$id_pays2]['drapeau_grand'] . '"/>
		<p class="date col-md-6 col-sm-6 col-xs-6">' . $calendrier['tour'] . '</p>
		    <p class="date col-md-6 col-sm-6 col-xs-6">Coefficient x' . $calendrier['coefficient'] . '</p>
		<p class="date col-md-6 col-sm-6 col-xs-6">' . $calendrier['stade'] . ' (' . $calendrier['ville'] . ')</p>
		<p class="date col-md-6 col-sm-6 col-xs-6">' . $calendrier['date_debut_fr'] . ' - ' . $calendrier['heure_debut_fr'] . '</p>
	    </div>
	    <div class="result-panel">';
	
      
    // MATCH COMMENCE        
    if($calendrier['commence']){
	$res .= '				
				    <div class="row player-result">
					<div class="table-box col-md-6 col-sm-6 col-xs-12">
						<div class="sectionSide">
							<p class="section-highlight">Résultat</p>
						</div>	
						<div class="label-side col-md-12 col-sm-12 col-xs-12">
							<div class="col-md-6 col-sm-6 col-xs-6"></div>
							<div class="tries-combo col-md-3 col-sm-3 col-xs-3">
								<span class="">Essais</span>
							</div>
							<div class="points-combo col-md-3 col-sm-3 col-xs-3">
								<span class="">Score</span>
							</div>
						</div>
						<div class="team-side col-md-12 col-sm-12 col-xs-12">
							<div class="team-flag col-md-2 col-sm-2 col-xs-2">
								<img class="item-flag" src="' . $pays[$id_pays1]['drapeau_moyen'] . '" alt=""/>
							</div>
							<div class="team-name col-md-4 col-sm-4 col-xs-4">
								<span>' . $nom_equipe1 . '</span>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 team-tries">
								<span>' . $essais_equipe1 . '</span>
							</div>
							<div class="col-md-3 col-sm-3  col-xs-3 team-points">
								<span>' . $points_equipe1 . '</span>
							</div>
						</div>
						<div class="team-side col-md-12 col-sm-12 col-xs-12">
							<div class="team-flag col-md-2 col-sm-2 col-xs-2">
								<img class="item-flag" src="' . $pays[$id_pays2]['drapeau_moyen'] . '" alt=""/>
							</div>
							<div class="team-name col-md-4 col-sm-4 col-xs-4">
								<span>' . $nom_equipe2 . '</span>
							</div>
							<div class="col-md-3 col-sm-3 col-xs-3 team-tries">
								<span>' . $essais_equipe2 . '</span>
							</div>
							<div class="col-md-3 col-sm-3  col-xs-3 team-points">
								<span>' . $points_equipe2 . '</span>
							</div>
						</div>
					</div>	
					<div class="table-stat-box col-md-6 col-sm-6 col-xs-12">
						<div class="sectionSide">
						<p class="section-highlight">Mon Pari'  . $classement . '</p>
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
						</div>
					</div>	
				</div>';
	
	
	
	
	$res .= '
				<div class="row other-result">
				    <div class="table-box col-md-6 col-sm-6 col-xs-12">
					    <div class="sectionSide">
						<p class="section-highlight">Scores</p>
					    </div>
					    <div class="classement-table">
						<table id="' . $ID_CAL . '" class="table table-hover scores">
						    <tbody>';
	
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
		$pts_joueur = $value['score_total']*$calendrier['coefficient'];
	    }
	    else{
		$pos = '';
		$pts_joueur = $value['prono_points1'] . ' - ' . $value['prono_points2'] ;
	    }
	    
	    $res .= '
							<tr class="' . $class_surlign_joueur . '">
							    <th class="table-place col-md-2">' . $pos .'</th>
							    <td class="table-name col-md-6">
								<span class="player-name">' . $value['joueur'] . '</span>';
	    
	    foreach($badges[$value['joueur']] as $key => $badge){
		$res .= '					<span title="' . $badge['nom_badge'] . ' (' . $badge['classement'] . ')" class="player-badge ' . $badge['class_badge'] . '">' . $badge['classement'] . '</span>';
	    }
	    
	    $res .= '
							    </td>
							    <td class="table-point col-md-4">' . $pts_joueur . '</td>
							</tr>';
	}
								
	$res .= '		    
						    </tbody>
						</table>
					    </div>
				    </div>
				    <div id="son_prono" class="table-stat-box col-md-6 col-sm-6 col-xs-12">
				    
				    </div>
				</div>';
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
							<span class="name">' . $nom_equipe1 . '</span>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 tries-combo">
							<div class="btn-group">
								<button type="button" id="essais1" name="essais1" class="btn btn-md btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_essais_equipe1 . '</button>
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
						</div>
						<div id="combo-points-1" class="col-md-3 col-sm-3 col-xs-3 points-combo">
						
							<div class="btn-group">
							    <button type="button" id="score1" name="score1" class="btn btn-lg btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_points_equipe1 . '</button>
							    <button type="button" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								    <span class="caret"></span>
								    <span class="sr-only">Toggle Dropdown</span>
							    </button>
							    <ul class="dropdown-menu points-list" role="menu">
							    </ul>
							</div>
						    
						</div>
					</div>

					<div class="team-mirror col-md-6 col-sm-12 col-xs-12">
						<div class="team-flag col-md-2 col-sm-2 col-xs-2">
							<img class="item-flag" src="' . $pays[$id_pays2]['drapeau_moyen'] . '" alt=""/>
						</div>
						<div class="team-name col-md-4 col-sm-4 col-xs-4">
							<span class="name">' . $nom_equipe2 . '</span>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-3 tries-combo">
							<div class="btn-group">
								<button type="button" id="essais2" name="essais2" class="btn btn-md btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_essais_equipe2 . '</button>
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
						</div>
						<div id="combo-points-2" class="col-md-3 col-sm-3 col-xs-3 points-combo">
							<div class="btn-group">
								<button type="button" id="score2" name="score2" class="btn btn-lg btn-default" data-toggle="dropdown" aria-expanded="false">' . $prono_points_equipe2 . '</button>
								<button type="button" class="btn btn-lg btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
									<span class="caret"></span>
									<span class="sr-only">Toggle Dropdown</span>
								</button>
								<ul class="dropdown-menu points-list" role="menu">
								</ul>
							</div>	
						</div>
					</div>
					<div id="send-pari" class="pres-button col-md-12 col-sm-12 col-xs-12">
						<button type="button" id="envoi_pari" class="btn btn-primary btn-lg ' . $state_button . '" >' . $txt_button . '</button>
					</div>
					<div class="alert-msg-prono col-md-12 col-sm-12 col-xs-12">

					</div>
				    </form>
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