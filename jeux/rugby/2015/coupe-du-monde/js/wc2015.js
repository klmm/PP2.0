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
	
	$("#validate").click(function(ev){
	   
	    var formURL = $(this).attr("action");
	    var id_cal =  $(this).parent().find("#id_cal").attr("value");;
	    var i = 0;
	    var postData = "id_jeu=" + id_jeu + "&id_cal=" + id_cal + "&essais1=" + essais1  + "&essais2=" + essais2  + "&score1=" + score1  + "&score2=" + score2;

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
		    $( "#cal-container" ).empty();
		    $( "#cal-container" ).append(html);
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