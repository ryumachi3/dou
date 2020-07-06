// JS

// 画面スクロール時
$(window).on('scroll',function(){
			if (window.matchMedia('(min-width:960px)').matches) {
				// PCの処理
			} else {
				// SPの処理
				// 初期画面から少しでもスクロールした場合
				if($(window).scrollTop() > 0){
						//$('#scrollBox').addClass('fo3');
				}
				else{
						//$('#scrollBox').removeClass('fo3');
				}	
				
				if($(window).scrollTop() > 100){
						//$('#logoBox').addClass('logoMv');
						//$('.main_img').addClass('dark');
						//$('#subNav').addClass('sbmv');
				}
				else{
						//$('#logoBox').removeClass('logoMv');
						//$('.main_img').removeClass('dark');
						//$('#subNav').removeClass('sbmv');
				}	
				
				//  ロゴとメニューの色変更
				brownBottom = $('.wrap-brown').height();
				//  Blogセクションより下にきた場合
				if($(window).scrollTop() > brownBottom){
						$('#logoBox').addClass('logo-b');
						$('#gnav-toggle').addClass('brown');
				}
				else{
						$('#logoBox').removeClass('logo-b');
						$('#gnav-toggle').removeClass('brown');
				}	
			}
});

$(window).trigger('scroll');

//画面サイズ変更時
$(window).resize(function(){
		$('#logoBox').removeClass('logoMv');
		$('#subNav').removeClass('sbmv');	
		$('#logoBox').removeClass('logo-b');
		$('#gnav-toggle').removeClass('brown');
});

$(window).on('beforeunload', function(event) {
		$('#logoBox').removeClass('logoMv');
		$('#subNav').removeClass('sbmv');	
		$('#logoBox').removeClass('logo-b');
		$('#gnav-toggle').removeClass('brown');
});

// JavaScript Document