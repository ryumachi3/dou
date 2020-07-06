// JS
// スクロールに合わせて表示
$(function() {
	$('.list-mv').on('inview', function(event, isInView, visiblePartX, visiblePartY) {
		if(isInView){
			$(this).stop().addClass('mv');
		}
		else{
			//  一度表示したらそのままにしたいのでコメントアウト
			// $(this).stop().removeClass('mv');
		}
	});

	$('.scr-disp').on('inview', function(event, isInView, visiblePartX, visiblePartY) {
		if(isInView){
			$(this).stop().addClass('disp');
		}
		else{
			//  一度表示したらそのままにしたいのでコメントアウト
			//$(this).stop().removeClass('mv');
		}
	});
	
});
// JavaScript Document