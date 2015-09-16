// A CHANGER LORS DE LA CREATION D'UN JEU
var id_jeu = 3;

function Init_Forms_Rugby()
{
	$(document).on('click', '#calendar a', function(e)
	{    
	    $('#list-cal li').each(function() {
		$(this).removeClass("active");
	    });
	    
	    $(this).parent().addClass("active");
	    
	    var id = $(this).attr("value");	
	    render_calendrier(id);
	    getAllComs(0,id_jeu,id,0);
	    
	    document.location.href='#resultats';
	});
	
	$(document).on('click', '.scores tr', function(e)
	{
		var joueur = $(this).find(".table-name").html().valueOf();
		var id_cal = $(this).parent().parent().attr("id");

		render_prono_autre(id_cal,joueur);
	});
	$(document).on('click', '#envoi_pari', function(e) {
	   
	    var id_cal =  $(this).parent().parent().find("#id_cal").attr("value");
		
	    var e1 = $(this).parent().parent().find("#essais1").text();
	    var e2 = $(this).parent().parent().find("#essais2").text();
	    var p1 = $(this).parent().parent().find("#score1").text();
	    var p2 = $(this).parent().parent().find("#score2").text();
	    var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal + "&essais1=" + e1  + "&essais2=" + e2  + "&score1=" + p1  + "&score2=" + p2;
	    $.ajax(
	    {
		
		url : "/jeux/rugby/lib/form/envoi_prono.php",
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{	 
		    var result = $.parseJSON(data);
		    var msg = result.msg;
		    var msg_final;
		    var res = result.resultat;
		    		        
		    if(msg != null && res != null){
			$( ".alert-msg-prono" ).empty();
			
			if(res == false){
			    msg_final = '<div class="alert alert-danger alert-dismissible" style="margin-bottom:15px;" role="alert">' +
				    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
					'<span aria-hidden="true">&times;</span>' +
				    '</button>' + msg +
				    '</div>';
			}
			else{
			    msg_final = '<div class="alert alert-success alert-dismissible" style="margin-bottom:15px;" role="alert">' +
				    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
					'<span aria-hidden="true">&times;</span>' +
				    '</button>' + msg +
				    '</div>';
			}
			
			
			$( ".alert-msg-prono" ).append(msg_final);
		    }
		    else{
			location.reload();
		    }
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
		    alert('error');//do nothing
		}
	    });
	});
	
	$(document).on('click', '.points-list>li', function(e)
	{    
		var $form = $(this).closest('#pari-form');
		var $points_elems = $form.find('.points-combo').find('.btn-default');
		$(this).parent().parent().find('.btn-default').text($(this).text());
		clicComboPoints($points_elems);
	});
	$(document).on('click', '.tries-list>li', function(e)
	{    
		var $tries = $(this).parent().parent().find('.btn-default');
		var $points = $(this).parent().parent().parent().find('.points-combo').find('.btn-default');
		var $points_list = $(this).parent().parent().parent().find('.points-combo').find('.points-list');
		$tries.text($(this).text());
		$points_list.empty();
		alert('in');
		if($(this).text()=="-"){
			$points.addClass("disabled");
			$points.addClass("disabled");
			$points.text("-");						
		} else {
			$points.removeClass("disabled");
			$points.removeClass("disabled");
			$points.text("-");
			clicComboEssais($points_list, parseInt($(this).text()));
		}
	});
}

function initCombosPoints(ess1, ess2){
    var e1 = $(document).find('#combo-points-1').find('.points-list');
    var e2 = $(document).find('#combo-points-2').find('.points-list');
    clicComboEssais(e1, ess1);
    clicComboEssais(e2, ess2);
}

function clicComboEssais(el, nb){
	//var e = 0;
	var scores = getScoresPossibles(nb);
	el.empty();
	el.append($("<li>").text("-"));
	for(var i=0; i<scores.length; i++) {
		// Ajout dans le combo du score
		el.append($("<li>").text(scores[i]));
	}

}

function clicComboPoints(elems){
	// Tests valeurs des deux combo points
	// Si != - alors bouton validation visible
	if($(elems[0]).text() != "-" && $(elems[1]).text() != "-"){
		$("#send-pari a").removeClass("disabled");
	} else {
		$("#validate a").addClass("disabled");
	}
}

function getScoresPossibles(e){
	var et, ent, p;
	var scores = [];
	var pmax = 10;
	
	for(ent=0;ent<=e;ent++)
	{
		et = e - ent;
		for(p=0;p<pmax;p++){
			scores.push(3*p + 5*ent + 7*et);
		}
	}
	
	// Enlever les doublons possibles
	var uniqueScores = [];
	$.each(scores, function(i, el){
		if($.inArray(el, uniqueScores) === -1) uniqueScores.push(el);
	});
	
	// Trier dans l'ordre croissant
	uniqueScores.sort(function(a, b) {
		return a - b;
	});
	
	return uniqueScores;
}

function render_calendrier(id_cal){
    	var formURL = "/jeux/rugby/lib/render/calendrier.php";
	var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal;
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{	    		    
		    var result = $.parseJSON(data);
		    var html = result.html;
		    var premier = result.premier;
		    var essais1 = result.essais1;
		    var essais2 = result.essais2;
		    $( "#cal-container" ).empty();
		    $( "#cal-container" ).append(html);
		    if (premier != null){
			render_prono_autre(id_cal,premier);
		    }
		    initCombosPoints(essais1, essais2);
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			alert('error');//do nothing
		}
	});
	
	
}

function render_prono_autre(id_cal,joueur){
    	var formURL = "/jeux/rugby/lib/render/prono_joueur.php";
	var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal + "&joueur=" + joueur;
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{	    
		    var result = data;
		    $( "#son_prono" ).empty();
		    $( "#son_prono" ).append(result);
		    
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			alert('error');//do nothing
		}
	});
}

function autocollapse() {
  
  var tabs = $('#tab-list');
  var tabsHeight = tabs.innerHeight();
  
   if (tabsHeight >= 60) {
    while(tabsHeight > 60) {
      //console.log("new"+tabsHeight);
      
      var children = tabs.children('li:not(:last-child)');
      var count = children.size();
      $(children[count-1]).prependTo('#collapsed');
      
      tabsHeight = tabs.innerHeight();
    }
  }
  else {
  	while(tabsHeight < 60 && ($('#collapsed').children('li').size()>0)) {
      
      var collapsed = $('#collapsed').children('li');
      var count = collapsed.size();
      $(collapsed[0]).insertBefore(tabs.children('li:last-child'));
      tabsHeight = tabs.innerHeight();
    }
    if (tabsHeight>60) { // double chk height again
    	autocollapse();
    }
  } 
  
};


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