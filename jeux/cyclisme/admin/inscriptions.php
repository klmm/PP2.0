<?php
	//--------------------------------------FONCTIONS--------------------------------------//
	include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_cycliste.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/get_equipe.php';
	//-------------------------------------------------------------------------------------//
	
	
	// ---------- Récupération des paramètres et des cyclistes/équipes inscrits/non inscrits   ----------------//
	$id_cal = $_GET['id_cal'];
	$id_jeu = $_GET['id_jeu'];
	$annee = $_GET['annee'];

	$cyclistes_tous = get_cyclistes_inscriptions($id_jeu, $id_cal);
	$nb_cyclistes_tous = sizeof($cyclistes_tous);
	
	$arr3 = get_equipes_inscrites($id_jeu, $id_cal);
	$nb3 = sizeof($arr3);
	
	$arr4 = get_equipes_non_inscrites($id_jeu, $id_cal, $annee);
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
	echo '<input type="text" name="annee" value="' . $annee . '" hidden>';
	
	$id_equipe_en_cours = 0;
	
	foreach($cyclistes_tous as $id_cycliste=>$cycliste) {
		$id_equipe_new = $cycliste['id_cyclisme_equipe'];
		if ($id_equipe_new != $id_equipe_en_cours){
			$arr_equipe_affichage = get_equipe_id($id_equipe_new);
			$id_equipe_en_cours = $id_equipe_new;
			echo '<h2>' . $arr_equipe_affichage['nom_courant'] . '</h2><br/>';
		}
		if ($cycliste['inscrit'] == 0){
			echo '  <input type="checkbox" name="cyclistes[]" value="' . $id_cycliste . '">' . $cycliste['prenom'] . ' ' . $cycliste['nom'] . '
                        <input type="text" name="equipe' . $id_cycliste .'" value="' . $cycliste['id_cyclisme_equipe'] .'" hidden>        
			<input type="text" name="forme' . $id_cycliste .'" value="80" maxlength="3" size="3"><br/>';
		}
		else{
			echo '  <input type="checkbox" name="cyclistes[]" value="' . $id_cycliste . '" checked>' . $cycliste['prenom'] . ' ' . $cycliste['nom'] . '
                        <input type="text" name="equipe' . $id_cycliste .'" value="' . $cycliste['id_cyclisme_equipe'] .'" hidden>        
			<input type="text" name="forme' . $id_cycliste .'" value="' . $cycliste['forme'] .'" maxlength="3" size="3"><br/>';
		}
	}

	
	echo '      <input type="submit" value="OK">
                </form>
            </td>
            <td valign="top">';
	
	
	
	
	echo '  <h1>Equipes</h1>
                <form method="post" action="/jeux/cyclisme/lib/form/update_inscriptions_equipes.php">
                    <input type="text" name="id_jeu" value="' . $id_jeu . '" hidden>
                    <input type="text" name="id_cal" value="' . $id_cal . '" hidden>
		    <input type="text" name="annee" value="' . $annee . '" hidden>';
	
	
	foreach($arr3 as $id_equipe=>$equipe) {
		echo '<input type="checkbox" name="equipes[]" value="' . $id_equipe . '" checked>' . $equipe['nom_courant'] . '<br/>';
	}
	
	foreach($arr4 as $id_equipe=>$equipe) {
		echo '<input type="checkbox" name="equipes[]" value="' . $id_equipe . '">' . $equipe['nom_courant'] . '<br/>';
	}
	
	echo '<input type="submit" value="OK">';
	echo '</form>';
	
	
	
	echo ' </td>
	</tr>
		</table>
		</html>';
?>