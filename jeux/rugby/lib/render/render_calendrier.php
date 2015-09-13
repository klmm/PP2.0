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
    $calendrier = get_calendrier($ID_JEU,$ID_CAL);
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    
    
    //--------------------------------------PRONOS DES JOUEURS------------------------------------------------//
    $tab_pronos = get_pronos_cal($ID_JEU, $ID_CAL);
    $nb_pronos = sizeof($tab_pronos);
    //--------------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    
    
    //--------------------------------------MON PRONO------------------------------------------------//
    if($bConnected){
	$prono_joueur = get_prono($ID_JEU,$ID_CAL,$loginjoueur);
    }
    else{
	$prono_joueur = null;
    }
    //--------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    

    
    
    
    //-------------------------------PAYS/EQUIPES------------------------------------//
    $tab_equipes = get_equipes_inscrites($ID_JEU, $ID_CAL);
    $pays = get_pays_tous();
    //-------------------------------PAYS/EQUIPES------------------------------------//
    
  
    $prono_joueur['prono'] = explode(';',$prono_joueur['prono']);
    $prono_joueur['points_prono'] = explode(';',$prono_joueur['points_prono']);
    $calendrier['classement'] = explode(';',$calendrier['classement']);
   
    
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
	$txt = 'Parier';
    }
    // Pas dispo
    else{
	$tmp = 'disabled';
	$txt = 'Bientôt';
    }

    $res = '	    
		<div class="pres-panel clearfix">
		    <p class="name section-highlight">' . $calendrier['nom_complet'] . $distance . '</p>
			<p class="date">' . $calendrier['date_debut_fr'] . ' - ' . $calendrier['heure_debut_fr'] . '</p>
		    <div class="pres-button col-md-12 col-sm-12 col-xs-12">
			<a class="btn btn-primary btn-lg ' . $tmp . '" href="' . $calendrier['url'] . '">' . $txt . '</a>
		    </div>
		</div>
		<div class="result-panel">';
    
    // AFFICHAGE PARTIE CALENDRIER
        
    if($calendrier['commence']){
	
	// RESULTAT
	$res .= '   <div class="row player-result">
			<div class="table-box col-md-6 col-sm-6 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Résultat</p>
			    </div>	
			    <table class="table">';
	
	for ($i=0;$i<10;$i++){
	    $id_entite_res = $calendrier['classement'][$i];
	    $res .= '		<tr class="">';
	    if(!$calendrier['profil_equipe']){
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-place col-md-4">' . $tab_cyclistes[$id_entite_res]['prenom'] . ' ' . $tab_cyclistes[$id_entite_res]['nom'] .'</td>';	    }
	    else{
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-place col-md-4">' . $tab_equipes[$id_entite_res]['nom_complet'] . '</td>';	   }
	   $res .= '		</tr>';
	}
	
	$res .= '	    </table>
			</div>';
	
	
	// PRONO JOUEUR
	$res .= '	<div class="table-stat-box col-md-6 col-sm-6 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Mon Top 10</p>
			    </div>
				<div class="col-md-9 col-sm-9 col-xs-9">
			    <table class="table">';
	
	
	
	for ($i=0;$i<10;$i++){
	    $id_entite_prono = $prono_joueur['prono'][$i];
	    $res .= '		<tr class="">';
	    if($calendrier['traite']){
		$pts_prono = $prono_joueur['points_prono'][$i];
	    }
	    else{
		$pts_prono = '';
	    }
	    
	    if(!$calendrier['profil_equipe']){
		
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-name col-md-6">' .  $tab_cyclistes[$id_entite_prono]['prenom'] . ' ' . $tab_cyclistes[$id_entite_prono]['nom'] .'</td>
				    <td class="table-point col-md-4">' . $pts_prono . '</td>';
	    }
	    else{
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-name col-md-6">' . $tab_equipes[$id_entite_prono]['nom_complet'] . '</td>
				    <td class="table-point col-md-4">' . $pts_prono . '</td>';
	    }
	    $res .= '		</tr>';
	}
	
	if(sizeof($prono_joueur['prono']) == 1){
	    $tmp_risque = '	<li class="risk">
				    <p class="stat-item">Pas de prono</p>
				</li>';
	}
	else{
	    if($calendrier['traite']){
		$score_total = $prono_joueur['score_total'];
		$bonus_reg = $prono_joueur['bonus_nombre'];
	    }
	    else{
		$score_total = '-';
		$bonus_reg = '-';
	    }
	    $tmp_risque = '	<li class="score">
				    <p class="stat-item">Score</p>
				    <p class="stat-value">'. $score_total .'</p>
				</li>
				<li class="risk">
				    <p class="stat-item">Risques</p>
				    <p class="stat-value">'. $prono_joueur['bonus_risque'] .'%</p>
				</li>
				<li class="number">
				    <p class="stat-item">Bonus</p>
				    <p class="stat-value">'. $bonus_reg .'</p>
				</li>';
	}
	
	
	
	$res .= '	    </table>
			</div>
			<div class="stat-box col-md-3 col-sm-3 col-xs-3">
			    <ul>' . $tmp_risque . '	
			    </ul>
			</div>
		    </div>	
		</div>';
	
	
	
	// AUTRES JOUEURS
	$res .= '   <div class="row other-result">
			<div class="table-box col-md-6 col-sm-6 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Scores</p>
			    </div>
			    <div class="classement-table">
				<table id="' . $ID_CAL . '" class="table table-hover scores">';
	
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
	    
	    $res .= '		    <tr class="' . $class_surlign_joueur . '">
					<th class="table-place col-md-2">' . $pos . '</th>
					<td class="table-name col-md-6">' . $value['joueur'] . '</td>
					<td class="table-point col-md-4">' . $pts_joueur . '</td>
				    </tr>';
	}
	
	$res .= '		</table>
			    </div>
			</div>
			<div id="son_prono" class="table-stat-box col-md-6 col-sm-6 col-xs-12">

			</div>
		    </div>';
	
    }
    else{
	// PRONO JOUEUR UNIQUEMENT
	$res .= '<div class="row later">
		    <div class="table-box col-md-6 col-sm-6 col-xs-12">
			<div class="sectionSide">
			    <p class="section-highlight">Mon Top 10</p>
			</div>
			<table class="table">';
	
	for ($i=0;$i<10;$i++){
	    $id_entite_prono = $prono_joueur['prono'][$i];
	    $res .= '	     <tr class="">';
	    if(!$calendrier['profil_equipe']){
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-name col-md-6">' . $tab_cyclistes[$id_entite_prono]['prenom'] . ' ' . $tab_cyclistes[$id_entite_prono]['nom'] .'</td>
				    <td class="table-point col-md-4">-</td>';
	    }
	    else{
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-name col-md-6">' . $tab_equipes[$id_entite_prono]['nom_complet'] . '</td>
				    <td class="table-point col-md-4">-</td>';
	   }
	   $res .= '	     </tr>';
	}
	
	if(sizeof($prono_joueur['prono']) > 1){
	    $tmp_risque = '		<li class="risk">
					    <p class="stat-item">Prise de risque</p>
					    <p class="stat-value">'. $prono_joueur['bonus_risque'] .'%</p>
					</li>';
	}
	else{
	    $tmp_risque = '		<li class="risk">
					    <p class="stat-item">Pas de prono</p>
					</li>';
	}
	
	$res .= '	</table>
		    </div>
		    <div class="stat-box col-md-6 col-sm-6 col-xs-12">
				<ul>' . $tmp_risque . '		
					<li class="time-out">
						<p class="stat-item">Temps restant</p>
						<p class="stat-value">' . $calendrier['temps_restant'] . '</p>
					</li>
				</ul>
		    </div>
		</div>';
    }
    
    $res .= '</div>';
    
    
    $envoi = array(
	    'premier' => $premier,
	    'html' => $res);
    
    echo json_encode($envoi);
?>