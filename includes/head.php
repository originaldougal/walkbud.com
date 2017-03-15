<?

session_start();
require_once 'includes/config.php';
$exeStart = microtime(true);

# include google api files
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_Oauth2Service.php';
require_once 'Mobile_Detect.php';

# do some googly things
$detect = new Mobile_Detect;
$gClient = new Google_Client();
$gClient->setApplicationName('walkBuddy login');
$gClient->setClientId($google_client_id);
$gClient->setClientSecret($google_client_secret);
$gClient->setRedirectUri($google_redirect_url);
$gClient->setDeveloperKey($google_developer_key);
$google_oauthV2 = new Google_Oauth2Service($gClient);

# to log out, unset Session variable
if (isset($_REQUEST['reset'])) 
{
  unset($_SESSION['token']);
  $gClient->revokeToken();
  header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL)); //redirect user back to page
  session_destroy();
}

# If code is empty, redirect user to google authentication page for code. Code is required to aquire Access Token from google Once we have access token, assign token to session variable and we can redirect user back to page and login.
if (isset($_GET['code'])) 
{ 
	$gClient->authenticate($_GET['code']);
	$_SESSION['token'] = $gClient->getAccessToken();
	header('Location: ' . filter_var($google_redirect_url, FILTER_SANITIZE_URL));
	return;
}

if (isset($_SESSION['token'])) 
{ 
	$gClient->setAccessToken($_SESSION['token']);
}


if ($gClient->getAccessToken()) 
{
	  # For logged in user, get details from google using access token
	  $user 				= $google_oauthV2->userinfo->get();
	  $user_id 				= $user['id'];
	  $user_name 			= filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $given_name 			= filter_var($user['given_name'], FILTER_SANITIZE_SPECIAL_CHARS);
	  $email 				= filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	  $profile_url 			= filter_var($user['link'], FILTER_VALIDATE_URL);
	  $profile_image_url 	= filter_var($user['picture'], FILTER_VALIDATE_URL);
	  $personMarkup 		= "$email<div><img src='$profile_image_url?sz=50'></div>";
	  $_SESSION['token'] 	= $gClient->getAccessToken();
}
else 
{
	//For Guest user, get google login url
	$authUrl = $gClient->createAuthUrl();
}


if(isset($authUrl)) //user is not logged in, show login button
{
	//echo '<a class="login" href="'.$authUrl.'"><img src="images/google-login-button.png" height="60px"/></a>';
} 
else // user logged in 
{
   /* connect to database using mysqli */
	$mysqli = new mysqli($hostname, $db_username, $db_password, $db_name);
	
	if ($mysqli->connect_error) {
		die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
	}
	
	//compare user id in our database
	$user_exist = $mysqli->query("SELECT COUNT(google_id) as usercount FROM google_users WHERE google_id=$user_id")->fetch_object()->usercount; 
	if($user_exist)
	{
		//	some stuff here for user existing
	}else{ 
		//user is new
		$mysqli->query("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link) 
		VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url')");
	}
	
	// set us up some session vars
	$_SESSION['user_id'] = $user_id;
	$_SESSION['given_name'] = $given_name;

	# CHECK FOR SLAVE ACCOUNT STATUS HERE AND OVERRIDE SESSION VARIABLES TO MASTER IDs

	$querysql = "SELECT * FROM google_users WHERE google_id = ".$user_id;
	$isSlaveresult = $mysqli->query($querysql);
	while($row = mysqli_fetch_array($isSlaveresult)){
		$master_id = $row['master_id'];										// get the master_id for this user's record so we can set it as the user
	}
	if($master_id != 0){													// if there is a master_id on this record, which indicates that it's a slave
		$slave_id = $_SESSION['user_id'];
		$_SESSION['user_id'] = $master_id;									// override session and temp variables
		$user_id = $master_id;		
		$querysql = "SELECT * FROM google_users WHERE google_id = ".$master_id;
		$masterresult = $mysqli->query($querysql);
		while($row = mysqli_fetch_array($masterresult)){
			$master_name = $row['google_name'];								// get the master_id for this user's record so we can set it as the user
			$master_email = $row['google_email'];
		}
	}
	
	# debugging for checking on the master and slave id settings
	/*
	echo '<pre>logged in.<br/>';
	echo 'session user_id ='.$_SESSION['user_id'].'<br/>';
	echo 'session slave_id='.$_SESSION['slave_id'].'<br/>';
	echo 'var user_id ='.$user_id.'<br/>';
	echo 'var slave_id='.$slave_id.'<br/>';
	echo '</pre>';
	*/
}


// Relative Time Generator from http://stackoverflow.com/questions/11/how-do-i-calculate-relative-time
define("SECOND", 1);
define("MINUTE", 60 * SECOND);
define("HOUR", 60 * MINUTE);
define("DAY", 24 * HOUR);
define("MONTH", 30 * DAY);
function relativeTime($time){   
    $delta = time() - $time;
    if ($delta < 1 * MINUTE){ return "just now"; }
    if ($delta < 2 * MINUTE){ return "a minute ago"; }
    if ($delta < 45 * MINUTE){ return floor($delta / MINUTE) . " minutes ago"; }
    if ($delta < 90 * MINUTE){ return "an hour ago"; }
    if ($delta < 24 * HOUR){ return floor($delta / HOUR) . " hours ago"; }
    if ($delta < 48 * HOUR){ return "yesterday"; }
    if ($delta < 30 * DAY){ return floor($delta / DAY) . " days ago"; }
    if ($delta < 12 * MONTH){ $months = floor($delta / DAY / 30); return $months <= 1 ? "one month ago" : $months . " months ago";}
    else{$years = floor($delta / DAY / 365); return $years <= 1 ? "one year ago" : $years . " years ago";}
}


?>