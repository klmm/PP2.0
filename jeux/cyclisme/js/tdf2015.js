function Init_TDF()
{
	$(document).on('click', '#calendar a', function(e)
	{
		
		var $this = $(this);
		var id = $(this).attr("value");
		alert(id);
		var postData = "id_cal=" + id;
		$.ajax(
		{
			url : "/lib/sql/getEventById.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
			/*
				bon ca c'est la grosse fonction ajax !!
				Suivant ce que je veux faire ...ça va faire mal ^^
				
				je t'envoie seulement l'id du jour/etape/match dans le calendrier
				A partir de la 3 cas : 
				- l'évenement est à venir : cas 1
				- en cours : cas 2
				- fini : cas 3
				
			*/
			/*
				Suivant ces cas, j'ai besoin d'un certain nombre d'informations, c'est la que les ennuis commencent !
				
				Cas 1: A venir
				- pour zone presentation : 
					- une image( url) -- oui une image par étape ^^ -- représentant arrivée, profil ou un paysage (alpes, pyrennes bretagne, etc...)
					- les infos stats (à voir ce qu'on met : nb km, notre note si c'est vallée/montagne/plaine, etc)
					- un texte de présentation si on veut (mais court, genre 3eme étape reliant...)
				- zone pari joueur :
					- login joueur, si il n'y a rien je comprendrais qu'il n'a pas encore parié
					- si oui, son top 10 et ses stats : prise de risque etc...
				- zone commentaire
					- la même chose que dab
					
				Cas 2 : En cours
				- pour zone presentation : 
					- la meme chose sauf que le texte presentation va devenir : "en cours..." ^^
				- zone pari joueur:
					- la même chose mais pour chaque joueur qui a parié ! Ahahah je rigole déjà d'avance
				- zone com : 
					- idem
					
				Cas 3 : Fini
				- plus de zone présentation (ouf ! je pense pas qu'on fasse de résumé hein :) )
				- zone resultat :
					- camme avant, le top 10 de chaque joueur avec ses stats, score et ordonné suivant le score ^^
					-le top 10 réel
				- zone com
				
				
				
				VOILA !!! ^^ c'est parait pas si insurmontable au final
				Je te laisse donc le loisir / plaisir / casse-tete de mettre cette magnifique réponse en forme !
				
				L'idéal pour moi c'est de recevoir en json avec le system clé/valeur c'est grave sympa.
				Il y a moyen de généraliser quelques trucs comme la zone pari joueur : qu'il y ait juste le pari du joueur ou de tous ça peut etre une liste...

				
				De mon côté ca ira, je ferais ça par zone de rendu : pres, pari, com
				Bien sûr, c'est amené à évoluer ^^ mais ça fera déja une bonne base de travail !
				
			*/			
				
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
			    // rien
			}
		});
		e.preventDefault(); //STOP default action
	});
	
	$(document).on('mouseover', '#calendar a', function(e)
	{
		var id = $(this).attr("value");
		//alert(id);
		//juste affichage de l'image...à voir
	});
}