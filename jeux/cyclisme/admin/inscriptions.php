<?php
	//--------------------------------------FONCTIONS--------------------------------------//
	include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/cycliste.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/equipe.php';
	//-------------------------------------------------------------------------------------//
	
	
	// ---------- Récupération des paramètres et des cyclistes/équipes inscrits/non inscrits   ----------------//
	$id_cal = $_GET['id_cal'];
	$id_jeu = $_GET['id_jeu'];

	$cyclistes_tous = get_cyclistes_inscriptions($id_jeu, $id_cal);
	$nb_cyclistes_tous = sizeof($cyclistes_tous);
	
	$arr3 = get_equipes_inscrites($id_jeu, $id_cal);
	$nb3 = sizeof($arr3);
	
	$arr4 = get_equipes_non_inscrites($id_jeu, $id_cal);
	$nb4 = sizeof($arr4);
	//---------------------------------------------------------------------------------------------------------//

	
	
	
	
	echo '<html>';
	
	echo '<h1>Cyclistes</h1>';
	
	echo '<table style="width:100%">
		  <tr>
			<td valign="top">';
	echo '<form method="post" action="/jeux/cyclisme/lib/form/update_inscriptions_cyclistes.php">';
	echo '<input type="text" name="id_jeu" value="' . $id_jeu . '" hidden>';
	echo '<input type="text" name="id_cal" value="' . $id_cal . '" hidden>';
	
	$id_equipe_en_cours = 0;
	
	for ($i = 0; $i < $nb_cyclistes_tous; $i++) {
		$id_equipe_new = $cyclistes_tous[$i]['id_cyclisme_equipe'];
		if ($id_equipe_new != $id_equipe_en_cours){
			$arr_equipe_affichage = get_equipe_id($id_equipe_new);
			$id_equipe_en_cours = $id_equipe_new;
			echo '<h2>' . $arr_equipe_affichage['nom_courant'] . '</h2><br/>';
		}
		if ($cyclistes_tous[$i]['inscrit'] == 0){
			echo '  <input type="checkbox" name="cyclistes[]" value="' . $cyclistes_tous[$i]['id_cyclisme_athlete'] . '">' . $cyclistes_tous[$i]['prenom'] . ' ' . $cyclistes_tous[$i]['nom'] . '
                                <input type="text" name="forme' . $cyclistes_tous[$i]['id_cyclisme_athlete'] .'" value="80" maxlength="3" size="3"><br/>';
		}
		else{
			echo '  <input type="checkbox" name="cyclistes[]" value="' . $cyclistes_tous[$i]['id_cyclisme_athlete'] . '" checked>' . $cyclistes_tous[$i]['prenom'] . ' ' . $cyclistes_tous[$i]['nom'] . '
                                <input type="text" name="forme' . $cyclistes_tous[$i]['id_cyclisme_athlete'] .'" value="' . $cyclistes_tous[$i]['forme'] .'" maxlength="3" size="3"><br/>';
		}
	}

	
	echo '      <input type="submit" value="OK">
                </form>
            </td>
            <td valign="top">';
	
	
	
	
	echo '  <h1>Equipes</h1>
                <form method="post" action="/jeux/cyclisme/lib/form/update_inscriptions_equipes.php">
                    <input type="text" name="id_jeu" value="' . $id_jeu . '" hidden>
                    <input type="text" name="id_cal" value="' . $id_cal . '" hidden>';
	
	
	for ($i = 0; $i < $nb3; $i++) {
		echo '<input type="checkbox" name="equipes[]" value="' . $arr3[$i]['id_cyclisme_equipe'] . '" checked>' . $arr3[$i]['nom_courant'] . '<br/>';
	}
	
	for ($i = 0; $i < $nb4; $i++) {
		echo '<input type="checkbox" name="equipes[]" value="' . $arr4[$i]['id_cyclisme_equipe'] . '">' . $arr4[$i]['nom_courant'] . '<br/>';
	}
	
	echo '<input type="submit" value="OK">';
	echo '</form>';
	
	
	
	echo ' </td>
	</tr>
		</table>
		</html>';
?>