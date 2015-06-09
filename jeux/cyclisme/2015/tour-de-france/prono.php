<?php
	$ID_JEU = 4;
	$js = '/jeux/cyclisme/2015/tour-de-france/js/tdf2015.js';
	$css = '/jeux/cyclisme/2015/tour-de-france/css/tdf2015.css';

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
	    header('Location: /redirect/erreur404.php');
	    return;
	}

	// Infos sur l'article en lui-même
	$jeu = get_jeu_id($ID_JEU);
	$calendrier = get_calendrier($ID_JEU,$ID_CAL);
	$liste_calendrier = get_calendrier_jeu_avenir($ID_JEU);
	if ($calendrier == null){
	    header('Location: /redirect/erreur404.php?url=' . $jeu['url']);
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
	<meta name="description" content="Pronostics sur le Tour de France 2015."/>
	<meta name="keywords" content="' . $titre . '"/>
	<meta name="subject" content=""/>
	<meta name="copyright" content="Parions Potes 2015"/>
	<meta name="identifier-url" content="www.parions-potes.fr"/>
	
        <link rel="shortcut icon" href="/img/logos/logo_site.ico"/>';

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
                        <h1><a href="#image">Parions Potes</a></h1>  
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
                                    <a class="navbar-brand logosmall" href="#image" id="logosmall" data-action="scrollTo"></a>
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
                                        <!-- <li class="active"><a href="#image" data-action="scrollTo">Image</a></li> -->
                                        <li class=""><a href="#presentation" data-action="scrollTo">Présentation</a></li>
					<li class=""><a href="#prono" data-action="scrollTo">Mon prono</a></li>
					<li class=""><a href="#commentaires" data-action="scrollTo">Commentaires</a></li>
                                        <li class=""><a href="../">Retour à l\'accueil</a></li>
                                    </ul>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>';
//---------------------------------------------FIN HEADER------------------------------------------------------//';


//---------------------------------------------PRENSENTATION ETAPE------------------------------------------------------//	
    echo '
            <section id="presentation" data-speed="2" data-type="background">
                <div class="container" id="presentation-etape">
		    <div class="btn-group">
			<button type="button" class="btn btn-default">' . $calendrier['nom_complet'] . '</button>
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			    <span class="caret"></span>
			    <span class="sr-only">Toggle Dropdown</span>
			</button>
			<ul id="calendar-list" class="dropdown-menu" role="menu">';
    
    foreach($liste_calendrier as $key => $value){
	$url_cal = $value['id_cyclisme_calendrier'] . '-' . $value['nom_complet'];
	echo '		    <li><a href="' . clean_url($url_cal) . '">' . $value['nom_complet'] . '</a></li>';
    }

    
    echo '
			</ul>
		    </div>
		    <div class="sectionSide">
			<h2 class="section-heading">' . $calendrier['nom_complet'] . '</h2>
			<p class="section-highlight">Faites votre pari ! Faites glisser vos favoris dans la zone prévue.</p>
		    </div>
		</div>
	    </section>';
//---------------------------------------------PRENSENTATION ETAPE------------------------------------------------------//

    
//---------------------------------------------ZONE PRONO------------------------------------------------------//	
    echo '
            <section id="prono" data-speed="2" data-type="background">
                <div class="container" id="zone-prono">';		  

    echo '
		    <div class="col-xs-6">
			<input id="item-search" type="text" placeholder="Recherche" name="nom" class="form-control" style="margin-bottom:25px;"/>
			<ul id="sortable1" class="connectedSortable ui-sortable">';
    
    if($calendrier['profil_equipe']){
	foreach($equipes as $id => $equipe){
	    if($equipe['pos_prono'] === 0){
		echo '	    <li id="' . $equipe['id_cyclisme_equipe'] . '" name="prono" class="ui-state-default ui-sortable-handle">' . $equipe['nom_complet'] . '<span>' . $equipe['etoiles'] . '</span><img src="' . $equipe['photo'] . '" alt=""/>' . $equipe['moyenne'] . '</li>';
	    }
	}
    }
    else{
	foreach($cyclistes as $id => $cycliste){
	    if($cycliste['pos_prono'] == 0){
		echo '	    <li id="' . $cycliste['id_cyclisme_athlete'] . '" name="prono" class="ui-state-default ui-sortable-handle">' . $cycliste['prenom'] . ' ' . $cycliste['nom'] . '<span>' . $cycliste['etoiles'] . '</span><span>' . $cycliste['equipe_nom_court'] . '</span><img src="' . $cycliste['photo'] . '" alt=""/><img src="' . $cycliste['pays_drapeau_petit'] . '" alt=""/>' . $cycliste['moyenne'] . '</li>';
	    }
	}
    }
    
   		
			
    echo '
			</ul>
		    </div>
		    <div class="col-xs-6">
			<ul id="numero" class="hidden-num">
			    <li class="ui-state-highlight ui-sortable-handle">1</li>
			    <li class="ui-state-highlight ui-sortable-handle">2</li>
			    <li class="ui-state-default ui-sortable-handle">3</li>
			    <li class="ui-state-highlight ui-sortable-handle">4</li>
			    <li class="ui-state-default ui-sortable-handle">5</li>
			    <li class="ui-state-highlight ui-sortable-handle">6</li>
			    <li class="ui-state-highlight ui-sortable-handle">7</li>
			    <li class="ui-state-highlight ui-sortable-handle">8</li>
			    <li class="ui-state-highlight ui-sortable-handle">9</li>
			    <li class="ui-state-highlight ui-sortable-handle">10</li>
			</ul>
			<ul id="sortable2" class="connectedSortable ui-sortable" data-text="jjj">';
    
    for($i=1;$i<11;$i++){
	if($calendrier['profil_equipe']){
	    $key = array_search($i, array_column($equipes, 'pos_prono'));
	    if($key !== false){
		echo '	    <li id="' . $equipes[$key]['id_cyclisme_equipe'] . '" name="prono" class="ui-state-default ui-sortable-handle">' . $equipes[$key]['nom_complet'] . '<span>' . $equipes[$key]['etoiles'] . '</span><img src="' . $equipes[$key]['photo'] . '" alt=""/></li>';
	    }
	}
	else{
	    $key = array_search($i, array_column($cyclistes, 'pos_prono'));
	    if($key !== false){
		echo '	    <li id="' . $cyclistes[$key]['id_cyclisme_athlete'] . '" name="prono" class="ui-state-default ui-sortable-handle">' . $cyclistes[$key]['prenom'] . ' ' . $cyclistes[$key]['nom'] . '<span>' . $cyclistes[$key]['etoiles'] . '</span><span>' . $cyclistes[$key]['equipe_nom_court'] . '</span><img src="' . $cyclistes[$key]['photo'] . '" alt=""/><img src="' . $cyclistes[$key]['pays_drapeau_petit'] . '" alt=""/>' . $cyclistes[$key]['moyenne'] . '</li>';
	    }
	}
		
    }
    
    echo '
			</ul>
		    </div>
		</div>
	    </section>';
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
			<form id="post-form-jeu" role="form" class="row contact-form" action="/lib/form/post_commentaire_jeu.php" method="POST">
			

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
		    $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
		    
		    Init_Forms();
		    Init_Forms_Cyclisme();
		    Init_Zone_Paris();
		    getAllComs(0,' . $ID_JEU . ',' . $ID_CAL . ',0);

		    $(window).resize(function() {		
			$(\'body\').scrollspy("refresh");
		    });
		    //$.scrollTo( 0 );
		    $(\'body\').scrollspy({ target: \'#navbar-main\',offset:250 })
		    $(\'.nav-tabs\').tab();
		    $(\'a[data-action="scrollTo"]\').click(function(e) {
			    e.preventDefault();
			    scrollX = $(\'.header\').height();
			    $(\'.menu\').toggleClass(\'active\');
			    if(this.hash == "#image") {
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

	       $(\'section[data-type="background"]\').each(function(){
		     // declare the variable to affect the defined data-type
		     var $scroll = $(this);

		      $(window).scroll(function() {
			    // HTML5 proves useful for helping with creating JS functions!
			    // also, negative value because we\'re scrolling upwards                             
			    var yPos = -($window.scrollTop() / $scroll.data(\'speed\')) + 100; 

			    // background position
			    var coords = \'50% \'+ yPos + \'px\';

			    // move the background
			    $scroll.css({ backgroundPosition: coords });    
		      }); // end window scroll
	       });  // end section function

	});
	    var cbpAnimatedHeader = (function() {

	var docElem = document.documentElement,
	    header = document.querySelector( \'header\' ),
	    didScroll = false,
	    changeHeaderOn = 98;


	function init() {
	    window.addEventListener( \'scroll\', function( event ) {
		if( !didScroll ) {
		    didScroll = true;
		    setTimeout( scrollPage, 250 );
		}
	    }, false );
	}
	    function stickyMenu(element,nextelement) {

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