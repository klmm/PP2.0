<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_jeux.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/sql/get_articles.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/clean_url.php';
    
    $articles = get_articles_tous();
    $jeux_en_cours = get_jeux_encours();

	    
    $res = '<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		    <url>
			<loc>http://www.parions-potes.fr/</loc>
		    </url>';
	
    echo '1';
    // ARTICLES
    foreach($articles as $id => $article){
	$url = '/articles/' . $article['categorie'] . '/' . $article['souscategorie'] . '/' . $article['id_article'] . '-' . $article['titre'];
	$url_propre = clean_url($url);
	$res .= '   
		    <url>
			<loc>http://www.parions-potes.fr' . $url_propre . '</loc>
		    </url>';
    }
    
    echo '2';

    // JEUX EN COURS
    foreach($jeux_en_cours as $id => $jeu){
	// PAGE PRINCIPALE
	$sport = $jeu['sport'];
	$url_jeu = $jeu['url'];
	$id_jeu = $jeu['id_jeu'];
	
	$res .= '   
		    <url>
			<loc>http://www.parions-potes.fr' . $url_jeu . '</loc>
		    </url>';
	
	// CALENDRIER
	if($sport == 'Cyclisme'){
	    require_once $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_calendrier.php';
	    $calendrier = get_calendrier_jeu($id_jeu);
	    
	    foreach($calendrier as $id => $cal){
		$url = clean_url('pronostic/' . $cal['id_cal'] . '-' . $cal['nom_complet']);
		$res .= '   
		    <url>
			<loc>http://www.parions-potes.fr' . $url_jeu . '/' . $url . '</loc>
		    </url>';
	    }
	}
    }
        echo '3';

    $res .= '
		</urlset>';
    
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/sitemap.xml', $res);
       
    echo '4';

?>