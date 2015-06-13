// A CHANGER LORS DE LA CREATION D'UN JEU
var id_jeu = 4;

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
		over: function( event, ui ) {
			if($("#sortable2 li").size() == 10) {
				$( "#numero" ).switchClass( "visible", "hidden", 1000);
			}
		}
	}).disableSelection();
	$( "#sortable2" ).sortable({
		connectWith: ".connectedSortable",
		scroll : true,
		cursor : "move",
		over: function( event, ui ) {
			if($("#sortable2 li").size() == 10) {
				$( "#numero" ).switchClass( "hidden", "visible", 1000);
			}
			if($("#sortable2 li").size() > 10) {
				$( "#sortable1" ).sortable( "cancel" );
			}
		}
//		receive: function( event, ui ) {calcrisk();}
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
		check_Pari();
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

function check_Pari(calendrier){
	var pari, pari1, pari2, pari3, pari4, pari5, pari6, pari7, pari8, pari9, pari10;
	var total = 0;
	
	if($("#sortable2 li").size() == 10) {
		var i = 1;
		$('#sortable2 li').each(function() {
			switch(i) {
				case 1:
				pari1=$(this).attr("id");
				pari1 = pari1.substring(11);
//				span1=$(this).getElementsByTagName("span");
//				note1=span1[0].innerHTML;
		
				break;
				case 2:
				pari2=$(this).attr("id");
				pari2 = pari2.substring(11);
//				span2=$(this).getElementsByTagName("span");
//				note2=span2[0].innerHTML;
				
				break;
				case 3:
				pari3=$(this).attr("id");
				pari3 = pari3.substring(11);
//				span3=$(this).getElementsByTagName("span");
//				note3=span3[0].innerHTML;
				
				break;
				case 4:
				pari4=$(this).attr("id");
				pari4 = pari4.substring(11);
//				span4=$(this).getElementsByTagName("span");
//				note4=span4[0].innerHTML;
				
				break;
				case 5:
				pari5=$(this).attr("id");
				pari5 = pari5.substring(11);
//				span5=$(this).getElementsByTagName("span");
//				note5=span5[0].innerHTML;
				
				break;
				case 6:
				pari6=$(this).attr("id");
				pari6 = pari6.substring(11);
//				span6=$(this).getElementsByTagName("span");
//				note6=span6[0].innerHTML;
				
				break;
				case 7:
				pari7=$(this).attr("id");
				pari7 = pari7.substring(11);
//				span7=$(this).getElementsByTagName("span");
//				note7=span7[0].innerHTML;
				
				break;
				case 8:
				pari8=$(this).attr("id");
				pari8 = pari8.substring(11);
//				span8=$(this).getElementsByTagName("span");
//				note8=span8[0].innerHTML;
				
				break;
				case 9:
				pari9=$(this).attr("id");
				pari9 = pari9.substring(11);
//				span9=$(this).getElementsByTagName("span");
//				note9=span9[0].innerHTML;
				
				break;
				case 10:
				pari10=$(this).attr("id");
				pari10 = pari10.substring(11);
//				span10=$(this).getElementsByTagName("span");
//				note10=span10[0].innerHTML;
				
				break;
			}
			
			
			i += 1;
			});
			pari = pari1+";"+pari2+";"+pari3+";"+pari4+";"+pari5+";"+pari6+";"+pari7+";"+pari8+";"+pari9+";"+pari10+";";
			
			//calcul note totale pari
			
			$('#sortable2 li span').each(function() {
				total += parseInt($(this).text());
				});
			pari = pari + total + ";";
			alert(pari);
		}
		else {
			alert("Votre pari n'est pas complet !");
			pari = "undefined";
		}
		
	//	return pari;
	
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