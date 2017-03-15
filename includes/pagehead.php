<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
    <title>walkBud</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="walkBuddy lets you keep track of your dog's outings, supporting multiple humans and multiple dogs.">
    <meta name="author" content="Dougal Walker">
    <!-- Favicon -->
    <link rel="shortcut icon" href="favicon.ico">
    <!-- CSS Global Compulsory -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="dist/css/bootstrapValidator.min.css"/>
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="assets/plugins/line-icons/line-icons.css">
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.min.css">
    <!-- CSS Page Style -->    
    <link rel="stylesheet" href="assets/css/pages/page_log_reg_v1.css">    
    <!-- CSS Theme -->    
    <link rel="stylesheet" href="assets/css/themes/default.css" id="style_color">
    <!-- CSS Customization -->
    <link rel="stylesheet" href="assets/css/custom.css">	
</head> 

<body class="boxed-layout container">  
<div class="wrapper">
    <!--=== Header ===-->
    <div class="header">
        <!-- Topbar -->
        <div class="topbar">
            <div class="container">
                <!-- Topbar Navigation -->
                <ul class="loginbar pull-right">
					<? if($user_exist) { ?>
						<li>
							<i class="fa fa-user"></i>
							<a><?=$user_name?></a>
							<ul class="lenguages" style="width:auto;">
								<li><a href="index.php?reset=1">log out</a></li>
							</ul>
						</li>
						<li class="topbar-devider"></li> 
					<? } ?>
                    <li>
                        <i class="fa fa-globe"></i>
                        <a>Languages</a>
                        <ul class="lenguages">
							<li><a href="http://walkbud.com<?=$_SERVER['PHP_SELF']?>">English</a></li>
                            <li><a href="http://translate.google.com/translate?js=n&sl=en&tl=zh-CN&u=http://walkbud.com<?=$_SERVER['PHP_SELF']?>">中文简体</a></li>
                            <li><a href="http://translate.google.com/translate?js=n&sl=en&tl=es&u=http://walkbud.com<?=$_SERVER['PHP_SELF']?>">Español</a></li>
							<li><a href="http://translate.google.com/translate?js=n&sl=en&tl=ja&u=http://walkbud.com<?=$_SERVER['PHP_SELF']?>">日本語</a></li>
							<li><a href="http://translate.google.com/translate?js=n&sl=en&tl=ru&u=http://walkbud.com<?=$_SERVER['PHP_SELF']?>">Русский</a></li>
                            <li><a href="http://translate.google.com/translate?js=n&sl=en&tl=de&u=http://walkbud.com<?=$_SERVER['PHP_SELF']?>">Deutsch</a></li>
                        </ul>
                    </li>
					<li class="topbar-devider"></li> 
					<li>
						<a href="help"><i class="fa fa-question-circle"></i> Help</a>
					</li>
						
                </ul>
                <!-- End Topbar Navigation -->
            </div>
        </div>
        <!-- End Topbar -->
    
        <!-- Navbar -->
        <div class="navbar navbar-default" role="navigation">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="fa fa-bars"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">
                        <h1><img src="images/dog.png" style="height:38px;margin-bottom:10px;margin-left:20px;"/> <span style="font-size:1.0em;">walk</span><span style="color:green;font-size:1.2em;"><strong>Bud</strong></span></strong></h1>
							

                    </a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse navbar-responsive-collapse">
                    <ul class="nav navbar-nav">
					    <!-- Blog -->
                        <li <?if($_SERVER['PHP_SELF']=="/index.php"){echo 'class="active"';}?>>
                            <a href="/"><i class="fa fa-home"></i>&nbsp;&nbsp;INTRO</a>
                        </li>
                        <!-- End Blog -->
						<!-- Blog -->
                        <li <?if($_SERVER['PHP_SELF']=="/tour.php"){echo 'class="active"';}?>>
                            <a href="tour"><i class="fa fa-rocket"></i>&nbsp;&nbsp;TOUR</a>
                        </li>
                        <!-- End Blog -->
						<!-- Blog -->
                        <li <?if($_SERVER['PHP_SELF']=="/about.php"){echo 'class="active"';}?>>
                            <a href="about"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;ABOUT</a>
                        </li>
                        <!-- End Blog -->
                        <!-- Blog -->             
						<?
						if($user_exist){
							echo '<li';
							if($_SERVER['PHP_SELF']=="/main.php"){echo ' class="active"';}
							echo '><a href="main"><i class="fa fa-cog"></i> SETUP</a></li>';
							echo '<li';
							if($_SERVER['PHP_SELF']=="/app.php"){echo ' class="active"';}
							echo '><a href="app" class="tooltips" data-toggle="tooltip" data-placement="bottom" data-original-title="Launch the web app"><i class="fa fa-mobile"></i>&nbsp;&nbsp;<strong>APP</strong></a></li>';
						}
						?>
                        <!-- End Blog -->
						
                    </ul>
                </div><!--/navbar-collapse-->
            </div>    
        </div>            
        <!-- End Navbar -->
    </div>
    <!--=== End Header ===-->    

    <!--=== Content Part ===-->
    <div class="container content">	