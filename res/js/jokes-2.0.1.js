var JOKE_LIST_SECTION = $('#jokeListSection');
var jokeIds = [];

$(function() {
	if (curPage=='recent') {
		reqRecentJokes(apiUrl,jokesReqData);
	}
	else {

	}

	$(window).on('resize', function(){
		
	});
});

$(window).scroll(function(){
	if (curPage=='recent' && $(window).scrollTop() > $(document).height() - $(window).height() - 10) {
		var jokesReqData = {
			Key: '',
			Viewer: '',
			Time: oldestTime,
			IsBefore: 1
		};
		reqRecentJokes(apiUrl,jokesReqData);
	};
});

function reqRecentJokes(pApiUrl,pJokesReqData) {
	$.ajax({
    url: pApiUrl,
    type: 'POST',
    dataType: 'json',
    success: function(data) {
    	$.each(data.Jokes, function(index, element) {
    		if (jokeIds[element.JokeId]!=1) {
    			jokeIds[element.JokeId]=1;
    			addJokeBox(JOKE_LIST_SECTION,element.JokeId,element.Joker,element.JokerPicUrl,element.JokerName,element.Title,element.Content,element.LikeCount,element.CommentCount);
    		}
    		oldestTime=element.UpdateTime;
    	});
    }.bind(this),
    error: function(xhr, status, err) {
      console.error(this.props.url, status, err.toString());
    }.bind(this),
    data: JSON.stringify(pJokesReqData)
  });
}

function addJokeBox(pHolder, pJokeId, pJoker, pJokerPicUrl, pJokerName, pJokeTitle, pJokeContent, pLikeCount, pCommentCount) {
	pHolder.append(getJokeBoxHtml(pJokeId, pJoker, pJokerPicUrl, pJokerName, pJokeTitle, pJokeContent, pLikeCount, pCommentCount));
}

function setJokeBox(pHolder, pJokeId, pJoker, pJokerPicUrl, pJokerName, pJokeTitle, pJokeContent, pLikeCount, pCommentCount) {
	pHolder.html(getJokeBoxHtml(pJokeId, pJoker, pJokerPicUrl, pJokerName, pJokeTitle, pJokeContent, pLikeCount, pCommentCount));
}