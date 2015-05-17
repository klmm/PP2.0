<?php
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/lib/fonctions/dates.php');
    
	function get_cyclistes_inscriptions($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_athlete
				WHERE EXISTS (
					SELECT id_equipe
					FROM cyclisme_inscription_equipe
					WHERE (cyclisme_inscription_equipe.id_equipe=cyclisme_athlete.id_cyclisme_equipe AND (id_cal=0 OR id_cal=?) AND id_jeu=?)
					)
				ORDER BY id_cyclisme_equipe ASC, nom ASC";
					
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
		$prep->execute();

		$sql2 = "SELECT *
				FROM cyclisme_inscription_athlete
				WHERE id_athlete=? AND (id_cal=? or id_cal=0) AND id_jeu=?";
					
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		
		
		// Parcours des cyclistes dont l'�quipe est inscrite
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$id_cycliste = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_athlete'] = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_equipe'] = $enregistrement->id_cyclisme_equipe;
			$arr[$id_cycliste]['nom'] = $enregistrement->nom;
			$arr[$id_cycliste]['prenom'] = $enregistrement->prenom;
			$arr[$id_cycliste]['date_naissance'] = $enregistrement->date_naissance;
			$arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
			$arr[$id_cycliste]['note_paves'] = $enregistrement->note_paves;
			$arr[$id_cycliste]['note_vallons'] = $enregistrement->note_vallons;
			$arr[$id_cycliste]['note_montagne'] = $enregistrement->note_montagne;
			$arr[$id_cycliste]['note_sprint'] = $enregistrement->note_sprint;
			$arr[$id_cycliste]['note_clm'] = $enregistrement->note_clm;
			$arr[$id_cycliste]['photo'] = $enregistrement->photo;
			$arr[$id_cycliste]['id_pays'] = $enregistrement->id_pays;
			$arr[$id_cycliste]['note_baroudeur'] = $enregistrement->note_baroudeur;
			
			//Cycliste inscrit ?
			$prep2->bindValue(1,$id_cycliste,PDO::PARAM_INT);
			$prep2->bindValue(2,$id_cal,PDO::PARAM_INT);
			$prep2->bindValue(3,$id_jeu,PDO::PARAM_INT);
			$prep2->execute();
			$enregistrement2 = $prep2->fetch();
			if($enregistrement2){
				$arr[$id_cycliste]['inscrit'] = 1;
				$arr[$id_cycliste]['forme'] = $enregistrement2->forme;
				$arr[$id_cycliste]['id_equipe_course'] = $enregistrement2->id_equipe;
				$arr[$id_cycliste]['abandon'] = $enregistrement2->abandon;
			}
			else{
				$arr[$id_cycliste]['inscrit'] = 0;
			}
			
			$i++;
		}
		
		return $arr;
	}
	
	function get_cyclistes_inscrits($id_jeu, $id_cal){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		
		$bdd = new Connexion();
		$db = $bdd->getDB();

		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_athlete
				WHERE EXISTS (
					SELECT id_athlete
					FROM cyclisme_inscription_athlete
					WHERE (id_cyclisme_athlete=id_athlete AND (id_cal=0 OR id_cal=?) AND id_jeu=?)
					)
				ORDER BY id_cyclisme_equipe ASC, nom ASC";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		$prep->bindValue(1,$id_cal,PDO::PARAM_INT);
		$prep->bindValue(2,$id_jeu,PDO::PARAM_INT);
		$prep->execute();
		
		$sql2 = "SELECT *
				FROM cyclisme_inscription_athlete
				WHERE id_athlete=? AND (id_cal=? or id_cal=0) AND id_jeu=?";
					
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		
		//On met les articles dans le tableau
		$i = 0;
		while( $enregistrement = $prep->fetch() )
		{
			$id_cycliste = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_athlete'] = $enregistrement->id_cyclisme_athlete;
			$arr[$id_cycliste]['id_cyclisme_equipe'] = $enregistrement->id_cyclisme_equipe;
			$arr[$id_cycliste]['nom'] = $enregistrement->nom;
			$arr[$id_cycliste]['prenom'] = $enregistrement->prenom;
			$arr[$id_cycliste]['date_naissance'] = $enregistrement->date_naissance;
			$arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
			$arr[$id_cycliste]['note_paves'] = $enregistrement->note_paves;
			$arr[$id_cycliste]['note_vallons'] = $enregistrement->note_vallons;
			$arr[$id_cycliste]['note_montagne'] = $enregistrement->note_montagne;
			$arr[$id_cycliste]['note_sprint'] = $enregistrement->note_sprint;
			$arr[$id_cycliste]['note_clm'] = $enregistrement->note_clm;
			$arr[$id_cycliste]['photo'] = $enregistrement->photo;
			$arr[$id_cycliste]['id_pays'] = $enregistrement->id_pays;
			$arr[$id_cycliste]['note_baroudeur'] = $enregistrement->note_baroudeur;
			$i++;
			
			$prep2->bindValue(1,$id_cycliste,PDO::PARAM_INT);
			$prep2->bindValue(2,$id_cal,PDO::PARAM_INT);
			$prep2->bindValue(3,$id_jeu,PDO::PARAM_INT);
			$prep2->execute();
			$enregistrement2 = $prep2->fetch();
			
			$arr[$id_cycliste]['inscrit'] = 1;
			$arr[$id_cycliste]['forme'] = $enregistrement2->forme;
			$arr[$id_cycliste]['id_equipe_course'] = $enregistrement2->id_equipe;
			$arr[$id_cycliste]['abandon'] = $enregistrement2->abandon;
		}
		
		return $arr;
	}
	
	function get_cyclistes_jeu_tab_id($id_jeu, $id_cal, $tab_id){
		// On �tablit la connexion avec la base de donn�es
		require_once($_SERVER['DOCUMENT_ROOT'] . '/admin/titi.php');
		$bdd = new Connexion();
		$db = $bdd->getDB();

		
		
		
		//On pr�pare la requ�te pour aller chercher les articles
		$sql = "SELECT *
				FROM cyclisme_athlete
				WHERE id_cyclisme_athlete=?";
		$prep = $db->prepare($sql);
		$prep->setFetchMode(PDO::FETCH_OBJ);
		
		
		
		
		$sql2 = "SELECT *
				FROM cyclisme_inscription_athlete
				WHERE id_athlete=? AND (id_cal=? or id_cal=0) AND id_jeu=?";
					
		$prep2 = $db->prepare($sql2);
		$prep2->setFetchMode(PDO::FETCH_OBJ);
		
		
		
		$sql3 = "SELECT *
				FROM pays
				WHERE Id_Pays=?";
					
		$prep3 = $db->prepare($sql3);
		$prep3->setFetchMode(PDO::FETCH_OBJ);
		
		
		
		$sql4 = "SELECT *
				FROM cyclisme_equipe
				WHERE id_cyclisme_equipe=?";
					
		$prep4 = $db->prepare($sql4);
		$prep4->setFetchMode(PDO::FETCH_OBJ);
		
		
		
		//On met les articles dans le tableau
		$nb_cycliste = sizeof($tab_id);
		if ($nb_cycliste > 0){
		    for ($i=0;$i<$nb_cycliste;$i++)
		    {
			    $prep->bindValue(1,$tab_id[$i],PDO::PARAM_INT);
			    $prep->execute();

			    $enregistrement = $prep->fetch();
			    
			    if($enregistrement){
				$id_cycliste = $enregistrement->id_cyclisme_athlete;
				$arr[$id_cycliste]['id_cyclisme_athlete'] = $enregistrement->id_cyclisme_athlete;
				$arr[$id_cycliste]['id_equipe_actuelle'] = $enregistrement->id_cyclisme_equipe;
				$arr[$id_cycliste]['nom'] = $enregistrement->nom;
				$arr[$id_cycliste]['prenom'] = $enregistrement->prenom;
				$arr[$id_cycliste]['date_naissance'] = $enregistrement->date_naissance;
				$arr[$id_cycliste]['date_naissance_fr'] = date_naissance_sql_to_fr($arr[$id_cycliste]['date_naissance']);
				$arr[$id_cycliste]['note_paves'] = $enregistrement->note_paves;
				$arr[$id_cycliste]['note_vallons'] = $enregistrement->note_vallons;
				$arr[$id_cycliste]['note_montagne'] = $enregistrement->note_montagne;
				$arr[$id_cycliste]['note_sprint'] = $enregistrement->note_sprint;
				$arr[$id_cycliste]['note_clm'] = $enregistrement->note_clm;
				$arr[$id_cycliste]['photo'] = $enregistrement->photo;
				$id_pays = $enregistrement->id_pays;
				$arr[$id_cycliste]['id_pays'] = $id_pays;
				$arr[$id_cycliste]['note_baroudeur'] = $enregistrement->note_baroudeur;

				$prep2->bindValue(1,$id_cycliste,PDO::PARAM_INT);
				$prep2->bindValue(2,$id_cal,PDO::PARAM_INT);
				$prep2->bindValue(3,$id_jeu,PDO::PARAM_INT);
				$prep2->execute();
				$enregistrement2 = $prep2->fetch();
				
				$id_equipe_actuelle = $enregistrement2->id_equipe;
				$arr[$id_cycliste]['id_equipe_course'] = $id_equipe_actuelle;
				$arr[$id_cycliste]['abandon'] = $enregistrement2->abandon;
				$arr[$id_cycliste]['inscrit'] = 1;
				$arr[$id_cycliste]['forme'] = $enregistrement2->forme;
				
				$prep3->bindValue(1,$id_pays,PDO::PARAM_INT);
				$prep3->execute();
				$enregistrement3 = $prep3->fetch();

				$arr[$id_cycliste]['pays_nom'] = $enregistrement3->Nom;
				$arr[$id_cycliste]['pays_drapeau_icone'] = $enregistrement3->drapeau_icone;
				$arr[$id_cycliste]['pays_drapeau_petit'] = $enregistrement3->drapeau_petit;
				$arr[$id_cycliste]['pays_drapeau_moyen'] = $enregistrement3->drapeau_moyen;
				$arr[$id_cycliste]['pays_drapeau_grand'] = $enregistrement3->drapeau_grand;
				$arr[$id_cycliste]['pays_abreviation'] = $enregistrement3->Abreviation;;
				
				$prep4->bindValue(1,$id_equipe_actuelle,PDO::PARAM_INT);
				$prep4->execute();
				$enregistrement4 = $prep4->fetch();

				$arr[$id_cycliste]['equipe_niveau'] = $enregistrement4->niveau;
				$arr[$id_cycliste]['equipe_nom_complet'] = $enregistrement4->nom_complet;
				$arr[$id_cycliste]['equipe_nom_courant'] = $enregistrement4->nom_courant;
				$arr[$id_cycliste]['equipe_nom_court'] = $enregistrement4->nom_court;
				$arr[$id_cycliste]['equipe_photo'] = $enregistrement4->photo;
			    }
		    }
		
		    return $arr;
		}
		else{
		    return null;
		}
	}
?>