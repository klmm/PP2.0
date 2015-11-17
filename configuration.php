<?php

//--------------------------------------FONCTIONS--------------------------------------//
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_joueurs.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/auto_login.php';   
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/update_joueurs.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_inscriptions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/ski-alpin/lib/sql/get_calendrier.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/biathlon/lib/sql/get_calendrier.php';
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


//--------------------------------------INFOS--------------------------------------//
    $joueur = get_joueur($loginjoueur);
    $no_mail_general = $joueur['no_mail'];
    $tmp_gen = "checked";
    if($no_mail_general){
	$tmp_gen = "";
    }
    
    $jeux = get_jeux_encours();
    $inscriptions = get_joueurs_inscriptions_joueur($loginjoueur);
//---------------------------------------------------------------------------------//





	
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
				<div class="navbar-collapse collapse" id="navbar-main">
                                    <ul class="nav navbar-nav pull-right" style="">
                                        <li class="active"><a href="#config" data-action="scrollTo">Mes paramètres</a></li>
					<li class="home"><a href="/" class="glyphicon glyphicon-home "  aria-label="home"><span> Retour au site</span></a></li>
                                    </ul>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>';
//---------------------------------------------FIN HEADER------------------------------------------------------//
		
    if($joueur["avatar"] == ""){
	$avatar_joueur = "";
    }
    else{
	$avatar_joueur = $joueur["avatar"];
    }

    echo '
	    <div class="section-config section" id="config" style="min-height: 214px;">
		<div class="container marketing">
		    <div id="contact-container" class="row">
			<div class="sectionSide">
			    <h2 class="section-heading">Paramètres du compte</h2>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12">
			    <div class="sectionSide">
				<p class="section-highlight">Gérez vos informations</p>
			    </div>
 
			    <p class="inter-section-highlight">Vos informations de connexion</p>
			    <p class="private-info" style="display:block;">' . $loginjoueur . '</p>

			    <button type="submit" class="btn btn-primary">
				Modifier mon mot de passe
			    </button>

			    <p class="inter-section-highlight">Vos informations personnelles</p>
			    <form id="playerInfo-form" role="form" class="row contact-form" method="post" action="/lib/form/post_infos.php" enctype="multipart/form-data">
				<div class="panelIcon">
				    <div id="avatar"  class="avatar" >
					<div class="overlay">
					    <span class="lblIcon">Icone</span>
					    <span class="glyphicon glyphicon-plus overlayImage" aria-hidden="true"></span>
					</div>
				    </div>

				    <input id="panelUpload" name="panelUpload" type="file" class="file upload">
				    <img id="avatar_img" src="' . $joueur["avatar"] . '" alt="aperçu indisponible"/>
				</div>
			    
				<input type="text" value="' . $joueur["nom"] . '" placeholder="Nom" name="nom" class="form-control" required="" data-validation-required-message="Nom obligatoire" />
				<input type="text" value="' . $joueur["prenom"] . '" placeholder="Prénom" name="prenom" class="form-control" required="" data-validation-required-message="Prénom obligatoire" />
				<input type="email" value="' . $joueur["mail"] . '" placeholder="Email (pour vous répondre)" name="mail" class="form-control" required="" data-validation-required-message="Mail obligatoire" />
				<textarea class="form-control" rows="3" name="punchline" placeholder="Ecrivez votre slogan !">' . $joueur["slogan"] . '</textarea> 
				<button type="submit" id="valid_conf" name="valid_conf" class="btn btn-primary" style="padding:0;margin-top:20px;width:200px;">
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
				<p class="section-highlight">Gérez votre niveau de dépendance aux jeux</p>
			    </div>
			    <div id="mail-games" class="toggle-div">
				<div class="row">
				    <div class="col-md-6 col-sm-6 col-xs-6">
					<p class="">Recevoir des mails de Parions Potes</p>
				    </div>
				    <div class="col-md-6 col-sm-6 col-xs-6 text-right">
					<input id="toggle-games" type="checkbox" ' . $tmp_gen . ' data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="danger" data-on="Oui" data-off="Non">
				    </div>
				</div>

				<form id="mail-games-form" role="form" class="row contact-form" method="post">
				    <ul class="toggle-list">';
    
				foreach($jeux as $key => $jeu){
				    $tmp = "checked";
				    if($inscriptions[$jeu['id_jeu']]['no_mail'] || $no_mail_general == true){
					$tmp = '';
				    }
				    
				    echo '
					<li class="toggle-box clearfix">
					    <div class="col-md-6 col-sm-6 col-xs-6 toggle-item-name">
						<p>' . $jeu['sport'] . ' - ' . $jeu['competition'] . '</p>
					    </div>
					    <div class="col-md-6 col-sm-6 col-xs-6 toggle-item">
						<input class="toggle-game" type="checkbox" ' . $tmp . ' data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-off="Non" data-on="Oui">
						<input class="hidden id_jeu" value="' . $jeu['id_jeu'] . '"/>
					    </div>
					</li>';
				}
					
					    
	echo '
				    </ul>
				</form>	
			    </div>

			    <div id="race-inscriptions" class="toggle-div">';
    
			    foreach($jeux as $key => $jeu){
				if($jeu['sport'] != 'Biathlon' && $jeu['sport'] != 'Ski alpin'){
				    continue;
				}
				    
				echo '
				<div class="blah">
				    <div class="row">
					<div class="col-md-6 col-sm-6 col-xs-6">
					    <p class="">' . $jeu['sport'] . ' - ' . $jeu['competition'] . '</p>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-6 text-right">
					    <input class="toggle-races" type="checkbox" checked data-toggle="toggle" data-size="small" data-onstyle="success" data-offstyle="danger" data-off="Aucune" data-on="Toutes">
					</div>
				    </div>
				    <form id="race-inscriptions-form" role="form" class="row contact-form" method="post">
					<input class="hidden id_jeu" value="' . $jeu['id_jeu'] . '">
					<ul class="toggle-list">';
				
				switch($jeu['sport']){
				    case 'Ski alpin':
					$epreuves = filtre_to_epreuves($inscriptions[$jeu['id_jeu']]['filtre']);
					break;

				    case 'Biathlon':
					$epreuves = filtre_to_epreuves_biathlon($inscriptions[$jeu['id_jeu']]['filtre']);
					break;

				    default:
					continue;
				}
				
				foreach($epreuves as $key2 => $epreuve){
				    $tmp = "";
				    if($epreuve['inscrit']){
					 $tmp = "checked";
				    }
				    
				    echo '
					    <li class="toggle-box clearfix">
						<div class="col-md-6 col-sm-6 col-xs-6 toggle-item-name">
						    <p>' . $epreuve['discipline'] . ' ' . $epreuve['genre_long']  . '</p>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-6 toggle-item">
						    <input id="' . $key2 . '" type="checkbox" ' . $tmp . ' data-toggle="toggle" data-onstyle="success" data-offstyle="danger" data-off="Non" data-on="Oui">
						</div>
					    </li>';
				}
					    
				echo '
					</ul>
					<button type="submit" class="btn btn-primary" style="padding:0;margin-top:20px;width:200px;">
					    <span style="display:block;padding: 0 8px 0 8px;;height:38px;line-height:38px;margin-right: 60px;float: right;">
						Enregistrer
					    </span>
					</button>
				    </form>
				</div>';
			    }
				
				    
    echo '
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
		    
		    // VALIDATION FORMULAIRE INFOS
		    $("#playerInfo-form").submit(function(e) {
			$(\'.alert\').alert(\'close\');
			
			$.ajax(
			{
			    url : "/lib/form/post_infos.php",
			    type: "POST",
			    data : new FormData(this),
			    processData: false,
			    contentType: false,
			    success:function(data, textStatus, jqXHR) 
			    {
				var result = $.parseJSON(data);
				if (result.success == true){
				    $( "#playerInfo-form" ).append( \'<div class="alert alert-success alert-dismissible" role="alert">\'+
					\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
					result.msg+\'</div>\' );		    
				}
				else{
				     $( "#playerInfo-form" ).append( \'<div class="alert alert-info alert-dismissible" role="alert">\'+
					\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
					\'<strong>Attention!  </strong>\'+result.msg+\'</div>\' );
				}
			    },
			    error: function(jqXHR, textStatus, errorThrown) 
			    {
				 alert("error");
				//error
			    }
			});
			e.preventDefault(); //STOP default action
		    });
		    
		    // PRE-VISUALISATION DE L IMAGE
		    $("#panelUpload").change(function(click) {
			var preview = document.querySelector("img[id=avatar_img]");; //selects the query named img
			var file    = document.querySelector("input[type=file]").files[0]; //sames as here
			var reader  = new FileReader();

			reader.onloadend = function () {
			    preview.src = reader.result;
			}

			if (file) {
			    reader.readAsDataURL(file); //reads the data as a URL
			} else {
			    preview.src = "";
			}
		    });

		    // TOGGLE NO MAIL GENERAL
		    $("#toggle-games").change(function() {
			var postData = "";

			if($(this).prop("checked")){
			    $(this).parent().parent().parent().parent().find("#mail-games-form .toggle-list .toggle-box .toggle-game").each(function(){
				$(this).bootstrapToggle("on");
				$(this).prop("disabled", false);
			    });
			    postData = "no_mail=0";
			} else {
			    $(this).parent().parent().parent().parent().find("#mail-games-form .toggle-list .toggle-box .toggle-game").each(function(){
				$(this).bootstrapToggle("off");
				$(this).prop("disabled", true);
			    });
			    postData = "no_mail=1";
			}
			
			$.ajax(
			{
			    url : "/lib/form/update_no_mail.php",
			    type: "POST",
			    data : postData,
			    success:function(data, textStatus, jqXHR) 
			    {
				var result = $.parseJSON(data);
				if (result.success == true){
				    // rien
				}
				else{
				    window.location.reload();
				}
			    },
			    error: function(jqXHR, textStatus, errorThrown) 
			    {
				//error
			    }
			});
			e.preventDefault(); //STOP default action
		    });
		    
		    // TOGGLE INSCRIPTION JEU
		    $(".toggle-game").change(function() {
			var id_jeu = $(this).parent().parent().find(".id_jeu").attr("value");
			var postData = "id_jeu=" + id_jeu;
			
			if($(this).prop("checked")){
			    postData += "&no_mail=0";
			} else {
			    postData += "&no_mail=1";
			}
			
			$.ajax(
			{
			    url : "/lib/form/update_inscr_jeu.php",
			    type: "POST",
			    data : postData,
			    success:function(data, textStatus, jqXHR) 
			    {
				var result = $.parseJSON(data);
				if (result.success == true){
				    //alert("ok");
				}
				else{
				    window.location.reload();
				}
			    },
			    error: function(jqXHR, textStatus, errorThrown) 
			    {
				//error
			    }
			});
			e.preventDefault(); //STOP default action
		    });

		    // TOGGLE TOUTES/AUCUNE EPREUVE(S)
		    $(".toggle-races").change(function() {
			if($(this).prop("checked")){
			    $(this).parent().parent().parent().parent().find("#race-inscriptions-form .toggle-list .toggle-box input").each(function(){
				$(this).bootstrapToggle("on");
			    });
			    
			} else {
			    $(this).parent().parent().parent().parent().find("#race-inscriptions-form .toggle-list .toggle-box input").each(function(){
				$(this).bootstrapToggle("off");
			    });
			}
		    });

		    // VALDIDATION EPREUVES
		    $(document).on("submit", "#race-inscriptions-form", function(e) {
			var id_jeu = $(this).find(".id_jeu").attr("value");
			$(\'.alert\').alert(\'close\');
			
			var postData = {
			    races: []
			}
			
			postData.id_jeu = id_jeu;
			
			$(this).find(".toggle-list .toggle-box .toggle-item input").each(function(){
			    var id = $(this).attr("id").toString();
			    var bool = $(this).prop("checked");
			    var obj = {};
			    obj["id"] = id;
			    obj["inscr"] = bool;
			    postData.races.push(obj);
			});
			
			var formURL = "/lib/form/inscr_epreuves.php";
			$.ajax(
			{
			    url : formURL,
			    type: "POST",
			    data : postData,
			    success:function(data, textStatus, jqXHR) 
			    {
				var result = $.parseJSON(data);
				alert($(this).html());
				if (result.success == true){
				    $(this).find("button").append( \'<div class="alert alert-success alert-dismissible" role="alert">\'+
					\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
					result.msg+\'</div>\' );		    
				}
				else{
				     $(this).find("button").append( \'<div class="alert alert-info alert-dismissible" role="alert">\'+
					\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
					\'<strong>Attention!  </strong>\'+result.msg+\'</div>\' );
				}
			    },
			    error: function(jqXHR, textStatus, errorThrown) 
			    {
				alert("erreur");
			    }
			});
			e.preventDefault(); //STOP default action
		    });
		});
	    </script>
	</body>
    </html>';
 ?>
