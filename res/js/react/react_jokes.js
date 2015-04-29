var jokeIds=[];
var lastJokeBoxId = '';
var loaded = false;
var status = 'init';

var initialJokes=[
	{
  	"JokeId":"#",
  	"Joker":"#",
  	"JokerName":"ASAMAHE",
  	"JokerPicUrl":"res/img/initial-meme.png",
		"Title":"Powered by:",
		"Content":"<a href='res/img/powered-by.jpg' class='popup-img'><img src='res/img/powered-by.jpg' class='img-responsive' /></a>",
		"CommentCount":0,
		"LikeCount":0,
		"ReportCount":0,
		"IsLiked":0,
		"IsReported":0,
		"Score":0,
		"IsAd":0,
		"IsBlocked":0,
		"Time":"1989/03/29 07:07:07.777",
    "UpdateTime":"1989/03/29 07:07:07.777"
	}
];

var JokeListSection = React.createClass({
	loadJokesFromServer: function() {
    $.ajax({
      url: apiUrl,
      type: 'POST',
      dataType: 'json',
      success: function(data) {
        this.setState({data: data.Jokes});
        status='notInit';
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this),
      data: JSON.stringify(jokesReqData)
    });
  },
	getInitialState: function() {
		return ({data: initialJokes},{test: 'test'});
  },
  componentDidMount: function() {
  	this.loadJokesFromServer();
  },
  componentDidUpdate: function () {
    var node = this.getDOMNode();
    console.log(oldestTime);
    if ($(window).scrollTop>parseInt($(window).height())-50 && status=='notInit') {
      loaded=true;
      jokesReqData = {
        Key: '',
        Viewer: '',
        Time: oldestTime,
        IsBefore: 1
      }
      this.loadJokesFromServer();
    };
  },
  render: function() {
  	var jokeBoxes = this.state.data.map(function (joke) {
      return (
        <JokeBox joke={joke} />
      );
    });
    return (
      <div>
        {jokeBoxes}
      </div>
    );
  }
});

var JokeBox = React.createClass({
  render: function() {
    oldestTime = this.props.joke.UpdateTime;
    var jokeBoxId = 'jokeBox'+this.props.joke.JokeId;
    lastJokeBoxId = jokeBoxId;
    return (
      <div className="row jokeBox" id={jokeBoxId}>
        <JokeUpper joke={this.props.joke} />
        <JokeTitleBox joke={this.props.joke} />
        <JokeContent joke={this.props.joke} />
        <LaughCommentCountBox joke={this.props.joke} />
      </div>
    );
  }
});

var JokeUpper = React.createClass({
  render: function() {
    return (
      <div className="row jokeUpper">
        <JokerPic joke={this.props.joke} />
        <JokerNameBox joke={this.props.joke} />
      </div>
    );
  }
});

var JokerPic = React.createClass({
  render: function() {
    var jokerUrl = this.props.joke.Joker=='#' ? 
      '<img class="jokerPic" src="'+this.props.joke.JokerPicUrl+'" />' :
      '<a class="jokerPic" href=joker.html?id='+this.props.joke.Joker+'><img src="'+this.props.joke.JokerPicUrl+'" /></a>';
    return (
      <div className="jokerPicBox" dangerouslySetInnerHTML={{__html: jokerUrl}}></div>
    );
  }
});

var JokerNameBox = React.createClass({
  render: function() {
    var jokerUrl = this.props.joke.Joker=='#' ? 
      '<span class="jokerName">'+this.props.joke.JokerName+'</span>' :
      '<a class="jokerName" href=joker.html?id="'+this.props.joke.Joker+'">'+this.props.joke.JokerName+'</a>';
    return (
      <div className="jokerNameBox" dangerouslySetInnerHTML={{__html: jokerUrl}}></div>
    );
  }
});

var JokeTitleBox = React.createClass({
  render: function() {
    var jokeUrl = this.props.joke.JokeId=='#' ?
    '<span class="jokeTitle">'+this.props.joke.Title+'</span>' :
    '<a class="jokeTitle" href="joke.html?id='+this.props.joke.JokeId+'">'+this.props.joke.Title+'</a>';
    return (
      <div className="row jokeTitleBox" dangerouslySetInnerHTML={{__html: jokeUrl}}></div>
    );
  }
});

var JokeContent = React.createClass({
  render: function() {
    return (
      <div className="row jokeContent" dangerouslySetInnerHTML={{__html: this.props.joke.Content}}></div>
    );
  }
});

var LaughCommentCountBox = React.createClass({
  render: function() {
    var laughAndCommentCount = this.props.joke.JokeId=='#' ?
      '<span class="laughCommentCount">'+this.props.joke.LikeCount+' laughs · '+this.props.joke.CommentCount+' comments</span>' :
      '<a class="laughCommentCount" href="joke.html?id='+this.props.joke.JokeId+'">'+this.props.joke.LikeCount+' laughs · '+this.props.joke.CommentCount+' comments</a>';
    return (
      <div className="row laughCommentCountBox" dangerouslySetInnerHTML={{__html: laughAndCommentCount}}></div>
    );
  }
});

React.render(
  <JokeListSection />,
  document.getElementById('jokeListSection')
);