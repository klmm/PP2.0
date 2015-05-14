<?php

//--------------------------------------FONCTIONS--------------------------------------//
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/articles/get_articles.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/jeux/get_jeux.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/images/get_images.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/auto_login.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/update_joueurs.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/commentaires/get_commentaires.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/likes/get_likes.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/clean_url.php';
//-------------------------------------------------------------------------------------//

    
//--------------------------------------CONSTANTES--------------------------------------//
$bcr = array(
    'Football'      => 'bcr-foot',
    'Ski alpin'     => 'bcr-ski',
    'Rugby'         => 'bcr-rugby',
    'Biathlon'      => 'bcr-biathlon',
    'Cyclisme'      => 'bcr-cyclisme'
);

$cr = array(
    'Football'      => 'cr-foot',
    'Ski alpin'     => 'cr-ski',
    'Rugby'         => 'cr-rugby',
    'Biathlon'      => 'cr-biathlon',
    'Cyclisme'      => 'cr-cyclisme'
);

//--------------------------------------------------------------------------------------//


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
        update_derniere_visite();
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
//------------------------------------------------------------------------------------------------//








//--------------------------------------INFOS SUR L'ARTICLE--------------------------------------//
    $id_article = $_GET['id'];

    if (!is_numeric($id_article)){
        echo 'article non trouvé';
        return;
    }

    // Infos sur l'article en lui-même
    $infos_article = get_article_infos($id_article);
    if ($infos_article['id_article'] == 0){
        echo 'article non trouvé';
        return;
    }
    $titre = $infos_article['titre'];
    $auteur = $infos_article['auteur'];
    $photo_chemin = $infos_article['photo_chemin'];
    $photo_chemin_deg = $infos_article['photo_chemin_deg'];
    $photo_credits = $infos_article['photo_credits'];
    $photo_titre = $infos_article['photo_titre'];
    $categorie = $infos_article['categorie'];
    $sous_categorie = $infos_article['souscategorie'];
    $date_pub = $infos_article['dateheurepub'];

    // Articles de la même rubrique
    $articles_categorie = get_articles_categorie($categorie,5);
    $nb_articles_categorie = sizeof($articles_categorie);

    // Articles à la une
    $articles_recents = get_articles_unes();
    $nb_articles_recents = sizeof($articles_recents);

    // Commentaires postés sur l'article
    $commentaires = get_commentaires_article($id_article);
    $nb_comm = sizeof($commentaires);

    // Commentaires likés/dislikés par le joueur
    if ($bConnected){
        $likes = get_likes($id_article, $loginjoueur);
    }
    else{
	$likes =  null;
    }
//------------------------------------------------------------------------------------------------//








	
//--------------------------------------HEADER--------------------------------------//
	
    // DOCTYPE, META
    echo '<!DOCTYPE html>
        <html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="generator" content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />

        <link rel="shortcut icon" href="/img/CarrNoir.png">';

    // TITLE
    echo '
        <title>' . $titre . '</title>';

    // BOOTSTRAP
    echo '
        <link href="/css/bootstrap.min.css" rel="stylesheet">		
        <link href="/css/font-awesome.min.css" rel="stylesheet">

        <link href="/css/carousel.css" rel="stylesheet">
        <link href="/css/style.css" rel="stylesheet">
        <link href="/css/pagearticle.css" rel="stylesheet">
	<link href="/css/social-buttons.css" rel="stylesheet">
        <script src="/js/modernizr.custom.js"></script></head>';

    // LIENS TWITTER FACEBOOK
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
                                        <li class=""><a href="#article" data-action="scrollTo">Article</a></li>
                                        <li class=""><a href="#commentaires" data-action="scrollTo">Commentaires (' . $nb_comm . ')</a></li>
                                        <li class=""><a href="/">Retour au site</a></li>
                                    </ul>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>';
//---------------------------------------------FIN HEADER------------------------------------------------------//
		


		
		
		
		











//---------------------------------------------IMAGE------------------------------------------------------//
    echo '
            <section id="image" data-speed="2" data-type="background" style="background-image:url(' . $photo_chemin . ')">
                <div class="container">

                </div>
            </section>';
//---------------------------------------------FIN IMAGE------------------------------------------------------//



//---------------------------------------------ARTICLE------------------------------------------------------//
    echo '
            <section id="article" data-speed="4" data-type="background">
                <div id="reuters" class="right">
                    <p>' . $photo_credits . '</p>
                </div>
                <div class="container">
                    <div class="col-md-8 col-sm-8">
                        <div class="sectionSide" style="padding-bottom: 15px; color:black;text-align:center;">
                            <h1 class="section-heading">' . $titre . '</h1>
                        </div>
                        <p class="article-text">';

    include $_SERVER['DOCUMENT_ROOT'] . '/articles/' . $id_article . '.htm';

    $url = '/articles/' . $categorie . '/' . $sous_categorie . '/' . $id_article . '-' . $titre;
    $url_propre = clean_url($url);

    echo '          
                        </p>
			<p class="col-md-6 article-text author">' . $auteur . '</p>
			<p class="col-md-6 article-text date">' . date_to_duration($date_pub) . '</p>
			<div class="social-sharing is-large" data-permalink="http://parions-potes.fr/' . $url_propre . '">
			    <a target="_blank" href="http://www.facebook.com/sharer.php?u=http://parions-potes.fr' . $url_propre . '" class="share-facebook">
				<span class="icon icon-facebook"></span>
				<span class="share-title">Share</span>
				<span class="share-count">0</span>
			    </a>

			    <a target="_blank" href="http://twitter.com/share?url=http://parions-potes.fr' . $url_propre . '" class="share-twitter">
				<span class="icon icon-twitter"></span>
				<span class="share-title">Tweet</span>
				<span class="share-count">0</span>
			    </a>
			</div>
                    </div>
		    
		    <div class="col-md-3 col-md-offset-1  col-sm-4 hidden-xs">
			<div id="list-right">';
				
    if ($nb_articles_categorie > 1){
	
    
        echo '
                            <div class="sectionSide">
				<h2 class="section-heading">' . $articles_categorie[0]['categorie'] . '</h2>	
                            </div>';
			
        for ($i = 0; $i < $nb_articles_categorie; $i++){
	    $url = '/articles/' . $articles_categorie[0]['categorie'] . '/' . $articles_categorie[$i]['souscategorie'] . '/' . $articles_categorie[$i]['id_article'] . '-' . $articles_categorie[$i]['titre'];
	    $url_propre = clean_url($url);
	
            if ($articles_categorie[$i]['id_article'] != $id_article){
		echo '
			    <div class="list-group list-articles-right clearfix">
				<a href="' . $url_propre . '" class="list-articles-item-right list-group-item col-md-12">
				    <span class="badge ' . $bcr[$categorie] . '">' . $categorie . '</span>
				    <img src="' . $articles_categorie[$i]['photo_chemin_deg'] . '" alt="' . $articles_categorie[$i]['photo_chemin_deg'] . '"/>
				    <h4 class="list-group-item-heading">' . $articles_categorie[$i]['titre'] . '</h4>
				    <p class="list-group-item-text">' . $articles_categorie[$i]['titre'] . '</p>
				</a>
			    </div>';
	    }
        }
    }
		
    if ($nb_articles_recents > 0){
	
        echo '
                            <div class="sectionSide">
                                <h2 class="section-heading">A LA UNE</h2>	
                            </div>';

        for ($j = 0; $j < $nb_articles_recents; $j++){
	    $url = '/articles/' . $articles_recents[$j]['categorie'] . '/' . $articles_recents[$j]['souscategorie'] . '/' . $articles_recents[$j]['id_article'] . '-' . $articles_recents[$j]['titre'];
	    $url_propre = clean_url($url);
	    
	    $categorie_une = $articles_recents[$j]['categorie'];
	    echo '
                            <div class="list-group list-articles-right clearfix">
                                <a href="' . $url_propre . '" class="list-articles-item-right list-group-item col-md-12">
                                    <span class="badge ' . $bcr[$categorie_une] . '">' . $categorie_une . '</span>
                                    <img src="' . $articles_recents[$j]['photo_chemin_deg'] . '" alt="' . $articles_recents[$j]['photo_chemin_deg'] . '"/>
                                    <h4 class="list-group-item-heading">' . $articles_recents[$j]['titre'] . '</h4>
                                    <p class="list-group-item-text">' . $articles_recents[$j]['titre'] . '</p>
                                </a>
                            </div>';
        }
    }

    echo '
                        </div>
                    </div>
                </div>
            </section>';
//---------------------------------------------FIN ARTICLE------------------------------------------------------//
	
	
	
	
	
	
	
	
	
	
	
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
                        <form id="post-form" role="form" class="row contact-form" action="/lib/form/post_commentaire.php" method="POST">
                            <div class="col-md-10 col-md-offset-1">
                                <input name="id_article" id="id_article" type="text" class="hidden" required="" value="' . $id_article . '"/>
                                <button type="submit" class="btn btn-primary pull-right" style="padding:10px;margin-bottom:10px;width:200px;">
                                    <span>Poster</span>
                                </button>

                                <textarea id="contenu" class="form-control" rows="5" name="contenu" placeholder="Votre message"></textarea>					
                            </div>
                        </form>
                    </div>';
    }
    else{
        echo '	    <div class="sectionSide">
			<h2 class="section-heading">Commentaires</h2>
			<p class="section-highlight">Connectez-vous pour participer au débat !</p>
			<input name="id_article" id="id_article" type="text" class="hidden" required="" value="' . $id_article . '"/>
		    </div>';
    }
	
	echo '	    <div class="row com-container">';
    /*
	for ($i = 0; $i < $nb_comm; $i++){
	$id_comm = $commentaires[$i]['id_commentaire'];
        echo '		
                        <div class="like-form col-md-10 col-md-offset-1">
                            <p id="id-com" value="' . $id_comm . '" class="hidden">' . $id_comm . '</p>
			    <p id="id-art" value="' . $id_article . '" class="hidden">' . $id_article . '</p>
                            <p class="user pull-left">' . $commentaires[$i]['joueur'] . '</p>
                            <p class="time pull-right">' . $commentaires[$i]['dateheurepub_conv'] . '</p>
                            <p class="comment">' . $commentaires[$i]['contenu'] . '</p>';
	
	if ($likes[$id_comm] == 1){
	    echo '	    <button class="btn-dislike btn btn-danger pull-right" style="margin-left:10px;" disabled>';
	}
	else{
	    echo '	    <button class="btn-dislike btn btn-danger pull-right" style="margin-left:10px;">';
	}
	
	echo '			<span class="glyphicon glyphicon-thumbs-down" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>
                                <span class="count">' . $commentaires[$i]['nbdislikes'] . '</span>
                            </button>';
	
	if ($likes[$id_comm] == 2){
	    echo '	    <button class="btn-like btn btn-success pull-right" style="margin-left:10px;" disabled>';
	}
	else{
	    echo '	    <button class="btn-like btn btn-success pull-right" style="margin-left:10px;">';
	}

        echo'
				<span class="glyphicon glyphicon-thumbs-up" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>
                                <span class="count">' . $commentaires[$i]['nblikes'] . '</span>
                            </button>
                        </div>';
                   
    }
	
	*/
    echo '
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
        <script src="/bower_components/velocity/velocity.js"></script>
        <script src="/bower_components/moment/min/moment-with-locales.min.js"></script>
        <script src="/bower_components/angular/angular.js"></script>
        <!-- <script src="/js/toucheffects.js"></script> -->
	<script src="/js/social-buttons.js"></script>
	
	
        <script>
            jQuery(document).ready(function ($) {
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
		
		Init_Forms();
		getAllComs($("#id_article").val());
		
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
