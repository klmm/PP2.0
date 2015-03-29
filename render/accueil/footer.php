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


<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
	
		Init_Forms();
		
		//$("body").scrollspy({ target: "#navbar-main",offset:250 }) /// lign qui "spy" le scroll de la page et surligne les menusp
		//pageSection();
		$(window).resize(function() {
			//pageSection();
			 $("body").scrollspy("refresh");
		});
		$("#archive-toggle").click(function() {
			$("[data-spy="scroll"]").each(function () {
			  setTimeout(function(){ $(this).scrollspy("refresh");}, 1000);		  
			});
		});
		//$.scrollTo( 0 );
		
        $(".nav-tabs").tab();
		$("a[data-action="scrollTo"]").click(function(e) {
			e.preventDefault();
			scrollX = $(".header").height();
			$(".menu").toggleClass("active");
			if(this.hash == "#myCarousel") {
				$("body").scrollTo(0,500,null);
					$(".section").removeClass("inactiveSection");
			} else {
				$("body").scrollTo(this.hash, 500, {offset: -scrollX});
			}
			$(".navbar-collapse").removeClass("in");
		});
		
		$("[data-toggle=dropdown]").dropdown();
	
		
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