<?php
session_start();

require_once('atil/constants.php');
require_once('lib/password.php');
require_once('sutil/crypto-util.php');
require_once('sutil/http.php');


require_once('lib/Facebook/Entities/AccessToken.php');
require_once('lib/Facebook/FacebookSDKException.php');
require_once('lib/Facebook/FacebookRequestException.php');
require_once('lib/Facebook/FacebookThrottleException.php');
require_once('lib/Facebook/FacebookAuthorizationException.php');
require_once('lib/Facebook/FacebookRedirectLoginHelper.php');
require_once('lib/Facebook/HttpClients/FacebookHttpable.php');
require_once('lib/Facebook/HttpClients/FacebookCurl.php');
require_once('lib/Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once('lib/Facebook/FacebookRequest.php');
require_once('lib/Facebook/FacebookResponse.php');
require_once('lib/Facebook/FacebookSession.php');
require_once('lib/Facebook/GraphObject.php');
require_once('lib/Facebook/GraphUser.php');
require_once('lib/Facebook/GraphSessionInfo.php');

use Facebook\Entities\AccessToken;
use Facebook\FacebookAuthorizationException;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookSession;
use Facebook\FacebookThrottleException;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;

$fbAppId = '1554892734776831';
$fbAppSecret = '95d8d439d82ab15984476237f1bda7f3';
FacebookSession::setDefaultApplication($fbAppId,$fbAppSecret);

$redirUrl=DOMAIN_URL;
$helper = new FacebookRedirectLoginHelper($redirUrl);
$session = $helper->getSessionFromRedirect();
// echo $_SESSION['id'];
if (!isset($_SESSION["id"]) 
	|| $_SESSION["id"]==''
	|| !isset($_SESSION["name"])
	|| $_SESSION["name"]=='') {
	if(isset($session)) {
		$request = new FacebookRequest($session,'GET','/me');
		$response = $request->execute();
		$graph = $response->getGraphObject(GraphUser::className());

		$_SESSION["id"] = 'fb_'.$graph->getId();
		$_SESSION["name"]=$graph->getName();
		$_SESSION["picUrl"]= 'https://graph.facebook.com/'.$graph->getId().'/picture';

		$url = API_URL_PREFIX."user/setuser/";
		$key = genKey($_SESSION["id"]);
		$postData = array(
	    	'Id' => $_SESSION["id"],
	    	'Key' => $key,
	    	'Name' => $_SESSION["name"],
	    	'PicUrl' => $_SESSION["picUrl"]
		);
		httpReq($url,$postData);
	}
}

$id=isset($_SESSION["id"])? $_SESSION["id"] : '';
$name=isset($_SESSION["name"])? $_SESSION["name"] : '';
$picUrl=isset($_SESSION["picUrl"])? $_SESSION["picUrl"] : '';
$loginUrl=$helper->getLoginUrl();
	
	$url = API_URL_PREFIX."joke/newjokes/";
	$key = genKey($id);

	$postData = array(
	    'Key' => $key,
	    'Viewer' => $id,
	    'IsBefore' => 1
	);
	$jokes = httpReq($url,$postData);
	
	$oldestTime = '';
	if (!is_null($jokes['Jokes'])) {
		$oldestJoke = end($jokes['Jokes']);
		$oldestTime = $oldestJoke['Time'];
	}

	require_once('html-includes.php');
	
		if (!is_null($jokes['Jokes'])) {
		?>
		<script type="text/javascript">
var oldestTime = '<?php echo $oldestTime; ?>';
var apiUrlPrefix = '<?php echo API_URL_PREFIX_PUBLIC; ?>';
$(window).scroll(function() {

    if ($(window).scrollTop() > $(document).height() - $(window).height() - 10) {
        var person = {
            Key: '<?php echo $key; ?>',
            Viewer: '<?php echo $id; ?>',
            Time: oldestTime,
            IsBefore: 1
        }

        $.ajax({
            url: apiUrlPrefix+'joke/newjokes/',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
            	var i = 0;
                $.each(data.Jokes, function(index, element) {
                	if (i!=0) {
						var jokeHtml='<div class="row" id="'+element.JokeId+'" style="padding:10px 5px;">'
							+'<div style="background-color:white; border-top-left-radius:10px; border-top-right-radius:10px; padding:10px 20px 20px;">';

						if ('<?=$menu?>'!='joker') {
							var picUrl= element.JokerPicUrl!=''? element.JokerPicUrl : 'images/unknown.png';
							var jokerName= element.JokerName!=''? element.JokerName : 'unknown';
							jokeHtml+='<div class="profPicHolder"><a href="joker.php?id='+element.Joker+'"><img src="'+picUrl+'" style="height:60px; display:inline-block;"></a></div>'
								+'<div style="display:inline-block;">'
								+'<span style="font-size:20px; font-weight:bold; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; margin-left:10px;"><a href="joker.php?id='+element.Joker+'" style="color:#1fd8ed">'+jokerName+'</a></span><br />'
								+'<span style="font-size:11px; font-weight:bold; color:#9197a3;">&nbsp;</span>'
								+'</div>'
								+'<br />';
						};

						var jokeTitle= element.Title!=''? element.Title : 'untitled';
						jokeHtml+='<div style="font-weight:bold; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;">'
							+'<a href="joke.php?id='+element.JokeId+'" style="color:black; font-size:20px;">'+jokeTitle+'</a>';

						if ('<?=$id?>'==element.Joker) {
							jokeHtml+='<a onclick="deleteJokeDialogFromList(\'<?=$key?>\',\'<?=$id?>\',\''+element.JokeId+'\',\'<?=$redirUrl?>\')"><img class="img-responsive function-button" src="images/delete.png" alt="Chania"></a>';
						};

						jokeHtml+='</div>'
							+'<div style="padding:0;">'
							+'<span style="font-size:16px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;">'+element.Content+'</span>'
							+'</div>'
							+'<br />'
							+'<span style="color:#1fd8ed; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;"><span id="like-count_'+element.LikeCount+'">'+element.LikeCount+'</span> laughs&nbsp;&nbsp;&nbsp;&nbsp;·&nbsp;&nbsp;&nbsp;&nbsp;<a href="joke.php?id='+element.JokeId+'" style="color:#71e2f2;">'+element.CommentCount+' comments</a></span><br />'
							+'</div>';

						if ('<?=$id?>'!='') {
							var isLiked = element.IsLiked==0? 0.4: 1.0;
							var isReported = element.IsReported==0? 0.4: 1.0;
							jokeHtml+='<div style="background-color:#d9d6d6; padding:5px 15px">'
								+'<div style="display:inline-block;">'
								+'<a onclick="toggleLike(\'<?=$key?>\',\'<?=$joke[JokeId]?>\',\'<?=$id?>\')">'
								+'<img class="img-responsive function-button like-btn_'+element.JokeId+'" '
								+'src="images/laugh.png" style="opacity:'+isLiked+';">'
								+'</a>'
								+'</div>'
								+'<div style="display:inline-block; float:right;">'
								+'<a onclick="report(\'<?=$key?>\',\'<?=$joke[JokeId]?>\',\'<?=$id?>\')">'
								+'<img class="img-responsive function-button report-btn_'+element.JokeId+'" '
								+'src="images/report.png" style="opacity:'+isReported+';">'
								+'</a>'
								+'</div>'
								+'</div>';
						};
							
						jokeHtml+='</div>';


						$('.jokes').append(jokeHtml);
						oldestTime = element.Time;
					}
					i++;
            	});
        	},
        	data: JSON.stringify(person)
        });
    }
});
		</script>
		<?php
		}

		$menu='newjokes';
		$logoutParams='uri=index';
		require_once('html-header.php');
		?>
		<div class="row badan-row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
		<?php
		require_once('jokes.php');
		?>
		</div>
				<div class="col-sm-3">
					<div id="dialog-confirm"></div>
				</div>
			</div>
		</div>
	</body>
</html>
