<?php

//--------------------------------------FONCTIONS--------------------------------------//
    include($_SERVER['DOCUMENT_ROOT'] . '/lib/sql/articles/get_articles.php');
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/jeux/get_jeux.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/images/get_images.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/auto_login.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/joueurs/update_joueurs.php';
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
        <title>Parions Potes : Pronostics gratuits sur les grands évènements de l\'année</title>';


    // BOOTSTRAP
    echo '
	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<link href="/css/font-awesome.min.css" rel="stylesheet">
	<link href="/css/carousel.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">	
	<link rel="stylesheet" type="text/css" href="/css/game-component.css" />
		
	<link href="http://fonts.googleapis.com/css?family=Quicksand:300,400,700|Nova+Square|Open+Sans" rel="stylesheet" type="text/css">	    
	<style type="text/css"></style></head>';
		
// LIENS TWITTER FACEBOOK
    echo '
    <body data-spy="scroll" data-target="#navbar-main" data-offset="100">
        <header id="home" class="no-js">
            <div class="navbar-wrapper" id="header-top">
                <div class="container">
                    <h1><a href="#myCarousel">Parions Potes</a></h1>  
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
                                <a class="navbar-brand logosmall" href="#myCarousel" id="logosmall" data-action="scrollTo"></a>
                                <ul class="nav nav-pills" style="position:absolute;margin-left:90px;width:50%">';

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
	echo '			</ul>
                            </div>
                            
                            <div class="navbar-collapse collapse" id="navbar-main">
                                <ul class="nav navbar-nav pull-right" style="">
                                    <li class="active"> <a href="#myCarousel" data-action="scrollTo">Actualités</a></li>
                                    <li class="">       <a href="#games" data-action="scrollTo">Jeux</a></li>';
							
	if ($bConnected == false){
		echo '              <li class=""><a href="#inscription" data-action="scrollTo">Inscription</a></li>';
	}
							
							
	echo '
                                    <li class=""><a href="#presentation" data-action="scrollTo">Présentation</a></li>
                                    <li class=""><a href="#contacts" data-action="scrollTo">Contacts</a></li>
                                </ul>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	</header>';
//---------------------------------------------FIN HEADER------------------------------------------------------//
		


		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
//--------------------------------------UNES--------------------------------------//
    // Récupération de tous les articles et des unes à afficher
    $NB_ARTICLES_LISTE = 4;
    $arr_tous = get_articles_tous();
    $nb_tous = sizeof($arr_tous);

    $arr_unes = get_articles_unes();
    $nb_unes = sizeof($arr_unes);

    // Affichage des unes
    echo '  <div id="news-section" class="section" style="background-color: white;">	
                <div class="container marketing">
                    <div class="row hidden-xs hidden-sm">
                        <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="3000" style="margin-top:0;"> 
                            <ol class="carousel-indicators">
                                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>';
 
    // Nombre de petits points (nombre de unes)
    for ($i = 1; $i < $nb_unes; $i++) {
        echo '                  
                                <li data-target="#myCarousel" data-slide-to="' . $i . '" class=""></li>';
    }

    echo '			
                            </ol>
                            <div class="carousel-inner" role="listbox">';

    // Unes
    for ($i = 0; $i < $nb_unes; $i++) {
        if ($i == 0){
            echo '              <div class="item active" style="">';
        }
        else{
            echo '              <div class="item">';
        }
        
        $filename = $_SERVER['DOCUMENT_ROOT'] . "/articles/" . $arr_unes[$i]['id_article'] . ".htm";
	$handle = fopen($filename, "r+");
	$debut_article = fread($handle, 300) . '...';
	fclose($handle);
        
        $categorie_article = $arr_unes[$i]['categorie'];
	$sous_categorie_article = $arr_unes[$i]['souscategorie'];
	
	$url = '/articles/' . $categorie_article . '/' . $sous_categorie_article . '/' . $arr_unes[$i]['id_article'] . '-' . $arr_unes[$i]['titre'];
	$url_propre = clean_url($url);
        echo '
                                    <a href="' . $url_propre . '">
                                        <img src="' . $arr_unes[$i]['photo_chemin_deg'] . '" class="img-responsive" alt="' . $arr_unes[$i]['photo_chemin_deg'] . '">
                                        <div class="container">
                                            <div class="carousel-caption">
                                                <div class="col-md-9">
                                                    <h1>'. $arr_unes[$i]['titre'] . '</h1>
                                                    <p class="unes-intro">' . $debut_article . '</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <p class="unes-sport ' . $cr[$categorie_article] . '">'. $categorie_article . '<br/>' . $arr_unes[$i]['souscategorie'] . '</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>';
    }


    // "Boutons" précédent/suivant					
    echo'
                            </div>
                            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>';


    // Liste des articles
    echo '          <div class="row">
                        <div class="sectionSide" >
                            <h2 class="section-heading">Articles</h2>
                        </div>
                        <ul class="list-group list-articles col-md-12 col-sm-12 col-xs-12">';

    // Colonne 1 sport - image - titre - catégorie
    for ($i = 0; $i < $nb_tous; $i++) {
        
        $categorie = $arr_tous[$i]['categorie'];
	$sous_categorie_article = $arr_tous[$i]['souscategorie'];
	
	$url = '/articles/' . $categorie . '/' . $sous_categorie_article . '/' . $arr_tous[$i]['id_article'] . '-' . $arr_tous[$i]['titre'];
	$url_propre = clean_url($url);
        echo '
                            <li class="list-articles-item list-group-item col-md-3 col-md-offset-0 col-sm-4 col-sm-offset-0 col-xs-6 col-xs-offset-0">
                                <a href="' . $url_propre . '">
                                    <span class="badge ' . $bcr[$categorie] . '">' . $categorie . '</span>
                                    <img src="' . $arr_tous[$i]['photo_chemin_deg'] . '" alt="oo ' . $arr_tous[$i]['photo_chemin_deg'] . '"/>
                                    <h4 class="list-group-item-heading">' . $arr_tous[$i]['titre'] . '</h4>
                                    <p class="list-group-item-text">' . $arr_tous[$i]['souscategorie'] . '</p>
                                </a>
                            </li>';
    }

    echo '              </ul>
                    </div>
                    
                    <div class="row">
                        <nav id="pagination">
                            <ul class="pagination">

                            </ul>
                        </nav>
                    </div>
                    <div class="filtweet hidden-md hidden-sm hidden-lg col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2" style="text-align: center;">
			
			<div class="sectionSide">
                            <h2 class="section-heading">Fil twitter</h2>
			</div>
			
			<div id="twitter"  >
                            <a class="twitter-timeline" href="https://twitter.com/ParionsPotes" width="100%" height="400" data-widget-id="463784722661777409">Tweets de @ParionsPotes</a>
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
                    </div>
                </div>
            </div>';
//--------------------------------------FIN UNES--------------------------------------//








































//--------------------------------------JEUX--------------------------------------//
    $arr_jeux_encours = get_jeux_encours();
    $nb_jeux_encours = sizeof($arr_jeux_encours);

    $arr_jeux_finis = get_jeux_finis();
    $nb_jeux_finis = sizeof($arr_jeux_finis);

    $arr_jeux = get_jeux_avenir();
    $nb_jeux_avenir = sizeof($arr_jeux);

    echo '  <div class="section products" id="games" style="margin-bottom:120px;">
                <div class="sectionSide">
                    <h2 class="section-heading">Jeux</h2>
                    <p class="section-highlight">Venez parier et vous amuser avec notre panel de sports.</p> 
                </div>';

    if ($nb_jeux_avenir > 0){
        echo " 
                <div class='actualGames'>
                    <ul class='gamesList' style='padding:0;'>";
        for ($i = 0; $i < $nb_jeux_avenir; $i++) {
            $jeu_sport = $arr_jeux[$i]['sport'];
            echo "
                        <li class='game-item'>
                            <section class='game-box' data-speed='6' data-type='background' style='background-image:url(" . $arr_jeux[$i]['image'] . ")'>
                                <div class='game-content'>
                                    <div class='game-text col-md-9 hidden-xs'>
                                        <div class='jumbotron " . $bcr[$jeu_sport]. "'>
                                            <h2>" . $jeu_sport . " - " . $arr_jeux[$i]['competition'] . "</h2>
                                            <p>" . $arr_jeux[$i]['description'] . "</p>
                                        </div>
                                    </div>
                                    <div class='game-button col-md-3 col-sm-3 col-xs-12'>
                                            <a class='btn btn-primary btn-lg " . $bcr[$jeu_sport]. "' href='" . $arr_jeux[$i]['url'] . "' disabled>Bientôt...</a>
                                    </div>
                                </div>
                            </section>
                        </li>";
        }
        echo " 
                    </ul>
                </div>";
    }

    if ($nb_jeux_encours > 0){
        echo " 
                <div class='actualGames'>
                    <ul class='gamesList' style='padding:0;'>";
        for ($i = 0; $i < $nb_jeux_encours; $i++) {
            $jeu_sport = $arr_jeux_encours[$i]['sport'];
            echo "
                        <li class='game-item'>
                            <section class='game-box' data-speed='6' data-type='background' style='background-image:url(" . $arr_jeux_encours[$i]['image'] . ")'>
                                <div class='game-content'>
                                    <div class='game-text col-md-9 col-sm-9 hidden-xs'>
                                        <div class='jumbotron " . $bcr[$jeu_sport]. "'>
                                            <h2>" . $arr_jeux_encours[$i]['sport'] . " - " . $arr_jeux_encours[$i]['competition'] . "</h2>
                                            <p>" . $arr_jeux_encours[$i]['description'] . "</p>
                                        </div>
                                    </div>
                                    <div class='game-button col-md-3 col-sm-3 col-xs-12'>
                                        <a class='btn btn-primary btn-lg " . $bcr[$jeu_sport]. "' href='" . $arr_jeux_encours[$i]['url'] . "'>Jouer</a>
                                    </div>
                                </div>
                            </section>
                        </li>";
        }
        echo " 
                    </ul>
                </div>";
    }
        

    if ($nb_jeux_finis > 0){
        echo " 
                <div class='archiveGames style='margin-bottom:10px;margin-top:10px;'>
                    <div class='collapse-group'>
			<div class='collapse' id='archives' >
                            <div class='sectionSide'>
                                <h2 class='section-heading'>Archives</h2>
                                <p class='section-highlight'>Revivez en ketchup vos plus grands moments de sports.</p> 
                            </div>
                            <ul class='gamesList' style='padding:0;'>";
        for ($i = 0; $i < $nb_jeux_finis; $i++) {
            $jeu_sport = $arr_jeux_finis[$i]['sport'];
            echo "
                                <li class='game-item'>
                                    <section class='game-box' data-speed='6' data-type='background' style='background-image:url(" . $arr_jeux_finis[$i]['image'] . ")'>
                                        <div class='game-content'>
                                            <div class='game-text col-md-9 col-sm-9 hidden-xs'>
                                                <div class='jumbotron " . $bcr[$jeu_sport]. "'>
                                                    <h2>" . $arr_jeux_finis[$i]['sport'] . " - " . $arr_jeux_finis[$i]['competition'] . "</h2>
                                                    <p>" . $arr_jeux_finis[$i]['description'] . "</p>
                                                </div>
                                            </div>
                                            <div class='game-button col-md-3 col-sm-3 col-xs-12'>
                                                <a class='btn btn-primary btn-lg " . $bcr[$jeu_sport]. "' href='" . $arr_jeux_finis[$i]['url'] . "'>Revoir</a>
                                            </div>
                                        </div>
                                    </section>
                                </li>";
        }
	
	echo '              </ul>
                        </div>
                        <div class="col-md-12" style="margin-top:10px;">
                            <button id="archive-toggle" class="btn btn-block btn-success btn-lg" data-toggle="collapse" data-target="#archives">Archives</button>
                        </div>
                    </div>
                </div>';
    }

    echo '
            </div>';
//--------------------------------------FIN JEUX--------------------------------------//























//--------------------------------------INSCRIPTION--------------------------------------//
    if ($bConnected == false){
        echo "
        <div class='section-contact section' id='inscription' style='min-height: 214px;'>
            <div id='inscription-container' class='container marketing '>
                <div class='sectionSide'>
                    <h2 class='section-heading'>Inscription</h2>
                    <p class='section-highlight'>Quelques infos avant les pronos !</p>
                </div>
                <form id='inscription-form' role='form' class='row contact-form' action='/lib/form/inscription_joueur.php' method='POST'>
                    <div class='col-md-6'>
                        <input type='text' placeholder='Nom' name='nom' class='form-control'>
                        <input type='text' placeholder='Prénom' name='prenom' class='form-control'>
                    </div>
                    <div class='col-md-6'>
                        <input type='text' placeholder='Login (entre 3 et 12 chiffres et lettres)' name='login' class='form-control' required='' data-validation-required-message='Veuillez choisir un login'>
                        <input type='email' placeholder='email' name='email' class='form-control' required='' data-validation-required-message='Veuillez indiquer une adresse mail valide'>	
                    </div>
                    <div class='col-md-4'></div>
                    <div class='col-md-4'>
                        <button type='submit' class='btn btn-block btn-primary' style='padding:0;margin-top:20px;margin-bottom:10px;text-align:center;'>
                                <span style='display:block;padding: 0 8px 0 8px;height:38px;line-height:38px;'>Envoyer</span>
                        </button>
                    </div>
                    <div class='col-md-4'></div>
                </form>
            </div>
        </div>";
    }
//--------------------------------------FIN INSCRIPTION--------------------------------------//


/*<input type='password' placeholder='Mot de passe (entre et 8 et 20 chiffres et lettres)' name='motdepasse' class='form-control' required='' data-validation-required-message='Veuillez choisir un mot de passe'>
<input type='password' placeholder='Confirmation du mot de passe' name='confmotdepasse' class='form-control' required='' data-validation-required-message='Veuillez confirmer votre mot de passe'>*/













//--------------------------------------PRESENTATION--------------------------------------//
    echo "
	<div class='container-services' id='presentation'>
            <div class='container marketing'>
                <div class='  about-us'>
                    <div class='sectionSide'>
                        <h2 class='section-heading'>Qui sommes-nous</h2>
                        <p class='section-highlight'>« deux simples étudiants, passionnés (le mot est faible) de sport, de tous les sports. »</p>
                    </div>

                    <div class='tab-pane section-content row' id='who-we-are'>
                        <div class='row-content col-md-9 '>	
                            <h3>Kevin et Thomas,</h3>
                            <p>deux « simples » étudiants, passionnés (le mot est faible) de sport, de tous les sports. 
                            Amateurs occasionnels de paris sportifs, nous n’avons cependant jamais recherché le gain d’argent 
                            (excuse habituelle de ceux qui perdent).</p><br/>
                            <p>Depuis des années, nous pronostiquons entre nous sur des courses de cyclisme, de ski alpin… 
                            Un simple bout de papier et des calculs dignes d’Einstein donnaient lieu à une compétition acharnée entre nous ! </p><br/>
                            <p>Nous vous proposons de vous joindre à cette compétition grâce à Parions Potes !
                            </p>
                        </div>
                        <div class='row-illustration col-md-3 ' style='text-align:center'>
                            <img src='img/static/thokes.jpg' alt='thokes' style='width:150px;'>
                        </div>
                    </div>

                    <div class='sectionSide'>
                        <h2 class='section-heading'>Qui êtes-vous</h2>
                        <p class='section-highlight'>« Venez prouver vos connaissances en vous confrontant aux meilleurs pronostiqueurs ! »</p>
                    </div>

                    <div class='tab-pane section-content row' id='who-you-are'>
                        <div class='row-illustration col-md-3 ' style='text-align:center'>
                            <img src='img/static/thokes.jpg' alt='thokes' style='width:150px;'>
                        </div>
                        <div class='row-content col-md-9 '>	
                            <h3>Pour vous,</h3>
                            <p>Pelé, Alain Prost, Raphael Poirée, Luc Alphand ou encore Bernard Hinault sont encore au sommet de la hiérarchie de leur sport respectif ? 
                            Mettez-vous à la page ! Venez découvrir les talents d’aujourd’hui qui sauront vous faire vibrer comme l’ont fait vos idoles d’antan !</p><br/>
                            <p>Vous ne jurez que par Karim Benzema, Martin Fourcade, Jean-Baptiste Grange, Romain Grosjean ou Sylvain Chavanel ? 
                            Vous êtes un brin chauvin (et vous avez raison) ! Venez parier sur vos français préférés, l'épreuve sera encore plus passionnant à suivre !</p><br/>
                            <p>Vous associez Lionel Messi, Sebastian Vettel, Lindsay Vonn, Alberto Contador et Ole-Einar Bjoerdalen à leur discipline ? 
                            Vous connaissez les stars actuelles les plus médiatisées ! Venez dénicher celles et ceux qui seront les stars de demain !</p><br/>
                            <p>Vous connaissez Arbeloa, Jean-Philippe Leguellec, Dominik Paris, Pastor Maldonado et Laurens Ten Dam ? 
                            Vous avez dû regarder un nombre de matches et de courses incroyable ! Venez prouver vos connaissances en vous confrontant aux meilleurs pronostiqueurs !</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>";
//--------------------------------------FIN PRESENTATION--------------------------------------//

















//--------------------------------------CONTACT--------------------------------------//
    echo "  <div class='section-contact section' id='contacts' style='min-height: 214px;'>
                <div class='container marketing '>
                    <div id='contact-container' class='row'>
                        <div class='sectionSide'>
                            <h2 class='section-heading'>Contact</h2>
                            <p class='section-highlight'>Un souci, une question, une idée? N'hésitez pas à nous contacter !</p>
                        </div>

                        <form id='contact-form' role='form' class='row contact-form' action='/lib/form/contact_mail.php' method='POST'>
                            <div class='col-md-6'>";
    
    if ($bConnected == false){
        echo "                  <input type='text' placeholder='Nom' name='nom' class='form-control' required='' data-validation-required-message='Nom obligatoire'>
                                <input type='email' placeholder='email (pour vous répondre)' name='mail' class='form-control' required='' data-validation-required-message='Mail obligatoire'>";
    }
    else{
        echo "                  <input type='text'  value='" . $loginjoueur . "' name='nom' class='form-control' readonly>
                                <input type='email'  value='" . $mailjoueur . "' name='mail' class='form-control' readonly>";
    }
								
								
    echo "
                                <input type='text' placeholder='Objet' name='objet' class='form-control' required='' data-validation-required-message='Objet obligatoire'>
                            </div>
                            <div class='col-md-6'>
                                <textarea class='form-control' rows='7' name='contenu' placeholder='Votre message'  required='' data-validation-required-message='Message obligatoire'></textarea>
                                <button type='submit' class='btn btn-primary' style='padding:0;margin-top:20px;width:200px;float:right;'>
                                    <span style='display:block;padding: 0 8px 0 8px;;height:38px;line-height:38px;margin-right: 60px;float: right;'>Envoyer</span>
                                    <span class='glyphicon glyphicon-ok' style='float:left;width:40px;height:38px;margin:0;padding: 0 0 0 8px;line-height:38px;font-size:1.5em;'></span>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div class='row' style='margin-top:40px;'>
                        <div class='contact-address col-md-12'>
                            <p class='address'>contact@parions-potes.fr</p>
                            <ul class='social'>
                                <li class='twitter'>
                                    <a href='https://twitter.com/ParionsPotes' target='_blank'>
                                        <img src='img/static/icons_set/no_border/twitter.png' alt='Twitter'></img>
                                    </a>
                                </li>
                                <li class='facebook'>
                                    <a href='https://www.facebook.com/parionspotes' target='_blank'>
                                        <img src='img/static/icons_set/no_border/facebook.png' alt='Facebook'></img>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>";
//--------------------------------------FIN CONTACT--------------------------------------//

























//--------------------------------------FOOTER--------------------------------------//
    echo '  <footer>
                <div class="container ">
                    <p>&copy 2015 Parions Potes </p>
                </div>
            </footer>

	    <!-- <script src="/js/modernizr.custom.js"></script> -->
	    <script src="/js/script.js"></script>
	    <script src="/js/pagination.js"></script>

	    <script src="/js/jquery.min.js"></script>
	    <script src="/js/bootstrap.min.js"></script>
	    <!--script src="./assets/js/docs.min.js"></script-->
	    <script src="/js/classie.js"></script>
	    <script src="/js/jquery.scrollTo.min.js"></script>
	    <script src="/js/jqBootstrapValidation.js"></script>

	    <!-- <script src="/bower_components/jquery/dist/jquery.js"></script>-->
	    <!-- <script src="/bower_components/velocity/velocity.js"></script>
	    <script src="/bower_components/moment/min/moment-with-locales.min.js"></script> -->
	    <!-- <script src="/bower_components/angular/angular.js"></script> -->

	    <!-- <script src="/js/toucheffects.js"></script> -->
	    <!-- <script src="http://code.jquery.com/jquery-latest.js"></script> -->
	    <script src="/js/jquery.effects.core.min.js"></script>
	    <script src="/js/jquery.effects.slide.min.js"></script>

	    <script src="/js/masonry.pkgd.min.js"></script>
	    <script src="/js/imagesloaded.pkgd.min.js"></script>

	    <script type="text/javascript">
	/*var pagedSections = ["#myCarousel","#games","#inscription","#presentation","#contact"];
	//var pagedSections = ["#contact"];
	var headerSize = 98;
	
	var pageSection = function() {	
		var viewportHeight = $(window).height();
		
		$.each(pagedSections,function(index,value) {
			$(value).css("min-height",viewportHeight-headerSize);
		});	
		// $(\'body\').scrollspy("refresh");
	}*/

    jQuery(document).ready(function ($) {
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	
		Init_Forms();
		initPagination();
		
		//$(\'body\').scrollspy({ target: \'#navbar-main\',offset:250 }) /// lign qui \'spy\' le scroll de la page et surligne les menusp
		//pageSection();
		$(window).resize(function() {
			//pageSection();
			 $(\'body\').scrollspy("refresh");
		});
		$(\'#archive-toggle\').click(function() {
			$(\'[data-spy="scroll"]\').each(function () {
			  setTimeout(function(){ $(this).scrollspy(\'refresh\');}, 1000);		  
			});
		});
		//$.scrollTo( 0 );
		
        //$(\'.nav-tabs\').tab();
		$(\'a[data-action="scrollTo"]\').click(function(e) {
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
		
		$(\'section[data-type="background"]\').each(function(){
		 // declare the variable to affect the defined data-type
		 var $scroll = $(this);
						 
		  $(window).scroll(function() {
			if($(window).width() > 768){
			    // HTML5 proves useful for helping with creating JS functions!
			    // also, negative value because we\'re scrolling upwards                             
			    var yPos = -($window.scrollTop() / $scroll.data(\'speed\')); 

			    // background position
			    var coords = \'50% \'+ yPos + \'px\';

			    // move the background
			    $scroll.css({ backgroundPosition: coords });
			}   
		  }); // end window scroll
	   });  // end section function
		
		var $container = $(\'.list-articles\');
		$container.imagesLoaded( function () {
			$container.masonry({
				columnWidth: \'.list-articles-item\',
				itemSelector: \'.item-visible\'
			});  
		});
		
		$(function(){
		   $("#archive-toggle").click(function () {
			  $(this).text(function(i, text){
				  return text === "Archives" ? "Réduire" : "Archives";
			  })
		   });
		})
		
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
//--------------------------------------FIN FOOTER--------------------------------------//

?>