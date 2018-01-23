<?php include("header.php"); ?>
<?php
if(!empty($_POST))
{

	$errors = array();
	
	$first_name = filterInputs($_POST["first_name"]);
	$last_name = filterInputs($_POST["last_name"]);
	$email_address = filterInputs($_POST["email_address"]);
	$subject = filterInputs($_POST["subject"]);
	$message = filterInputs($_POST["message"]);
	
	$captcha = md5(filterInputs($_POST["captcha"]));
	
	if(empty($first_name)){
		$errors[] = lang("FIRST_NAME_BLANK");
	}
	if(empty($last_name)){
		$errors[] = lang("LAST_NAME_BLANK");
	}
	if(!isValidEmail($email_address))
	{
		$errors[] = lang("ACCOUNT_INVALID_EMAIL");
	}
	if(empty($subject)){
		$errors[] = lang("SUBJECT_NAME_BLANK");
	}
	if(empty($message)){
		$errors[] = lang("MESSAGE_NAME_BLANK");
	}
	if ($captcha != $_SESSION['captcha'])
	{
		$errors[] = lang("CAPTCHA_FAIL");
	}
	
	//Setup our custom hooks
		$hooks = array(
			"searchStrs" => array("#FIRSTNAME#","#SURNAME#","#EMAIL#","#MESSAGE#"),
			"subjectStrs" => array($first_name,$last_name,$email_address,$message)
			);
	//End data validation
	if(count($errors) == 0)
	{	
		$mail = new userCakeMail();	
		if(!$mail->newTemplateMsg("contact-form.txt",$hooks))
		{
			$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
		}
		else
		{	
			$toEmail=$emailAddress;
			$websiteName=$first_name.' '.$last_name;
			$emailAddress=$email_address;
			if(!$mail->sendMail($toEmail,$subject))
			{
				$errors[] = lang("MAIL_ERROR");
			}
			else
			{
				$successes[]  = lang("CONTACT_FORM_SUBMITTED");
			}	
		}
	}
	
}
?>
<div class="outer-wrapper">
<section class=" blue-bg main-content">
  <div class="wrapper">
		<h1 class="form-heading">Contact Us</h1>
  </div>
</section>
</div>
<div class="outer-wrapper">
<section class="padding-5000 padding-0050 ads-section">
  <div class="wrapper">
  <div class="comm-float  contact-us">
	    
		<div class="col-sm-4 plr-15 contact-address">
		<h4 class="main-heading">Contact Us</h4>
		<p>You can use the form to the right to contact us directly. You can also use the details below to write, email or telephone us for any information.</p>
		<ul>
		<li><span><i class="fa fa-map-marker" aria-hidden="true"></i></span>Business Park, City, Location, PO1 1CO, Country</li>
		<li><span><i class="fa fa-phone" aria-hidden="true"></i></span>01878 889 547</li>
		<li><span><i class="fa fa-envelope" aria-hidden="true"></i></span>contact@websitename.co.uk</li>
		</ul>
		</div>
		<div class="col-sm-8 plr-15 contact-address">
		<h4 class="main-heading">Get in touch with us</h4>
		<form method="post" id="contact_us" name="contact_us">
		<?php echo resultBlock($errors,$successes); ?>
		<div class="col-sm-6 pr-15">
			<input placeholder="First Name" id="first_name" name="first_name" type="text">
		</div>
		<div class="col-sm-6 pl-15">
			<input placeholder="Last Name" id="last_name" name="last_name" type="text">
		</div>
			<input  placeholder="Email Address" id="email_address" name="email_address" type="text">
			<input placeholder="Subject" id="subject" name="subject" type="text">
			<textarea placeholder="Message" id="message" name="message"></textarea>
			<div class="comm-float">
			<h4>Security Code</h4>
			<div class="left-block">
			<input type="text" name="captcha" id="captcha" placeholder="Enter Security Code" >
			</div>
			<div class="left-block right-block">
			<img class="capcha"	src='models/captcha.php'>
			</div>
			</div>
			<div class="comm-float text-left contact-submit">
			<input name="submit" id="submit" value="Submit" type="submit">
			</div>
		</form>
		</div>
  </div>
  </div>
</section>
</div>
<script>

$().ready(function() {
		// validate signup form on keyup and submit
		$("#contact_us").validate({
			rules: {
				first_name: "required",
				last_name: "required",
				subject: "required",
				message: "required",
				captcha: "required",
				email_address: {
					required: true,
					email: true
				}
			},
			messages: {
				first_name: "Please enter your first name.",
				last_name: "Please enter your last name.",
				subject: "Please enter a subject.",
				message: "Please enter a message.",
				captcha: "Captch must be completed correctly.",
				email_address: "Please enter a valid email address."
				
			}
		});
	});
	</script>

<?php include("footer.php"); ?>