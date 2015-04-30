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

FacebookSession::setDefaultApplication(FB_APP_ID,FB_APP_SECRET);

$v = 'v2/2.1.0-a/';

$redirUrl=DOMAIN_URL.$v.'login.php?page='.$_GET['page'];
$helper = new FacebookRedirectLoginHelper($redirUrl);
$session = $helper->getSessionFromRedirect();

if(isset($session)) {
	$request = new FacebookRequest($session,'GET','/me');
	$response = $request->execute();
	$graph = $response->getGraphObject(GraphUser::className());

	$id = $graph->getId();
	$name = $graph->getName();
	$key = genKey($id);
	$picUrl = 'https://graph.facebook.com/'.$graph->getId().'/picture';

	$url = API_URL_PREFIX."user/setuser/";
	$postData = array(
    	'Id' => $id,
    	'Key' => $key,
    	'Name' => $name,
    	'PicUrl' => $picUrl
	);
	httpReq($url,$postData);

	$cnId = "id";
	$cvId = "fb_".$id;
	setcookie($cnId, $cvId, time() + (86400 * 7), "/"); // 86400 = 1 day

	$cnName = "name";
	$cvName = $name;
	setcookie($cnName, $cvName, time() + (86400 * 7), "/"); // 86400 = 1 day

	$cnKey = "key";
	$cvKey = $key;
	setcookie($cnKey, $cvKey, time() + (86400 * 7), "/"); // 86400 = 1 day

	$cnPicUrl = "picUrl";
	$cvPicUrl = $picUrl;
	setcookie($cnPicUrl, $cvPicUrl, time() + (86400 * 7), "/"); // 86400 = 1 day

	$page = $_GET['page'];
	$goBackPage = DOMAIN_URL.$v.$page;
?>
<script type="text/javascript">
window.location.replace("<?php echo $goBackPage; ?>");
</script>
<?php
}
else {
?>
<script type="text/javascript">
window.location.replace("<?php echo $helper->getLoginUrl(); ?>");
</script>
<?php
}

?>