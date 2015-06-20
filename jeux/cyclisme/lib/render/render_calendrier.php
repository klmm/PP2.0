<?php
    
    /* --------- INPUTS -------
    id_jeu
    id_cal
    ------------ INPUTS -------*/
   
    /* --------- OUTPUTS -------
    
    ------------ OUTPUTS -------*/
   

    //--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_prono.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_equipe.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_cycliste.php';
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
    $b_equipe = $calendrier['profil_equipe'];
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
    
    
    
    
    
    
    

    
    
    
    //-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
    if($b_equipe){
	$chaine_id_equipes = $prono_joueur['prono'];
	if($b_traite){
	    $chaine_id_equipes .= $calendrier['classement'];
	}

	$tab_id_equipes = array_unique(explode(";", $chaine_id_equipes));
	$tab_equipes = get_equipes_tab_id($tab_id_equipes);
	$tab_cyclistes = null;
    }
    else{
	$chaine_id_cyclistes = $prono_joueur['prono'];
	if($b_traite){
	    $chaine_id_cyclistes .= ';' . $calendrier['classement'];
	}
	
	$tab_id_cyclistes = array_unique(explode(";", $chaine_id_cyclistes));
	$tab_cyclistes = get_cyclistes_jeu_tab_id($ID_JEU,$ID_CAL,$tab_id_cyclistes);
	$tab_equipes = null;
    }
    //-------------------------------CYCLISTES/EQUIPES UTILES------------------------------------//
    
  
    
    $calendrier['url'] = clean_url('pronostic/' . $ID_CAL . '-' . $calendrier['nom_complet']);
    
    $prono_joueur['prono'] = explode(';',$prono_joueur['prono']);
    $prono_joueur['points_prono'] = explode(';',$prono_joueur['points_prono']);
    $calendrier['classement'] = explode(';',$calendrier['classement']);
   
    
    // AFFICHAGE PARTIE CALENDRIER
    
    if($calendrier['commence']){
	$tmp = 'disabled';
    }
    else{
	$tmp = '';
    }
    
    // GERER POUR LE BOUTON PARIER : NON-DISPO / DISPO / COMMENCE
    
    $res = '	    
		    <div class="pres-box" style="background-image:url(/img/articles/cyclisme/demare_deg.jpg)">
			<div class="pres-panel">
			   <div class="pres-button col-md-12 col-sm-12 col-xs-12">
				<a class="btn btn-primary btn-lg ' . $tmp . '" href="' . $calendrier['url'] . '">Parier</a>
			    </div>
			    <div class="pres-text col-md-9 col-sm-9 hidden-xs">
				<div class="jumbotron bcr-rugby">
				    <p>' . $calendrier['nom_complet'] . '</p>
				</div>
			    </div>
			    <div class="pres-stat col-md-3 col-sm-3 hidden-xs">
				<div class="stat-box stat-inline">

				</div>
			    </div>
			</div>
		    </div>';
    
    // AFFICHAGE PARTIE CALENDRIER
    
    
    
    if($calendrier['commence']){
	
	// RESULTAT
	$res .= '   <div class="row player-result">
			<div class="table-box col-md-6 col-sm-6 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Résultat</p>
			    </div>	
			    <table class="table table-hover">';
	
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
	$res .= '	<div class="table-box col-md-6 col-sm-6 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Mon Top 10</p>
			    </div>
			    <table class="table table-hover">';
	
	for ($i=0;$i<10;$i++){
	    $id_entite_prono = $prono_joueur['prono'][$i];
	    $res .= '		<tr class="">';
	    if(!$calendrier['profil_equipe']){
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-name col-md-6">' .  $tab_cyclistes[$id_entite_prono]['prenom'] . ' ' . $tab_cyclistes[$id_entite_prono]['nom'] .'</td>
				    <td class="table-point col-md-4">' . $prono_joueur['points_prono'][$i] . '</td>';
	    }
	    else{
		$res .= '	    <th class="table-place col-md-2">' . ($i+1) .'</th>
				    <td class="table-name col-md-6">' . $tab_equipes[$id_entite_prono]['nom_complet'] . '</td>
				    <td class="table-point col-md-4">' . $prono_joueur['points_prono'][$i] . '</td>';
	    }
	    $res .= '		</tr>';
	}
	
	$res .= '	    </table>
			</div>';
	
	
	
	// AUTRES JOUEURS
	$res .= '	<div class="row other-result">
			    <div class="table-box col-md-6 col-sm-6 col-xs-12">
				<div class="sectionSide">
				    <p class="section-highlight">Scores</p>
				</div>	
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
	    $res .= '		    <tr class="' . $class_surlign_joueur . '">
					<th class="table-place col-md-2">' . $value['classement'] . '</th>
					<td class="table-name col-md-6">' . $value['joueur'] . '</td>
					<td class="table-point col-md-4">' . $value['score_total'] . '</td>
				    </tr>';
	}
	
	$res .= '		</table>
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
			<table class="table table-hover">';
	
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
	
	$res .= '	</table>
		    </div>
		</div>';
    }
    
    
    
    
    $envoi = array(
	    'premier' => $premier,
	    'html' => $res);
    
    echo json_encode($envoi);
/*
		// AFFICHAGE PARTIE CLASSEMENTS
		    
		    // AFFICHAGE PRONO JOUEUR
		    var content_prono_joueur = '' +
			'<div class="row later">
			    '<div class="table-box col-md-6 col-sm-6 col-xs-12">
				'<div class="sectionSide">
				    '<p class="section-highlight">Mon Top 10</p>
				'</div>
				'<table class="table table-hover">
				    '<tr class="">';
				    
		    if(prono_joueur == null){
			for (var i = 1; i <= 10; i++) {
			    content_prono_joueur += '' +    
				'<th class="table-place col-md-2">' + i + '.</th>
				'<td class="table-name col-md-6">-</td>
				'<td class="table-point col-md-4">-</td>';
			}
		    }
		    else{
			for (var i = 1; i <= prono_joueur.prono.length; i++) {
			    content_prono_joueur += '' +    
				'<th class="table-place col-md-2">' + i + '.</th>
				'<td class="table-name col-md-6">' + cyclistes[prono_joueur.prono[i-1]].prenom + ' ' + cyclistes[prono_joueur.prono[i-1]].nom + '</td>';
			
			    if(calendrier.traite){
				content_prono_joueur += '<td class="table-point col-md-4">' + prono_joueur.points_prono[i-1] + '</td>';
			    }
			    else{
				content_prono_joueur += '<td class="table-point col-md-4">-</td>';
			    }	
			}
		    }
		    
		    content_prono_joueur +='
					    '</tr>
					'</table>
				    '</div>
				    '<div class="stat-box col-md-6 col-sm-6 col-xs-12">
					    '<!-- zone pour des stats -->
				    '</div>
				'</div>';
		    
		    
		    
		    
		    // AFFICHAGE TOP 10 REEL
		    if(calendrier.traite == "1"){
			
		    }
		    else{

		    }
  */
?>