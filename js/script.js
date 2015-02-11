function Init()
		{
			$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	
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
					//alert(data);
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
		//	e.unbind(); //unbind. to stop multiple form submit.
		});
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
					//alert(data);
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
					//alert(errorThrown);
					$( "#contact-container" ).append( '<div class="alert alert-danger alert-dismissible" role="alert">'+
						'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
						'<strong>Attention!  </strong>'+errorThrown+'</div>' );
					     
				}
			});
			e.preventDefault(); //STOP default action
		//	e.unbind(); //unbind. to stop multiple form submit.
		});
		
		//$('body').scrollspy({ target: '#navbar-main',offset:250 }) /// lign qui 'spy' le scroll de la page et surligne les menusp
		//pageSection();
		$(window).resize(function() {
			//pageSection();
			 $('body').scrollspy("refresh");
		});
		$('#archive-toggle').click(function() {
			$('[data-spy="scroll"]').each(function () {
			  setTimeout(function(){ $(this).scrollspy('refresh');}, 1000);		  
			});
		});
		//$.scrollTo( 0 );
		
        $('.nav-tabs').tab();
		$('a[data-action="scrollTo"]').click(function(e) {
			e.preventDefault();
			scrollX = $('.header').height();
			$('.menu').toggleClass('active');
			if(this.hash == "#myCarousel") {
				$('body').scrollTo(0,500,null);
					$(".section").removeClass("inactiveSection");
			} else {
				$('body').scrollTo(this.hash, 500, {offset: -scrollX});
			}
			$('.navbar-collapse').removeClass('in');
		});
		
		$('[data-toggle=dropdown]').dropdown();
		
	}