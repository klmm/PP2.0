<?php
	
	$mails = [ 'eliane.alferez@free.fr','tom.vallet@lamache.org','adrienbrazier@hotmail.com','beemer@hotmail.fr','mathilde.moulin@insa-lyon.fr','sylvain.jd69@gmail.com','stephane.brat@orange.fr','linder.jeanmarc@gmail.com','bernardinmarie@hotmail.fr','lololelyonnais@hotmail.com','philippe.veyret@neuf.fr','yoann.de.mauroy@wanadoo.fr','alexis.sadok@gmail.com','gauthier2501@hotmail.com','mcoquard@etu.isara.fr','trivero.oscar@yahoo.fr','baulandmaxime@wanadoo.fr',
	'andreadicarlo15@yahoo.fr','girault.al@gmail.com',
	'beemer@hotmail.fr','titinemou@gmail.com','colindeb07@orange.fr',
    'arthur_bochard@hotmail.com','ptit_gars256@hotmail.fr',
    'edward.woodhouse@gmail.com','fanol68@aol.com','mathilde.moulin@insa-lyon.fr',
    'baulandmaxime@wanadoo.fr','mathildedesutter@gmail.com',
    'trivero.oscar@yahoo.fr','sylvain.jd69@gmail.com',
	'peur.de.rien.blues@free.fr','amo69ol@hotmail.com',
    'martin.emeric@gmail.com','tipiaf69230@live.fr','cbrat69@hotmail.fr',
	'cedric.ganguia@hotmail.fr','luiiiis@hotmail.fr',
    'quentin.andre90@gmail.com','clairedubouis@hotmail.com',
    'julien-delorme@hotmail.fr','sophiedelorme@msn.com','rousseau.xavier@gmail.com',
    'lionel.landy@ac-lyon.fr','luca.monnery@gmail.com','ptit_gars256@hotmail.fr',
    'maret.django@hotmail.fr','funkybabtou@hotmail.com','arnouch001@hotmail.fr',
    'f.laroche@flip-elec.fr','fredholm@outlook.com','le-hir.anaelle@outlook.fr',
    'louisdu98@live.fr','scerato@hotmail.fr',
	'baulandmaxime@wanadoo.fr','peur.de.rien.blues@free.fr',
    'dagornariane@orange.fr','amo69ol@hotmail.com','martin.emeric@gmail.com',
    'r.demonte@flip-elec.fr','paulrepellin@hotmail.fr','tipiaf69230@live.fr',
    'cbrat69@hotmail.fr','nicolebalouzat@gmail.com','louisjuni@hotmail.fr',
    'sanchesjessica@hotmail.com','free-ways@wanadoo.fr','olivieraudoin@gmail.com',
    'eileen.marchall@gmail.com',
	'thomas.cerato@gmail.com','duche8@hotmail.fr',
    'louis.dominique.giraud@gmail.com','b.escaich@gmail.com','colindeb07@orange.fr',
    'scerato@hotmail.fr','patrick.monassier@free.fr','kevin.monnery@orange.fr',
    'nyol-69@hotmail.fr','martineetroland@hotmail.fr','famillecerato@gmail.com',
    'romain.cerato@hotmail.fr','camille.pascual1@gmail.com',
    'lequin.arthur@gmail.com','mcoquard@etu.isara.fr','bossman692@hotmail.com',
    'crevetta@hotmail.fr',
	'thomas.m.volcom@hotmail.fr','j.perrin.lyon@gmail.com',
    'marting69@hotmail.com','g.faure@flip-elec.fr',
    'andreadicarlo15@yahoo.fr','lagounelyon@hotmail.fr',
    'arthur_bochard@hotmail.com','hans.brisch@laposte.net','eddodjerbwa@gmail.com',
    'oceane.alliod@hotmail.fr',
    'chris-lloyd.canal@centrale-marseille.fr','forti22@laposte.net',
    'arthur.guignabert@gmail.com','eloirolez@hotmail.com','fanol68@aol.com',
    'guillaume.mazard@gmail.com',
	'le-hir.anaelle@outlook.fr','f.laroche@flip-elec.fr',
    'bouboule_br@hotmail.fr','arnouch001@hotmail.fr','funkybabtou@hotmail.com',
    'jcdeglesne@free.fr','clems691@hotmail.fr','lebosdu63@hotmail.fr',
    'maret.django@hotmail.fr','ptit_gars256@hotmail.fr','luca.monnery@gmail.com',
    'aragones.matthieu@hotmail.com','lionel.landy@ac-lyon.fr',
    'dimitrimercier@laposte.net','rousseau.xavier@gmail.com',
    'sophiedelorme@msn.com','julien-delorme@hotmail.fr',
    'recordier.francois@hotmail.fr'];
	
	$mails2 = array_unique($mails);
	
	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
	$bdd = new Connexion();
	$db = $bdd->getDB();

	//On fait la requete sur le login
	$sql = "SELECT *
			FROM Joueurs
			WHERE mail=?";
	$prep = $db->prepare($sql);
	
	
	foreach($mails2 as $key => $mail){
		$prep->bindValue(1,$mail,PDO::PARAM_INT);
		$prep->execute();
		$prep->setFetchMode(PDO::FETCH_OBJ);
		
		if( !$enregistrement = $prep->fetch() )
		{
			$mails3[] = $mail;
		}
	}
	
	
	$db = null;
	
	echo 'mails : ' . sizeof($mails) . '<br/><br/>';
	print_r($mails);
	echo '<br/><br/>';
	
	echo 'mails uniques: ' . sizeof($mails2) . '<br/><br/>';
	print_r($mails2);
	echo '<br/><br/>';
	
	echo 'mails pas inscrits: ' . sizeof($mails3) . '<br/><br/>';
	print_r($mails3);
	echo '<br/><br/>';
	
	$i=0;
	foreach($mails3 as $key=>$value){
		$i++;
		echo $value . '; ';
		
		if($i == 18){
			$i =0;
			echo '<br/><br/>';
		}
	}
?>