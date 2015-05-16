function getJokeBoxHtml(pJokeId, pJoker, pJokerPicUrl, pJokerName, pJokeTitle, pJokeContent, pLikeCount, pCommentCount) {
return '<div id="joke'+pJokeId+'" class="row jokeBox">'+
	'<div class="row jokeUpper">'+
		'<div class="jokerPicBox"><a class="jokerPic" href="joker.html?id='+pJoker+'"><img src="'+pJokerPicUrl+'" /></a></div>'+
		'<div class="jokerNameBox"><a class="jokerName" href="joker.html?id='+pJoker+'">'+pJokerName+'</a></div>'+
	'</div>'+
	'<div class="row jokeTitleBox"><a class="jokeTitle" href="joke.html?id='+pJokeId+'">'+pJokeTitle+'</a></div>'+
	'<div class="row jokeContent">'+pJokeContent+'</div>'+
	'<div class="row laughCommentCountBox"><a class="laughCommentCount" href="joke.html?id='+pJokeId+'">'+pLikeCount+' laughs · '+pCommentCount+' comments</a></div>'+
'</div>';
}