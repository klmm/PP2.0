<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'] . '/sql/articles/get_articles.php');
	include $_SERVER['DOCUMENT_ROOT'] . '/sql/jeux/get_jeux.php';

	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/header.php';
	
	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/unes.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/point_info.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/jeux.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/inscription.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/presentation.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/contact.php';
	
	include $_SERVER['DOCUMENT_ROOT'] . '/render/accueil/footer.php';
?>