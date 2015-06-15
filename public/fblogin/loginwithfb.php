<?php
session_start();
$_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection

$app_id = "1454396131497883";//change this
$redirect_url = "http://www.hassyl.com/fblogin/callback.php"; //change this

$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($redirect_url) . "&state="
       . $_SESSION['state'] . "&scope=email,read_friendlists,user_online_presence";

?>
<html>
<body>
<h1>Facebook OAuth Dailog Demo</h1>

Click the image to see how OAuth works in Facebook.
<a href="<?php echo $dialog_url;?>"><img src="login-fb2.jpg"></a>
</html>