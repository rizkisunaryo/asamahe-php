<?php
session_start();

require_once('atil/constants.php');
require_once('lib/password.php');
require_once('sutil/crypto-util.php');
require_once('sutil/http.php');


require_once('lib/Facebook/Entities/AccessToken.php');
require_once('lib/Facebook/FacebookSDKException.php');
require_once('lib/Facebook/FacebookRequestException.php');
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
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\GraphSessionInfo;

$fbAppId = '1554892734776831';
$fbAppSecret = '95d8d439d82ab15984476237f1bda7f3';
FacebookSession::setDefaultApplication($fbAppId,$fbAppSecret);

$jokerId = $_GET['id'];

$redirUrl=DOMAIN_URL.'joker.php?id='.$jokerId;
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
	
	$url = API_URL_PREFIX."user/jokes/";
	$key = genKey($id);

	$postData = array(
	    'Key' => $key,
	    'Viewer' => $id,
	    'Joker' => $jokerId,
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
            Joker: '<?php echo $jokerId; ?>',
            Time: oldestTime,
            IsBefore: 1
        }

        $.ajax({
            url: apiUrlPrefix+'user/jokes/',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                // console.log('test');
                var i = 0;
                $.each(data.Jokes, function(index, element) {
                	if (i!=0) {
                		var jokeHtml='<div class="row" style="padding:5px;">';
                		var picUrl= element.JokerPicUrl!=''? element.JokerPicUrl : 'images/unknown.png';
						jokeHtml+='<a href="joker.php?id='+element.Joker+'"><img src="'+picUrl+'" style="height:60px; display:inline-block; margin-bottom:10px;"></a>';
						var jokerName= element.JokerName!=''? element.JokerName : 'unknown';
						jokeHtml+='<div style="display:inline-block; margin-left:5px;">'
							+'<span style="font-size:14px; font-weight:bold; color:blue;"><a href="joker.php?id='+element.Joker+'">'+jokerName+'</a></span><br />'
							+'<span style="font-size:11px; font-weight:bold; color:#9197a3;">&nbsp;</span>'
							+'</div><br />';
						var jokeTitle= element.Title!=''? element.Title : 'untitled';
						jokeHtml+='<div style="font-size:18px; font-weight:bold;">'
						jokeHtml+='<a href="joke.php?id='+element.JokeId+'">'+jokeTitle+'</a>'
						if (element.Joker=='<?=$id?>') {
							jokeHtml+='<a onclick="deleteJokeDialogFromList(\'<?=$key?>\',\'<?=$id?>\',\''+element.JokeId+'\',\'<?=$redirUrl?>\')"><img class="img-responsive function-button" src="images/delete.png" alt="Chania"></a>'
						}
						jokeHtml+='</div>';
						jokeHtml+='<div style="padding:0 10px; padding-bottom:0px;">'
								+'<span style="font-size:14px;">'+element.Content+'</span>'
							+'</div>'
							+'<br />'
							+'<span>'+element.LikeCount+' laughs · <a href="joke.php?id='+element.JokeId+'">'+element.CommentCount+' comments</a></span><br />';
						
						jokeHtml+='<span>';
						<?php
						if ($id!='') {
						?>
							jokeHtml+='<img class="img-responsive function-button" src="images/laugh.png" alt="Chania">';
							jokeHtml+='<img class="img-responsive function-button" src="images/report.png" alt="Chania">';
						<?php
						}
						?>
							jokeHtml+='</span>';
						
						jokeHtml+='</div>'
						+'<hr />';
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

		$menu='joker';
		$logoutParams='uri=joker&id='.$jokerId;
		require_once('html-header.php');
		$jokerName 		= $jokes['Joker']['Name']==''? 		'Unknown' 				: $jokes['Joker']['Name'];
		$jokerPicUrl 	= $jokes['Joker']['PicUrl']==''? 	'images/unknown.png' 	: $jokes['Joker']['PicUrl'];
		?>
		<div class="row badan-row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
					<div class="row" style="margin-top:20px;">
						<img height="60" src="<?=$jokerPicUrl?>" class="center-block">
						<h4 class="text-center" style="color:#3070c0; font-weight:bold;"><?=$jokerName?></h4>
					</div>
					<!-- <hr /> -->
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
