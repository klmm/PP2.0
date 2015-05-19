function Init_TDF()
{
	$(document).on('click', '#calendar a', function(e)
	{
		
		var $this = $(this);
		var id = $(this).attr("value");
		
		var postData = "id_cal=" + id + "id_jeu=4";
		$.ajax(
		{
			url : "/lib/render/render_calendrier.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				var result = $.parseJSON(data);
				var calendrier = result.calendrier;
				var prono_joueur = result.prono_joueur;
				var pronos = result.pronos;
				var cyclistes = result.cyclistes;
				var equipes = result.equipes;
				
				render_pres_panel(calendrier);
				
				
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
	
	$(document).on('click', '#scores > tr', function(e)
	{
		var $this = $(this);
		var joueur = $(this).find(".table-name").html().valueOf();
		var id_jeu = 4;
		var id_cal = $(this).parent().attr("id");
	
		var postData = "id_cal=" + id_cal + "id_jeu=" + id_jeu + "joueur=" + joueur;
		$.ajax(
		{
			url : "/lib/render/render_prono.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				var result = $.parseJSON(data);
				var calendrier = result.calendrier;
				var pronos = result.pronos;
				var cyclistes = result.cyclistes;
				var equipes = result.equipes;
				
			
				
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
			    // rien
			}
		});
		e.preventDefault(); //STOP default action
	});
}

function render_pres_panel(calendrier){
	
}

function Build_Chart(el, result){
	
	var width = 960,
		height = 500,
		radius = Math.min(width, height) / 2;

	var color = d3.scale.ordinal()
		.range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

	var arc = d3.svg.arc()
		.outerRadius(radius - 10)
		.innerRadius(0);

	var pie = d3.layout.pie()
		.sort(null)
		.value(function(d) { return d.population; });

	var svg = d3.select("body").append("svg")
		.attr("width", width)
		.attr("height", height)
	  .append("g")
		.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

	d3.csv("data.csv", function(error, data) {

	  data.forEach(function(d) {
		d.population = +d.population;
	  });

	  var g = svg.selectAll(".arc")
		  .data(pie(data))
		.enter().append("g")
		  .attr("class", "arc");

	  g.append("path")
		  .attr("d", arc)
		  .style("fill", function(d) { return color(d.data.age); });

	  g.append("text")
		  .attr("transform", function(d) { return "translate(" + arc.centroid(d) + ")"; })
		  .attr("dy", ".35em")
		  .style("text-anchor", "middle")
		  .text(function(d) { return d.data.age; });

	});
}