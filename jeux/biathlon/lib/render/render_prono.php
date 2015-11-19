<?php

    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    joueur
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------
    
    ------------ OUTPUTS -------*/

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_athlete.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_equipe.php';
    //-------------------------------------------------------------------------------------//
    
    

    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    $joueur = $_POST['joueur'];
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = biathlon_get_calendrier($ID_CAL);
    if($calendrier['specialite'] == 'Relais'){
	$b_relais = true;
    }
    else{
	$b_relais = false;
    }
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    if($b_commence){
	//-------------------------PRONO DU JOUEUR-----------------------------------//
	$prono = get_prono($ID_CAL,$joueur);
	//----------------------------------------------------------------------------//

	//-------------------------------ATHLETES/EQUIPES UTILES------------------------------------//
	$chaine_id_athletes = $prono['prono'];
	if($b_relais == false){
	    $tab_athletes = get_athletes_tab_id($chaine_id_athletes);
	}
	else{
	    $tab_equipes = get_equipes_genre($calendrier['genre']);
	}
	//-------------------------------ATHLETES/EQUIPES UTILES------------------------------------//
    }
    else{
	$prono = null;
	$tab_athletes = null;
	$tab_equipes = null;
    }
    
    $prono['prono'] = explode(";", $prono['prono']);
    $prono['points_prono'] = explode(";", $prono['points_prono']);
    
    $res = '	    <div class="sectionSide">
			<p class="section-highlight">Top 10 de ' . $joueur . '</p>
		    </div>
			<div class="col-md-9 col-sm-9 col-xs-9">
		    <table class="table">';
    
    for($i=0;$i<10;$i++){
	
	$id_entite_prono = $prono['prono'][$i];
	$res .= '	<tr class "">';
	if($b_traite){
	    $pts_prono = $prono['points_prono'][$i];
	}
	else{
	    $pts_prono = '';
	}
	
	if($b_relais){
	    $res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
			    <td class="table-name col-md-6">' .  $tab_equipes[$id_entite_prono]['nom'] .'</td>
			    <td class="table-point col-md-4">' . $pts_prono . '</td>';
	}
	else{
	    $res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
			    <td class="table-name col-md-6">' .  $tab_athletes[$id_entite_prono]['prenom'] . ' ' . $tab_athletes[$id_entite_prono]['nom'] .'</td>
			    <td class="table-point col-md-4">' . $pts_prono . '</td>';
	}
	
	    
	$res .= '	</tr>';
    }
    
    if(sizeof($prono['prono']) > 1){
	if($b_traite){
	    $score_total = $prono['score_total'];
	    $bonus_reg = $prono['bonus_nombre'];
	}
	else{
	    $score_total = '-';
	    $bonus_reg = '-';
	}
	    
	$tmp_risque = '	    <li class="score">
				    <p class="stat-item">Score</p>
				    <p class="stat-value">'. $score_total .'</p>
			    </li>
			    <li class="risk">
				    <p class="stat-item">Risques</p>
				    <p class="stat-value">'. $prono['bonus_risque'] .'%</p>
			    </li>
			    <li class="number">
				    <p class="stat-item">Bonus</p>
				    <p class="stat-value">'. $bonus_reg .'</p>
			    </li>';
    }
    else{
	$tmp_risque = '	    <li class="risk">
				    <p class="stat-item">Pas de prono</p>
			    </li>';
    }
    
    $res .= '	    </table>
				</div>
				<div class="stat-box col-md-3 col-sm-3 col-xs-3">
					<ul>' . $tmp_risque . '
					</ul>
				</div>';
    
    
    echo $res;
	
?>