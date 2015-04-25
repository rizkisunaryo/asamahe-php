var WINDOW_WIDTH_XS = 768;
var PULL_RIGHT_CLASS = 'pull-right';

$(function(){
	reposCrtNewBtn();
	$(window).resize(function(){
		reposCrtNewBtn();
	});
});

function reposCrtNewBtn() {
	console.log(1);

	var selector = $('#crtNewBtn');
	var marginLeft = '30px';
	var marginRight = '10px';
	var windowWidth = $(window).width();
	if (windowWidth<WINDOW_WIDTH_XS) {
		console.log(2);
		selector.addClass(PULL_RIGHT_CLASS);
		selector.css('margin-left','0');
		selector.css('margin-right',marginRight);
	}
	else {
		console.log(3);
		selector.removeClass(PULL_RIGHT_CLASS);
		selector.css('margin-left',marginLeft);
		selector.css('margin-right','0');
	}
}