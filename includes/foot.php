
    </div><!--/container-->		
    <!--=== End Content Part ===-->

    <!--=== Footer ===-->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 md-margin-bottom-40">
                    <div class="headline"><h3>Coordinate</h3></div>  
                    <p class="margin-bottom-25 md-margin-bottom-40">Don't wait around for him to poop if he already did earlier, out with someone else. Now you'll know when he was out last and what "went down." </p>
					</div>
					<div class="col-md-4 md-margin-bottom-40">
                    <div class="headline"><h3>Track</h3></div>  
                    <p class="margin-bottom-25 md-margin-bottom-40">Get insight into your pet's pooping patterns with our exclusive suite of excretory analytics.</p>    
					</div>
					<div class="col-md-4 md-margin-bottom-40">
                    <div class="headline"><h3>Multi-dog enabled</h3></div>  
                    <p class="margin-bottom-25 md-margin-bottom-40">Got a little pack of your own going? Keep records for multiple dogs with our easy touch interface.</p>
                </div><!--/col-md-4-->
            </div>
        </div> 
    </div><!--/footer-->
    <!--=== End Footer ===-->

    <!--=== Copyright ===-->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-12">               
                    <p>
                        walkBud.com | <a href="about">Contact</a> | <a href="privacy">Privacy Policy</a> | <a href="tos">Terms of Service</a> | walkBud &lt;3s <a href="http://silvers.net">Silvers Networks</a>
							<?$end = microtime(true);$time = number_format(($end - $exeStart), 2);?>
							<span style="float:right;font-size:0.9em;"><?=$version.' '.$time?></span>
                    </p>
                </div>
            </div>
        </div> 
    </div><!--/copyright--> 
    <!--=== End Copyright ===-->
</div><!--/wrapper-->

<!-- JS Global Compulsory -->           
<script type="text/javascript" src="assets/plugins/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="assets/plugins/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="/path/to/bootstrap/js/bootstrap.min.js"></script>
<!-- JS Implementing Plugins -->           
<!-- <script type="text/javascript" src="assets/plugins/back-to-top.js"></script> -->
<!-- JS Page Level -->
<script type="text/javascript" src="/path/to/dist/js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="assets/js/app.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        App.init();
    });
jQuery(document).ready(function($) {
      $(".clickableRow").click(function() {
            window.document.location = $(this).attr("href");
      });
	  $('form:first *:input[type!=hidden]:first').focus();

});
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-43301241-2', 'walkbud.com');
  ga('send', 'pageview');

</script>
<!--[if lt IE 9]>
    <script src="assets/plugins/respond.js"></script>
<![endif]-->

</body>
</html>
<?if($mysqli){$mysqli->close();}?>