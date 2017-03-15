<?
require_once 'includes/head.php';

if(isset($authUrl)){  //user is not logged in
	header("Location:index");
}

// do queries to get result sets for dogs and people and the walks the user has logged

$querysql = "SELECT * FROM dogs WHERE user = ".$user_id;
$dogsresult = $mysqli->query($querysql);

$querysql = "SELECT * FROM people WHERE user = ".$user_id;
$peopleresult = $mysqli->query($querysql);

if(mysqli_num_rows($peopleresult) == 0){			// INSERT THE FIRST PERSON IF THERE AREN'T ANY
		$querysql = "INSERT INTO people (user, personname) VALUES (".$user_id.", '".$_SESSION['given_name']."')";
		$mysqli->query($querysql);
}
if(mysqli_num_rows($dogsresult) == 0){				// DISPLAY THE ADD DOG MODAL IF THERE ARE NO DOGS
		$forceAddDog = "";
}

if(isset($_REQUEST['dogname'])){					// ADD DOG
	$dogscount = $dogsresult->num_rows;
	if($dogscount > 3){
		$alert = "<strong>Sorry, you can only have four dogs in the system. ".$_REQUEST['dogname']." was not added.</strong>";
		$alertType = "danger";
	}
	else{
		$querysql = "INSERT INTO dogs (user, dogname, sex) VALUES (".$user_id.", '".filter_var($_REQUEST['dogname'],FILTER_SANITIZE_STRING)."', '".$_REQUEST['sex']."')";
		$mysqli->query($querysql);
		$alert = $_REQUEST['dogname']." added.";
		$alertType = "success";
	}	
}

if(isset($_REQUEST['personname'])){					// ADD PERSON
	$peoplecount = $peopleresult->num_rows;
	if($peoplecount > 3){
		$alert = "<strong>Sorry, you can only have four people in the system. ".$_REQUEST['personname']." was not added.</strong>";
		$alertType = "danger";
	}
	else{
		$querysql = "INSERT INTO people (user, personname) VALUES (".$user_id.", '".filter_var($_REQUEST['personname'],FILTER_SANITIZE_STRING)."')";
		$mysqli->query($querysql);
		$alert = $_REQUEST['personname']." added.";
		$alertType = "success";
	}
}

if(isset($_REQUEST['delete'])){						// DELETE DOG
	$querysql = "DELETE FROM dogs WHERE id = ".$_REQUEST['id'];
	$mysqli->query($querysql);
			unset($_REQUEST);
		header("Location:main");
	$alert = "Dog removed.";
	$alertType = 'success';
}

if(isset($_REQUEST['deleteperson'])){				// DELETE PERSON
	$querysql = "DELETE FROM people WHERE id = ".$_REQUEST['id'];
			unset($_REQUEST);
		header("Location:main");
	$mysqli->query($querysql);
	$alert = "Person removed.";
	$alertType = 'success';
}

if(isset($_REQUEST['connectEmail'])){					// CONNECT ACCOUNT
	# check if other account exists
	$querysql = "SELECT * FROM google_users WHERE google_email = '".filter_var($_REQUEST['connectEmail'],FILTER_SANITIZE_STRING)."'";
	$connectresult = $mysqli->query($querysql);
	$connectNumRows = mysqli_num_rows($connectresult);
	if($connectNumRows>0){
		$querysql = "UPDATE google_users SET master_id=".$user_id." WHERE google_email = '".filter_var($_REQUEST['connectEmail'],FILTER_SANITIZE_STRING)."'";
		$connectresult = $mysqli->query($querysql);
		$alert =  $_REQUEST['connectEmail']." connected.";
		$alertType = 'success';
	}
	else{
		$alert = $_REQUEST['connectEmail']." not found.";
		$alertType = 'danger';
	}
}

if(isset($_REQUEST['disconnectEmail'])){					// DISCONNECT ACCOUNT
	$querysql = "UPDATE google_users SET master_id=NULL WHERE google_email = '".filter_var($_REQUEST['disconnectEmail'],FILTER_SANITIZE_STRING)."'";
	$disconnectresult = $mysqli->query($querysql);
	$alert =  $_REQUEST['disconnectEmail']." disconnected.";
	$alertType = 'success';
}

// do the main queries here in case something was added or deleted above

$querysql = "SELECT * FROM dogs WHERE user = ".$user_id;
$dogsresult = $mysqli->query($querysql);
$dogscount = $dogsresult->num_rows;
if($dogscount > 3){
	$dogsDisable = " disabled";
	$dogsButtonMessage = "Can't add more dogs";
}else{
	$dogsButtonMessage = "<strong>+</strong> dog";
}

$querysql = "SELECT * FROM people WHERE user = ".$user_id;
$peopleresult = $mysqli->query($querysql);
$peoplecount = $peopleresult->num_rows;
if($peoplecount > 3){
	$peopleDisable = " disabled";
	$peopleButtonMessage = "Can't add more people";
}
else{
	$peopleButtonMessage = "<strong>+</strong> person";
}

# check if there are any accounts associated with this one so i can display them
$querysql = "SELECT * FROM google_users WHERE master_id = ".$user_id;
$slaveresult = $mysqli->query($querysql);
while ($row = mysqli_fetch_array($slaveresult))
{
	$slaveEmail = $row['google_email'];
}

require_once 'includes/pagehead.php';

if($alert){
	echo '
			<div class="alert alert-'.$alertType.' alert-dismissable" id="alertBox">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			'.$alert.'</div>
	';
}
	?>
<script>setTimeout(function(){$('#alertBox').fadeOut('slow');}, 4500);</script>

<div class="">
	<h3><img src="<?=$profile_image_url?>" height="32" class="img img-circle"> <?=$given_name?>'s Account</h3>
</div>
<div style="height:30px;"></div>
<!-- begin DOGS column -->
<div class="col-md-6">
			<div class="shadow-wrapper" style="width:90%;">
				<div class="tag-box tag-box-v1 box-shadow shadow-effect-2">

<? 
// IF THERE ARE DOGS, LET'S SHOW THE TABLE OF DOGS. OTHERWISE LET'S PESTER THE USER TO ADD ONE. :)
if(mysqli_num_rows($dogsresult)){?>
	<div class="headline"><h2><strong>Dogs</strong></h2></div>
	<p>Did somebody say, <em>"outside?"</em></p>
	<div class="table-responsive" style="">
	  <table class="table table-hover">
		<thead class="text-center">
		<tr>
			<td><h3><strong>Name</strong></h3></td>
			<td><h3><strong>Last out</strong></h3></td>
			<td></td>
		</tr>
		</thead>
		<tbody class="text-center">
			<?																		// THE TABLE OF DOGS
			$timeout = "";
			while($row = mysqli_fetch_array($dogsresult)){
				$querysql = "SELECT * FROM walks WHERE dogid = ".$row['id']." ORDER BY time DESC LIMIT 1"; //check for walks 
				$walksresult = $mysqli->query($querysql);
				$walksnumrows = mysqli_num_rows($walksresult); // get number of walks
				if ($walksnumrows > 0){
					while($rowz = mysqli_fetch_array($walksresult)){	// if there were walks, get the time
						$timeout = relativeTime(strtotime($rowz['time']));
					}
				} else { $timeout = "never"; }

				echo '
				<tr class="">
					<td style="vertical-align: middle"><strong><a href="#"><img src="images/dog.png" style="height:25px; float:left;">'.$row["dogname"].'</a></strong></td>
					<td style="vertical-align: middle"><strong>'.$timeout.'</strong></td>
					<td><a href="main?delete=1&id='.$row["id"].'" ';
					
					//this is the delete confirmation popup
					$js='return confirm("Are you sure you want to remove '.$row["dogname"].'?\n\nThis is irreversible.")';
					echo "onclick='".$js."'";
					
					echo '><span class="glyphicon glyphicon-remove-circle" style="color:red"></span></a></td>
				</tr>
				';
			}
			?>
		</tbody>
	  </table>
	</div>
	<div>
		<!-- <button class="btn-u btn-primary" data-toggle="modal" data-target="#responsive">Add a dog</button> -->
		<a href="#myModal" role="button" class="" data-toggle="modal"><button class="btn btn-success<?=$dogsDisable?>"><?=$dogsButtonMessage?></button></a>
	</div>
	</div>
	</div>
	<? }
	else	// WE GOT NO DOGS, LET'S GET THE USER TO ADD ONE NOW.
	{
		?>
		<div style="height:15px;"></div>
			<div class="shadow-wrapper" style="width:400px;">
				<div class="tag-box tag-box-v1 box-shadow shadow-effect-2">

		<div class="headline"><h2><strong>Add your dog</strong></h2></div>
            <form class="form-horizontal" name="commentform" method="post" action="main">
                <div class="form-group">
                    <label class="control-label col-md-4" for="first_name">Name</label>
                    <div class="col-md-6">
                    	<input type="text" class="form-control" id="dogname" name="dogname" placeholder="Dog's name"/>
                    </div>
                </div>
                <div class="form-group">
                    <!-- Multiple Radios -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="sex">Sex</label>
					  <div class="col-md-4">
					  <div class="radio" style="text-align:left">
						<label for="sex-0">
						  <input type="radio" name="sex" id="sex-0" value="Male">
						  Male
						</label>
						</div>
					  <div class="radio" style="text-align:left">
						<label for="sex-1">
						  <input type="radio" name="sex" id="sex-1" value="Female">
						  Female
						</label>
						</div>
					  </div>
					</div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 pull-right">
					<button type="submit" value="Submit" class="btn btn-custom btn-success" id="send_btn"><span class="glyphicon glyphicon-ok"> </span> add</button>
                    </div>
                </div>
			</div> <!-- ends panel body -->
            </form>
		</div>	<!-- ends panel panel-default -->
	<?
	}										// END DISPLAYING THE FORM IN PLACE OF THE DOGS LIST IN THE CASE THAT THERE ARE NO DOGS
	?>	
</div>														<!-- end 6-column panel for displaying dogs -->

																<!-- begin PEOPLE column -->

<div class="col-md-6">
	<div class="shadow-wrapper" style="width:90%;">
	<div class="tag-box tag-box-v1 box-shadow shadow-effect-2">
	<div class="headline"><h2><strong>People</strong></h2></div>
	<p>Who lets the dogs out?</p>
	<div class="table-responsive" style="">
	  <table class="table table-hover">
		<thead class="text-center">
		<tr>
			<td><h3><strong>Name</strong></h3></td>
			<td><h3><strong>Last out</strong></h3></td>
			<td></td>
		</tr>
		</thead>
		<tbody class="text-center">
<?

$timeout = "";
while($row = mysqli_fetch_array($peopleresult)){
	$querysql = "SELECT * FROM walks WHERE person = ".$row['id']." ORDER BY time DESC LIMIT 1"; //check for walks 
	$walksresult = $mysqli->query($querysql);
	$walksnumrows = mysqli_num_rows($walksresult); // get number of walks
	if ($walksnumrows > 0){
		while($rowz = mysqli_fetch_array($walksresult)){	// if there were walks, get the time
			$timeout = relativeTime(strtotime($rowz['time']));
		}
	} else { $timeout = "never"; }

	echo '
	<tr class="">
		<td style="vertical-align: middle"><i class="fa fa-user fa-lg" style="float:left;"></i><strong> <a href="#">'.$row["personname"].'</a></strong></td>
		<td style="vertical-align: middle"><strong>'.$timeout.'</strong></td>
		<td><a href="main?deleteperson=1&id='.$row["id"].'"><span class="glyphicon glyphicon-remove-circle" style="color:red"></span></a></td> <!-- data-toggle="modal" data-target="#confirm-delete went in that <a> to make it work w confirmation modal -->
	</tr>
	';
}

?>
		</tbody>
  </table>
</div>
<div>
	<a href="#myPeopleModal" role="button" class="" data-toggle="modal"><button class="btn btn-success<?=$peopleDisable?>"><?=$peopleButtonMessage?></button></a>
</div>
</div>
</div>
</div>

<!-- BEGIN SHARING PANEL -->

<div class="col-md-6">
	<div class="shadow-wrapper" style="width:90%;">
	<div class="tag-box tag-box-v1 box-shadow shadow-effect-2">
	<div class="headline"><h2><strong>Sharing</strong></h2></div>
	<?if($slaveEmail && !isset($slave_id)){
	echo 'You\'re sharing account access with <strong>'.$slaveEmail.'</strong><br/><br/><a href="#myDisconnectModal" role="button" class="" data-toggle="modal"><button class="btn btn-warning">Disconnect</button></a>';
	}elseif(!isset($slave_id)){
	echo 'You\'re not sharing access with any other account.<br/><br/><a href="#myConnectModal" role="button" class="" data-toggle="modal"><button class="btn btn-success">Connect an account</button></a>';
	}
	elseif(isset($slave_id)){
			echo 'You\'re sharing this account with <strong>'.$master_email.'</strong>.<br/><br/><a href="#myDisconnectSlaveModal" role="button" class="" data-toggle="modal"><button class="btn btn-warning">Disconnect</button></a>';
	}
	?>
	
</div>
</div>
</div>

<!-- ------------------ begin new dog form ------------------- -->

    <!-- Modal -->
    <div id="myModal" class="modal fade modalOn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title">Add a dog</h2>
        </div>
        <div class="modal-body">
            <form class="form-horizontal" name="commentform" method="post" action="main">
                <div class="form-group">
                    <label class="control-label col-md-4" for="first_name">Name</label>
                    <div class="col-md-6">
                    	<input type="text" class="form-control" id="dogname" name="dogname" placeholder="Dog's name" required="" />
                    </div>
                </div>
                <div class="form-group">
                    <!-- Multiple Radios -->
					<div class="form-group">
					  <label class="col-md-4 control-label" for="sex">Sex</label>
					  <div class="col-md-4">
					  <div class="radio" style="text-align:left">
						<label for="sex-0">
						  <input type="radio" name="sex" id="sex-0" value="Male" required="">
						  Male
						</label>
						</div>
					  <div class="radio" style="text-align:left">
						<label for="sex-1">
						  <input type="radio" name="sex" id="sex-1" value="Female">
						  Female
						</label>
						</div>
					  </div>
					</div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 pull-right">
					<button type="submit" value="Submit" class="btn btn-custom btn-success" id="send_btn"><span class="glyphicon glyphicon-ok"> </span> Save</button>
						<button type="button" class="btn btn-custom btn-u-default" data-dismiss="modal">Close</button>
						<!-- the one that works -->					
                    </div>
                </div>
            </form>
        </div><!-- End of Modal body -->
        </div><!-- End of Modal content -->
        </div><!-- End of Modal dialog -->
    </div><!-- End of Modal -->
	
	
<!-- ------------------ begin new person form ------------------- -->

    <!-- Modal -->
    <div id="myPeopleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title">Add a person</h2>
        </div>
        <div class="modal-body">
            <form class="form-horizontal" name="commentform" method="post" action="main">
                <div class="form-group">
                    <label class="control-label col-md-4" for="first_name">Name</label>
                    <div class="col-md-6">
                    	<input type="text" class="form-control" id="personname" name="personname" placeholder="Person's name"/ required="">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 pull-right">
					<button type="submit" value="Submit" class="btn btn-custom btn-success" id="send_btn"><span class="glyphicon glyphicon-ok"> </span> Save</button>
						<button type="button" class="btn btn-custom btn-u-default" data-dismiss="modal">Close</button>			
                    </div>
                </div>
            </form>
        </div><!-- End of Modal body -->
        </div><!-- End of Modal content -->
        </div><!-- End of Modal dialog -->
    </div><!-- End of Modal -->
	
<!-- ------------------ begin connecting account modal ------------------- -->

    <!-- Modal -->
    <div id="myConnectModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title">Connect an account</h2>
        </div>
        <div class="modal-body">
			<p>To connect another Google account, enter the email address registered to that account.<br/>The second user must have logged into walkBuddy at least once with their Google credentials.<br/>The dogs, people, and their histories on your account will be shared with the account you connect, and both accounts will be able to log walks.</p>
            <form class="form-horizontal" name="connectEmailForm" method="post" action="main">
                <div class="form-group">
                    <label class="control-label col-md-4" for="connectEmail">Email address to connect:</label>
                    <div class="col-md-6">
                    	<input type="text" class="form-control" id="connectEmail" name="connectEmail" placeholder="" required=""/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 pull-right">
					<button type="submit" value="Submit" class="btn btn-custom btn-success" id="send_btn"><span class="glyphicon glyphicon-ok"> </span> Connect</button>
						<button type="button" class="btn btn-custom btn-u-default" data-dismiss="modal">Cancel</button>			
                    </div>
                </div>
            </form>
        </div><!-- End of Modal body -->
        </div><!-- End of Modal content -->
        </div><!-- End of Modal dialog -->
    </div><!-- End of Modal -->
	
<!-- ------------------ begin DISconnecting account modal ------------------- -->

    <!-- Modal -->
    <div id="myDisconnectModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h2 class="modal-title">Disconnect an account</h2>
        </div>
        <div class="modal-body">
            <form class="form-horizontal" name="disconnectForm" method="post" action="main">
				<input type="hidden" name="disconnectEmail" value="<?=$slaveEmail?>">
                <div class="form-group">
					<div class="text-center">
					<p>Are you sure you want to disconnect<strong> <?=$slaveEmail?></strong>?<br/>They will no longer be able to access the dogs, people, and history on this account.</p>
					<button type="submit" value="Submit" class="btn btn-custom btn-danger" id="send_btn"><span class="glyphicon glyphicon-remove-circle"> </span> Disconnect </button>
						<button type="button" class="btn btn-custom btn-u-default" data-dismiss="modal">Cancel</button>			
                    </div>
                </div>
            </form>
        </div><!-- End of Modal body -->
        </div><!-- End of Modal content -->
        </div><!-- End of Modal dialog -->
    </div><!-- End of Modal -->	
	

<!-- ------------------------------- begin confirm delete modal ------------------------------- -->
									
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm
            </div>
            <div class="modal-body">
                Are you sure you want to delete this dog?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="<?=$deleteUrl?>" class="btn btn-danger danger">Delete</a>
            </div>
        </div>
    </div>
</div> <!-- end confirm delete modal -->

</div><!-- End of Container -->
<div style="height:20px;"></div>

<?
require_once 'includes/foot.php';