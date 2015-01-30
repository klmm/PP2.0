<?php
	session_start();
	echo $_SESSION['IDJoueur'];
	echo $_SESSION['LoginJoueur'];
	//print_r(array_values($_SERVER));
	/*include 'sql/joueurs/get_joueurs.php';
	include 'sql/articles/get_articles.php';
	include 'sql/jeux/get_jeux.php';
	
	$res = array();
	
	echo 'Tous les joueurs<br/>';
	$res = get_joueurs_tous();
	print_r(array_values($res));
	
	echo '<br/><br/>Joueurs admins<br/>';
	$res = get_joueurs_admins();
	print_r(array_values($res));
	
	echo '<br/><br/>Tous les articles<br/>';
	$res = get_articles_tous();
	print_r(array_values($res));
	
	echo '<br/><br/>Articles de la rubrique 1<br/>';
	$res = get_articles_rubrique(1);
	print_r(array_values($res));
	
	echo '<br/><br/>Articles de cyclisme<br/>';
	$res = get_articles_categorie('Cyclisme');
	print_r(array_values($res));
	
	echo '<br/><br/>Articles du TDF 2014<br/>';
	$res = get_articles_souscategorie('Cyclisme','Tour de France 2014');
	print_r(array_values($res));
	
	echo '<br/><br/>Articles à la Une<br/>';
	$res = get_articles_unes();
	print_r(array_values($res));
	
	echo '<br/><br/>Tous les jeux<br/>';
	$res = get_jeux_tous();
	print_r(array_values($res));
	
	echo '<br/><br/>Jeux en cours<br/>';
	$res = get_jeux_encours();
	print_r(array_values($res));
	
	echo '<br/><br/>Jeux terminés<br/>';
	$res = get_jeux_finis();
	print_r(array_values($res));
	
	echo '<br/><br/>Jeux à venir<br/>';
	$res = get_jeux_avenir();
	print_r(array_values($res));*/
	

?>