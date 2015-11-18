// A CHANGER LORS DE LA CREATION D'UN JEU
var id_jeu = 5;

var test = 1;
function Init_Forms_Biathlon()
{
	$(document).on('click', '#calendar a', function(e)
	{    	    
	    $('#list-cal li').each(function() {
		$(this).removeClass("active");
	    });
	    
	    $(this).parent().addClass("active");
	    
	    var id = $(this).attr("value");
	    render_pres_panel(id);
	    
	    document.location.href='#resultats';
	});
	
	$(document).on('click', '.scores tr', function(e)
	{
	    var joueur = $(this).find(".player-name").html().valueOf();
	    var id_cal = $(this).parent().parent().attr("id");

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
		/*if (pari_valide){
			return true;
		} else {
			dialog(href, titre, texte);
			return false;
		}*/
		
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
		},
		update: function( event, ui ) {  
		    updateNums();
		},
		receive: function( event, ui ) {
		    if($("#sortable2 li").size() == 11) {
			$( "#sortable1" ).sortable( "cancel" );
		    }
		    updateNums();
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
		    var msg_final;
		    var res = result.resultat;
		        
		    if(msg != null && res != null && (rafr != null || rafr == false)){
			$( ".alert-msg-prono" ).empty();
			
			msg_final = '<div class="alert alert-info alert-dismissible" style="margin-bottom:15px;" role="alert">' +
				    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
					'<span aria-hidden="true">&times;</span>' +
				    '</button>' + msg +
				    '</div>';
			
			$( ".alert-msg-prono" ).append(msg_final);
			document.location.href='#pari-panel';
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

function render_liste_calendrier(filtre, joueur){
	var formURL = "/jeux/biathlon/lib/render/render_liste_calendrier.php";
	var postData = "id_jeu=" + id_jeu + "&filtre=" + filtre;
	test = 1;
	$( "#resultats" ).empty();
	$( "#resultats" ).append("Chargement...");	

	$.ajax(
	{
		url : formURL,
		type: "POST",
		data : postData,
		success:function(data, textStatus, jqXHR) 
		{	    		    
		    var result = $.parseJSON(data);
		    var html = result.html;
		    var id_cal = result.id_cal;
		    		    
		    $( "#resultats" ).empty();
		    $( "#resultats" ).append(html);
		    render_pres_panel(id_cal);
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
		    $( "#resultats" ).empty();
		    $( "#resultats" ).append("Erreur...");
		}
	});
}

function render_pres_panel(id_cal){
    	var formURL = "/jeux/biathlon/lib/render/render_calendrier.php";
	var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal;
	$( "#cal-container" ).empty();
	$( "#cal-container" ).append("Chargement...");
	
	
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
		    
		    $( "#cal-container" ).empty();
		    $( "#cal-container" ).append(html);
		    
		    if (premier != null){
			render_prono_autre(id_cal,premier);
		    }
		    getAllComs(0,id_jeu,id_cal,0);
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
		    $( "#cal-container" ).empty();
		    $( "#cal-container" ).append("Erreur...");
		}
	});
	
	if(test == 1){
	    $("#post-form").submit(function(e)
	    {
		    $('.alert').alert('close');
		    var postData = $(this).serializeArray();
		    var formURL = $(this).attr("action");
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
					getAllComs(result[1],result[2],result[3],0);
				    }else {
					    $( "#post-container" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					    '<strong>Attention!  </strong>'+result[0]+'</div>' );
				    }
				    $('#post-form')[0].reset();
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
	    test = 0;
	}
}

function render_prono_autre(id_cal,joueur){
    	var formURL = "/jeux/biathlon/lib/render/render_prono.php";
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