<?php

echo '<footer>
		<div class="container ">
			<p>© 2014 Parions Potes </p>
		</div>
	</footer>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!--script src="./assets/js/docs.min.js"></script-->
	<script src="js/classie.js"></script>
	<script src="js/jquery.scrollTo.min.js"></script>
	<script src="js/jqBootstrapValidation.js"></script>
	
	<!-- <script src="bower_components/jquery/dist/jquery.js"></script>-->
<script src="bower_components/velocity/velocity.js"></script>
<script src="bower_components/moment/min/moment-with-locales.min.js"></script>
<script src="bower_components/angular/angular.js"></script>
<!-- <script src="bower_components/lumx/dist/js/lumx.js"></script>  -->

<script src="js/toucheffects.js"></script>
<script src="js/script.js"></script>

<script type="text/javascript">
	/*var pagedSections = ["#myCarousel","#games","#inscription","#presentation","#contact"];
	//var pagedSections = ["#contact"];
	var headerSize = 98;
	
	var pageSection = function() {	
		var viewportHeight = $(window).height();
		
		$.each(pagedSections,function(index,value) {
			$(value).css("min-height",viewportHeight-headerSize);
		});	
		// $(\'body\').scrollspy("refresh");
	}*/
    jQuery(document).ready(function ($) {
<<<<<<< HEAD
		Init();
=======
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	
		$("#inscription-form").submit(function(e)
		{
			$(\'.alert\').alert(\'close\');
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
					var result = data.split(\';\');
					if (result[0] == \'success\'){
						$( "#inscription-container" ).append( \'<div class="alert alert-success alert-dismissible" role="alert">\'+
						\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
						result[1]+\'</div>\' );
					}else {
						$( "#inscription-container" ).append( \'<div class="alert alert-info alert-dismissible" role="alert">\'+
						\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
						\'<strong>Attention!  </strong>\'+result[0]+\'</div>\' );
					}
					$(\'form\')[0].reset();
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					//alert(errorThrown);
					$( "#inscription-container" ).append( \'<div class="alert alert-danger alert-dismissible" role="alert">\'+
						\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
						\'<strong>Attention!  </strong>\'+errorThrown+\'</div>\' );
					     
				}
			});
			e.preventDefault(); //STOP default action
		//	e.unbind(); //unbind. to stop multiple form submit.
		});
		$("#contact-form").submit(function(e)
		{
			$(\'.alert\').alert(\'close\');
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
					var result = data.split(\';\');
					if (result[0] == \'success\'){
						$( "#contact-container" ).append( \'<div class="alert alert-success alert-dismissible" role="alert">\'+
						\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
						result[1]+\'</div>\' );
					}else {
						$( "#contact-container" ).append( \'<div class="alert alert-info alert-dismissible" role="alert">\'+
						\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
						\'<strong>Attention!  </strong>\'+result[0]+\'</div>\' );
					}
					$(\'form\')[0].reset();
				},
				error: function(jqXHR, textStatus, errorThrown) 
				{
					//alert(errorThrown);
					$( "#contact-container" ).append( \'<div class="alert alert-danger alert-dismissible" role="alert">\'+
						\'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>\'+
						\'<strong>Attention!  </strong>\'+errorThrown+\'</div>\' );
					     
				}
			});
			e.preventDefault(); //STOP default action
		//	e.unbind(); //unbind. to stop multiple form submit.
		});
		
		//$(\'body\').scrollspy({ target: \'#navbar-main\',offset:250 }) /// lign qui \'spy\' le scroll de la page et surligne les menusp
		//pageSection();
		$(window).resize(function() {
			//pageSection();
			 $(\'body\').scrollspy("refresh");
		});
		$(\'#archive-toggle\').click(function() {
			$(\'[data-spy="scroll"]\').each(function () {
			  setTimeout(function(){ $(this).scrollspy(\'refresh\');}, 1000);		  
			});
		});
		//$.scrollTo( 0 );
		
        $(\'.nav-tabs\').tab();
		$(\'a[data-action="scrollTo"]\').click(function(e) {
			e.preventDefault();
			scrollX = $(\'.header\').height();
			$(\'.menu\').toggleClass(\'active\');
			if(this.hash == "#myCarousel") {
				$(\'body\').scrollTo(0,500,null);
					$(".section").removeClass("inactiveSection");
			} else {
				$(\'body\').scrollTo(this.hash, 500, {offset: -scrollX});
			}
			$(\'.navbar-collapse\').removeClass(\'in\');
		});
		
		$(\'[data-toggle=dropdown]\').dropdown();
>>>>>>> origin/master
	
		
    });
	var cbpAnimatedHeader = (function() {
 
		var docElem = document.documentElement,
			header = document.querySelector( \'header\' ),
			didScroll = false,
			changeHeaderOn = 98;
			
	 
		function init() {
			window.addEventListener( \'scroll\', function( event ) {
				if( !didScroll ) {
					didScroll = true;
					setTimeout( scrollPage, 250 );
				}
			}, false );
		}
		function stickyMenu(element,nextelement) {
			
		}
		function scrollPage() {
			var sy = scrollY();
			if ( sy >= changeHeaderOn ) {
				classie.add( header, \'small\' );
			}
			else {
				classie.remove( header, \'small\' );
			}
			didScroll = false;
			
			var activeTarget = $("#navbar-main li.active a").attr("href");
			//alert(activeTarget);
			$(".activeSection").removeClass("activeSection");
			if(sy > 650) {
			$(".section").addClass("inactiveSection");
			
			$(activeTarget).addClass("activeSection");
			} else {
			$(".section").removeClass("inactiveSection");
			}
			
		}
	 
		function scrollY() {
			return window.pageYOffset || docElem.scrollTop;
		}
	 
		init();
	 
	})();


	</script>  
  

</body></html>';

?>