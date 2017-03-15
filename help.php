<?
require_once 'includes/head.php';
require_once 'includes/pagehead.php';
?>

<div class="row-fluid" style="width:90%;margin:0 auto 0 auto;">
	<div class="headline"><h2>Help</h2></div>
	<h2>How do I...</h2>
	<!-- Accordion-->
	<div class="panel-group acc-v1" id="accordion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						add a dog to my account?
					</a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse">
				<div class="panel-body">
					<p>Log in, click the <i class="fa fa-cog"></i> SETUP tab at the top of the page, then click the <span class="label label-success">+ dog</span> button.</p>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
						add a person to my account?
					</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse">
				<div class="panel-body">
					<p>Log in, click the <i class="fa fa-cog"></i> SETUP tab at the top of the page, then click the <span class="label label-success">+ person</span> button.</p>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
						use the app on my phone or tablet?
					</a>
				</h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse">
				<div class="panel-body">
					<p>Visit <a href="http://walkbud.com/app">walkbud.com/app</a> on your mobile device's web browser.<br/>
					It's a good idea to bookmark that page for future use, and to create a shortcut on your device's home screen so you can access the app in one tap, just as if it were installed on your phone.</p>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
						keep track of multiple dogs?
					</a>
				</h4>
			</div>
			<div id="collapseFour" class="panel-collapse collapse">
				<div class="panel-body">
					<p>If you add more than one person to your account, you will be able to select which person is walking the dog when the outing is logged. If there is only one person on the account, no person selection is needed on the app screen.</p>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
						do something not listed here?
					</a>
				</h4>
			</div>
			<div id="collapseFive" class="panel-collapse collapse">
				<div class="panel-body">
					<p><a href="about">Contact us</a>.</p>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title">
					<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseSix">
						notify people by text message?
					</a>
				</h4>
			</div>
			<div id="collapseSix" class="panel-collapse collapse">
				<div class="panel-body">
					<p>Help on this topic has yet to be developed. Sorry!</p>
				</div>
			</div>
		</div>
	</div><!--/acc-v1-->
	<!-- End Accordion-->
</div><!--/row-fluid-->       
<?
require_once 'includes/foot.php';
?>