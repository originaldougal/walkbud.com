<?
// walkBuddy 2.0 
// Dougal Walker
// May, 2014

require_once 'includes/head.php';

if(isset($authUrl)){  //user is not logged in
	header("Location:index.php");
}

$user_id = $_SESSION['user_id'];
$given_name = $_SESSION['given_name'];

$dev_table = 'd_blog.buddy';
$prod_table = 'd_blog.buddy_prod';

// enable one of these to select prod or dev data table
$table = $dev_table;
//$table = $dev_table;

// array of congratulatory phrases to print after submitting a record
$randomphrase = array (
'Oh what a good boy!',
'He\'s a good boy, yes he is yes he is!',
'What a good fuzzmuzzle!',
'Woot!',
'Oh yiss!',
'Who\'s a good boy?!'
);

// -------------------------------------------------------------

if(isset($_REQUEST['flag1'])){
		$querysql = "UPDATE google_users SET flag1=1 WHERE google_id=".$user_id;
		$mysqli->query($querysql);
		//echo $querysql;
		unset($_POST);
		header("Location:app");
}

if(isset($_REQUEST['dogid'])){
		$querysql = "INSERT INTO walks (user, dogid, pee, poop, person) VALUES (".$user_id.", '".filter_var($_REQUEST['dogid'],FILTER_SANITIZE_STRING)."', '".filter_var($_REQUEST['pee'],FILTER_SANITIZE_STRING)."', '".filter_var($_REQUEST['poop'],FILTER_SANITIZE_STRING)."', '".filter_var($_REQUEST['personid'],FILTER_SANITIZE_STRING)."')";
		$mysqli->query($querysql);
		unset($_POST);
		header("Location:app?0=".$_REQUEST['dogid']);
}

$querysql = "SELECT * FROM google_users WHERE google_id = ".$user_id;
$usersresult = $mysqli->query($querysql);

$querysql = "SELECT * FROM dogs WHERE user = ".$user_id;
$dogsresult = $mysqli->query($querysql);

$querysql = "SELECT * FROM people WHERE user = ".$user_id;
$peopleresult = $mysqli->query($querysql);

$querysql = "SELECT 1 FROM walks WHERE user = ".$user_id." ORDER BY timestamp DESC";
$lastout = $mysqli->query($querysql);

// query for walks history
$querysql = "SELECT * FROM walks INNER JOIN people on walks.person=people.id JOIN dogs ON walks.dogid=dogs.id WHERE walks.user = ".$user_id." ORDER BY walks.time DESC LIMIT 12";
$history = $mysqli->query($querysql);

$submitDisability = "disabled"; // sets the submit button to disabled, if there is only one person on the account, it will be changed to "".

if ( $detect->isMobile() ) {				// FOR MOBILE
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Web app to track the excretory functions of pet animals." />
		<meta property="og:type" content="app" />
		<meta property="og:title" content="" />
		<meta property="og:description" content="Web app to track the excretory functions of pet animals." />
		<meta property="og:url" content="" />
		<meta property="og:site_name" content="" />
		<meta name="mobile-web-app-capable" content="yes">
		<link rel="icon" sizes="128x28" href="/icon_128.png">
		<title>walkBud</title>
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		<style>
			h1{
				font-size:2.5em;
			}
			tr {
				margin-left: auto;
				margin-right: auto;
				width: 6em;
				text-align: center;
			}
			.btn-xlarge {
				padding: 15px 25px;
				font-size: 1.3em;
				line-height: normal;
				-webkit-border-radius: 10px;
				   -moz-border-radius: 10px;
						border-radius: 10px;
			}
			.btn.active, .btn:active {
				background-color: #33CC33;
			}
		</style>
	</head>

	<body>
	<div style="padding:10px;"></div>
	  <div class="container" style="width:99%;">
<? } else {											// FOR DESKTOP
    include_once('includes/pagehead.php'); ?>
			<style>
			tr {
				margin-left: auto;
				margin-right: auto;
				width: 6em;
				text-align: center;
			}
			.btn-xlarge {
				padding: 15px 25px;
				font-size: 1.9em;
				line-height: normal;
				-webkit-border-radius: 10px;
				   -moz-border-radius: 10px;
						border-radius: 10px;
			}
			.btn.active, .btn:active {
				background-color: #33CC33;
			}
		</style>
		
	<div class="headline"><h2><?=$given_name?>'s walkBud</h2></div>

	<div class="container" style="width:700px;">
		<div style="padding:10px;"></div>

	<? while($row = mysqli_fetch_array($usersresult)){ 
		if($row['flag1'] == 0){
	?>
	<div class="alert alert-warning alert-dismissable hidden-xs col-md-12" id="alert">
		<i class="icon-lg fa fa-exclamation-triangle" style="float:left;margin:10px 0px 20px 20px;"></i><br/>
		The current view of the web app is simple because it's designed for mobile phones and devices.<br/>
		For best results, log on from your mobile device. This message does not appear on mobile.<br/><br/>
		<a href="app?flag1=1" type="button" class="btn btn-warning" style="padding:10px;width:60px;">OK</a>
	</div>
<?
		}// end flag1=1 ifstatement
	}// end query loop
}// end for desktop														
														// FOR ALL DEVICES
?>
		<div class="panel panel-default">
		<div class="panel-body">
			<span style="margin-left:auto;margin-right;auto;text-align:center;">	
			
			<? if($_REQUEST['0']) { ?> <!-- if submitted a walk, 0 will have some value. -->
			<h2>
			<? 
				echo "Walk activity saved!";
			?>
			
			<br/><br/><center>
			<a href="app"><button class="btn btn-success btn-xlarge" onclick=""><span class="glyphicon glyphicon-repeat"></span></button></a></center>
			<? } else { ?>
			
			<form action="app" method="post" role="form" name="theForm" id="theForm">
				
					<? // BEGIN dog SELECTION BUTTONS
					
					if($dogsresult->num_rows == 1){
						while($row = mysqli_fetch_array($dogsresult)){ 
						echo '
							<input type="hidden" name="dogid" value="'.$row["id"].'"><div style="height:40px;">'.$row["dogname"].'\'s walkBuddy</div>';
						}
					}
					elseif($dogsresult->num_rows == 2){
						echo '<div class="btn-group" data-toggle="buttons" style="width:100%;">';
						while($row = mysqli_fetch_array($dogsresult)){ 
						echo '
							<label class="btn btn-xlarge btn-primary" style="width:50%;">
								<input type="radio" name="dogid" id="dogid'.$row["id"].'" value="'.$row["id"].'" data-toggle="button" required="">'.$row["dogname"].'
							</label>';
						}
						echo '</div><div style="padding:10px;"></div>';
					}
					elseif($dogsresult->num_rows == 3){
						// this really sucks having three dogs. don't have three dogs because it makes it so hard to implement this UI :(
						echo '<div class="btn-group" data-toggle="buttons" style="width:100%;">';
						$i = 0;
						while($row = mysqli_fetch_array($dogsresult)){ 
							if ($i == 0 || $i == 1){
								echo '
									<label class="btn btn-xlarge btn-primary" style="width:50%;">
										<input type="radio" name="dogid" id="dogid'.$row["id"].'" value="'.$row["id"].'" data-toggle="button" required="">'.$row["dogname"].'
									</label>';
							}
							else{
								echo '
									<label class="btn btn-xlarge btn-primary" style="width:100%;">
										<input type="radio" name="dogid" id="dogid'.$row["id"].'" value="'.$row["id"].'" data-toggle="button">'.$row["dogname"].'
									</label>';
							}
							$i++;
						}
					
						echo '</div><div style="padding:10px;"></div>';
					}
					elseif($dogsresult->num_rows == 4){
						echo '<div class="btn-group" data-toggle="buttons" style="width:100%;">';
						$i = 0;
						while($row = mysqli_fetch_array($dogsresult)){ 
							if ($i == 0 || $i == 1){
								echo '
									<label class="btn btn-xlarge btn-primary" style="width:50%;">
										<input type="radio" name="dogid" id="dogid'.$row["id"].'" value="'.$row["id"].'" data-toggle="button" required="">'.$row["dogname"].'
									</label>';
							}
							elseif($i == 2){
								echo '
									<label class="btn btn-xlarge btn-primary" style="width:50%;">
										<input type="radio" name="dogid" id="dogid'.$row["id"].'" value="'.$row["id"].'" data-toggle="button">'.$row["dogname"].'
									</label>';
							}
							else{
								echo '
									<label class="btn btn-xlarge btn-primary" style="width:50%">
										<input type="radio" name="dogid" id="dogid'.$row["id"].'" value="'.$row["id"].'" data-toggle="button">'.$row["dogname"].'
									</label>';
							}
							$i++;
						}
						echo '</div><div style="padding:10px;"></div>';
					}
					else{echo "There was a software error. Are there any dogs on the account?";}
					
					// END dog SELECTION BUTTONS
					
					while($row = mysqli_fetch_array($dogsresult)){ 
						$flag1=$row['flag1'];
					}
				
					?>
					<div class="btn-group" data-toggle="buttons" style="width:100%;">
						<label class="btn btn-xlarge btn-primary" style="width:50%;">
							<input type="checkbox" name="pee" id="peebutton" value="1" data-toggle="button">peed
						</label>
						<label class="btn btn-xlarge btn-primary" style="width:50%;">
							<input type="checkbox" name="poop" id="poopbutton" value="1" data-toggle="button">pooped
						</label>
					</div>
					<div style="padding:10px;"></div> <!-- BUFFER BETWEEN BUTTONS -->
					<?
					
					
					// BEGIN PERSON SELECTION BUTTONS
					
					if($peopleresult->num_rows == 1){
						while($row = mysqli_fetch_array($peopleresult)){ 
						echo '
							<input type="hidden" name="personid" id="personid" value="'.$row["id"].'">';
							$submitDisability = "";
						}
					}
					elseif($peopleresult->num_rows == 2){
						echo '<div class="btn-group" data-toggle="buttons" style="width:100%;">';
						while($row = mysqli_fetch_array($peopleresult)){ 
						echo '
							<label class="btn btn-xlarge btn-primary personid" style="width:50%;">
								<input type="radio" name="personid" id="personid" value="'.$row["id"].'" data-toggle="button" required="">'.$row["personname"].'
							</label>';
						}
						echo '</div><div style="padding:10px;"></div>';
					}
					elseif($peopleresult->num_rows == 3){
						// this really sucks having three people. don't have three people because it makes it so hard to implement this UI :(
						echo '<div class="btn-group" data-toggle="buttons" style="width:100%;">';
						$i = 0;
						while($row = mysqli_fetch_array($peopleresult)){ 
							if ($i == 0 || $i == 1){
								echo '
									<label class="btn btn-xlarge btn-primary personid" style="width:50%;">
										<input type="radio" name="personid" id="personid" value="'.$row["id"].'" data-toggle="button" required="">'.$row["personname"].'
									</label>';
							}
							else{
								echo '
									<label class="btn btn-xlarge btn-primary personid" style="width:100%;">
										<input type="radio" name="personid" id="personid" value="'.$row["id"].'" data-toggle="button">'.$row["personname"].'
									</label>';
							}
							$i++;
						}
					
						echo '</div><div style="padding:10px;"></div>';
					}
					elseif($peopleresult->num_rows == 4){
						echo '<div class="btn-group" data-toggle="buttons" style="width:100%;">';
						$i = 0;
						while($row = mysqli_fetch_array($peopleresult)){ 
							if ($i == 0 || $i == 1){
								echo '
									<label class="btn btn-xlarge btn-primary personid" style="width:50%;">
										<input type="radio" name="personid" id="personid" value="'.$row["id"].'" data-toggle="button" required="">'.$row["personname"].'
									</label>';
							}
							elseif($i == 2){
								echo '
									<label class="btn btn-xlarge btn-primary personid" style="width:50%;">
										<input type="radio" name="personid" id="personid" value="'.$row["id"].'" data-toggle="button">'.$row["personname"].'
									</label>';
							}
							else{
								echo '
									<label class="btn btn-xlarge btn-primary personid" style="width:50%">
										<input type="radio" name="personid" id="personid" value="'.$row["id"].'" data-toggle="button">'.$row["personname"].'
									</label>';
							}
							$i++;
						}
						echo '</div><div style="padding:10px;"></div>';
					}
					else{echo "There was a software error. Are there any dogs on the account?";}
					
					// END PERSON SELECTION BUTTONS
					
					?>
					
					<button class="btn btn-primary btn-xlarge" type="submit" style="width:100%;" id="submitbutton" <?=$submitDisability?> ><span class="glyphicon glyphicon-ok"></span><small>save</small></button>
					<div style="padding:10px;"></div>
					<a href="main"><button class="btn btn-warning btn-small" type="button" style="width:100%;" id="setupbutton">setup</button></a>
					
				</form>
		<? } ?>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>Last outing</strong> was
				
				<?	
					$i=0;
					while($row = mysqli_fetch_array($history)){
						if($i==0){
							echo $row['dogname']; ?> at <?
							echo relativeTime(strtotime($row['time'])); ?> with <?
							echo $row['personname'];
							?></div><div class="panel-body"><center><table><tr><td><h1><?
							if ($row['pee']){?><span class="label label-success">peed</span><?}
							?></h1></td><td>&nbsp;</td><td><h1><?
							if ($row['poop']){?><span class="label label-success">pooped</span><?}
							?></h1></td></tr></center></table><?
						}
						$i++;
					}
					mysqli_data_seek($history, 0); 
					
				?>
				
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">
				<strong>History</strong>
			</div>
			<div class="panel-body">
				<table width="100%">
					<tr>
						<td><strong>Action</strong></td>
						<td><strong>Dog</strong></td>
						<td><strong>Time</strong></td>
						<td><strong>Person</strong></td>
					</tr>

				<?
				while($row = mysqli_fetch_array($history)){
					if($row['pee']){$peeicon = '<span class="label label-success">1</span>';}
					else{$peeicon = '<span class="label label-danger">1</span>';}
					if($row['poop']){$poopicon = '<span class="label label-success">2</span>';}
					else{$poopicon = '<span class="label label-danger">2</span>';}
					
					echo '<tr><td>'.$peeicon.' '.$poopicon.'</td><td>';
					echo $row['dogname'].'</td><td>';
					echo relativeTime(strtotime($row['time'])).'</td><td>'.stripslashes($row['personname']).'</td></tr>';
					
					}
				?>
				
				</table>
			</div>
		</div>
	<!--
	<div class="panel panel-default">
		<div class="panel-body" style="margin-left:auto;margin-right;auto;text-align:center;">
			<small><strong>walkBuddy</strong> by <a href="dougal@dougalwalker.com">Dougal Walker</a></small>
		</div>
	</div>
	-->
	</div> <!-- end container -->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script>
		$(function(){
			$('.personid').click(function(){
				$('#submitbutton').prop("disabled", false);
			});
		});
	</script>
</body>
</html>