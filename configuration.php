<?php

//--------------------------------------FONCTIONS--------------------------------------//
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_articles.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/auto_login.php';   
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_joueurs.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_commentaires.php'; 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_likes.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/clean_url.php';
//-------------------------------------------------------------------------------------//


//--------------------------------------VARIABLES DE SESSION--------------------------------------//
    session_start();
    $loginjoueur = $_SESSION['LoginJoueur'];

    if($loginjoueur == ""){
        auto_login();
        $loginjoueur = $_SESSION['LoginJoueur'];
    }

    if($loginjoueur != ""){
        update_derniere_visite($loginjoueur);
    }
    else{
        header('Location: /redirect/erreur404.html');
        return;
    }
//------------------------------------------------------------------------------------------------//










	
//--------------------------------------HEADER--------------------------------------//
	
    // DOCTYPE, META
    echo '
<!DOCTYPE html>
    <html lang="en">
	<head>
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta name="generator" content="HTML Tidy for HTML5 (experimental) for Windows https://github.com/w3c/tidy-html5/tree/c63cc39" />
	    <meta http-equiv="Content-Type" content="text/html;utf-8" />
	    <meta charset="utf-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	    <meta name="author" content="" />
	    <meta name="content-language" content="fr"/>
	    <meta name="keywords" content="pronostics paris gratuits"/>
	    <meta name="subject" content=""/>
	    <meta name="copyright" content="Parions Potes 2015"/>
	    <meta name="identifier-url" content="http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '"/>

	    <link rel="shortcut icon" href="/img/logos/icone.ico"/>';

    // TITLE
    echo '
	    <title>Mon compte</title>';

    // BOOTSTRAP
    echo '
	    <link href="/css/bootstrap.min.css" rel="stylesheet">		
	    <link href="/css/font-awesome.min.css" rel="stylesheet">
	    <link href="/css/bootstrap-toggle.min.css" rel="stylesheet">

	    <link href="/css/carousel.css" rel="stylesheet">
	    <link href="/css/style.css" rel="stylesheet">
	    <link href="/css/pagearticle.css" rel="stylesheet">
	    <link href="/css/social-buttons.css" rel="stylesheet">
	</head>';

    // LIENS TWITTER FACEBOOK
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
                                    <a class="navbar-brand logosmall" href="#image" id="logosmall" data-action="scrollTo"></a>
                                    <ul class="nav nav-pills">';

    // CONNEXION - ESPACE JOUEUR
    echo '				<li class="dropdown" id="menuUser">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:rgb(115, 50, 130);background:transparent;">
                                                <span class="glyphicon glyphicon-user"></span><span id="bUsername">  ' . $loginjoueur . ' </span>
                                            </a>
                                        </li>';

    // MENU
    echo '                          </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>';
//---------------------------------------------FIN HEADER------------------------------------------------------//
		


    echo '
	    <div class="section-config section" id="config" style="min-height: 214px;">
		<div class="container marketing">
		    <div id="contact-container" class="row">
			<div class="sectionSide">
			    <h2 class="section-heading">Paramètres du compte</h2>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Gérez vos informations et vos inscriptions</p>
			    </div>
			    <div class="panelIcon">
				<div id="avatar"  class="avatar" >
				    <div class="overlay">
					<span class="lblIcon">Icone</span>
					<span class="glyphicon glyphicon-plus overlayImage" aria-hidden="true"></span>
				    </div>
				</div>

				<form id="formAvatar" role="form" class="" action="/lib/form/??????????.php" method="post">
				    <input id="panelUpload" type="file" class="file upload">
				</form>
			    </div>

			    <p class="inter-section-highlight">Vos informations de connexion</p>
			    <p class="private-info" style="display:block;">' . $loginjoueur . '</p>

			    <button type="submit" class="btn btn-primary">
				Modifier mon mot de passe
			    </button>

			    <p class="inter-section-highlight">Vos informations personnelles</p>
			    <form id="playerInfo-form" role="form" class="row contact-form" action="/lib/form/??????????.php" method="post">

				<input type="text" placeholder="Nom" name="nom" class="form-control" required="" data-validation-required-message="Nom obligatoire" />
				<input type="text" placeholder="Prénom" name="prenom" class="form-control" required="" data-validation-required-message="Prénom obligatoire" />
				<input type="email" placeholder="Email (pour vous répondre)" name="mail" class="form-control" required="" data-validation-required-message="Mail obligatoire" />
				<textarea class="form-control" rows="3" name="punchline" placeholder="Ecrivez votre slogan !"></textarea> 
				<button type="submit" class="btn btn-primary" style="padding:0;margin-top:20px;width:200px;">
				    <span style="display:block;padding: 0 8px 0 8px;;height:38px;line-height:38px;margin-right: 60px;float: right;">
					Enregistrer
				    </span>
				</button>
			    </form>

			    <div id="logo-pp" class="hidden-sm">
				<img src="/img/logos/photo_couv.png"/>
			    </div>
			</div>

			<div class="col-md-6 col-sm-12 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Gérez votre niveau de dépendance aux jeux !</p>
			    </div>
			    <div id="mail-games" class="toggle-div">
				<div class="row">
				    <div class="col-md-6 col-sm-6 col-xs-6">
					<p class="">Réception des mails de nouveautés ou de rappel</p>
				    </div>
				    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
					<input id="toggle-games" type="checkbox" checked data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="danger" data-on="Tout selectionner" data-off="Tout désélectionner">
				    </div>
				</div>

				<form id="mail-games-form" role="form" class="row contact-form" action="/lib/form/??????????.php" method="post">
				    <ul class="toggle-list">
					<li class="toggle-box clearfix">
					    <div class="col-md-6 col-sm-6 col-xs-6 toggle-item-name">
						<p>TDF 2015</p>
					    </div>
					    <div class="col-md-6 col-sm-6 col-xs-6 toggle-item">
						<input id="idjeu" type="checkbox" checked data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
					    </div>
					</li>
				    </ul>
				    <button type="submit" class="btn btn-primary" style="padding:0;margin-top:20px;width:200px;">
					<span style="display:block;padding: 0 8px 0 8px;;height:38px;line-height:38px;margin-right: 60px;float: right;">
					    Enregistrer
					</span>
				    </button>
				</form>	
			    </div>

			    <div id="race-inscriptions" class="toggle-div">
				<div class="row">
				    <div class="col-md-6 col-sm-6 col-xs-6">
					<p class="">Epreuves visibles</p>
				    </div>
				    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
					<input id="toggle-races" type="checkbox" checked data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="danger" data-off="Ne rien jouer" data-on="Tout jouer">
				    </div>
				</div>
				<form id="race-inscriptions-form" role="form" class="row contact-form" action="/lib/form/??????????.php" method="post">
				    <ul class="toggle-list">
					<li class="toggle-box clearfix">
					    <div class="col-md-6 col-sm-6 col-xs-6 toggle-item-name">
						<p>SoeldenF</p>
					    </div>
					    <div class="col-md-6 col-sm-6 col-xs-6 toggle-item">
						<input id="idreconnaissable" type="checkbox" checked data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
					    </div>
					</li>
				    </ul>
				    <button type="submit" class="btn btn-primary" style="padding:0;margin-top:20px;width:200px;">
					<span style="display:block;padding: 0 8px 0 8px;;height:38px;line-height:38px;margin-right: 60px;float: right;">
					    Enregistrer
					</span>
				    </button>
				</form>
			    </div>
			</div>
		    </div>
		</div>
	    </div>';		
		




    echo '
	    <footer>
		<div class="container ">
		    <p>© 2015 Parions Potes </p>
		</div>
	    </footer>

	    <script src="/js/jquery.min.js"></script>
	    <script src="/js/bootstrap.min.js"></script>
	    <script src="/js/jqBootstrapValidation.js"></script>
	    <script src="/js/social-buttons.js"></script>
	    <script src="/js/bootstrap-toggle.min.js"></script>

	    <script>
		jQuery(document).ready(function ($) {
		    $(document).on("click", ".overlay", function(e)
		    {
			$("#panelUpload").click();
		    });
		    
		    $("#panelUpload").change(function(click) {
			//alert(this.files[0]);
			var postData = "";

			//côté back, checker si c"est un fichier de type jpg ou png puis l"enregistrer sur le serveur + path dans bdd
			$.ajax(
			{
			    url : "/lib/form/get_avatar.php",
			    type: "POST",
			    data : postData,
			    success:function(data, textStatus, jqXHR) 
			    {
				if (data == "success"){
				    //afficher l"image
				}
			    },
			    error: function(jqXHR, textStatus, errorThrown) 
			    {
			    
			    }
			});
			e.preventDefault(); //STOP default action
		    });


		    $("#toggle-games").change(function() {
			if($(this).prop("checked")){
			    $("#mail-games .toggle-list input").bootstrapToggle("on");
			} else {
			    $("#mail-games .toggle-list input").bootstrapToggle("off");
			}
		    });

		    $("#toggle-races").change(function() {
			if($(this).prop("checked")){
			    $("#race-inscriptions .toggle-list input").bootstrapToggle("on");
			} else {
			    $("#race-inscriptions .toggle-list input").bootstrapToggle("off");
			}
		    });


		    $(document).on("submit", "#mail-games-form", function(e) {
			var postData = {
			    jeux: []	
			}
			$("#mail-games .toggle-list input").each(function(){
			    var id = $(this).attr("id").toString();
			    var bool = $(this).prop("checked");
			    var obj = {};
			    obj[id] = bool;
			    postData.jeux.push(obj);
			});
			var formURL = $(this).attr("action");
			$.ajax(
			{
			    url : formURL,
			    type: "POST",
			    data : postData,
			    success:function(data, textStatus, jqXHR) 
			    {

			    },
			    error: function(jqXHR, textStatus, errorThrown) 
			    {

			    }
			});
			e.preventDefault(); //STOP default action
		    });

		    $(document).on("submit", "#race-inscriptions-form", function(e) {
			var postData = {
			    races: []	
			}
			$("#race-inscriptions .toggle-list input").each(function(){
			    var id = $(this).attr("id").toString();
			    var bool = $(this).prop("checked");
			    var obj = {};
			    obj[id] = bool;
			    postData.races.push(obj);
			});
			var formURL = $(this).attr("action");
			$.ajax(
			{
			    url : formURL,
			    type: "POST",
			    data : postData,
			    success:function(data, textStatus, jqXHR) 
			    {

			    },
			    error: function(jqXHR, textStatus, errorThrown) 
			    {

			    }
			});
			e.preventDefault(); //STOP default action
		    });
		});
	    </script>
	</body>
    </html>';
 ?>
