<?php
require_once dirname(__DIR__, 3) . "../../php/classes/autoload.php";
//require_once dirname(__DIR__, 3) . "../../php/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * api for current weather
 *
 * @author Tucker (Github)
 **/

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//start session
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	//grab mySQL statement
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/pimirrors.ini");
	//determine which HTTP method is being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//If method is post handle the sign in logic
	if($method === "POST") {
		//make sure the XSRF Token is valid.
		verifyXsrf();

		//process the request content and decode the json object into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//check to make sure the lat & long is not empty.s
		/*Angular populates a javascript variable called userLocation. userLocation is then decoded with json_decode providing php usable geo location information from the user's browser. If it returns empty,  user must be offline*/
		if(empty($requestObject->userLocation) === true) {
			throw(new \InvalidArgumentException("Currently disconnected", 401));
		} else {
			$profileEmail = filter_var($requestObject->profileEmail, FILTER_SANITIZE_EMAIL);
		}

		/*
		//grab the profile from the database by the email provided
		$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
		if(empty($profile) === true) {
			throw(new \InvalidArgumentException("Invalid Email", 401));
		}
		*/
		/*
		//if the profile activation is not null throw an error
		if($profile->getProfileActivationToken() !== null){
			throw (new \InvalidArgumentException ("you are not allowed to sign in unless you have activated your account", 403));
		} else {
		throw(new \InvalidArgumentException("Invalid HTTP method request."));
	}
		*/

	// if an exception is thrown update the
} catch(\Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);