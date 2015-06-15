<?php
require 'src/facebook.php';  // Include facebook SDK file
//require 'functions.php';  // Include functions
$facebook = new Facebook(array(
  'appId'  => '1454396131497883',   // Facebook App ID 
  'secret' => '31b43458d3777068d04b749bd5447259',  // Facebook App Secret
  'cookie' => true,	
));
$user = $facebook->getUser();

if ($user) {
  try {
    $user_profile = $facebook->api('/me');
  	    $fbid = $user_profile['id'];                 // To Get Facebook ID
 	    $fbuname = $user_profile['username'];  // To Get Facebook Username
 	    $fbfullname = $user_profile['name']; // To Get Facebook full name
	    $femail = $user_profile['email'];    // To Get Facebook email ID
	/* ---- Session Variables -----*/
	    $_SESSION['FBID'] = $fbid;           
	    $_SESSION['USERNAME'] = $fbuname;
        $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;
    //       checkuser($fbid,$fbuname,$fbfullname,$femail);    // To update local DB
  } catch (FacebookApiException $e) {
    error_log($e);
   $user = null;
  }
}
if ($user) {
	header("Location: index.php");
} else {
 $loginUrl = $facebook->getLoginUrl(array(
		'scope'		=> 'email', // Permissions to request from the user
		));
 header("Location: ".$loginUrl);
}
?>
