<?

require_once 'includes/head.php';
require_once 'includes/pagehead.php';

?>

    	<div class="row">
			<div class="col-md-4">
				<img src="images/phone.png" width="400px" />
			</div>
			<div class="col-md-4">
				<h2>walk<span style="color:green;"><strong>Bud</strong></span></strong> is a simple web app designed for mobile devices that allows you to record your dog's excretory functions. With as few as <span style="color:green;">four taps</span> on your smartphone, you can record what your dog did, when he did it, and who it was with. This is especially useful for multi-human households in situations where different people take the dog out.</h2>
			</div>
			
            <div class="col-md-4">
			<div class="shadow-wrapper" style="">
				<div class="tag-box tag-box-v1 box-shadow shadow-effect-2">

<?

if(isset($authUrl)) //user is not logged in, show login button
{	echo '<h1>Log in to walk<span style="color:green;"><strong>Bud</strong></span></h1><br/>';
	echo '<a class="login" href="'.$authUrl.'"><img src="images/google-login-button.png" height="60px"/></a>';
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
	{					// PANEL ON THE RIGHT SIDE OF THE PAGE WHEN THE USER IS LOGGED IN
		?>
		
		<h2><img src="<?=$profile_image_url?>" height="32" class="img img-circle"> <?=$given_name?>'s walk<span style="color:green;"><strong>Bud</strong></span></h2>
		<hr/>
		<a href="app"><button class="btn btn-success homepageButton">app</button></a>&nbsp;&nbsp;
		<a href="main"><button class="btn btn-primary homepageButton">setup</button></a>&nbsp;&nbsp;
		<a href="/?reset=1"><button class="btn btn-warning homepageButton">logout</button></a>
		<div style="height:12px"></div>
		<?
	}else{
		//user is new
		//echo 'Hi '.$user_name.', Thanks for Registering!';
		$mysqli->query("INSERT INTO google_users (google_id, google_name, google_email, google_link, google_picture_link) 
		VALUES ($user_id, '$user_name','$email','$profile_url','$profile_image_url')");
	}

}

?>
</div></div>
</div>
</div><!--/row-->
<?

require_once 'includes/foot.php';