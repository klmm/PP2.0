<?php

echo '
	<footer>
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
		// $("body").scrollspy("refresh");
	}*/
    jQuery(document).ready(function ($) {
		Init();
	
		
    });
	var cbpAnimatedHeader = (function() {
 
		var docElem = document.documentElement,
			header = document.querySelector( "header" ),
			didScroll = false,
			changeHeaderOn = 98;
			
	 
		function init() {
			window.addEventListener( "scroll", function( event ) {
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
				classie.add( header, "small" );
			}
			else {
				classie.remove( header, "small" );
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