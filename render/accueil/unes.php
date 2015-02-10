<?php

// Récupération de tous les articles et des unes à afficher
$arr_tous = get_articles_tous();
$nb_tous = sizeof($arr_tous);

$arr_unes = get_articles_unes();
$nb_unes = sizeof($arr_unes);

// Affichage des unes
if ($nb_unes > 0){
echo '<div id="news-section" class="section" style="background-color: ghostwhite;">	
	<div class="container marketing">
		<div class="row">
			<div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="3000" style="margin-top:0;"> 
				<ol class="carousel-indicators">
					<li data-target="#myCarousel" data-slide-to="0" class="active"></li>';
}
// Nombre de petits points (nombre de unes)					
for ($i = 1; $i < $nb_unes; $i++) {
    echo '<li data-target="#myCarousel" data-slide-to="' . $i . '" class=""></li>';
}

// Première une (active)					
echo '			</ol>
				<div class="carousel-inner" role="listbox">
					<div class="item active" style="">
						<img src="' . $arr_unes[0][11] . '" alt="' . $arr_unes[0][11] . '">
						<div class="container">
							<div class="carousel-caption"> 
								<h1>'. $arr_unes[0][4] . '</h1>
								<p>'. $arr_unes[0][2] . ' - ' . $arr_unes[0][3] . '</p>
							</div>
						</div>
					</div>';

// Autres unes
for ($i = 1; $i < $nb_unes; $i++) {
    echo '			<div class="item">
						<img src="' . $arr_unes[$i][11] . '" alt="' . $arr_unes[$i][11] . '">
						<div class="container">
							<div class="carousel-caption">
								<h1>'. $arr_unes[$i][4] . '</h1>
								<p>'. $arr_unes[$i][2] . ' - ' . $arr_unes[$i][3] . '</p>
							</div>
						</div>
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
echo '	<div class="row">
			<div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
				<div class="list-group list-articles">';
				
// Colonne 1 sport - image - titre - catégorie
for ($i = 0; $i <= ($nb_tous-1); $i= $i+2) {
echo '
					<a href="#" class="list-articles-item list-group-item ">
						<span class="badge">' . $arr_tous[$i][2] . '</span>
						<img src="' . $arr_tous[$i][11] . '" alt="' . $arr_tous[$i][11] . '"/>
						<h4 class="list-group-item-heading">' . $arr_tous[$i][4] . '</h4>
						<p class="list-group-item-text">' . $arr_tous[$i][3] . '</p>
					</a>';
}

echo '
				</div>
			</div>
			<div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
				<div class="list-group list-articles">';


// Colonne 2
for ($i = 1; $i <= ($nb_tous-1); $i= $i+2) {			
echo '
					<a href="#" class="list-articles-item list-group-item ">
						<span class="badge">' . $arr_tous[$i][2] . '</span>
						<img src="' . $arr_tous[$i][11] . '" alt="' . $arr_tous[$i][11] . '"/>
						<h4 class="list-group-item-heading">' . $arr_tous[$i][4] . '</h4>
						<p class="list-group-item-text">' . $arr_tous[$i][3] . '</p>
					</a>';
}
					
echo '
				</div>
			</div>
		</div>
	</div>
</div>';

?>