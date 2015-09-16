<?php
    $ID_JEU = 3;
    $js = '/jeux/rugby/2015/coupe-du-monde/js/wc2015.js';
    $css = '/jeux/rugby/2015/coupe-du-monde/css/wc2015.css';
    $titre = 'Parions Potes - Coupe du Monde de Rugby 2015';
    $competition = 'Coupe du Monde de Rugby 2015';
    $sous_titre = 'du 18 septembre au 31 octobre 2015';
    $logo = '/img/logos/logo_share.jpg';
    $description = 'Pronostics gratuits sur la Coupe du Monde de Rugby 2015.';
    $keywords = 'pronostics paris gratuits sport rugby coupe du monde 2015';
    
    //--------------------------------------FONCTIONS--------------------------------------//
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_breves.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/auto_login.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/clean_url.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/get_classements.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_joueurs.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_pays.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_articles.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_prono.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_equipe.php';
    //-------------------------------------------------------------------------------------//
    
    $bcr = array(
	'Football'      => 'bcr-foot',
	'Ski alpin'     => 'bcr-ski',
	'Rugby'         => 'bcr-rugby',
	'Biathlon'      => 'bcr-biathlon',
	'Cyclisme'      => 'bcr-cyclisme'
    );
    
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
        update_derniere_visite($joueur);
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    //------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $id_cal = get_id_calendrier_actuel($ID_JEU);
    $jeu = get_jeu_id($ID_JEU);
        
    $arr_breves = get_breves_jeu($ID_JEU);
    $nb_breves = sizeof($arr_breves);
    
    $arr_articles = get_articles_jeu($ID_JEU);
    $nb_articles = sizeof($arr_articles);
    
    $arr_calendrier = get_calendrier_jeu($ID_JEU);
    $nb_calendrier = sizeof($arr_calendrier);
    
    $pays = get_pays_tous();
    $equipes = get_equipes_inscrites($ID_JEU);
    
    $classements = get_classements($jeu['url'] . '/classements');
    $nb_classements = sizeof($classements);
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//

    
    
    
    //---------------------------------JEU COMMENCE ?---------------------------//

    if($jeu['commence'] == "0" && $admin == false){
	header('Location: /redirect/erreur404.html');
	return;
    }
    //---------------------------------JEU COMMENCE ?---------------------------//
    
    

    
    

    
    
    // DOCTYPE, META
    echo '<!DOCTYPE html>
        <html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="generator" content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
        <meta http-equiv="Content-Type" content="text/html;utf-8" />
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="author" content="" />

	<meta name="content-language" content="fr" />
	<meta name="description" content="' . $description . '" />
	<meta name="keywords" content="' . $keywords . '" />
	<meta name="subject" content="" />
	<meta name="copyright" content="Parions Potes 2015" />
	<meta name="identifier-url" content="http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '" />
	<meta property="og:title" content="' . $titre . '" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '" />
	<meta property="og:image" content="http://www.parions-potes.fr' . $logo . '" />
	<meta property="og:description" content="' . $description . '" />
	
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
                        <h1><a href="/">Parions Potes</a></h1>  
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
                                    <a class="navbar-brand logosmall" href="#news" id="logosmall" data-action="scrollTo"></a>
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
                                        <li class="active"><a href="#news" data-action="scrollTo">Actualité</a></li>';
    
    if($nb_classements > 0){
	echo '				<li class=""><a href="#classements" data-action="scrollTo">Classements</a></li>';
    }					
						
    echo '
					<li class=""><a href="#resultats" data-action="scrollTo">Pronostics</a></li>
					<li class="home"><a href="/" class="glyphicon glyphicon-home" aria-label="home"><span> Retour au site</span></a></li>
                                    </ul>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>';
//---------------------------------------------FIN HEADER------------------------------------------------------//';


    
    
    
    
    
    
    
    
    
    
    
 
//---------------------------------------------BREVES------------------------------------------------------//	
    echo '  <div class="game-header tdf2015 section">
				 <h1>' . $competition . '</h1>
				 <h2>' . $sous_titre . '</h2>
			</div>
	<div id="news" class="section" style="background-color: white;">
		<div class="container">
			<div class="col-md-8 col-md-offset-0 col-sm-8 col-sm-offset-0 col-xs-12 col-xs-offset-0">
			<div class="sectionSide" style="padding-bottom: 15px; color:black;text-align:center;">
			    <h1 class="section-heading">News</h1>
			</div>
			<div id="zone-news">';
    
    if($nb_breves > 0){
	for ($i=0;$i<$nb_breves;$i++){
	    echo '	    <p class="article-text bold">' . $arr_breves[$i]['datepub_fr'] . ' : ' . $arr_breves[$i]['titre'] . '</p>';
	    echo '	    <p class="article-text">' . $arr_breves[$i]['contenu'] . '</p>';
	}
    }
    echo '		</div>
		    </div>
		    <div class="col-md-3 col-md-offset-1 col-sm-4 hidden-xs">
			<div id="list-right">
			    <div class="sectionSide">
				<h2 class="section-heading">Sur le sujet</h2>	
			    </div>
			    <div class="list-group list-articles-right clearfix">';
    
    
    
    if($nb_articles > 0){
	for ($i=0;$i<$nb_articles;$i++){
	    $url = clean_url('/articles/' . $arr_articles[$i]['categorie'] . '/' . $arr_articles[$i]['souscategorie'] . '/' . $arr_articles[$i]['id_article'] . '-' . $arr_articles[$i]['titre']);
	    echo '		<a href="' . $url . '" class="list-articles-item-right list-group-item col-md-12">
				    <span class="badge ' . $bcr[$arr_articles[$i]['categorie']] . '">' . $arr_articles[$i]['categorie'] . '</span>
				    <img src="' . $arr_articles[$i]['photo_chemin_deg'] . '" alt="aie"/>
				    <h4 class="list-group-item-heading">' . $arr_articles[$i]['titre'] . '</h4>
				    <p class="list-group-item-text">Categorie</p>
				</a>';
	}
    }
    
    echo '
			    </div>
			</div>
		    </div>
		</div>
	    </div>';
		
//---------------------------------------------BREVES------------------------------------------------------//	    

    
    
    
    
    
    
    
    
    
    
    
    
    
//---------------------------------------------CLASSEMENTS------------------------------------------------------//	
    if($nb_classements > 0){
	echo '  <div class="section" id="classements" style="margin-bottom:120px;">
		    <div class="sectionSide">
			<h2 class="section-heading">Classements</h2>
		    </div>
		    <div id="tabs" class="tabs nav-center">
			<ul class="nav nav-tabs" role="tablist" id="tab-list">';

	for ($i=0;$i<$nb_classements;$i++){
	    if($i == 0){
		 echo '	    <li role="presentation" class="active">';
	    }
	    else{
		echo '	    <li role="presentation" class="">';
	    }
	    
	    echo '		<a href="#section-' . $i . '" class="" role="tab" data-toggle="tab">
				    <span>' . $classements[$i]['titre'] . '</span>
				</a>
			    </li>';
	}

	echo '		    <li id="lastTab" class="dropdown">
				<a class="btn dropdown-toggle" data-toggle="dropdown" >
				    Plus <span class="caret"></span>
				</a>
				<ul class="dropdown-menu" id="collapsed">

				</ul>
			    </li>
			</ul>
		    </div>
		    <div class="tab-content">';

	for ($i=0;$i<$nb_classements;$i++){
	    if($i == 0){
		 echo '	<div id="section-' . $i . '" role="tabpanel" class="tab-pane active">';
	    }
	    else{
		echo '	<div id="section-' . $i . '" role="tabpanel" class="tab-pane">';
	    }
	    
	    echo '	    <div class="table-box col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2">
				<!--<div class="sectionSide">
				    <p class="section-highlight">' . $classements[$i]['description'] . '</p>
				</div>-->
				<div class="classement-table">
				    <table class="table">
					<tbody>';   

	    // TITRES DE COLONNES
	    echo '			    <tr class="">';

	    for($j=0;$j<sizeof($classements[$i]['titre_colonnes']);$j++){
		echo '				<th class="col-md-' . $classements[$i]['largeur_colonnes'][$j] . '">' . $classements[$i]['titre_colonnes'][$j] . '</th>';
	    }

	    echo '			    </tr>';
	    
	    
	    // AUTRES LIGNES
	    for($j=0;$j<sizeof($classements[$i]['classement']);$j++){
		if($classements[$i]['classement'][$j][1] == $loginjoueur){
		    $class_surlign_joueur = 'goodbet';
		}
		else{
		    $class_surlign_joueur = '';
		}
		echo '			    <tr class="' . $class_surlign_joueur . '">';
		for($k=0;$k<sizeof($classements[$i]['classement'][$j]);$k++){
		    echo '			<td class="">' . $classements[$i]['classement'][$j][$k] . '</th>';
		}
		echo '			    </tr>';
	    }

	    echo '			</tbody>
				    </table>
				</div>
			    </div>
			</div>';
	}
	echo '	    </div>
		</div>';
    }
    
//---------------------------------------------CLASSEMENTS------------------------------------------------------//	  

  
    
    
    

    
//---------------------------------------------CALENDRIER------------------------------------------------------//
    if($nb_calendrier > 0){
	echo '	<div class="section clearfix" id="resultats" style="min-height: 214px;">
		    <div class="sectionSide" style="margin-bottom:50px;">
			<h2 class="section-heading">Calendrier</h2>
		    </div>
		    <div class="left-content col-md-3 col-sm-3">
			<nav id="calendar" class="navbar navbar-default" role="navigation">
			    <div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-calendar">
				    <span class="sr-only">Toggle navigation</span>';

	for ($i=0;$i<4;$i++){
	    echo '		    <span class="icon-bar"></span>';
	}
	
	echo '			</button>
			    </div>

			    <div class="collapse navbar-collapse navbar-calendar">
				<ul id="list-cal" class="nav navbar-nav">';
	
	foreach($arr_calendrier as $key => $calendrier){
	    $id_equipe1 = $calendrier['id_equipe1'];
	    $nom_equipe1 = $equipes[$id_equipe1]['nom'];
	    $id_pays1 = $equipes[$id_equipe1]['id_pays'];
	    $drapeau_equipe1 = $pays[$id_pays1]['drapeau_icone'];
	    
	    $id_equipe2 = $calendrier['id_equipe2'];
	    $nom_equipe2 = $equipes[$id_equipe2]['nom'];
	    $id_pays2 = $equipes[$id_equipe2]['id_pays'];
	    $drapeau_equipe2 = $pays[$id_pays2]['drapeau_icone'];
	    
	    $id = $calendrier['id'];
	    if($id == $id_cal){
		$tmp_class = 'active';
	    }
	    else{
		$tmp_class = '';
	    }
	    
	    if($calendrier['commence'] == "0"){
		$tmp_date = $calendrier['date_debut_fr_tcourt'] . ' à ' . $calendrier['heure_debut_fr'];
		if($calendrier['disponible'] == "1"){
		    $tmp_ico = 'glyphicon-play';
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
		}
	    }
	    	    
	    echo '		    <li class="' . $tmp_class . '">
					<a class="clearfix" value="' . $calendrier['id'] . '" data-action="goTo">
					    <img class="item-flag col-md-2 col-sm-2 hidden-xs" src="' . $pays[$id_pays1]['drapeau_icone'] . '"/>
					    <span class="title col-md-8 col-sm-8 col-xs-12">' . $nom_equipe1 . ' - ' . $nom_equipe2 . '</span>
					    <img class="item-flag col-md-2 col-sm-2 hidden-xs" src="' . $pays[$id_pays2]['drapeau_icone'] . '"/>
					    <span class="date col-md-6">' . $tmp_date . '</span>
					    <span class="glyphicon ' . $tmp_ico . ' col-md-6"></span>
					</a>
				    </li>';
	}
					    
	echo '
				</ul>
			    </div>
			</nav>
		    </div>
		    





	<!-- CONTENU -->
     	<div class="right-content col-md-9 col-sm-9 col-xs-12">	
		<div class="scroll-content">
			<div id="cal-container">	
			
			</div>
		    




	<!-- COMMENTAIRES -->
		    <div class="comment-panel">
			<div class="" id="commentaires">';
	
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
        echo '		    <div class="sectionSide">
				<h2 class="section-heading">Commentaires</h2>
				<p class="section-highlight">Connectez-vous pour participer au débat !</p>
			    </div>';
    }
	
    echo '		    <div class="row com-container">
	
			    </div>
			</div>
			</div>
			</div>
		    </div>
		</div>'; 
    }
//---------------------------------------------CALENDRIER------------------------------------------------------//


    
    
    
    
    
    
    
    
    
    
    
    
    
    

//---------------------------------------------FOOTER------------------------------------------------------//	
    echo '
        <footer>
            <div class="container">
			<div class="copyright col-md-6 col-sm-6 col-xs-12">
				<p>© 2015 Parions Potes</p>
			</div>
			<div class="rules col-md-6 col-sm-6 col-xs-12">
				<a href="reglement.htm" target="_blank" class="btn btn-primary">
					<span>
					    Règlement
					</span>
				</a>
			</div>
        </div>
	</footer>
	
	<script src="' . $js . '"></script>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
	<script src="/js/classie.js"></script>
	<script src="/js/jquery.scrollTo.min.js"></script>
	<script src="/js/jqBootstrapValidation.js"></script>	
	<script src="/js/script.js"></script>
        <script src="/bower_components/velocity/velocity.js"></script>
        <script src="/bower_components/moment/min/moment-with-locales.min.js"></script>
        <script src="/bower_components/angular/angular.js"></script>
	<script src="/js/social-buttons.js"></script>
	
	<script>
	    var time = new Date().getTime();
	    $(document.body).bind("mousemove keypress", function(e) {
		time = new Date().getTime();
	    });

	    function refresh() {
		if(new Date().getTime() - time >= 600000)
		    window.location.reload(true);
		else
		    setTimeout(refresh, 10000);
	    }

	    setTimeout(refresh, 10000);
	</script>

	
        <script>
	    
            jQuery(document).ready(function ($) {
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
				
		getAllComs(0,' . $ID_JEU . ',' . $id_cal . ',0);
		render_calendrier(' . $id_cal . ');
		    
		Init_Forms();
		Init_Forms_Rugby();
		
		$(window).resize(function() {
			//pageSection();
			 $(\'body\').scrollspy("refresh");
			if($(window).width() > 768){
				$(".right-content").height($(".left-content").height());
			} else {
				$(".right-content").height("inherits");
			}
		});
         
		if($(window).width() > 768){
			$(".right-content").height($(".left-content").height());
		} else {
			$(".right-content").height("inherits");
		}
                
        //$(\'.nav-tabs\').tab();
        $(\'a[data-action="scrollTo"]\').click(function(e)
                {
                        e.preventDefault();
                        scrollX = $(\'.header\').height();
                       // $(\'.menu\').toggleClass(\'active\');
                        if(this.hash == "#myCarousel") {
                                        $(\'body\').scrollTo(0,500,null);
                                                   
                                        $(".section").removeClass("inactiveSection");
                        } else {
                                        $(\'body\').scrollTo(this.hash, 500, {offset: -scrollX});
                        }
                $(\'.navbar-collapse\').removeClass(\'in\');
                });
				
		 $(\'a[data-action="goTo"]\').click(function(e)
		{
				e.preventDefault();
				//$(\'.menu\').toggleClass(\'active\');
				
				$(\'.navbar-collapse\').removeClass(\'in\');
		});
                
                $(\'[data-toggle=dropdown]\').dropdown();
        
                // cache the window object
            $window = $(window);
                
             autocollapse(); // when document first loads

			$(window).on(\'resize\', autocollapse); // when window is resized  
 
            
                
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