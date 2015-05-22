<?php
    $ID_JEU = 4;
    
    //--------------------------------------FONCTIONS--------------------------------------//
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_breves.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/auto_login.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_joueurs.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_articles.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_prono.php';
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
        update_derniere_visite();
        $bConnected = true;
    }
    else{
        $bConnected = false;
    }
    //------------------------------------------------------------------------------------------------//
    
    
    
    
    
    
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//
    $id_cal = get_id_calendrier_actuel($ID_JEU);
    echo 'id_cal : ' . $id_cal;
    $jeu = get_jeu_id($ID_JEU);
    
    /*
    $arr['date_debut'];
    $arr['date_fin'];
    $arr['sport'];
    $arr['competition'];
    $arr['url'];
    $arr['image'];
    $arr['description'];*/
    
    $arr_breves = get_breves_jeu($ID_JEU);
    $nb_breves = sizeof($arr_breves);
    
    $arr_articles = get_articles_jeu($ID_JEU);
    $nb_articles = sizeof($arr_articles);
    
    $arr_calendrier = get_calendrier_jeu($ID_JEU);
    $nb_calendier = sizeof($arr_calendrier);
    //--------------------------------------RECUPERATIONS DES INFOS--------------------------------------//

    
    
    
    //---------------------------------JEU COMMENCE ?---------------------------//
    $now = time();
    $dh_debut = strtotime($jeu['date_debut']);

    if($now < $dh_debut && $admin != true){
	header('Location: /redirect/404.php');
	return;
    }
    //---------------------------------JEU COMMENCE ?---------------------------//
    
    
    
    
    
    
    // -------------------------------- TRI DU CALENDRIER ------------------------//
    foreach($arr_calendrier as $id_c => $cal){
	if($cal['commence'] == "1"){
	    $arr_calendrier[$id_c]['date_debut'] = $cal['date_fin'];
	    $arr_calendrier[$id_c]['date_debut_fr'] = $cal['date_fin_fr'];
	}
    }
    
    function compare_date_debut($a, $b)
    {
      return strnatcmp($a['date_debut'], $b['date_debut']);
    }
    usort($arr_calendrier, 'compare_date_debut');
    // -------------------------------- TRI DU CALENDRIER ------------------------//
    
    
    
    
    
    
    
    
    
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

	<meta name="content-language" content="fr"/>
	<meta name="description" content="Pronostics sur le Tour de France 2015."/>
	<meta name="keywords" content="pronostics paris gratuits sport cyclisme tdf tour de france 2015"/>
	<meta name="subject" content=""/>
	<meta name="copyright" content="Parions Potes 2015"/>
	<meta name="identifier-url" content="www.parions-potes.fr"/>

        <link rel="shortcut icon" href="/img/logos/logo_site.ico"/>';

    // TITLE
    echo '
        <title>Parions Potes : Tour de France 2015</title>';

    // BOOTSTRAP
    echo '
        <link href="/css/bootstrap.min.css" rel="stylesheet">		
        <link href="/css/font-awesome.min.css" rel="stylesheet">

        <link href="/css/carousel.css" rel="stylesheet">
        <link href="/css/style.css" rel="stylesheet">
        <link href="/css/pagearticle.css" rel="stylesheet">
	<link href="/css/social-buttons.css" rel="stylesheet">
        <script src="/js/modernizr.custom.js"></script></head>';
    
    echo '
        <body data-spy="scroll" data-target="#navbar-main" data-offset="100">';
    
    
    
    
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
                            <div class="col-md-10 col-md-offset-1">
				<input name="id_jeu" id="id_jeu" type="text" class="hidden" required="" value="' . $id_jeu . '"/>
                                <input name="id_cal" id="id_cal" type="text" class="hidden" required="" value="' . $id_cal . '"/>
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
			<input name="id_jeu" id="id_jeu" type="text" class="hidden" required="" value="' . $id_jeu . '"/>
			<input name="id_cal" id="id_cal" type="text" class="hidden" required="" value="' . $id_cal . '"/>
		    </div>';
    }
	
    echo '	    <div class="row com-container">';
	
    echo '
		    </div>
		</div>
            </section>
	</body>';
//---------------------------------------------FIN COMMENTAIRES------------------------------------------------------//	





    echo '
        <footer>
            <div class="container ">
                <p>© 2015 Parions Potes </p>
            </div>
	</footer>
	
	<script src="/jeux/cyclisme/2015/tour-de-france/js/tdf2015.js"></script>
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
		
		Init_Forms_Cyclisme();
		getAllComsJeu(' . $ID_JEU . ',' . $id_cal . ');
		
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
 
 
 
 </html>';