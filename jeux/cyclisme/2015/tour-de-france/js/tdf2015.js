// A CHANGER LORS DE LA CREATION D'UN JEU
var id_jeu = 1;

function Init_Forms_Cyclisme()
{
	$(document).on('click', '#calendar a', function(e)
	{
		var id = $(this).attr("value");	
		render_pres_panel(id);
	});
	
	$(document).on('mouseover', '#calendar a', function(e)
	{
		var id = $(this).attr("value");
		//alert(id);
		//juste affichage de l'image...à voir
	});
	
	$(document).on('click', '.scores tr', function(e)
	{
		var joueur = $(this).find(".table-name").html().valueOf();
		var id_cal = $(this).parent().parent().attr("id");
		
		alert(id_cal + ' ' + joueur);
		render_prono_autre(id_cal,joueur);
	});
}

function Init_Zone_Paris()
{
	var pari_valide = false;
	if($("#sortable2 li").size() == 10) {
		pari_valide = true;
	}
	
	$('#calendar-list li').on('click', function(){
		var href = $(this).find('a').attr('href');
		var titre = "Avertissement !";
		var texte = "Votre pari ne semble pas terminé. Voulez-vous continuer ?";
		if (pari_valide){
			return true;
		} else {
			dialog(href, titre, texte);
			return false;
		}
		
	});
	
	$( "#sortable1").sortable({
		connectWith: ".connectedSortable",
		scroll : true,
		cursor : "move",
		receive: function( event, ui ) {
		    calcrisk();
		    updateNums();
		}
	}).disableSelection();
	
	$( "#sortable2" ).sortable({
		connectWith: ".connectedSortable",
		scroll : true,
		cursor : "move",
		over: function( event, ui ) {  
		    updateNums();
		    if($("#sortable2 li").size() == 11) {
			$( "#sortable1" ).sortable( "cancel" );
			updateNums();
			calcrisk();
		    }
		},
		update: function( event, ui ) {  
		    updateNums();
		},
		receive: function( event, ui ) {
		    calcrisk();
		}
	}).disableSelection();
	
	$("#item-search").keyup(function(){
		filterItems($("#item-search").val());
	});
	
	$.expr[':'].icontains = function(obj, index, meta, stack){
		return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
	};
	
	$('a[data-confirm]').click(function(ev) {
		var href = $(this).attr('href');
		dialog(href);
		return false;
	});
	$('button[data-alert]').click(function(ev) {
		var titre = "";
		var texte = "";
		popup(titre, texte);
		return false;
	});
	
	$("#validate").click(function(ev){
	   
	    var formURL = $(this).attr("action");
	    var id_cal =  $(this).parent().find("#id_cal").attr("value");;
	    var i = 0;
	    var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal;
	   	    
	    $('#sortable2 li').each(function() {
		postData += "&prono[" + i + "]=" + $(this).attr("id");
		i++;
	    });
	    //alert(postData);
	    $.ajax(
	    {
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{	 
		    var result = $.parseJSON(data);
		    var msg = result.msg;
		    var rafr = result.rafr;
		    var res = result.resultat;
		        
		    if(msg != null && res != null){
			alert(msg);
			$( "#msg-container" ).empty();
			$( "#msg-container" ).append(msg);
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
	
}

function filterItems(search){
	
	$("#sortable1 li").css( "display", "none");
	$("#sortable1 li:icontains("+search +")").css( "display", "block");
	
}
	
function dialog(href, titre, message){
	if (!$('#dataConfirmModal').length) {
		$('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">'+
		'<div class="modal-dialog">'+
		'<div class="modal-content">'+
		'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">'+ titre +'</h3></div>'+
		'<div class="modal-body"><p>'+ message +'</p></div>'+
		'<div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Annuler</button><a class="btn btn-danger" id="dataConfirmOK">Confirmer</a></div></div></div></div>');
	}
	$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
	$('#dataConfirmOK').attr('href', href);
	$('#dataConfirmModal').modal({show:true});
}

function dialog(href, titre, message){
	if (!$('#dataConfirmModal').length) {
		$('body').append('<div id="dataConfirmModal" class="modal" role="dialog" aria-labelledby="dataConfirmLabel" aria-hidden="true">'+
		'<div class="modal-dialog">'+
		'<div class="modal-content">'+
		'<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h3 id="dataConfirmLabel">'+ titre +'</h3></div>'+
		'<div class="modal-body"><p>'+ message +'</p></div>'+
		'<div class="modal-footer"><button class="btn" data-dismiss="modal" aria-hidden="true">Ok</button></div></div></div></div>');
	}
	$('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
	$('#dataConfirmModal').modal({show:true});
}



function render_pres_panel(id_cal){
    	var formURL = "/jeux/cyclisme/lib/render/render_calendrier.php";
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
		    $( ".test" ).empty();
		    $( ".test" ).append(html);
		    if (premier != null){
			render_prono_autre(id_cal,premier);
		    }
		    
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			alert('error');//do nothing
		}
	});
}

function render_prono_autre(id_cal,joueur){
    	var formURL = "/jeux/cyclisme/lib/render/render_prono.php";
	var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal + "&joueur=" + joueur;
	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{	    
		    var result = data;
		    $( ".son_prono" ).empty();
		    $( ".test" ).append(result);
		    
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			alert('error');//do nothing
		}
	});
}

function calcrisk(){
	if($("#sortable2 li").size() > 10) {
				return;
	}
	var jauge = $('.result-area').find('.progress-bar');
	var total = 0;
	
	$('#sortable2 li').each(function() {
		var rating = $(this).find('.item-rating .glyphicon-star').size();
		if(rating == 0){
			total += 6;
		} else if(rating == 1) {
			total += 4;
		} else if(rating == 2) {
			total += 2;
		}
		
	});
	
//	var moyenne = total/$("#sortable2 li").size();
	
	$('.progress-bar').css('width', total+'%').attr('aria-valuenow', total);
	jauge.text(total +'%');
}

function updateNums(){

		var i = 1;
		$('#sortable2 li').each(function() {
			$(this).find('.item-place').text(i +'. ');

			i += 1;
		});
		
		$('#sortable1 li').each(function() {
			$(this).find('.item-place').text('');
		});
		
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