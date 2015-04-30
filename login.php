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

$redirUrl=DOMAIN_URL.'v2/2.1.0-a/login.php';
$helper = new FacebookRedirectLoginHelper($redirUrl);
$session = $helper->getSessionFromRedirect();

if(isset($session)) {
	$request = new FacebookRequest($session,'GET','/me');
	$response = $request->execute();
	$graph = $response->getGraphObject(GraphUser::className());

	$id = $graph->getId();
	$name = $graph->getName();
	$key = genKey($id);

	echo $id.' : '.$name.' : '.$key;
}
else {
?>
<script type="text/javascript">
window.location.replace("<?php echo $helper->getLoginUrl(); ?>");
</script>
<?php
}

?>