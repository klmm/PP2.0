<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/jeux/rugby/lib/sql/get_calendrier.php';
    
    $calendrier = get_calendrier_jeu(3);
    
    echo '<h1>Mailing Résultat Rugby Potes</h1>

	<form method="post" action="envoi_resultat.php">
		<label for="id_jeu">ID Jeu</label>
		<input type="text" id="id_jeu" name="id_jeu">
		<label for="id_cal">ID Calendrier</label>
		<input type="text" id="id_cal" name="id_cal">
		<br/>
		<label for="score1">score1</label>
		<input type="text" id="score1" name="score1">
		<br/>
		<label for="essais1">essais1</label>
		<input type="text" id="essais1" name="essais1">
		<br/>
		<label for="score2">score2</label>
		<input type="text" id="score2" name="score2">
		<br/>
		<label for="essais2">essais2</label>
		<input type="text" id="essais2" name="essais2">
		<br/>
		
		<button type="submit"/>Envoyer</button>
	</form><br/>';
    
    foreach($calendrier as $key => $value){
	echo $value['date_debut'] . ' : ' . $value['id'] . '<br/>';
    }

?>