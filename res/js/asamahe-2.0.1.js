var API_URL_PREFIX = 'http://asamahe.com:2903/api/';
var WINDOW_WIDTH_XS = 768;
var PULL_RIGHT_CLASS = 'pull-right';
var BUTTON_CLASS = 'btn';

var BOTTOM_TRIGGER = $('#bottomTrigger');

var lastScrollTop = 0;
var navbarStatus = 'showing';

function getCurTimeStr() {
	var d = new Date();
  var Y = d.getFullYear();
  var M = zeroized(d.getMonth()+1);
  var D = zeroized(d.getDate());
  var h = zeroized(d.getHours());
  var m = zeroized(d.getMinutes());
  var s = zeroized(d.getSeconds());
  var ms = zeroized(d.getMilliseconds());
  return formatTimeStr(Y+'/'+M+'/'+D+' '+h+':'+m+':'+s+'.'+ms);
}

function formatTimeStr(Y,M,D,h,m,s,ms) {
	return Y+'/'+M+'/'+D+' '+h+':'+m+':'+s+'.'+ms;
}

function zeroized (pNumber) {
  return pNumber<=9? '0'+pNumber : pNumber;
}

$(function() {
	popupImg();
	$(window).on('scroll', function(){
		showHideNavbar();
	});
});

function popupImg() {
	$('.image-link').magnificPopup({type:'image'});
	$('.popup-img').magnificPopup({ 
	  type: 'image'
		// other options
	});
}

function showHideNavbar() {
	var st = $(window).scrollTop();
	var webScrollHeight = $('body').height() - $(window).height();
	
	if (st<0) {
		st=0;
		lastScrollTop=st+10;
	}
	else if (st>webScrollHeight) {
		st=webScrollHeight;
		lastScrollTop=st-10;
	};

	if (st<lastScrollTop && navbarStatus=='hidden') {
		navbarStatus='appearing';
		$( "#navbarHolder" ).animate({
	    top:-15
	  }, 500, function() {
	    navbarStatus='showing';
	  });
	}
	else if (st>lastScrollTop && navbarStatus=='showing') {
		navbarStatus='disappearing';
		$( "#navbarHolder" ).animate({
	    top:-105
	  }, 500, function() {
	    navbarStatus='hidden';
	  });
	}
	lastScrollTop=st;
}