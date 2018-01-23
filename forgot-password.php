<?php

require_once("models/config.php");
include('models/mail-templates/your-lost-send.php');
include('models/mail-templates/your-lost-password.php');
if (!securePage($_SERVER['PHP_SELF'])){die();}

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
if(!empty($_POST))
{
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
				$confirm_url = $websiteUrl."forgot-password.php?confirm=".$userdetails["activation_token"];
				$deny_url = $websiteUrl."forgot-password.php?deny=".$userdetails["activation_token"];
				
				
				$date = date('Y-m-d H:i:s');
				$trans12 = array("#USERNAME#" => ucfirst($userdetails["user_name"]), "#CONFIRM-URL#" => $confirm_url,"#WEBSITENAME#"=>$websiteName,"#DENY-URL#"=>$deny_url,"#DATE#"=>$date);
				$result2 = strtr($body2,$trans12);
				
				if(empty($body2))
				{
					$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
				}			
				else
				{
					if(!$mail->sendMail($userdetails["email"],"Lost password request",$result2))
					{
						$errors[] = lang("MAIL_ERROR");
					}
					else
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
				}
			}
		}
	}
}

require_once("header.php");

?>

<div class="outer-wrapper">
<section class=" blue-bg main-content">
<div class="wrapper">
<h1 class="form-heading">SIGN IN FORM</h1>
</div>
</section>
</div>
<div class="outer-wrapper">
<section class="padding-5000 padding-0050 contact-form">
<div class="wrapper">
<div class="sign-in">
<form name="newLostPass" id="signupForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

<label>Recover your lost password.</label>
<?php echo resultBlock($errors,$successes); ?>
<input type="text" id="username" name="username" placeholder="Username">
<input type="text" id="email" name="email" placeholder="Email Address">
<a class="forgot-password" href="resend-activation.php">Resend Email Activation</a>
<input type='submit' name="forgot-password" value='Submit' class='submit'/></div>
<div class="create-account">Not Registered?<a href="register.php">Create an Account</a></div>
</form>
</div>
</div>
</section>
</div>


<?php include("footer.php"); ?>

<script>
$().ready(function() {
		// validate signup form on keyup and submit
		$("#signupForm").validate({
			rules: {
				username: "required",
				email: "required",
			},
			messages: {
				username: {
					required: "Please enter your username.",
				},
				email: {
					required: "Please enter your email address.",
				},
			}
		});
	});
</script>