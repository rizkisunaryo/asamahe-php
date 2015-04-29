function getNavbarHtml (pCurPage) {
	var menuHlClass = 'menuHl';
	var recentHl = pCurPage=='recent'?menuHlClass:'';
	var hotHl = pCurPage=='hot'?menuHlClass:'';
	var topHl = pCurPage=='top'?menuHlClass:'';
	return '<div id="navbarHolder">'+
	'<div id="navbarHider"></div>'+
	'<div id="navbar" class="row">'+
  	'<div class="col-sm-2">'+
			'<div id="logoHolder">'+
				'<div id="logo"><img src="res/img/asamahe_logo.png" /></div>'+
			'</div>'+
		'</div>'+
  	'<div class="col-sm-5">'+
    	'<div class="row">'+
    		'<div class="col-xs-2">'+
	      	'<div id="'+recentHl+'" class="text-center">'+
						'<a href="recent-jokes.html"><div id="recentIcon" class="menuIcon btn"></div></a>'+
					'</div>'+
				'</div>'+
    		'<div class="col-xs-2">'+
	      	'<div id="'+hotHl+'" class="text-center">'+
						'<a href="hot-jokes.html"><div id="hotIcon" class="menuIcon btn"></div></a>'+
					'</div>'+
				'</div>'+
				'<div class="col-xs-2">'+
	      	'<div id="'+topHl+'" class="text-center">'+
						'<a href="top-jokes.html"><div id="topIcon" class="menuIcon btn"></div></a>'+
					'</div>'+
				'</div>'+
    		'<div class="col-xs-6">'+
					'<div class="row">'+
						'<div id="crtNewBtn" class="btn"></div>'+
					'</div>'+
				'</div>'+
			'</div>'+
		'</div>'+
  	'<div class="col-sm-5">'+
			'<div class="row" id="rightNavHolder">'+
				'<div class="pull-right">'+
					'<div id="searchHolder">'+
						'<input id="searchTxt" type="text" size="18" placeholder="Search..." />'+
						'<div id="searchBtn" class="btn"></div>'+
					'</div>'+
					'<div id="loginBtn" class="btn"></div>'+
				'</div>'+
			'</div>'+
		'</div>'+
  '</div>'+
'</div>';
}