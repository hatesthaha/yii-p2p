
$(document).ready(function(e) {			
	t = $('.fixed').offset().top;
	//mh = $('.main').height();
	fh = $('.fixed').height();

	$('.fixed').css('position','fixed');
	$('.fixed').css('top','100px');
	$(window).scroll(function(e){
		s = $(document).scrollTop();	
		if(s > t - 10){
			$('.fixed').css('position','fixed');
			$('.fixed').css('top','100px');
			//if(s + fh > mh){
			//	$('.fixed').css('top',mh-s-fh+'px');
			//}
		}else{
			$('.fixed').css('position','');
		}
	})
});
