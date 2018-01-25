<?php include("header.php");
error_reporting(0);

//Prevent the user visiting the logged in page if he/she is already logged in
if(isUserLoggedIn()) { header("Location: account.php"); die(); }

if(isset($_GET['ref_id']) && !empty($_GET['ref_id'])){
	$ref_id = $_GET['ref_id'];
}

//Forms posted
if(!empty($_POST))
{

	$errors = array();
	$name = filterInputs($_POST["name"]);
	$email = filterInputs($_POST["email"]);
	$username = filterInputs($_POST["username"]);
	$password = filterInputs($_POST["password"]);
	$confirm_pass = filterInputs($_POST["passwordc"]);
	$telephone_number = filterInputs($_POST["telephone_number"]);
	$address = filterInputs($_POST["address"]);
	$postal_code = filterInputs($_POST["postal_code"]);
	$country = filterInputs($_POST["country"]);
	$payment_method = filterInputs($_POST["payment_method"]);
	$payment_details = filterInputs($_POST["payment_details"]);
	$traffic_details = filterInputs($_POST["traffic_details"]);
		
	$ref_id = filterInputs($_POST["ref_id"]);
	$sub_id = filterInputs($_POST["sub_id"]);
	$captcha = md5(filterInputs($_POST["captcha"]));
	
	
	if ($captcha != $_SESSION['captcha'])
	{
		$errors[] = lang("CAPTCHA_FAIL");
	}
	if(empty($address)){
		$errors[] = lang("ADDRESS_VALIDATION");
	}
	if(empty($postal_code)){
		$errors[] = lang("POSTALCODE_VALIDATION");
	}
	if(empty($payment_method)){
		$errors[] = lang("PAYMENT_VALLDATION");
	}
	if(empty($payment_details)){
		$errors[] = lang("PAYMENT_DETAILS_VALIDATION");
	}
	if(empty($country)){
		$errors[] = lang("COUNTRY_VALIDATION");
	}
	if(empty($traffic_details)){
		$errors[] = lang("TRAFFICDETAILS_VALIDATION");
	}
	if(empty($name)){
		$errors[] = lang("NAME_VALIDATION");
	}
	if(minMaxRange(1,11,$telephone_number))
	{
		$errors[] = lang("TELEPHONE_LIMIT",array(1,11));
	}
	if(empty($telephone_number)){
		$errors[] = lang("PHONE_VALIDATION");
	}
	if(minMaxRange(5,25,$username))
	{
		$errors[] = lang("ACCOUNT_USER_CHAR_LIMIT",array(5,25));
	}
	if(!ctype_alnum($username)){
		$errors[] = lang("ACCOUNT_USER_INVALID_CHARACTERS");
	}
	if(minMaxRange(8,50,$password) && minMaxRange(8,50,$confirm_pass))
	{
		$errors[] = lang("ACCOUNT_PASS_CHAR_LIMIT",array(8,50));
	}
	else if($password != $confirm_pass)
	{
		$errors[] = lang("ACCOUNT_PASS_MISMATCH");
	}
	if(!isValidEmail($email))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	//End data validation
	if(count($errors) == 0)
	{
		//Construct a user object
		$user = new User($username,$password,$email,$ref_id,$sub_id,$name,$address,$postal_code,$telephone_number,$country,$payment_method,$traffic_details,$payment_details);
		
		//Checking this flag tells us whether there were any errors such as possible data duplication occured
		if(!$user->status)
		{
			if($user->username_taken) $errors[] = lang("ACCOUNT_USERNAME_IN_USE",array($username));
			if($user->email_taken) 	  $errors[] = lang("ACCOUNT_EMAIL_IN_USE",array($email));		
		}
		else
		{
			//Attempt to add the user to the database, carry out finishing  tasks like emailing the user (if required)
			if(!$user->membershipScriptAddUser())
			{
				if($user->mail_failure) $errors[] = lang("MAIL_ERROR");
				if($user->sql_failure)  $errors[] = lang("SQL_ERROR");
			}
		}
	}
	if(count($errors) == 0) {
		$successes[] = $user->success;
	}
}

?>

<div class="outer-wrapper">
<section class=" blue-bg main-content">
  <div class="wrapper">
		<h1 class="form-heading">Create a Publisher Account.</h1>
  </div>
</section>
</div>

<div class="outer-wrapper">
<section class="create-form">
  <div class="wrapper">
  <div class="padding-5000 padding-0050 white_bg dashboard">
  <div class="dashboard-left box-shadow">
  <form id="signupForm" name='newUser' action='<?php $_SERVER['PHP_SELF'] ?>' method='post' >
  <div class="personal-details">
    <h4>Your Personal Details</h4>
	<?php echo resultBlock($errors,$successes); ?>
	<input type="hidden" name="ref_id" value="<?php if(isset($_GET['ref_id']))echo addslashes($_GET['ref_id']); ?>">
	<input type="hidden" name="sub_id" value="<?php if(isset($_GET['sub_id']))echo addslashes($_GET['sub_id']); ?>">
	<div class="comm-float">
	<div class="left-block">
	<input type="text" id="name"  name="name" placeholder="Full Name">
	</div>
	<div class="left-block right-block">
	<input type="text" name="email"  id="email"  placeholder="Email">
	</div>
	</div>
	<div class="comm-float">
	<div class="left-block">
	<input type="text" name="telephone_number" id="telephone_number" placeholder="Telephone Number">
	</div>
	<div class="left-block right-block">
	<input type="text" name="address" id="address"  placeholder="Address">
	</div>
	</div>
	<div class="comm-float">
	<div class="left-block">
	<input type="text" name="postal_code" id="postal_code"  placeholder="Postal Code">
	</div>
	<div class="left-block right-block">
<?php 
		$sql = "SELECT * FROM uc_countries order by country_name ASC";
		$result = $mysqli->query($sql);
		
		
	?>
	<select name="country" id="country">
			<option value="0">Select Country</option>
			<?php while($row = $result->fetch_assoc()) { ?>
				<option value="<?php echo $row['id'] ?>" selected><?php echo $row['country_name'] ?></option>
			<?php } ?>
		</select>
    </div>
	</div>
	
  </div>
  <div class="personal-details">
    <h4>Account Details</h4>
	<div class="comm-float">
	<div class="left-block">
	<input type="text" name="username"  id="username"   placeholder="User Name" >
	</div>
	</div>
	<div class="left-block">
	<input type="password" name="password" id="password" placeholder="Password" >
	</div>
	<div class="left-block right-block">
	<input type="password" name="passwordc" id="passwordc" placeholder="Confirm Password" >
	</div>
  </div>  
   <div class="personal-details">
    <h4>Payment Information</h4>
	<div class="comm-float">
	<div class="left-block">
    <?php 
		$sql = "SELECT * FROM uc_payments_method order by id ASC";
		$result = $mysqli->query($sql);
		
		
	?>
		<p class="input-heading">Payment Method </p>
		<select name="payment_method" id="payment_method">
        <option value="0">Select Payment Method</option>
		<?php while($row = $result->fetch_assoc()) { ?>
		<option value="<?php echo $row['id'] ?>" selected><?php echo $row['payment_method'] ?></option>
		<?php } ?>
		</select>
	</div>
	</div>
	
	<div class="left-block">
	<p class="input-heading">Payment Details </p>
	<textarea  cols="25" name="payment_details" id="payment_details" rows="4" placeholder="Enter your Paypal email address, or your bank Account Number and Sort Code (UK Only)." ></textarea>
	</div>
    <div class="left-block right-block">
	<p class="input-heading">Terms &amp; Conditions </p>
	<textarea  cols="25" rows="4" disabled >Terms &amp; Conditions</textarea>
	<p><input type="checkbox" name="agree"   id="agree">
	I accept the Terms &amp; Conditions</p>
	<div id="errfn">   </div>
	<label style="float: none;" for="terms_accept" class="error" generated="true"></label>

	</div>
	<div class="left-block right-block">
	
	</div>
  </div> 
  	<div class="personal-details">
    <h4>Traffic Information</h4>
	<div class="left-block">
		<p class="input-heading">Traffic Detail</p>		
		<textarea name="traffic_details" id="traffic_details" cols="25" rows="4" ></textarea>
		
	</div>
	<div class="left-block right-block">
	<p class="term-conditions">Please describe how you will generate traffic to our campaigns. Please include all website URL's you wish to use including all  promotional methods.</p>
	</div>
	<div class="comm-float">
	<div class="left-block">
	<p class="input-heading">Enter a referral ID, if any: </p>
	<input type="text"  name="ref_id" id="ref_id" value="<?php echo isset($ref_id) ? $ref_id : '' ?>" placeholder="Enter referral ID" >
	</div>
    <div class="left-block right-block">
	<p class="term-conditions">If you have been referred to us by a current publisher, please enter their code here.</p>
	</div>
	</div>
  </div> 
  <div class="personal-details">
    <h4>Security Code</h4>
	<div class="left-block">
	<input type="text" name="captcha" id="captcha" placeholder="Enter Security Code" >
	</div>
	<div class="left-block right-block">
	<img class="capcha"	src='models/captcha.php'>
	</div>
	<div class="comm-float">
	<input type="submit" id="btncheck" class="submit" value="Submit">
	<div class="error">
			
		</div>
	</div>
  </div>
  </form>
  </div>
  <div class="sidebar-right">
	
	  <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
	    </div>

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
				name: "required",
				address: "required",
				postal_code: "required",
				country: "required",
				captcha: "required",
				traffic_details:"required",
				payment_details: "required",
				payment_method: "required",
				username: {
					required: true,
					minlength: 8
				},
				password: {
					required: true,
					minlength: 8
				},
				passwordc: {
					required: true,
					minlength: 8,
					equalTo: "#password"
				},
				email: {
					required: true,
					email: true
				},
				telephone_number: {
					required: true,
					minlength: 11
				},
				agree: "required"
			},
			messages: {
				name: "Please enter your full name.",
				address: "Please enter your address.",
				postal_code: "Please enter your postal code.",
				country: "Please select your country.",
				captcha: "Captch must be completed correctly.",
				traffic_details:"Please enter your traffic & promotion details.",
				payment_details: "Please enter your payment details.",
				payment_method: "Please select a payment method.",
				telephone_number: {
					required: "Please enter your telephone number.",
					minlength: "Please enter a valid telephone number."
				},
				username: {
					required: "Please enter a username.",
					minlength: "Your username must be a minimum of 8 characters."
				},
				password: {
					required: "Please provide a password",
					minlength: "Your password must be a minimum of 8 characters."
				},
				passwordc: {
					required: "Please provide a password.",
					minlength: "Your password must be a minimum of 8 characters.",
					equalTo: "Passwords don't match."
				},
				email: "Please enter a valid email address.",
				agree: "Please accept our Terms & Conditions.",
			}
		});
	});
	</script>		