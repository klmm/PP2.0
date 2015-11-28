<?php

    //--------------------------------------FONCTIONS--------------------------------------//
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_pays.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_calendrier.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_weekend.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_prono.php';
    //-------------------------------------------------------------------------------------//
    
    
    //--------------------------------------VARIABLES DE SESSION--------------------------------------//
    $ID_JEU = $_POST['id_jeu'];
    $FILTRE = $_POST['filtre'];
    //------------------------------------------------------------------------------------------------//

    
    //--------------------------------------CONNECTE--------------------------------------//
    session_start();
    $JOUEUR = $_SESSION['LoginJoueur'];

    if($JOUEUR != ""){
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    //------------------------------------------------------------------------------------------------//
    
    /*
     	    <div class="calendar-filter">
		<ul class="nav nav-pills nav-justified">
		    <li role="presentation" class="active filtre_courses"><a onclick="javascript:render_liste_calendrier(' . $FILTRE . ');" id="' . $FILTRE . '">Mes courses</a></li>
		    <li role="presentation" class="filtre_courses"><a id="0" onclick="javascript:render_liste_calendrier(0);">Toutes</a></li>
		</ul>
	    </div>
     */
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $id_cal = biathlon_get_id_calendrier_actuel($ID_JEU,$FILTRE);

    $arr_calendrier = biathlon_get_calendrier_jeu_filtre($ID_JEU,$FILTRE);
    $nb_calendrier = sizeof($arr_calendrier);

    $pays = get_pays_tous();
    
    $weekends = get_weekend_jeu($ID_JEU);
    
    $pronos_joueur = get_pronos_joueurs_jeu($ID_JEU,$JOUEUR);
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    
    $res = '';
    
    //---------------------------------------------CALENDRIER------------------------------------------------------//	
    if($nb_calendrier > 0){
	$res .= '
<div class="sectionSide" style="margin-bottom:50px;">
    <h2 class="section-heading">Calendrier</h2>
    <p class="section-highlight"><a target="_blank" href="/configuration">Choisir mes courses</a></p>
</div>
<div class="left-content col-md-3 col-sm-3">
    <nav id="calendar" class="navbar navbar-default" role="navigation">
	<div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-calendar">
		<span class="sr-only">Toggle navigation</span>';

	for ($i=0;$i<4;$i++){
	    $res .= '
		<span class="icon-bar"></span>';
	}
	
	$res .= '		
	    </button>
	</div>

	<div class="collapse navbar-collapse navbar-calendar">
	    <ul id="list-cal" class="nav navbar-nav">';
	
	for($i=0;$i<$nb_calendrier;$i++){
	    $calendrier = $arr_calendrier[$i];
	    $id = $calendrier['id_biathlon_calendrier'];
	    $id_we = $calendrier['id_weekend'];
	    
	    if($id == $id_cal){
		$tmp_class = 'active';
	    }
	    else{
		$tmp_class = '';
	    }
	    $tmp_prono = '';
	    if($calendrier['annule'] == "0"){
		if($calendrier['commence'] == "0"){
		    $tmp_date = $calendrier['date_debut_fr_tcourt'] . ', à ' . $calendrier['heure_debut_fr'];
		    if($calendrier['disponible'] == "1"){
			$tmp_ico = 'glyphicon-play';
			if($pronos_joueur[$id] != null){
			    $tmp_prono = '<font color="green"><b>Prono validé</b></font>';
			}
			else{
			    $tmp_prono = '<font color="red"><b>Pas de prono</b></font>';
			}
		    }
		    else{
			$tmp_ico = 'glyphicon-lock';
		    }
		}
		else{
		    if($calendrier['traite'] == "0"){
			$tmp_ico = 'glyphicon-refresh';
			$tmp_date = 'En cours';
		    }
		    else{
			$tmp_ico = 'glyphicon-stats';
			$tmp_date = 'Terminé';
			if($pronos_joueur[$id] != null){
			    $class_prono = $pronos_joueur[$id]['classement'];
			    if($class_prono == 1){
				$class_prono = '1er';
			    }
			    else{
				$class_prono .= 'ème';
			    }
			    $tmp_date .= ' (' . $class_prono . ')';
			}
			else{
			    $tmp_date .= ' (NC)';
			}
		    }
		}
	    }
	    else{
		$tmp_ico = 'glyphicon-stats';
		$tmp_date = 'Annulé';
	    }
	    	    
	    $res .= '
		<li class="' . $tmp_class . '">
		    <a class="clearfix" value="' . $id . '" data-action="goTo">
			<div class="flex-center"><img class="item-flag col-md-2 col-sm-2 hidden-xs" src="' . $pays[$weekends[$id_we]['id_pays']]['drapeau_icone'] . '"/>
			<span class="title col-md-10 col-sm-10">' . $weekends[$id_we]['lieu'] . ' - ' . $calendrier['specialite'] . ' ' . $calendrier['genre_fr'] . '</span></div>
			<span class="date col-md-6">' . $tmp_date . '<br/>' . $tmp_prono . '</span>
			<span class="glyphicon ' . $tmp_ico . ' col-md-6"></span>
		    </a>
		</li>';
	}
					    
	$res .= '
	    </ul>
	</div>
    </nav>
</div>

<div class="right-content col-md-9 col-sm-9 col-xs-12">	
    <div id="big-cal-container" class="scroll-content">
	<div id="cal-container" class="scroll-content">

	</div>';
	
	
	$res .= '
	<div class="comment-panel">
	    <div class="" id="commentaires">';
	
	if ($bConnected){
	    $res .= '
		<div class="sectionSide">
		    <h2 class="section-heading">Commentaires</h2>
		    <p class="section-highlight">Venez donner votre point de vue !</p>			
		</div>

		<div class="row post-container">		
		    <form id="post-form" role="form" class="row post-form" action="/lib/form/post_commentaire.php" method="POST">

		    </form>
		</div>';
	}
	else{
	    $res .= '		    
		<div class="sectionSide">
		    <h2 class="section-heading">Commentaires</h2>
		    <p class="section-highlight">Connectez-vous pour participer au débat !</p>
		</div>';
	}

	$res .= '		    
		<div class="row com-container">

		</div>
	    </div>
	</div>
    </div>
</div>';
	
	
	$arr = array(	'html' => $res,
			'id_cal' => $id_cal
		    );
	
	echo json_encode($arr);
    }
?>