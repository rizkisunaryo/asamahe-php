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

$helper = new FacebookRedirectLoginHelper(DOMAIN_URL.'topjokers.php');
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
	
$url = API_URL_PREFIX."user/topjokers/";
$key = genKey($id);

$postData = array(
    'Key' => $key,
    'Viewer' => $id,
    'IsBefore' => 1
);
$jokers = httpReq($url,$postData);

require_once('html-includes.php');
$menu='topjokers';
$logoutParams='uri=topjokers';
require_once('html-header.php');
?>
		<div class="row badan-row">
				<div class="col-sm-3"></div>
				<div class="col-sm-6">
				<?php
				foreach ($jokers['Jokers'] as $key => $joker) {
				$jokerName 		= $joker['Name']==''? 		'Unknown' 				: $joker['Name'];
				$jokerPicUrl 	= $joker['PicUrl']==''? 	'images/unknown.png' 	: $joker['PicUrl'];
				?>
					<div class="row" style="margin-top:20px;">
						<a href="joker.php?id=<?=$joker['Id']?>"><img height="60" src="<?=$jokerPicUrl?>" class="center-block"></a>
						<a href="joker.php?id=<?=$joker['Id']?>"><h4 class="text-center" style="color:#3070c0; font-weight:bold;"><?=$jokerName?></h4></a>
					</div>
					<hr />
				<?php
				}
				?>
				</div>
				<div class="col-sm-3"></div>
			</div>
		</div>
	</body>
</html>
