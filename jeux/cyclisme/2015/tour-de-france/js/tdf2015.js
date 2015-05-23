// A CHANGER LORS DE LA CREATION D'UN JEU
var id_jeu = 4;

function Init_Forms_Cyclisme()
{
	$(document).on('click', '#calendar a', function(e)
	{
		
		var $this = $(this);
		var id = $(this).attr("value");
		
		var postData = "id_cal=" + id + "id_jeu=4";
		$.ajax(
		{
			url : "/jeux/cyclisme/lib/render/render_calendrier.php",
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
			url : "/jeux/cyclisme/lib/render/render_prono.php",
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
	
	$("#post-form-jeu").submit(function(e)
	{
		$('.alert').alert('close');
		
		var postData = $(this).serializeArray();
		var formURL = $(this).attr("action");
		var id_cal = $(this).find("#id_cal").attr("value");
		
		$.ajax(
		{
			url : formURL,
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				var result = data.split(';');
				
				if (result[0] == 'success'){
					//chargement des coms
					getAllComsJeu(id_jeu,id_cal);
				}else {
					$( "#post-container" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+result[0]+'</div>' );
				}
				$('form')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$( "#post-container" ).append( '<div class="alert alert-danger alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+errorThrown+'</div>' );
			}
		});
		e.preventDefault(); //STOP default action
		
	});
	
	

	
	$(document).on('click', '.btn-like-jeu', function(e)
	{
		var $this = $(this);
		var id_cal = $(this).parent().parent().find("#id-cal").attr("value");
		var id_comm = $(this).parent().parent().find("#id-com").attr("value");
		var $antithis = $(this).parent().find(".btn-dislike-jeu");
		var postData = "id_jeu=" + id_jeu + "&type=1&id_cal=" + id_cal + "&id_comm=" + id_comm;
		
		$.ajax(
		{
			url : "/lib/form/post_like_jeu.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				if (data == 'success'){
				    $this.addClass("disabled");
				    $this.blur();				
				    $antithis.addClass("disabled");
				    var c = $this.find(".count").html().valueOf();
				    c++;
				    $this.find(".count").text(c);
					
				}
				else{
				    
				}
				
				//$('form')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
			    // rien
			}
		});
		e.preventDefault(); //STOP default action
	});
	
	$(document).on('click', '.btn-dislike-jeu', function(e)
	{
		var $this = $(this);
		var id_com = $(this).parent().parent().find("#id-com").attr("value");
		var id_cal = $(this).parent().parent().find("#id-cal").attr("value");
		var $antithis = $(this).parent().find(".btn-like-jeu");
		var postData = "id_comm=" + id_com + "&type=0&id_jeu=" + id_jeu + "&id_cal=" + id_cal;
		$.ajax(
		{
			url : "/lib/form/post_like_jeu.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				if (data == 'success'){
				    $this.addClass("disabled");
					$this.blur();				
					$antithis.addClass("disabled");
					var c = $this.find(".count").html().valueOf();
					c++;
					$this.find(".count").text(c);
				}
				//$('form')[0].reset();
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

function getAllComsJeu(id_jeu,id_cal) {
	
	
	var formURL = "/lib/render/render_commentaires_jeu.php";
	var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal;
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{
			var result = $.parseJSON(data);
			var coms = result.commentaires;
			var likes = result.likes;
			var connecte = result.connecte;
			var likedisable, dislikedisable;
			var no_like = false;

			$( ".contact-form" ).empty();
			if(connecte){
			     $( ".contact-form" ).append('<div class="col-md-10 col-md-offset-1">'+
				    '<input name="id_jeu" id="id_jeu" type="text" class="hidden" required="" value="' + id_jeu + '"/>'+
				    '<input name="id_cal" id="id_cal" type="text" class="hidden" required="" value="' + id_cal + '"/>'+
				    '<button type="submit" class="btn btn-primary pull-right" style="padding:10px;margin-bottom:10px;width:200px;">'+
					'<span>Poster</span>'+
				    '</button>'+
				    '<textarea id="contenu" class="form-control" rows="5" name="contenu" placeholder="Votre message"></textarea>'+				
				'</div>');
			}
			else{
			    
			}
			
			$( ".com-container" ).empty();
			if(coms == null){			    
			    $( ".com-container" ).append(  'AUCUN COMMENTAIRE');
			    return;
			}
			
			if(likes == null){
			    no_like = true;
			}
			
			for (var i = 0; i < coms.length; i++) {
				var object = coms[i];
				
				if(no_like == false){
				    if(likes.hasOwnProperty(object['id_commentaire']))	{
					    if(likes[object['id_commentaire']] == 1){
						    likedisable = "disabled";
						    dislikedisable = "disabled";
					    } else {
						    likedisable = "disabled";
						    dislikedisable = "disabled";
					    }

				    } else {
					    likedisable = "";
					    dislikedisable = "";
				    }
				}
				
				$( ".com-container" ).append(  '<div class="like-form col-md-10 col-md-offset-1">' +		
				   
						'<p id="id-cal" value="' + id_cal + '" class="hidden"></p>' +
						'<p id="id-com" value="' + object['id_commentaire'] + '" class="hidden"></p>' +
						'<div class="comment-box clearfix"><p class="user col-md-4 col-sm-4 col-xs-4">' + object['joueur'] + '</p>' +
						    '<p class="time col-md-8 col-sm-8 col-xs-8">' + object['dateheurepub_conv'] + '</p>' +
						    '<p class="time pull-right hidden">' + object['dateheurepub_court'] + '</p>' +
						    '<p class="comment col-md-12 col-sm-12 col-xs-12">' + object['contenu'] + '</p>'+
						'</div>' +

						'<div class="like-box clearfix">' +
						    '<button class="btn-dislike-jeu btn btn-danger pull-right '+ dislikedisable +'" style="margin-left:10px;">' +
							'<span class="glyphicon glyphicon-thumbs-down" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>' +
							'<span class="count">' + object['nbdislikes'] + '</span>' +
						    '</button>' +
						    '<button class="btn-like-jeu btn btn-success pull-right '+ likedisable +'" style="margin-left:10px;">' +
							'<span class="glyphicon glyphicon-thumbs-up" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>' +
							'<span class="count">' + object['nblikes'] + '</span>' +
						    '</button>' + 
						'</div>' +
				'</div>' );

			}
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			alert('error');//do nothing
		}
	});
};