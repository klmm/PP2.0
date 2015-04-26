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
				$('form')[0].reset();
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
				$('form')[0].reset();
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
				$('form')[0].reset();
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
					location.reload();
				}else {
					$( "#lostPassword" ).append( '<div class="alert alert-info alert-dismissible" role="alert">'+
					'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
					'<strong>Attention!  </strong>'+result[0]+'</div>' );
				}
				$('form')[0].reset();
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
				$('form')[0].reset();
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
				$('form')[0].reset();
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
					getAllComs();
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
	
	$(".btn-like").click(function(e)
	{
		e.preventDefault(); //STOP default action
		var id_com = $(this).parent().find("#id-com").attr("value");
		var id_art = $(this).parent().find("#id-art").attr("value");
		var postData = "id_comm=" + id_com + "&type=1&id_article=" + id_art;
		$.ajax(
		{
			url : "/lib/form/post_like.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				if (data == 'success'){
					
					$(this).addClass("disabled");
					$(this).blur();					
					var c = $(this).find(".count").html().valueOf();
					c++;
					$(this).find(".count").text(c);
					
				}
				
				//$('form')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
			    // rien
			}
		});
	
	});
	
	$(".btn-dislike").click(function(e)
	{
		e.preventDefault(); //STOP default action
		var id_com = $(this).parent().find("#id-com").attr("value");
		var id_art = $(this).parent().find("#id-art").attr("value");
		var postData = "id_comm=" + id_com + "&type=0&id_article=" + id_art;
		$.ajax(
		{
			url : "/lib/form/post_like.php",
			type: "POST",
			data : postData,
			success:function(data, textStatus, jqXHR) 
			{
				if (data == 'success'){
				    $(this).addClass("disabled");
				    $(this).blur();
				    var c = $(this).find(".count").html().valueOf();
				    c++;
				    $(this).find(".count").text(c);	
				}
				//$('form')[0].reset();
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
			    // rien
					 
			}
		});
		
		
	});
}

function getAllComs() {

	//var postData = $(this).serializeArray();
	var formURL = "/lib/ajax/refresh_commentaires.php";
	$.ajax(
	{
		url : formURL,
		type: "POST",
		success:function(data, textStatus, jqXHR) 
		{
			//alert(result);
			$( ".com-container" ).empty();
			for (var i = 0; i < result.length; i++) {
				var object = result[i];
					
				$( "#com-container" ).append( '<div id="' + object['id_commentaire'] + '" class="row com-container">' +		
				   '<div class="col-md-10 col-md-offset-1">' +
						'<p id="' + object['id_commentaire'] + 'b" class="hidden">Id</p>' +
						'<p class="user pull-left">' + object['joueur'] + '</p>' +
						'<p class="time pull-right">' + object['dateheurepub'] + '</p>' +
						'<p class="comment">' + object['contenu'] + '</p>' +

						'<button class="btn btn-danger pull-right" style="margin-left:10px;" onclick="/lib/form/post_like.php?like=1&id_article=' + object['id_article'] + '&id_comm=' + object['id_commentaire'] + '">' +
							'<span class="glyphicon glyphicon-thumbs-down" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>' +
							'<span>' + object['nblikes'] + '</span>' +
						'</button>' +
						'<button class="btn btn-success pull-right" style="margin-left:10px;" onclick="lib/sql/likes/add_like.php?like=0&id_article=' + object['id_article'] + '&id_comm=' + object['id_commentaire'] + '">' +
							'<span class="glyphicon glyphicon-thumbs-up" style="float:left;padding: 0 10px 0 0;font-size:1em;"></span>' +
							'<span>' + object['nbdislikes'] + '</span>' +
						'</button>' +
					'</div>' +
				'</div>' );

			}
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
			//do nothing
		}
	});
	e.preventDefault(); //STOP default action	
	
	
}
