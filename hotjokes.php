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

$redirUrl=DOMAIN_URL.'hotjokes.php';
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
	
$url = API_URL_PREFIX."joke/hotjokes/";
$key = genKey($id);

// Key      string
// 	Viewer   string
// 	Score    int
// 	IsBefore int
// 	From     int
// 	Size     int

$postData = array(
    'Key' => $key,
    'Viewer' => $id,
    'IsBefore' => 1,
    'Score' => -1000,
    'From' => 0,
    'Size' => 100
);
$jokes = httpReq($url,$postData);

require_once('html-includes.php');
$menu='hotjokes';
$logoutParams='uri=hotjokes';
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
