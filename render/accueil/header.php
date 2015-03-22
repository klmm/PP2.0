<?php

include $_SERVER['DOCUMENT_ROOT'] . '/sql/joueurs/auto_login.php';

session_start();
$loginjoueur = $_SESSION['LoginJoueur'];
$idjoueur = $_SESSION['IDJoueur'];

if($loginjoueur == ""){
	auto_login();
	$loginjoueur = $_SESSION['LoginJoueur'];
	$idjoueur = $_SESSION['IDJoueur'];
}

// DOCTYPE, META
echo '<!DOCTYPE html>
	<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/CarrNoir.png">';
	
	
// TITLE
echo '<title>Parions Potes</title>';


// BOOTSTRAP
echo '
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	<link href="css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
	<link href="http://fonts.googleapis.com/css?family=Quicksand:300,400,700|Nova+Square|Open+Sans" rel="stylesheet" type="text/css">
   
	<link href="css/carousel.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="css/default.css" /> <!-- caption hover effects -->
	<link rel="stylesheet" type="text/css" href="css/component.css" />
	<script src="js/modernizr.custom.js"></script>
	<style type="text/css"></style></head>';

	
// HEADER

// LIENS TWITTER FACEBOOK
echo '<body>
 
	<header id="home" class="no-js">
	   <div class="navbar-wrapper" id="header-top">
			<div class="container">
				<h1><a href="#myCarousel">Parions Potes</a></h1>  
				<ul class="social">
					<!-- <li class="googleplus"><a href="">Google+</a></li> -->
					<li class="twitter"><a href="#">Twitter</a></li>
					<!-- <li class="linkedIn"><a href="#">LinkedIN</a></li> -->
					<li class="facebook"><a href="#">facebook</a></li>
					<!-- <li class="youtube"><a href="#">youtube</a></li> -->
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
if ($loginjoueur == ""){
	echo '						<li class="dropdown" id="menuLogin">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="navLogin" style="color:rgb(115, 50, 130);background:transparent;">
										<span class="glyphicon glyphicon-user"></span><span id="bConnect">  Se connecter </span>
									</a>
									<ul class="dropdown-menu" style="padding:17px;margin: 2px -10px 0;">
										<form role="form" id="formLogin" class="form" action="sql/joueurs/verif_login.php" method="POST">
											<label>Se connecter</label>
											<input name="username" id="username" type="text" placeholder="Login" title="Login" required="">
											<input name="password" id="password" type="password" placeholder="Mot de passe" title="Mot de passe" required=""><br>
											<button type="submit" id="btnLogin" class="btn btn-block btn-primary">Se connecter</button>
										</form>
									</ul>
								</li>';

}
else
{
	echo '						<li class="dropdown" id="menuUser">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color:rgb(115, 50, 130);background:transparent;">
										<span class="glyphicon glyphicon-user"></span><span id="bUsername">  ' . $loginjoueur . ' </span>
									</a>
									<ul class="dropdown-menu" style="min-width:202px;">
										<form style="text-align: center; padding: 5px;">
											<a data-toggle="collapse" data-target="#changePassword">Changer de mot de passe</a>
										</form>
						
										<form id="changePassword" role="form" action="/sql/joueurs/change_pass.php" method="POST" class="form collapse" style="padding: 17px;height: auto;text-align: center;background: gainsboro;">
											<input name="oldpassword" id="oldpassword" type="password" placeholder="Mot de passe actuel" required=""> 
											<input name="newpassword" id="newpassword" type="password" placeholder="Nouveau mot de passe" required=""><br>                                  
											<input name="newpassword2" id="newpassword2" type="password" placeholder="Confirmer nouveau" required=""><br>                                  
											<button type="submit" id="btnRegister" class="btn btn-success">Valider</button>
										</form>
										
										<li>
											<ul id="changeNotifs" style="padding: 17px;height: auto;">
												<form class="form ">
													<label class="checkbox-inline">
														<input type="checkbox" id="inlineCheckbox1" value="option1">Recevoir les notifications par mail
													</label>
												</form>
											</ul>
										</li>
										<li class="divider"> </li>
										<li>
											<form class="form" action="/sql/joueurs/deconnexion.php" method="POST">
												<button type="submit" id="logout" class="btn btn-primary btn-block">Déconnexion</button>	
											</form>
										</li>
										<li class="divider"> </li>
										<li>
											<form class="form" action="/tests/test_req.php" method="POST">
												<button type="submit" id="test" class="btn btn-primary btn-block">Test session</button>	
											</form>
										</li>
										
									</ul>
								</li>';

}

	
	
// MENU
echo '						</ul>
						</div>
					<div class="navbar-collapse collapse" id="navbar-main">
					  <ul class="nav navbar-nav pull-right" style="">
						<li class="active"><a href="#myCarousel" data-action="scrollTo">Actualités</a></li>
						<li class=""><a href="#games" data-action="scrollTo">Jeux</a></li>
						<!-- <li class=""><a href="#articles" data-action="scrollTo">Articles</a></li> -->';
						
if ($loginjoueur == ""){
	echo '				<li class=""><a href="#inscription" data-action="scrollTo">Inscription</a></li>';
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

?>