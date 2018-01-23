<?php

require_once("header.php");
include('models/mail-templates/resend-activation.php');

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

//Resend Activation Function
//Forms posted
if(!empty($_POST['resend']) && $emailActivation)
{
	$resend = 1;
	$email = $_POST["email"];
	$username = $_POST["username"];
	
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
						
						$activation_url = $websiteUrl."login.php?token=".$new_activation_token;
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

<div class="outer-wrapper">
<section class=" blue-bg main-content">
<div class="wrapper">
<h1 class="form-heading">RESEND ACTIVATION</h1>
</div>
</section>
</div>
<div class="outer-wrapper">
<section class="padding-5000 padding-0050 contact-form">
<div class="wrapper">
<div class="sign-in">

<form name="login" id="signupForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
<label>Resend Activation Email</label>
<?php echo resultBlock($errors,$successes); ?>
<input type="text" id="username" name="username" placeholder="Username">
<input type="text" id="email" name="email" placeholder="Email Address">
<a class="forgot-password" href="forgot-password.php">Forgot your password?</a>
<input type='submit' name="resend" value='Submit' class='submit'/></div>
<div class="create-account">Not Registered?<a href="register.php">Create an Account</a></div>
</form>
</div>
</div>
</section>
</div>


<?php include ("footer.php"); ?>

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