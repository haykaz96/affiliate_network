<?php

include("header.php");

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }


//Activate account token
//Get token param
if(!empty($_GET["token"]))
{	
	$token = $_GET["token"];	
	if(!isset($token))
	{
		$errors[] = lang("FORGOTPASS_INVALID_TOKEN");
	}
	else if(!validateActivationToken($token)) //Check for a valid token. Must exist and active must be = 0
	{
		$errors[] = lang("ACCOUNT_TOKEN_NOT_FOUND");
	}
	else
	{
		//Activate the users account
		if(!setUserActive($token))
		{
			$errors[] = lang("SQL_ERROR");
		}
	}
	if(count($errors) == 0) {
		$successes[] = lang("ACCOUNT_ACTIVATION_COMPLETE");
	}
	
}


//Login forms
//Forms posted
if(!empty($_POST))
{
	$errors = array();
	$username = sanitize(trim($_POST["username"]));
	$password = trim($_POST["password"]);
	
	//Perform some validation
	//Feel free to edit / change as required
	if($username == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_USERNAME");
	}
	if($password == "")
	{
		$errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
	}

	if(count($errors) == 0)
	{
		//A security note here, never tell the user which credential was incorrect
		if(!usernameExists($username))
		{
			$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
		}
		else
		{
			$userdetails = fetchUserDetails($username);
			
			//See if the user's account is activated
			if($userdetails["active"]==0)
			{
				$errors[] = lang("ACCOUNT_INACTIVE");
			}
			else
			{
				//Hash the password and use the salt from the database to compare the password.
				$entered_pass = generateHash($password,$userdetails["password"]);
				
				if($entered_pass != $userdetails["password"])
				{
					//Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
					$errors[] = lang("ACCOUNT_USER_OR_PASS_INVALID");
				}
				else
				{
					//Passwords match! we're good to go'
					$user_id = $userdetails["id"];
					$sql_permission = "select * from uc_user_permission_matches where user_id='$user_id'" ;
					$result_permission = mysqli_query($mysqli,$sql_permission);
					$row_permission = mysqli_fetch_array($result_permission);
					if($row_permission['permission_id'] !=6){
					//Construct a new logged in user object
					//Transfer some db data to the session object
					$loggedInUser = new loggedInUser();
					$loggedInUser->email = $userdetails["email"];
					$loggedInUser->user_id = $userdetails["id"];
					$loggedInUser->hash_pw = $userdetails["password"];
					$loggedInUser->title = $userdetails["title"];
					$loggedInUser->displayname = $userdetails["display_name"];
					$loggedInUser->username = $userdetails["user_name"];
					
					//Update last sign in
					$loggedInUser->updateLastSignIn();
					
					$session_token=md5(uniqid(rand(), true));
					
					$query=mysqli_query($mysqli,"update uc_users set session_token='$session_token' where id=".$userdetails["id"]);
					
					$_SESSION["membershipScriptUser"] = $loggedInUser;
					
					//Redirect to user account page
					header("Location: account.php");
					die();
				}else{
					$errors[] = lang("ACCOUNT_SUSPENDED");
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
<h1 class="form-heading">SIGN IN FORM</h1>
</div>
</section>
</div>
<div class="outer-wrapper">
<section class="padding-5000 padding-0050 contact-form">
  <div class="wrapper">
		<div class="sign-in">
			<form name="login" id="signupForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
			<label>Login to your Account</label>
			<?php echo resultBlock($errors,$successes); ?>
				<input type="text" id="username" name="username" placeholder="Username">
				<input type="password" id="password" name="password" placeholder="Password">
				<a class="forgot-password" href="forgot-password.php">Forgot your password?</a>
				<input type="submit"  value="Submit">
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
				password: "required",
			},
			messages: {
				username: {
					required: "Please enter your username.",
				},
				password: {
					required: "Please enter your password.",
				},
			}
		});
	});
	</script>