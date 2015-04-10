<?php
	//--------------------------------------FONCTIONS--------------------------------------//
	include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/cycliste.php';
	include $_SERVER['DOCUMENT_ROOT'] . '/jeux/cyclisme/lib/sql/equipe.php';
	//-------------------------------------------------------------------------------------//
	
	$id_cal = $_GET['id_cal'];
	$id_jeu = $_GET['id_jeu'];
	
	$arr1 = get_cyclistes_inscrits($id_jeu, $id_cal);
	$nb1 = sizeof($arr1);
	
	
	$arr2 = get_cyclistes_non_inscrits($id_jeu, $id_cal);
	$nb2 = sizeof($arr2);
	
	$arr3 = get_equipes_inscrites($id_jeu, $id_cal);
	$nb3 = sizeof($arr3);
	
	$arr4 = get_equipes_non_inscrites($id_jeu, $id_cal);
	$nb4 = sizeof($arr4);
	
	
	echo '<html>';
	
	echo '<h1>Cyclistes</h1>';
	
	echo '<table style="width:100%">
		  <tr>
			<td valign="top">';
	echo '<form method="post" action="lib/sql/update_inscriptions_cyclistes.php">';
	echo '<input type="text" name="id_jeu" value="' . $id_jeu . '" hidden><br/>';
	echo '<input type="text" name="id_cal" value="' . $id_cal . '" hidden><br/>';
	
	
	for ($i = 0; $i < $nb1; $i++) {
		echo '<input type="checkbox" name="cyclistes[]" value="' . $arr1[$i][0] . '" checked>' . $arr1[$i][3] . ' ' . $arr1[$i][2] . '<br/>';
	}
	
	for ($i = 0; $i < $nb2; $i++) {
		echo '<input type="checkbox" name="cyclistes[]" value="' . $arr2[$i][0] . '">' . $arr2[$i][3] . ' ' . $arr2[$i][2] . '<br/>';
	}
	
	echo '<input type="submit" value="OK">';
	echo '</form>
		</td>
		<td valign="top">';
	
	
	
	echo '<h1>Equipes</h1>';
	echo '<form method="post" action="lib/sql/update_inscriptions_equipes.php">';
	echo '<input type="text" name="id_jeu" value="' . $id_jeu . '" hidden><br/>';
	echo '<input type="text" name="id_cal" value="' . $id_cal . '" hidden><br/>';
	
	
	for ($i = 0; $i < $nb3; $i++) {
		echo '<input type="checkbox" name="equipes[]" value="' . $arr3[$i][0] . '" checked>' . $arr3[$i][2] . '<br/>';
	}
	
	for ($i = 0; $i < $nb4; $i++) {
		echo '<input type="checkbox" name="equipes[]" value="' . $arr4[$i][0] . '">' . $arr4[$i][2] . '<br/>';
	}
	
	echo '<input type="submit" value="OK">';
	echo '</form>';
	
	
	
	echo ' </td>
	</tr>
		</table>
		</html>';
?>