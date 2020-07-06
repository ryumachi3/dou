// JS
// メニュー表示
$(function() {
    $('#gnav-toggle').click(function() {
        $(this).toggleClass('active');
 
        if ($(this).hasClass('active')) {
            $('#globalnav').addClass('active');
            $('#subNav').addClass('active');
        } else {
            $('#globalnav').removeClass('active');
            $('#subNav').removeClass('active');
				}
    });
});

//画面サイズ変更時
$(window).resize(function(){
		$('#gnav-toggle').removeClass('active');
		$('#globalnav').removeClass('active');
		$('#subNav').removeClass('active');
});
// JavaScript Document