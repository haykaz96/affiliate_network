<?php

require_once("header.php");
include('models/mail-templates/resend-activation.php');
//Prevent the user visiting the logged in page if he/she is already logged in
include('models/mail-templates/your-lost-password.php');
include('models/mail-templates/your-lost-send.php');
if(isUserLoggedIn()) { header("Location: account.php"); die(); }


//Forgot Password Function
//User has confirmed they want their password changed 
if(!empty($_GET["confirm"]))
{
	$token = trim($_GET["confirm"]);
	
	if($token == "" || !validateActivationToken($token,TRUE))
	{
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	}
	else
	{
		$rand_pass = getUniqueCode(15); //Get unique code
		$secure_pass = generateHash($rand_pass); //Generate random hash
		$userdetails = fetchUserDetails(NULL,$token); //Fetchs user details
		$mail = new userCakeMail();		
		
		if(empty($body1))
		{
			$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
		}			
		else
		{	
			$trans1 = array("#USERNAME#" => ucfirst($userdetails["user_name"]),"#WEBSITENAME#"=>$websiteName,"#WEBSITEURL#"=>$websiteUrl,"#GENERATED-PASS#"=>$rand_pass);
			$result11 = strtr($body1,$trans1);
		
			if(!$mail->sendMail($userdetails["email"],"Your new password",$result11))
			{
				$errors[] = lang("MAIL_ERROR");
			}
			else
			{
				if(!updatePasswordFromToken($secure_pass,$token))
				{
					$errors[] = lang("SQL_ERROR");
				}
				else
				{	
					if(!flagLostPasswordRequest($userdetails["user_name"],0))
					{
						$errors[] = lang("SQL_ERROR");
					}
					else {
						$successes[]  = lang("FORGOTPASS_NEW_PASS_EMAIL");
					}
				}
			}
		}
	}
}

//User has denied this request
if(!empty($_GET["deny"]))
{
	$token = trim($_GET["deny"]);
	
	if($token == "" || !validateActivationToken($token,TRUE))
	{
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	}
	else
	{
		
		$userdetails = fetchUserDetails(NULL,$token);
		
		if(!flagLostPasswordRequest($userdetails["user_name"],0))
		{
			$errors[] = lang("SQL_ERROR");
		}
		else {
			$successes[] = lang("FORGOTPASS_REQUEST_CANNED");
		}
	}
}

//Forms posted
if(!empty($_POST['forgot']))
{
	$forgot =1;
	$email = $_POST["email"];
	$username = sanitize($_POST["username"]);
	
	//Perform some validation
	//Feel free to edit / change as required
	
	if(trim($email) == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
	}
	//Check to ensure email is in the correct format / in the db
	else if(!isValidEmail($email) || !emailExists($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	
	if(trim($username) == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
	}
	else if(!usernameExists($username))
	{
		$errors[] = lang("ACCOUNT_INVALID_USERNAME");
	}
	
	if(count($errors) == 0)
	{
		
		//Check that the username / email are associated to the same account
		if(!emailUsernameLinked($email,$username))
		{
			$errors[] =  lang("ACCOUNT_USER_OR_EMAIL_INVALID");
		}
		else
		{
			//Check if the user has any outstanding lost password requests
			$userdetails = fetchUserDetails($username);
			if($userdetails["lost_password_request"] == 1)
			{
				$errors[] = lang("FORGOTPASS_REQUEST_EXISTS");
			}
			else
			{
				//Email the user asking to confirm this change password request
				//We can use the template builder here
				
				//We use the activation token again for the url key it gets regenerated everytime it's used.
				
				$mail = new userCakeMail();
				$confirm_url = $websiteUrl."forgot-resend.php?confirm=".$userdetails["activation_token"];
				$deny_url = $websiteUrl."forgot-resend.php?deny=".$userdetails["activation_token"];
				
				//Setup our custom hooks
			
				
				if(empty($body2))
				{
					$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
				}
				else
				{
					$date = date('Y-m-d H:i:s');
					$trans12 = array("#USERNAME#" => ucfirst($userdetails["user_name"]), "#CONFIRM-URL#" => $confirm_url,"#WEBSITENAME#"=>$websiteName,"#DENY-URL#"=>$deny_url,"#DATE#"=>$date);
					$result2 = strtr($body2,$trans12);
				
					if($mail->sendMail($userdetails["email"],"Lost password request",$result2))
					{
						//Update the DB to show this account has an outstanding request
						if(!flagLostPasswordRequest($userdetails["user_name"],1))
						{
							$errors[] = lang("SQL_ERROR");
						}
						else {
							
							$successes[] = lang("FORGOTPASS_REQUEST_SUCCESS");
						}
					}
					else
					{
						$errors[] = lang("MAIL_ERROR");
						
					}
				}
			}
		}
	}
}


//Resend Activation Function
//Forms posted
if(!empty($_POST['resend']) && $emailActivation)
{
	$resend = 1;
	$email = $_POST["email1"];
	$username = $_POST["username1"];
	
	//Perform some validation
	//Feel free to edit / change as required
	if(trim($email) == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
	}
	//Check to ensure email is in the correct format / in the db
	else if(!isValidEmail($email) || !emailExists($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	
	if(trim($username) == "")
	{
		$errors[] =  lang("ACCOUNT_SPECIFY_USERNAME");
	}
	else if(!usernameExists($username))
	{
		$errors[] = lang("ACCOUNT_INVALID_USERNAME");
	}
	
	if(count($errors) == 0)
	{
		//Check that the username / email are associated to the same account
		if(!emailUsernameLinked($email,$username))
		{
			$errors[] = lang("ACCOUNT_USER_OR_EMAIL_INVALID");
		}
		else
		{
			$userdetails = fetchUserDetails($username);
		
			//See if the user's account is activation
			if($userdetails["active"]==1)
			{
				$errors[] = lang("ACCOUNT_ALREADY_ACTIVE");
			}
			else
			{
				if ($resend_activation_threshold == 0) {
					$hours_diff = 0;
				}
				else {
					$last_request = $userdetails["last_activation_request"];
					$hours_diff = round((time()-$last_request) / (3600*$resend_activation_threshold),0);
				}
				
				if($resend_activation_threshold!=0 && $hours_diff <= $resend_activation_threshold)
				{
					$errors[] = lang("ACCOUNT_LINK_ALREADY_SENT",array($resend_activation_threshold));
				}
				else
				{
					//For security create a new activation url;
					$new_activation_token = generateActivationToken();
					
					if(!updateLastActivationRequest($new_activation_token,$username,$email))
					{
						$errors[] = lang("SQL_ERROR");
					}
					else
					{
						$mail = new userCakeMail();
						
						$activation_url = $websiteUrl."login-register.php?token=".$new_activation_token;
						if(empty($body))
						{
							$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
						}
						else
						{							
							$trans = array("#USERNAME#" => ucfirst($userdetails["user_name"]), "#ACTIVATION-URL#" => $activation_url,"#WEBSITENAME#"=>$websiteName,"#WEBSITEURL#"=>$websiteUrl);
							$result = strtr($body,$trans);
							
							
							if($mail->sendMail($userdetails["email"],"Verify your email address",$result))
							{
								//Success, user details have been updated in the db now mail this information out.
								$successes[] = lang("ACCOUNT_NEW_ACTIVATION_SENT");
								
							}
							else
							{
								$errors[] = lang("MAIL_ERROR");
							}
						}
					}
				}
			}
		}
	}
}



?>

<body>
<section class="text-center pt-30 pb-30 blue_bg">
<div class="wrapper">
<div class="col-sm-12 text-center">	
	<h2 class="main-heading">Reset Password &amp; Resend Activation.</h2>
	<p>You can use the forms below to regain access to your account. You can reset your password or resend your email confirmation message.</p>
	</div>
</div>
</section>
<section class="login-section">
<div class="wrapper">
<div class="box-shadows login-register">
	
	<div class="mt-30 mb-30 col-sm-6 login">
	<div class="form-wrapper">
<?php 
	if($forgot==1){
		echo resultBlock($errors,$successes);
	}elseif($_GET['deny']){
		echo resultBlock($errors,$successes);
	}elseif($_GET['confirm']){
		echo resultBlock($errors,$successes);
	}
 ?>
<p>
			<h2 class="main-heading">Forgot Password</h2>
				<form name='forgot' id="forgot" action='<?php $_SERVER['PHP_SELF'] ?>' method='post'>
						<label>Username:</label>
						<input type='text' id="username" name='username' />
						   
						<label>Email:</label>
						<input type='text' id="email" name='email' />
						
						<input type='submit' value='Submit' name="forgot" class='submit' />		
				</form>
				</div>
</div>
<div class="mt-30 mb-50 col-sm-6 register">
<div class="form-wrapper">

<?php 
	if($resend==1){
		echo resultBlock($errors,$successes); 
	}elseif(!empty($_GET['token'])){
		echo resultBlock($errors,$successes); 
	}
?>
<p>
<h2 class="main-heading">Resend Activation</h2>
<form name='resend' id="resend" action='<?php $_SERVER['PHP_SELF'] ?>' method='post'>
<label>Username:</label>
<input type='text' id="username1" name='username1' />
<label>Email:</label>
<input type='text' id="email1" name='email1' />
<input type='submit' value='Submit' name="resend" class='submit' />
</form>
</div>
</div>
</div>
</div>
</section>
		


<?php require_once ("footer.php"); ?>
<script>

$().ready(function() {
		$("#forgot").validate({
			rules: {	
				username: "required",
				email: {
					required: true,
					email: true
				}
			},
			messages: {				
				username: "Please enter your username.",
				email: "Please enter a valid email address.",
			}
		});
		$("#resend").validate({
			rules: {	
				username1: "required",
				email1: {
					required: true,
					email: true
				}
			},
			messages: {				
				username1: "Please enter your username.",
				email1: "Please enter a valid email address.",
			}
		});
	});
</script>		