function Init_Forms()
{
	
	
// Formulaire d'inscription
	$("#inscription-form").submit(function(e)
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
					$( "#inscription-container" ).append( '<div class="alert alert-success alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					result[1]+'</div>' );
				}else {
					$( "#inscription-container" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+result[0]+'</div>' );
				}
				$('#inscription-form')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				//alert(errorThrown);
				$( "#inscription-container" ).append( '<div class="alert alert-danger alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+errorThrown+'</div>' );
					 
			}
		});
		e.preventDefault(); //STOP default action
	});
	
// Formulaire de contact
	$("#contact-form").submit(function(e)
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
					$( "#contact-container" ).append( '<div class="alert alert-success alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					result[1]+'</div>' );
				}else {
					$( "#contact-container" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+result[0]+'</div>' );
				}
				$('#contact-form')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$( "#contact-container" ).append( '<div class="alert alert-danger alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+errorThrown+'</div>' );
					 
			}
		});
		e.preventDefault(); //STOP default action
	});

// Formulaire de connexion
	$("#formLogin").submit(function(e)
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
					location.reload();
				}else {
					$( "#formLogin" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+result[0]+'</div>' );
				}
				$('#formLogin')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$( "#formLogin" ).append( '<div class="alert alert-danger alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+errorThrown+'</div>' );
					 
			}
		});
		e.preventDefault(); //STOP default action
		
	});
	
	$("#lostPassword").submit(function(e)
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
					$( "#lostPassword" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					result[1]+'</div>' );
				}else {
					$( "#lostPassword" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+result[0]+'</div>' );
				}
				$('#lostPassword')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$( "#lostPassword" ).append( '<div class="alert alert-danger alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+errorThrown+'</div>' );
					 
			}
		});
		e.preventDefault(); //STOP default action
		
	});
	
	$("#changePassword").submit(function(e)
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
					$( "#changePassword" ).append( '<div class="alert alert-success alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					result[1]+'</div>' );
					setTimeout( '$("#logout").click();',2000 );
				}else {
					$( "#changePassword" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+result[0]+'</div>' );
				}
				$('#changePassword')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				$( "#changePassword" ).append( '<div class="alert alert-danger alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+errorThrown+'</div>' );
					 
			}
		});
		e.preventDefault(); //STOP default action
		
	});
	
	$("#logout-form").submit(function(e)
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
				if (result[0] == 'ok' || result[0] == 'nok'){
					location.reload();
				}else {
					//on ne fait rien ?
				}
				//$('form')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				//on ne fait rien
					 
			}
		});
		e.preventDefault(); //STOP default action
		
	});
	
	$("#post-form").submit(function(e)
	{
		$('.alert').alert('close');
		var postData = $(this).serializeArray();
		var formURL = $(this).attr("action");
		var id_art = $(this).find("#id_article").attr("value");
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
					getAllComs(id_art);
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
	
	$(document).on('click', '.btn-like', function(e)
	//$(".btn-like").click(function(e)
	{
		
		var $this = $(this);
		var id_com = $(this).parent().parent().find("#id-com").attr("value");
		var id_art = $(this).parent().parent().find("#id-art").attr("value");
		var $antithis = $(this).parent().find(".btn-dislike");
		var $status = $(this).parent().find(".like-status");
		var postData = "id_comm=" + id_com + "&type=1&id_article=" + id_art;
		$.ajax(
		{
			url : "/lib/form/post_like.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				if (data == 'success'){
				    $this.addClass("disabled");
				    $this.blur();				
				    $antithis.addClass("disabled");
				    //$status.addClass("like-status");
					$status.text('Vous avez aimé');
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
	$(document).on('click', '.btn-dislike', function(e)
	//$(".btn-dislike").click(function(e)
	{
		var $this = $(this);
		var id_com = $(this).parent().parent().find("#id-com").attr("value");
		var id_art = $(this).parent().parent().find("#id-art").attr("value");
		var $antithis = $(this).parent().find(".btn-like");
		var $status = $(this).parent().find(".like-status");
		var postData = "id_comm=" + id_com + "&type=0&id_article=" + id_art;
		$.ajax(
		{
			url : "/lib/form/post_like.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				if (data == 'success'){
				    $this.addClass("disabled");
					$this.blur();				
					$antithis.addClass("disabled");
					$status.removeClass("like-status");
					$status.addClass("dislike-status");
					$status.text('Vous n\'avez pas aimé');
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

function getAllComs(idart) {

	var formURL = "/lib/render/render_commentaires.php";
	var postData = "id_article=" + idart;
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
			var btn_disabled_class, status_class, status_text;
			var no_like = false;
			
			$( ".com-container" ).empty();
			if(coms == null){			    
			    $( ".com-container" ).append( '<div class="sectionSide">'+
							'<p class="section-highlight">Il n\'y a encore aucun commentaire</p>'+
							'</div>');
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
						    btn_disabled_class = "disabled";
						    status_class = "dislike-status";
						    status_text = "Vous n'avez pas aimé";
					    } else {
						    btn_disabled_class = "disabled";
						    status_class = "like-status";
						    status_text = "Vous avez aimé";
					    }

				    } else {
					    btn_disabled_class = "";
					    status_class = "like-status";
					    status_text = "";
				    }
				}
				
				$( ".com-container" ).append(  '<div class="like-form col-md-10 col-md-offset-1">' +		
				   
						'<p id="id-com" value="' + object['id_commentaire'] + '" class="hidden">Id</p>' +
						'<p id="id-art" value="' + idart + '" class="hidden">Id</p>' +
						'<div class="comment-box clearfix"><p class="user col-md-4 col-sm-4 col-xs-4">' + object['joueur'] + '</p>' +
						    '<p class="time col-md-8 col-sm-8 col-xs-8">' + object['dateheurepub_conv'] + '</p>' +
						    '<p class="time pull-right hidden">' + object['dateheurepub_court'] + '</p>' +
						    '<p class="comment col-md-12 col-sm-12 col-xs-12">' + object['contenu'] + '</p>'+
						'</div>' +

						'<div class="like-box clearfix">' +
						    '<button class="btn-dislike btn btn-danger pull-right '+ btn_disabled_class +'" style="margin-left:10px;">' +
							'<span class="glyphicon glyphicon-thumbs-down" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>' +
							'<span class="count">' + object['nbdislikes'] + '</span>' +
						    '</button>' +
						    '<button class="btn-like btn btn-success pull-right '+ btn_disabled_class +'" style="margin-left:10px;">' +
							'<span class="glyphicon glyphicon-thumbs-up" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>' +
							'<span class="count">' + object['nblikes'] + '</span>' +
						    '</button>' + 
							'<span class="'+status_class+' pull-right">'+status_text+'</span>' +
						'</div>' +
				'</div>' );

			}
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			alert('error');//do nothing
		}
	});
	
}
