<?php

	$arr_jeux_encours = get_jeux_encours();
	$nb_jeux_encours = sizeof($arr_jeux_encours);
	
	$arr_jeux_finis = get_jeux_finis();
	$nb_jeux_finis = sizeof($arr_jeux_finis);
	
	if ($nb_jeux_encours > 0){
	
	echo "
    <div class='container marketing'>
		<div class='section products' id='games' style='margin-bottom:120px;'>
			<div class='sectionSide'>
				<h2 class='section-heading'>Jeux</h2>
				<p class='section-highlight'>Venez parier et vous amuser avec notre panel de sports.</p> 
			</div>
			<div class='row'>
				<ul class='grid cs-style-2'>";
				
				for ($i = 0; $i < $nb_jeux_encours; $i++) {
					echo "
					<li class='col-md-4'>
						<figure>
							// <img src='" . $arr_jeux_encours[$i][9] . "' alt='" . $arr_jeux_encours[$i][9] . "'>
							<figcaption>
								<a class='btn-block btn-lg' href='" . $arr_jeux_encours[$i][5] . "'>Jouer</a>
							</figcaption>
						</figure>
					</li>";
				}
	echo "				
				</ul>
			</div>";
			
			
			// Archives
			if ($nb_jeux_finis > 0){
			
			echo"
			<div class='row' style='margin-bottom:10px;margin-top:10px;'>
				<div class='collapse-group'>
					<div class='collapse' id='archives' >
						<ul class='grid cs-style-3'>
							<li class='col-md-2'></li>";
							
						for ($i = 0; $i < $nb_jeux_finis; $i++) {
							echo "
							<li class='col-md-4'>
								<figure>
									<img src='" . $arr_jeux_finis[$i][9] . "' alt='" . $arr_jeux_finis[$i][9] . "'>
									<figcaption>
										<a class='btn-block btn-lg' href='" . $arr_jeux_finis[$i][5] . "'>Revoir</a>
									</figcaption>
								</figure>
							</li>";
						}
							
							
				
				
				echo "  	<li class='col-md-2'></li>
						</ul>
					</div>";
					
					echo "
					<div class='col-md-12' style='margin-top:10px;'>
						<button class='btn btn-block btn-success btn-lg' data-toggle='collapse' data-target='#archives' lx-ripple>Archives</button>	
					</div>";
			}
					
	echo "
				</div>
			</div>
		</div>
	</div>";
	}
?>