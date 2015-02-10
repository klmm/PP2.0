<?php
	include 'sql/jeux/get_jeux.php';
	
	$arr_jeux = get_jeux_avenir();
	$nb_jeux_avenir = sizeof($arr_jeux);
	
	echo '
	<div class="section" style="background-color: rgb(166, 109, 179); margin-bottom:80px;">	
		<div class="container marketing">	
			<div class="sectionSide" style="padding-bottom: 15px; color:white;">
				<h2 class="section-heading">Point info</h2>
			</div>
			<div class="row">
				<div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2" style="text-align: center;">
					<div id="twitter" style="padding:40px;">
						<a class="twitter-timeline" href="https://twitter.com/ParionsPotes" width="100%" height="400" data-widget-id="463784722661777409">Tweets de @ParionsPotes</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</div>
				</div>';
				
				if ($nb_jeux_avenir > 0){
				
					echo '
				<div class="col-md-6 col-md-offset-0 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
					<ul class="grid cs-style-1">';
					
					for ($i = 0; $i <= $nb_jeux_avenir; $i++) {
						echo '
						<li>
							<figure>
								<img src="' . $arr[$i][6] . '" alt="Pub">
								<figcaption>
									<h3>Du ' . date_format($arr[$i][1], 'd-m-Y') . ' au ' . date_format($arr[$i][2], 'd-m-Y'); . ' soyez-prêts !</h3>
									<a href="' . $arr[$i][5] . '" style="display:none;"></a>
								</figcaption>
							</figure>
						</li>';
						
					}
					
					echo '
					</ul>
				</div>';
				}
				
echo'
			</div>
		</div>
	</div>';
?>