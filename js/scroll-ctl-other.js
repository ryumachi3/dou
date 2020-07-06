// JS

// 画面スクロール時
$(window).on('scroll',function(){
			if (window.matchMedia('(min-width:960px)').matches) {
				// PCの処理
			} else {
				// SPの処理				
				//  ロゴとメニューの色変更
				pankuzuBottom = $('#pankuzu').height()
												+ $('#headerTop').height();
				//  パンくずエリアより下にきた場合
				if($(window).scrollTop() > pankuzuBottom - 20){
						//$('#logoBox').addClass('logo-b');
						//$('#logoBox').addClass('fix');
						//$('#gnav-toggle').addClass('brown');
						//$('#gnav-toggle').addClass('fix');
				}
				else{
						//$('#logoBox').removeClass('logo-b');
						//$('#logoBox').removeClass('fix');
						//$('#gnav-toggle').removeClass('brown');
						//$('#gnav-toggle').removeClass('fix');
				}	
			}
});

$(window).trigger('scroll');

//画面サイズ変更時
$(window).resize(function(){
//		$('#logoBox').removeClass('logo-b');
//		$('#logoBox').removeClass('fix');
//		$('#gnav-toggle').removeClass('brown');
});

$(window).on('beforeunload', function(event) {
//		$('#logoBox').removeClass('logo-b');
//		$('#logoBox').removeClass('fix');
//		$('#gnav-toggle').removeClass('brown');
});

// JavaScript Document