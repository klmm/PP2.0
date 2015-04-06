function initPagination() {
	var listElement = $('.list-articles');
	var perPage = 4;
	var numItems = listElement.children().size();
	var numPages = Math.ceil(numItems/perPage);
	 
	
	$('.pagination').data("curr",0);

	$('<li><a aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>').appendTo('.pagination');
	var curr = 0;
	while(numPages > curr){
	  $('<li><a class="page_link">'+(curr+1)+'</a></li>').appendTo('.pagination');
	  curr++;
	}
	$('<li><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>').appendTo('.pagination');
	
	$('.pagination li:nth-child(2)').addClass('active');
	$('.pagination li:first').addClass('disabled');
	if(numItems < perPage){
		$('.pagination li:last').addClass('disabled');
	}

	listElement.children().css('display', 'none');
	listElement.children().slice(0, perPage).css('display', 'block');

	$('.pagination li a').click(function(){
		
		var clickedPage;
		if($(this).children().length > 0) {
			clickedPage = $(this).children().html().valueOf();
			if(clickedPage == "»") {
				next();
			} else if(clickedPage == "«") {
				previous();
			} else {
				clickedPage = 0;
			}
		} else {
			clickedPage = $(this).html().valueOf() - 1;
			goTo(clickedPage);
		}
		
	});



	function previous(){
	  var goToPage = parseInt($('.pagination').data("curr")) - 1;
	  goTo(goToPage);
	}

	function next(){
	  goToPage = parseInt($('.pagination').data("curr")) + 1;
	  goTo(goToPage);
	}

	function goTo(page){
	  var startAt = page * perPage,endOn = startAt + perPage;
	  
	  listElement.children().css('display','none').slice(startAt, endOn).css('display','block');
      if(page > parseInt($('.pagination').data("curr"))){
		  $('.list-articles').show('slide', {direction: 'right'}, 500);
	  } else {
		  $('.list-articles').show('slide', {direction: 'left'}, 500);
	  }
		
	  $('.pagination').data("curr",page);
	  $('.pagination li').removeClass('active');
	  var res = page + 2;
	  $('.pagination li:nth-child('+res+')').addClass('active');
	  $('.pagination li').removeClass('disabled');
	  if(page == 0) {
		  $('.pagination li:first').addClass('disabled');
	  } else if(page == numPages -1) {
		  $('.pagination li:last').addClass('disabled');
	  }
	  
	}
	
	
}