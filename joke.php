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

$jokeId = $_GET['id'];

$helper = new FacebookRedirectLoginHelper(DOMAIN_URL.'joke.php?id='.$jokeId);
$session = $helper->getSessionFromRedirect();
$loginUrl=$helper->getLoginUrl();
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
$picUrl=isset($_SESSION["picUrl"])? $_SESSION["picUrl"] : 'images/unknown.png';

$url = API_URL_PREFIX."joke/joke/";
$key = genKey($id);
$postData = array(
	'Key' => $key,
	'Viewer' => $id,
	'JokeId' => $jokeId
);
$joke = httpReq($url,$postData);

$jokeTitle='untitled';
$title = 'asamahe - untitled joke';
if ($joke['Joke']['Title']!="") {
	$jokeTitle=$joke['Joke']['Title'];
	$title = $joke['Joke']['Title'];
}
require_once('html-includes.php');
?>
<script type="text/javascript">
var apiUrlPrefix = '<?php echo API_URL_PREFIX_PUBLIC; ?>';
var id = '<?=$id?>';
var idKey = '<?=$key?>';

function checkLogin() {
	if (id=='') {
		window.location.assign("<?=$loginUrl?>");
	}
}

function submitComment() {
	var commentVal = $('.comment').val();
	if (commentVal.length>2048) {
        alert("Maximum per comment: 2 Kb");
        return false;
    }
	commentVal = commentVal.split("?").join("&#63;");
	if (commentVal.trim()!='') {
		$('.btn').prop('disabled', true);

		var person = {
            Key: '<?php echo genKey($id); ?>',
            Commentator: '<?php echo $id; ?>',
            JokeId: '<?php echo $jokeId; ?>',
            Comment: commentVal
        }

	$.ajax({
            url: apiUrlPrefix+'comment/comment/',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                // setTimeout(function(){window.location.href='<?php echo DOMAIN_URL."joke.php?id=".$jokeId; ?>';}, 200);
                // window.location.href='<?php echo DOMAIN_URL."joke.php?id=".$jokeId; ?>';
                // window.location.reload();
                if (data.Status==0) {
                	var html = '<div class="row" id="'+data.Id+'" style="min-height:80px; padding-left:60px; margin-bottom:0px; background-image:url(\'<?=$picUrl?>\'); background-repeat: no-repeat; background-position: left top;">'
								+'<div>'
								+'<a href="joker.php?id=<?=$id?>"><span style="font-size:14px; font-weight:bold; color:blue; word-wrap:break-word;"><?=$name?></span></a>&nbsp;'
								+'<a onclick="deleteCommentDialog(\''+data.Id+'\')"><img class="img-responsive function-button" src="images/delete.png" alt="Chania"></a>'
								+'<br />'
								+'<span style="font-size:14px; word-wrap:break-word;">'+commentVal+'</span>'
								+'</div>'
								+'<br />'
							+'</div>';
					$('.jokes').append(html);
					$('.comment').val('');
					$('#commentCount').text(parseInt($('#commentCount').text())+1);
					$("html, body").animate({ scrollTop: $(document).height() }, 500);
                } else {
                	alert("Ups, something wrong.");
                }
                $('.btn').prop('disabled', false);
            },
            data: JSON.stringify(person)
        });
	}
	else {
		$('.comment').css({"border-color":"#a94442"});
		$('.comment-label').css({"color":"#a94442"});
		// border-color
	};
}

function deleteCommentDialog(commentId) {
    $("#dialog-confirm").html("Do you want to delete this comment?<br />(CAN'T BE UNDONE)");

    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Delete Comment",
        height: 200,
        width: 300,
        buttons: {
            "Yes": function () {
            	deleteCommentAction(commentId);
                $(this).dialog('close');
            },
            "Cancel": function () {
                $(this).dialog('close');
            }
        }
    });
}

function deleteCommentAction(commentId) {
    var person = {
    	Key: '<?php echo genKey($id); ?>',
    	UserId: '<?php echo $id; ?>',
    	JokeId: '<?php echo $jokeId; ?>',
    	CommentId: commentId
    }

	$.ajax({
		url: apiUrlPrefix+'comment/deletecomment/',
		type: 'POST',
		dataType: 'json',
		success: function(data) {
			// setTimeout(function(){window.location.href='<?php echo DOMAIN_URL."joke.php?id=".$jokeId; ?>';}, 200);
			// window.location.href='<?php echo DOMAIN_URL."joke.php?id=".$jokeId; ?>';
			// window.location.reload();
			$('#'+commentId).hide(500);
			$('#commentCount').text(parseInt($('#commentCount').text())-1);
		},
		data: JSON.stringify(person)
    });
}
</script>
<?php
$logoutParams='uri=joke&id='.$jokeId;
require_once('html-header.php');
?>

			<div class="row badan-row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
					<div class="row jokes" style="margin-left:10px; margin-right:10px; margin-top:10px;">
						<div style="background-color:white; border-top-left-radius:10px; border-top-right-radius:10px; padding:10px 20px 20px;">
							<?php
								$picUrl='images/unknown.png';
								if ($joke['Joke']['JokerPicUrl']!="") {
									$picUrl = $joke['Joke']['JokerPicUrl'];
								}
								?>
							<div class="profPicHolder"><a href="joker.php?id=<?=$joke['Joke']['Joker']?>"><img src="<?=$picUrl?>" style="height:60px; display:inline-block;"></a></div>
							<div style="display:inline-block;">
								<?php
								$jokerName='unknown';
								if ($joke['Joke']['JokerName']!="") {
									$jokerName=$joke['Joke']['JokerName'];
								}
								?>
								<span style="font-size:20px; font-weight:bold; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; margin-left:10px;"><a href="joker.php?id=<?=$joke['Joke']['Joker']?>" style="color:#1fd8ed"><?=$jokerName?></a></span><br />
								<span style="font-size:11px; font-weight:bold; color:#9197a3;"><!--?=date_format(date_create($joke['Time']),"d M - h:i a")?-->&nbsp;</span>
							</div>
							<br />
							<?php
								$jokeTitle='untitled';
								if ($joke['Joke']['Title']!="") {
									$jokeTitle=$joke['Joke']['Title'];
								}
								?>
							<div style="color:black; font-size:20px; font-weight:bold; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;">
								<?=$jokeTitle?>
								<?php
								if ($id==$joke['Joke']['Joker']) {
								?>
								<a onclick="deleteJokeDialog('<?=$key?>','<?=$id?>','<?=$jokeId?>','<?=DOMAIN_URL?>redirect-index.php')"><img class="img-responsive function-button" src="images/delete.png" alt="Chania"></a>
								<?php
								}
								?>
							</div>
							<div style="padding:0;">
								<span style="font-size:16px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;"><?=$joke['Joke']['Content']?></span>
							</div>
							<br />
							<span style="color:#1fd8ed; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;"><span id="like-count_<?=$joke['Joke']['JokeId']?>"><?=$joke['Joke']['LikeCount']?></span> laughs&nbsp;&nbsp;&nbsp;&nbsp;·&nbsp;&nbsp;&nbsp;&nbsp;<?=$joke['Joke']['CommentCount']?> comments</span><br />
						</div>
							
						<div style="background-color:#d9d6d6; padding:5px 15px">
							<?php
							if ($id!='') {
							?>
							<!-- <div style="border-radius:10px; border-style:solid; border-color:white; padding:5px; display:inline-block;"> -->
							<div style="display:inline-block;">
							<a onclick="toggleLike('<?=$key?>','<?=$joke[Joke][JokeId]?>','<?=$id?>')">
								<img class="img-responsive function-button like-btn_<?=$joke[Joke][JokeId]?>" 
								src="images/laugh.png" style="opacity:<?php echo $joke['Joke']['IsLiked']==0? 0.4 : 1.0; ?>">
							</a>
							</div>
							<?php } ?>
							<div class="fb-share-button" data-href="<?php echo DOMAIN_URL.'joke.php?id='.$jokeId; ?>" data-layout="button_count" style="margin-left:5px; display:inline-block;"></div>
							<?php 
							if ($id!='') {
							?>
							<div style="display:inline-block; float:right;">
							<a onclick="report('<?=$key?>','<?=$joke[Joke][JokeId]?>','<?=$id?>')">
								<img class="img-responsive function-button report-btn_<?=$joke['Joke']['JokeId']?>" 
								src="images/report.png" style="opacity:<?php echo $joke['Joke']['IsReported']==0? 0.4 : 1.0; ?>">
							</a>
							</div>
							<?php } ?>
						</div>

						<br />

							<form role="form">
								<div class="form-group">
      								<label class="sr-only comment-label" for="comment">Comment:</label>
      								<textarea class="form-control comment" rows="3" id="comment" placeholder="Write your comment..." onfocus="checkLogin()"></textarea>
    							</div>
    							<input type="button" class="btn btn-primary pull-right" onclick="submitComment()" value="Post" />
							</form>
							<br />
							<br />
							<?php
							if (!is_null($joke['Comments'])) {
							foreach ($joke['Comments'] as $arrayKey => $comment) {
								$commentatorPicUrl='images/unknown.png';
								if ($comment['CommentatorPicUrl']!="") {
									$commentatorPicUrl = $comment['CommentatorPicUrl'];
								}
							?>
							<div class="row" id="<?=$comment[CommentId]?>" style="min-height:80px; padding-left:60px; margin-bottom:0px; background-image:url('<?=$commentatorPicUrl?>'); background-repeat: no-repeat; background-position: left top;">
								<div>
								<?php
								$jokerName='unknown';
								if ($comment['CommentatorName']!="") {
									$jokerName=$comment['CommentatorName'];
								}
								?>
								<a href="joker.php?id=<?=$comment['Commentator']?>"><span style="font-size:14px; font-weight:bold; color:blue; word-wrap:break-word;"><?=$jokerName?></span></a>
								<?php
								if ($comment['Commentator']==$id) {
								?>
								<a onclick="deleteCommentDialog('<?=$comment[CommentId]?>')"><img class="img-responsive function-button" src="images/delete.png" alt="Chania"></a>
								<?php
								}
								?>
								<br />
								<span style="font-size:14px; word-wrap:break-word;"><?=$comment['Comment']?></span>
								</div>
								<br />
							</div>
							<?php
							}
							}
							?>
							<div id="dialog-confirm"></div>
					</div>
				</div>
				<div class="col-sm-3">
					<div id="dialog-confirm"></div>
				</div>
			</div>
		</div>
	</body>
</html>
