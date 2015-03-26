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

$helper = new FacebookRedirectLoginHelper(DOMAIN_URL.'createjoke.php');
$session = $helper->getSessionFromRedirect();
$loginUrl=$helper->getLoginUrl();
// $isLoggedIn=0;
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

		// $isLoggedIn=1;
	}
	else {
		header('Location: '.$loginUrl);
	}
}
// else {
// 	$isLoggedIn=1;
// }

$id=isset($_SESSION["id"])? $_SESSION["id"] : '';
$name=isset($_SESSION["name"])? $_SESSION["name"] : '';
$picUrl=isset($_SESSION["picUrl"])? $_SESSION["picUrl"] : '';

$menu='createjoke';
$logoutParams='uri=index';
require_once('html-includes.php');
?>
<script type="text/javascript">
var apiUrlPrefix = '<?php echo API_URL_PREFIX_PUBLIC; ?>';

$( window ).load(function() {
	$('.badan-row').height($(window).height() - $('.kepala').height());
});
$(window).resize(function() {
	$('.badan-row').height($(window).height() - $('.kepala').height());
});

function submitJoke() {
	// var jokeContent = $('.joke-content').val();
	var jokeContent = document.getElementById("jokedrop").innerHTML;
	if (jokeContent.length>153600) {
        alert("Maximum joke content: 150 Kb");
        return false;
    }

	var jokeTitle = $('.joke-title').val();
	if (jokeTitle.length>512) {
        alert("Maximum title: 512 bytes");
        return false;
    }
    
	jokeContent = jokeContent.split("?").join("&#63;");
	if (jokeContent.trim()!='') {
		$('.btn').prop('disabled', true);

		jokeTitle = jokeTitle.split("?").join("&#63;");
		var person = {
            Key: '<?php echo genKey($id); ?>',
            Joker: '<?php echo $id; ?>',
            Title: jokeTitle,
            Content: jokeContent
        }

		$.ajax({
            url: apiUrlPrefix+'joke/createjoke/',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
            	// setTimeout(function(){window.location.href='<?php echo DOMAIN_URL; ?>';}, 200);
            	window.location.href='<?php echo DOMAIN_URL; ?>redirect-index.php';
            },
            data: JSON.stringify(person)
        });
	}
	else {
		$('.joke-content').css({"border-color":"#a94442"});
		$('.joke-content-label').css({"color":"#a94442"});
		// border-color
	};
}
</script>
<script type="text/javascript">
			if(window.FileReader) { 
			  addEventHandler(window, 'load', function() {
			    var status = document.getElementById('status');
			    var drop   = document.getElementById('jokedrop');
			    var list   = document.getElementById('jokedrop');
			  	
			    function cancel(e) {
			      if (e.preventDefault) { e.preventDefault(); }
			      return false;
			    }
			  
			    // Tells the browser that we *can* drop on this target
			    addEventHandler(drop, 'dragover', cancel);
			    addEventHandler(drop, 'dragenter', cancel);
			    addEventHandler(drop, 'drop', function (e) {
			  e = e || window.event; // get window.event if e argument missing (in IE)   
			  if (e.preventDefault) { e.preventDefault(); } // stops the browser from redirecting off to the image.
			
			  var dt    = e.dataTransfer;
			  var files = dt.files;
			  for (var i=0; i<files.length; i++) {
			    var file = files[i];
			    var reader = new FileReader();
			      
			    addEventHandler(reader, 'loadend', function(e, file) {
			    	if (file.size>102400) {
			    		alert("Maximum image size: 100 Kb");
			    	} else {
			    var bin           = this.result; 
			    // var newFile       = document.createElement('div');
			    // newFile.innerHTML = 'Loaded : '+file.name+' size '+file.size+' B';
			    // list.appendChild(newFile);  
			    // var fileNumber = list.getElementsByTagName('div').length;
			    // status.innerHTML = fileNumber < files.length 
			    //                  ? 'Loaded 100% of file '+fileNumber+' of '+files.length+'...' 
			    //                  : 'Done loading. processed '+fileNumber+' files.';
			
			    var img = document.createElement("img"); 
			    img.file = file;
			    img.src = bin;
			    img.className = "img-responsive";
			    list.appendChild(img);
			}
			}.bindToEventHandler(file));
			   
			    reader.readAsDataURL(file);
			  }
			  return false;
				});
			
			  });
			} else { 
			  document.getElementById('status').innerHTML = 'Your browser does not support the HTML5 FileReader.';
			}
			
			function addEventHandler(obj, evt, handler) {
			    if(obj.addEventListener) {
			        // W3C method
			        obj.addEventListener(evt, handler, false);
			    } else if(obj.attachEvent) {
			        // IE method.
			        obj.attachEvent('on'+evt, handler);
			    } else {
			        // Old school method.
			        obj['on'+evt] = handler;
			    }
			}
			
			Function.prototype.bindToEventHandler = function bindToEventHandler() {
			  var handler = this;
			  var boundParameters = Array.prototype.slice.call(arguments);
			  //create closure
			  return function(e) {
			      e = e || window.event; // get window.event if e argument missing (in IE)   
			      boundParameters.unshift(e);
			      handler.apply(this, boundParameters);
			  }
			};
</script>
<style>
#jokedrop {
	border: 1px solid blue;
	padding: 10px;
	margin-bottom: 15px;
}
</style>
<?php
require_once('html-header.php');
	?>

			<div class="row badan-row">
				<?php
				// if (!$isLoggedIn) {
				?>
				<!-- <a href="<php echo $loginUrl; ?>">
					<img class="img-responsive fb-login-btn" src="images/fb-login.png" alt="Chania" 
					style="margin-left:auto; margin-right:auto;">
					</a> -->
<?php
// } else {
?>
<div class="row" style="margin-top:20px;">
<div class="col-sm-2"></div>
<div class="col-sm-8" style="margin:0 30px;">
	<!-- <form role="form">
    	<div class="form-group">
      		<label for="email">Title</label>
      		<input type="email" class="form-control joke-title" id="email" placeholder="Title">
    	</div>
    	<div class="form-group">
  			<label for="comment" class="joke-content-label">Joke Content *</label>
  			<textarea class="form-control joke-content" rows="10" id="comment" placeholder="Write the joke..."></textarea>
		</div>
    	<input type="button" class="btn btn-primary pull-right" onclick="submitJoke()" value="Post" />
  	</form> -->
  	<form role="form">
    	<div class="form-group">
      		<label for="email">Title</label>
      		<input type="email" class="form-control joke-title" id="email" placeholder="Title">
    	</div>
    	<div class="form-group">
  			<label for="comment" class="joke-content-label">Joke Content *</label>
  			<div id="jokedrop" contentEditable="true" style="height:auto; min-height:300px; overflow-x:scroll;"></div>
  			<!-- <textarea id="jokepost" name="jokeContent" hidden="true"></textarea> -->
  		</div>
    	<input type="button" class="btn btn-primary pull-right" onclick="submitJoke()" value="Post" />
  	</form>
</div>
<div class="col-sm-2"></div>
</div>
<?php 
// }
?>
			</div>
		</div>
	</body>
</html>
