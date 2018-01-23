<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>

<!DOCTYPE html>
<html lang="en">
<head>
          
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Website Name</title>
<meta name="robots" content="index, follow">
<meta name="revisit-after" content="7 days">
<meta name="keywords" content="" />
<meta name="description" content="" />
<link href="<?php echo $template ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="models/site-templates/css/font-awesome.css">
<link rel="stylesheet" href="models/site-templates/css/font-awesome.min.css">
<link rel="stylesheet" href="models/site-templates/css/jquery-ui.css">

<script src="models/site-templates/js/jquery.min.js"></script> 
<script src="models/site-templates/js/jquery-1.12.4.js"></script> 
<script src="models/site-templates/js/jquery-ui.js"></script>
<script src="models/site-templates/js/jquery.validate.js"></script> 
<script src="models/site-templates/js/jquery.validate.min.js"></script>
<script src="models/site-templates/js/exporting.js"></script>   

</head>
<body class="top-border">
<header>
<div class="outer-wrapper">
<section class="top-header">
  <div class="wrapper">
  	<div class="call-now">
    	<ul>
		<li><span><i class="fa fa-phone"></i></span>01234 567 891</li>
		<li><span><i class="fa fa-envelope"></i></span>contact@website.co.uk</li>
		</ul>
    </div>
    <div class="signin">
            <ul>
            <li><a href="register.php">Join Us</i></a></li>
            <li><a href="index.php">Sign In</a></li>
            </ul>
    </div>
  </div>
</section>
</div>
<div class="outer-wrapper">
<section class="menu-section">
	<div class="wrapper">
		<div class="logo">
			<a href="index.php"><img src="models/site-templates/images/logo.jpg"></a>
		</div>
		<div class="menu">
            <ul>
            <li><a href="contact-us.php">Contact Us</a></li>
            </ul>
		</div>
	</div>
</section> 
</div>                                        
</header>