<?php
	$ID_JEU = 1;
	$js = '/jeux/cyclisme/2015/tour-de-france/js/tdf2015.js';
	$css = '/jeux/cyclisme/2015/tour-de-france/css/tdf2015.css';
	$logo = '/img/logos/2015/tdf.png';
	$description = 'Pronostics gratuits sur le Tour de France 2015.';
	
    //--------------------------------------FONCTIONS--------------------------------------//
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/auto_login.php';   
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_joueurs.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_commentaires.php'; 
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_likes.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/clean_url.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
	require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/render/render_zone_prono.php';
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
	    update_derniere_visite($loginjoueur);
	    $bConnected = true;
	}
	else{
	    $bConnected = false;
	}
    //------------------------------------------------------------------------------------------------//








    //--------------------------------------INFOS SUR LE CALENDRIER--------------------------------------//
	$ID_CAL = $_GET['id'];

	if (!is_numeric($ID_CAL)){
	    header('Location: /redirect/erreur404.html');
	    return;
	}
	// Infos sur l'article en lui-même
	$jeu = get_jeu_id($ID_JEU);
	$calendrier = get_calendrier($ID_JEU,$ID_CAL);
	$liste_calendrier = get_calendrier_jeu_avenir($ID_JEU);
	
	if(!$jeu['commmence']){
	    header('Location: /redirect/erreur404.html');
	    return;
	}
	
	if ($calendrier == null){
	    header('Location: /redirect/erreur404.html');
	    return;
	}
	
	if($admin == false){
	    $mise_resultat = false;
	    if ($calendrier['commence']){
		echo '<h2>Le pronostic n\'est plus disponible...</h2><script>setTimeout(function(){ window.location.href = "../"; }, 3000)</script>';
		return;
	    }   
	}
	else{
	    if ($calendrier['commence']){
		$mise_resultat = true;
	    } 
	    else{
		$mise_resultat = false;
	    }
	}
	
	if (!$calendrier['disponible']){
	    echo '<h2>Le pronostic n\'est pas encore disponible...</h2><script>setTimeout(function(){ window.location.href = "../"; }, 3000)</script>';
	    return;
	}
	
	$titre = $calendrier['nom_complet'];
		
	$res = get_zone_prono($ID_JEU, $ID_CAL);
	$cyclistes = $res['cyclistes'];
	$prono = $res['prono'];
	$equipes = $res['equipes'];
    //------------------------------------------------------------------------------------------------//









    // DOCTYPE, META
    echo '<!DOCTYPE html>
        <html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="generator" content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
        <meta http-equiv="Content-Type" content="text/html;utf-8" />
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="author" content="" />

	<meta name="content-language" content="fr"/>
	<meta name="description" content="' . $description . '"/>
	<meta name="keywords" content="' . $titre . '"/>
	<meta name="subject" content=""/>
	<meta name="copyright" content="Parions Potes 2015"/>
	<meta name="identifier-url" content="www.parions-potes.fr"/>
	<meta property="og:title" content="' . $titre . '" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="www.parions-potes.fr" />
	<meta property="og:image" content="' . $logo . '"/>
	
        <link rel="shortcut icon" href="/img/logos/icone.ico"/>';

    // TITLE
    echo '
        <title>' . $titre . '</title>';

    // BOOTSTRAP
    echo '
        <link href="/css/bootstrap.min.css" rel="stylesheet">		
        <link href="/css/font-awesome.min.css" rel="stylesheet">

        <link href="/css/carousel.css" rel="stylesheet">
        <link href="/css/style.css" rel="stylesheet">
	<link href="' . $css . '" rel="stylesheet">
        <link href="/css/pagearticle.css" rel="stylesheet">
	<link href="/css/social-buttons.css" rel="stylesheet">
        <script src="/js/modernizr.custom.js"></script></head>';
    
    echo '
        <body data-spy="scroll" data-target="#navbar-main" data-offset="100">
	    <header id="home" class="no-js">
               <div class="navbar-wrapper" id="header-top">
                    <div class="container">
                        <h1><a href="../">Parions Potes</a></h1>  
                        <ul class="social" style="float:right; margin-right: 20px;">
                        <li class="twitter">
                            <a href="https://twitter.com/ParionsPotes" target="_blank">
                                <img src="/img/static/icons_set/no_border/twitter.png" alt="Twitter"></img>
                            </a>
                        </li>
                        <li class="facebook">
                            <a href="https://www.facebook.com/parionspotes" target="_blank">
                                <img src="/img/static/icons_set/no_border/facebook.png" alt="Facebook"></img>
                            </a>
                        </li>
                    </ul>
                    </div>	
                </div>
                <div class="navbar-wrapper" id="header-bottom">
                    <div class="container">
                        <div class="navbar  navbar-static-top" role="navigation" id="mainNavigation">
                            <div class="container">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button> 
                                    <a class="navbar-brand logosmall" href="#pari-panel" id="logosmall" data-action="scrollTo"></a>
                                    <ul class="nav nav-pills">';

    
    // CONNEXION - ESPACE JOUEUR
    if ($bConnected == false){
            echo '                      <li class="dropdown" id="menuLogin">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="navLogin" style="color:rgb(115, 50, 130);background:transparent;">
                                                <span class="glyphicon glyphicon-user"></span><span id="bConnect">  Se connecter </span>
                                            </a>
                                            <ul class="dropdown-menu" style="padding:13px;margin: 2px -10px 0;">
                                                <form role="form" id="formLogin" class="form" action="/lib/form/connect_joueur.php" method="POST">
                                                    <label>Se connecter</label>
                                                    <input name="username" id="username" type="text" placeholder="Login" title="Login" required="">
                                                    <input name="password" id="password" type="password" placeholder="Mot de passe" title="Mot de passe" required=""><br>
                                                    <button type="submit" id="btnLogin" class="btn btn-block btn-primary">Se connecter</button>
                                                </form>
						<li class="divider"> </li>
						<form style="text-align: center; padding: 5px; cursor:pointer;">
						    <a href="/#inscription">S\'inscrire</a>
						</form>
						<form style="text-align: center; padding: 5px; cursor:pointer;">
						    <a data-toggle="collapse" data-target="#lostPassword">Mot de passe oublié ?</a>
						</form>
						<form id="lostPassword" role="form" action="/lib/form/pass_oublie.php" method="POST" class="form collapse" style="height: auto;text-align: center;">
						    <input name="mail" id="mailChangePwd" type="text" placeholder="Mail" title="Mail" required="">                                  
						    <button type="submit" id="valChangePwd" class="btn btn-success">Valider</button>
						</form>
                                            </ul>
                                        </li>';
    }
    else
    {
            echo '                      <li class="dropdown" id="menuUser">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:rgb(115, 50, 130);background:transparent;">
                                                <span class="glyphicon glyphicon-user"></span><span id="bUsername">  ' . $loginjoueur . ' </span>
                                            </a>
                                            <ul class="dropdown-menu" style="min-width:202px;">
                                                <form style="text-align: center; padding: 5px; cursor:pointer;">
                                                    <a data-toggle="collapse" data-target="#changePassword">Changer de mot de passe</a>
                                                </form>

                                                <form id="changePassword" role="form" action="/lib/form/change_pass.php" method="POST" class="form collapse" style="padding: 17px;height: auto;text-align: center;background: gainsboro;">
                                                    <input name="oldpassword" id="oldpassword" type="password" placeholder="Mot de passe actuel" required=""> 
                                                    <input name="newpassword" id="newpassword" type="password" placeholder="Nouveau mot de passe" required=""><br>                                  
                                                    <input name="newpassword2" id="newpassword2" type="password" placeholder="Confirmer nouveau" required=""><br>                                  
                                                    <button type="submit" id="btnRegister" class="btn btn-success">Valider</button>
                                                </form>

                                                <li class="divider"> </li>

                                                <li>
                                                    <form id="logout-form" class="form" action="/lib/form/deconnect_joueur.php" method="POST">
                                                        <button type="submit" id="logout" class="btn btn-primary btn-block">Déconnexion</button>	
                                                    </form>
                                                </li>
                                            </ul>
                                        </li>';
    }

    // MENU
    echo '                          </ul>
                                </div>
                                <div class="navbar-collapse collapse" id="navbar-main">
                                    <ul class="nav navbar-nav pull-right" style="">
					<li class="active"><a href="#pari-panel" data-action="scrollTo">Mon prono</a></li>
					<li class=""><a href="#commentaires" data-action="scrollTo">Commentaires</a></li>
					<li class="home"><a href="../" class="glyphicon glyphicon-home "  aria-label="home"><span> Retour au site</span></a></li>
				    </ul>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>';
//---------------------------------------------FIN HEADER------------------------------------------------------//';


//---------------------------------------------PRENSENTATION ETAPE------------------------------------------------------//	
    if($calendrier['distance'] != 0){
	$distance = ' (' . $calendrier['distance'] . ' km)';
    }
    else{
	$distance = '';
    }
    
    echo '
            <div id="pari-panel" class="section" style="background-color: white;">
                <div class="container" id="presentation-etape">
		    <div class="sectionSide">
			<h2 class="section-heading">' . $calendrier['nom_complet'] . $distance . '</h2>
			<p class="section-highlight" style="margin-bottom:50px">' . $calendrier['date_debut_fr'] . ' - '. $calendrier['heure_debut_fr'] . '</p>
			
			<div class="btn-group calendar-combo">
			    <button type="button" class="btn btn-default">' . $calendrier['nom_complet'] . '</button>
			    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			    </button>
			    <ul id="calendar-list" class="dropdown-menu" role="menu">';
    
    foreach($liste_calendrier as $key => $value){
	$url_cal = $value['id_cyclisme_calendrier'] . '-' . $value['nom_complet'];
	echo '			<li><a href="' . clean_url($url_cal) . '">' . $value['nom_complet'] . '</a></li>';
    }
    
    echo '
			    </ul>
			</div>
			<p class="section-highlight">Faites votre pari ! Faites glisser vos favoris dans la zone prévue.</p>
		    </div>';
//---------------------------------------------PRENSENTATION ETAPE------------------------------------------------------//

   
//---------------------------------------------ZONE PRONO------------------------------------------------------//	
    echo '
		    <div class="col-xs-6">
			<input id="item-search" type="text" placeholder="Recherche" name="nom" class="form-control" style="margin-bottom:25px;"/>
			<ul id="sortable1" class="connectedSortable ui-sortable">';
    
    if($calendrier['profil_equipe']){
	foreach($equipes as $id => $equipe){
	    if($equipe['pos_prono'] === 0){
		echo '	    <li id="' . $equipe['id_cyclisme_equipe'] . '" name="prono" class="ui-state-default ui-sortable-handle"><span class="item-place"></span><span class="item-name">' . $equipe['nom_complet'] . '</span><img class="item-flag hidden-xs" src="' . $equipe['photo'] . '" alt=""/><div class="item-rating">';
	    
		for($z=0; $z<$equipe['etoiles']; $z++){
		    echo '	<span class="glyphicon glyphicon-star"></span>';
		}
		echo '	    </div></li>';
	    }
	}
    }
    else{
	foreach($cyclistes as $id => $cycliste){
	    if($cycliste['pos_prono'] === 0 || $mise_resultat == true){
		echo '	    <li id="' . $cycliste['id_cyclisme_athlete'] . '" name="prono" class="ui-state-default ui-sortable-handle"><span class="item-place"></span><span class="item-name">' . $cycliste['prenom'] . ' ' . $cycliste['nom'] . '</span><img class="item-flag hidden-xs" src="' . $cycliste['pays_drapeau_petit'] . '" alt=""/><div class="item-rating">';
	    
		for($z=0; $z<$cycliste['etoiles']; $z++){
		    echo '	<span class="glyphicon glyphicon-star"></span>';
		}
		echo '	    </div></li>';
	    }
	}
    }	
			
    echo '
			</ul>
			<div id="bottom-list"></div>
		    </div>
		    <div class="col-xs-6">
			<div class="result-area clearfix" data-spy="affix">
			    <div id="msg-container"> </div>
			    <input name="id_cal" id="id_cal" type="text" class="hidden" required="" value="' . $ID_CAL . '"/>';
    
    
    if($bConnected && $mise_resultat == false){
	echo '		    <button id="validate" type="button" class="btn btn-primary btn-block" action="/jeux/cyclisme/lib/form/envoi_prono.php">Valider</button>';
    }
    else{
	if($mise_resultat == true){
	    echo '	    <button id="validate" type="button" class="btn btn-primary btn-block" action="/jeux/cyclisme/admin/envoi_resultat.php">Enregistrer</button>';
	}
    }
				    
    echo '
			    <ul id="sortable2" class="connectedSortable ui-sortable" data-text="jjj">';
    
    if($bConnected && $mise_resultat == false){
	for($i=0;$i<sizeof($prono['cyclistes_prono']);$i++){
	    if($calendrier['profil_equipe']){
		$equipe = $prono['cyclistes_prono'][$i];
		if ($equipe['id_cyclisme_equipe']){
		    echo '	    <li id="' . $equipe['id_cyclisme_equipe'] . '" name="prono" class="ui-state-default ui-sortable-handle"><span class="item-place"></span><span class="item-name">' . $equipe['nom_complet'] . '</span><img class="item-flag hidden-xs" src="' . $equipe['photo'] . '" alt=""/><div class="item-rating">';
		    for($z=0; $z<$equipe['etoiles']; $z++){
			echo '	<span class="glyphicon glyphicon-star"></span>';
		    }
		    echo '	    </div></li>';}
	    }
	    else{
		$cycliste = $prono['cyclistes_prono'][$i];
		echo '	    <li id="' . $cycliste['id_cyclisme_athlete'] . '" name="prono" class="ui-state-default ui-sortable-handle"><span class="item-place"></span><span class="item-name">' . $cycliste['prenom'] . ' ' . $cycliste['nom'] . '</span><img class="item-flag hidden-xs" src="' . $cycliste['pays_drapeau_petit'] . '" alt=""/><div class="item-rating">';
	    
		for($z=0; $z<$cycliste['etoiles']; $z++){
		    echo '	<span class="glyphicon glyphicon-star"></span>';
		}
		echo '	    </div></li>';
	    }
	}
    }

    echo '
			    </ul>		
			    <button id="register" type="button" class="btn btn-primary btn-block hidden" >Enregistrer</button>
			    <div class="progress progress-striped active"><span class="progress-title">prise de risque</span>
				<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="60" style="min-width:2em">0%
				</div>
			    </div>	
			</div>
		    </div>
		</div>
	    </div>';
//---------------------------------------------ZONE PRONO------------------------------------------------------//   
   

 
//---------------------------------------------COMMENTAIRES------------------------------------------------------//	
    echo '
            <section id="comment" data-speed="2" data-type="background">
                <div class="container" id="commentaires">';
				
    if ($bConnected){
        echo '
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
        echo '	    <div class="sectionSide">
			<h2 class="section-heading">Commentaires</h2>
			<p class="section-highlight">Connectez-vous pour participer au débat !</p>
		    </div>';
    }
	
    echo '	    <div class="row com-container">
	
		    </div>
		</div>
            </section>';
//---------------------------------------------FIN COMMENTAIRES------------------------------------------------------//





	echo '
	    <footer>
		<div class="container ">
		    <p>© 2015 Parions Potes </p>
		</div>
	    </footer>

	    <script src="/js/jquery.min.js"></script>
	    <script src="/js/bootstrap.min.js"></script>
	    <script src="/js/classie.js"></script>
	    <script src="/js/jquery.scrollTo.min.js"></script>
	    <script src="/js/jqBootstrapValidation.js"></script>	
	    <script src="/js/script.js"></script>
	    <script src="/js/jquery-ui.dd.min.js"></script>
	    <script src="' . $js . '"></script>
	    <script src="/bower_components/velocity/velocity.js"></script>
	    <script src="/bower_components/moment/min/moment-with-locales.min.js"></script>
	    <script src="/bower_components/angular/angular.js"></script>
	    <script src="/js/social-buttons.js"></script>
	    <script src="/js/pagination.js"></script>
	    <script src="/js/imagesloaded.pkgd.min.js"></script>
	    <script src="/js/d3.min.js"></script>
	    
	    <script>
	    jQuery(document).ready(function ($) {
		$(function () {$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();} );

		    calcrisk();
		    updateNums();
		    getAllComs(0,' . $ID_JEU . ',' . $ID_CAL . ',0);
			
		    Init_Forms();
		    Init_Forms_Cyclisme();
		    Init_Zone_Paris();
                
		$(window).resize(function() {
				 $(\'body\').scrollspy("refresh");
		});
	                
		$(\'a[data-action="scrollTo"]\').click(function(e)
		{
			e.preventDefault();
			scrollX = $(\'.header\').height();
			$(\'.menu\').toggleClass(\'active\');
			if(this.hash == "#myCarousel") {
					$(\'body\').scrollTo(0,500,null);
						   
					$(".section").removeClass("inactiveSection");
			} else {
					$(\'body\').scrollTo(this.hash, 500, {offset: -scrollX});
			}
			$(\'.navbar-collapse\').removeClass(\'in\');	
		});
                
		$(\'[data-toggle=dropdown]\').dropdown();
        
		// cache the window object
		$window = $(window);
                
		$(\'.result-area\').affix({
		    offset: {
			  top: 380,
			  bottom: function () {
			    var total = $(\'body\').height();
			    var bottomfixed = $(\'#bottom-list\').offset().top;
			    var offset = total - bottomfixed;
			    //if(total - offset < bottomfixed) {offset = ofset - $(\'.result-area\').height();}
			    return offset;
			  }
		    }
		  });     
		
             
	    });
	    


	    var cbpAnimatedHeader = (function() {
 
		var docElem = document.documentElement,
				header = document.querySelector( \'header\' ),
				didScroll = false,
				resultarea = document.querySelector( \'.result-area\' ),
				fixResultZone = 150,
				changeHeaderOn = 98;
                        
         
		function init() {
				window.addEventListener( \'scroll\', function( event ) {
						if( !didScroll ) {
								didScroll = true;
								setTimeout( scrollPage, 250 );
						}
				}, false );
		}
                
		function scrollPage() {
			var sy = scrollY();
			if ( sy >= changeHeaderOn ) {
					classie.add( header, \'small\' );
			}
			else {
					classie.remove( header, \'small\' );
			}
			didScroll = false;
			
			var activeTarget = $("#navbar-main li.active a").attr("href");
			//alert(activeTarget);
		   
			$(".activeSection").removeClass("activeSection");
			if(sy > 650) {
		   
				$(".section").addClass("inactiveSection");
			
				$(activeTarget).addClass("activeSection");
			} else {
		   
				$(".section").removeClass("inactiveSection");
			}
				
				
			
		}
         
		function scrollY() {
				return window.pageYOffset || docElem.scrollTop;
		}

		init();
         
        })();

	    </script>



     </body></html>';

?>