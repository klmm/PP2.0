<?php

    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    joueur
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------
    
    ------------ OUTPUTS -------*/

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_equipe.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_cycliste.php';
    //-------------------------------------------------------------------------------------//
    
    

    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $ID_CAL = $_POST['id_cal'];
    $joueur = $_POST['joueur'];
    //------------------------------------------------------------------------------------------------//
    
    //--------------------------------------CALENDRIER--------------------------------------//
    $calendrier = get_calendrier($ID_JEU,$ID_CAL);
    $b_equipe = $calendrier['profil_equipe'];
    $b_commence = $calendrier['commence'];
    $b_traite = $calendrier['traite'];
    //------------------------------------------------------------------------------------------------//
    
    if($b_commence){
	//-------------------------PRONO DU JOUEUR-----------------------------------//
	$prono = get_prono($ID_JEU,$ID_CAL,$joueur);
	//----------------------------------------------------------------------------//

	//-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
	if($b_equipe){
	    $chaine_id_equipes = $prono['prono'];

	    $tab_id_equipes = array_unique(explode(";", $chaine_id_equipes));
	    $tab_equipes = get_equipes_tab_id($chaine_id_equipes);
	    $tab_cyclistes = null;
	}
	else{
	    $chaine_id_cyclistes = $prono['prono'];

	    $tab_id_cyclistes = array_unique(explode(";", $chaine_id_cyclistes));
	    $tab_cyclistes = get_cyclistes_jeu_tab_id($ID_JEU,$ID_CAL,$chaine_id_cyclistes);
	    $tab_equipes = null;
	}
	//-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
    }
    else{
	$prono = null;
	$tab_cyclistes = null;
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
	
	if(!$b_equipe){
	    $res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
			    <td class="table-name col-md-6">' .  $tab_cyclistes[$id_entite_prono]['prenom'] . ' ' . $tab_cyclistes[$id_entite_prono]['nom'] .'</td>
			    <td class="table-point col-md-4">' . $prono['points_prono'][$i] . '</td>';
	}
	else{
	    $res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
			    <td class="table-name col-md-6">' . $tab_equipes[$id_entite_prono]['nom_complet'] . '</td>
			    <td class="table-point col-md-4">' . $prono['points_prono'][$i] . '</td>';
	}
	$res .= '	</tr>';
    }
    
    if(sizeof($prono['prono']) > 1){
	$tmp_risque = '	    <li class="score">
				    <p class="stat-item">Score</p>
				    <p class="stat-value">'. $prono['score_total'] .'</p>
			    </li>
			    <li class="risk">
				    <p class="stat-item">Risques</p>
				    <p class="stat-value">'. $prono['bonus_risque'] .'%</p>
			    </li>
			    <li class="number">
				    <p class="stat-item">Bonus</p>
				    <p class="stat-value">'. $prono['bonus_nombre'] .'</p>
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