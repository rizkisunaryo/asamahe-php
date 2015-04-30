<?php 

require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/Entities/AccessToken.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/FacebookSDKException.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/FacebookRequestException.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/FacebookAuthorizationException.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/FacebookRedirectLoginHelper.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/HttpClients/FacebookHttpable.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/HttpClients/FacebookCurl.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/HttpClients/FacebookCurlHttpClient.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/FacebookRequest.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/FacebookResponse.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/FacebookSession.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/GraphObject.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/GraphUser.php');
require_once($_SERVER['DOCUMENT_ROOT'] .'/lib/Facebook/GraphSessionInfo.php');

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

$GLOBALS['fbAppId'] = '1554892734776831';
$GLOBALS['fbAppSecret'] = '95d8d439d82ab15984476237f1bda7f3';

	function loginFB($uri) {
FacebookSession::setDefaultApplication($GLOBALS['fbAppId'],$GLOBALS['fbAppSecret']);

$helper = new FacebookRedirectLoginHelper(DOMAIN_URL.$uri);
$session = $helper->getSessionFromRedirect();
if(isset($session)) {
	$request = new FacebookRequest($session,'GET','/me');
	$response = $request->execute();
	$graph = $response->getGraphObject(GraphUser::className());
	$_SESSION["id"] = $graph->getId();
	$_SESSION['name'] = $graph->getName();
}
return $helper;
	}
?>