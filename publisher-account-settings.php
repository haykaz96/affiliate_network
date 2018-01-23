<?php

require_once("dashboard-header.php");

//Prevent the user visiting the logged in page if he is not logged in
if (!isUserLoggedIn()) {
    header("Location: login.php");
    die();
}
$userdetails = fetchUserDetails(NULL, NULL, $user_id); //Fetch user details
if (isset($_POST['name'])) {

    $user_id = $loggedInUser->user_id;
    $query = mysqli_query($mysqli, "select * from uc_users where id=" . $loggedInUser->user_id);
    $temp_user = mysqli_fetch_assoc($query);
    if ($temp_user['session_token'] == $_POST['log_user_id']) {
        $errors = array();
        $successes = array();
        $errors = array();
        $userdetails = array_merge($userdetails, $_POST);

        $name = $_POST["name"];
        $address = $_POST["address"];
        $postal_code = $_POST["post_code"];
        $country = $_POST["country"];
        $telephone_number = $_POST["telephone"];
        $payment_method = $_POST["payment_method"];
        $payment_details = $_POST["payment_details"];
        $traffic_details = $_POST["traffic_details"];

        updateAccountDetails($user_id, $name, $address, $postal_code, $country, $telephone_number, $payment_method, $payment_details, $traffic_details);
        $successes[] = lang("PERSONAL_DETAIL_UPDATED");
        if (count($errors) == 0 AND count($successes) == 0) {
            $errors[] = lang("NOTHING_TO_UPDATE");
        }
    } else {
        $errors[] = lang("NOTHING_TO_UPDATE");
    }

}
// ACCOUNT DETAIL SUBMISSION GOES HERE
if (isset($_POST['account_detail']) && !empty($_POST['account_detail'])) {
    $user_id = $loggedInUser->user_id;
    $query = mysqli_query($mysqli, "select * from uc_users where id=" . $loggedInUser->user_id);
    $temp_user = mysqli_fetch_assoc($query);
    if ($temp_user['session_token'] == $_POST['log_user_id']) {
        $errors = array();
        $successes = array();
        $password = $_POST["password"];
        $password_new = $_POST["passwordc"];
        $password_confirm = $_POST["passwordcheck"];

        $errors = array();
        $email = $_POST["email"];

        //Perform some validation
        //Feel free to edit / change as required

        //Confirm the hashes match before updating a users password
        $entered_pass = generateHash($password, $loggedInUser->hash_pw);

        if (trim($password) == "") {
            $errors[] = lang("ACCOUNT_SPECIFY_PASSWORD");
        } else if ($entered_pass != $loggedInUser->hash_pw) {
            //No match
            $errors[] = lang("ACCOUNT_PASSWORD_INVALID");
        }
        if ($email != $loggedInUser->email) {
            if (trim($email) == "") {
                $errors[] = lang("ACCOUNT_SPECIFY_EMAIL");
            } else if (!isValidEmail($email)) {
                $errors[] = lang("ACCOUNT_INVALID_EMAIL");
            } else if (emailExists($email)) {
                $errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
            }

            //End data validation

            if (count($errors) == 0) {
                $loggedInUser->updateEmail($email);
                $successes[] = lang("ACCOUNT_DETAIL_UPDATED");
            }
        }

        if ($password_new != "" OR $password_confirm != "") {
            if (trim($password_new) == "") {
                $errors[] = lang("ACCOUNT_SPECIFY_NEW_PASSWORD");
            } else if (trim($password_confirm) == "") {
                $errors[] = lang("ACCOUNT_SPECIFY_CONFIRM_PASSWORD");
            } else if (minMaxRange(8, 50, $password_new)) {
                $errors[] = lang("ACCOUNT_NEW_PASSWORD_LENGTH", array(8, 50));
            } else if ($password_new != $password_confirm) {
                $errors[] = lang("ACCOUNT_PASS_MISMATCH");
            }

            //End data validation
            if (count($errors) == 0) {
                //Also prevent updating if someone attempts to update with the same password
                $entered_pass_new = generateHash($password_new, $loggedInUser->hash_pw);

                if ($entered_pass_new == $loggedInUser->hash_pw) {
                    //Don't update, this fool is trying to update with the same password Â¬Â¬
                    $errors[] = lang("ACCOUNT_PASSWORD_NOTHING_TO_UPDATE");
                } else {
                    //This function will create the new hash and update the hash_pw property.
                    $loggedInUser->updatePassword($password_new);
                    $successes[] = lang("ACCOUNT_PASSWORD_UPDATED");
                }
            }
        }
        if (count($errors) == 0 AND count($successes) == 0) {
            $errors[] = lang("NOTHING_TO_UPDATE");
        }
    } else {
        $errors[] = lang("NOTHING_TO_UPDATE");
    }
}

?>

<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">
<div class="comm-float pt-20 pb-10">

<?php echo resultBlock($errors,$successes); ?>
<h3 class="main-heading">Administrator Configuration.</h3>
<p>Use the form below to update the configuration of the website.</p>
</div>
<div class="website-form">

<form name='personal_details' id="personal_details" action='<?php $_SERVER['PHP_SELF']; ?>' method='post'>
<input type="hidden" name="log_user_id" id="log_user_id" value="<?php echo $_SESSION["log_user_id"]; ?>">
<div class="comm-float">
<div class="col-sm-12">
<div class="col-sm-6 npl">
<label>Full Name:</label>
<input class="form-control" type='text' name='name' value='<?php echo $userdetails['name']; ?>' id="name" disabled="disabled">
                                </div>
                                <div class="col-sm-6 npl">
                                    <label>Address:</label>
                                    <input class="form-control" type='text' name='address'
                                           value='<?php echo $userdetails['address']; ?>' id="address">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="col-sm-6 npl">
                                    <label>Postal Code:</label>
                                    <input class="form-control" type='text' name='post_code'
                                           value='<?php echo $userdetails['postal_code']; ?>' id="postal_code">
                                </div>
                                <div class="col-sm-6 npl">
                                    <label>Country: </label>
                                    <select name="country" id="country">
                                        <option value="">Pick country</option>
                                        <?php
                                        $query2 = "SELECT * FROM uc_countries";
                                        $result2 = mysqli_query($mysqli, $query2);
                                        ?>
                                        <?php while ($row2 = mysqli_fetch_assoc($result2)) { ?>

                                            <option <?php if ($row2['id'] == $userdetails['country']) {
                                                echo 'selected';
                                            } ?> value="<?php echo $row2['id']; ?>"><?php echo $row2['country_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-sm-6 npl">
                            <label>Telephone Number:</label>
                            <input class="form-control" type='text' name='telephone'
                                   value='<?php echo $userdetails['telephone_number']; ?>' id="telephone_number">
                        </div>
                        <div class="col-sm-6 npl">
                            <label>Payment Method: </label>
                            <select name="payment_method" id="payment_method">
                                <option value="Paypal" <?php if ('Paypal' == $userdetails['payment_method']) {
                                    echo 'selected';
                                } ?>>Paypal
                                </option>
                                <option value="Moneybookers" <?php if ('Moneybookers' == $userdetails['payment_method']) {
                                    echo 'selected';
                                } ?>>Moneybookers
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 nplr">
                        <label>Payment Details:</label>
                        <input class="form-control" type='text' name='payment_details'
                               value='<?php echo $userdetails['payment_details']; ?>' id="payment_details">
                    </div>
                    <div class="col-sm-12 nplr">
                        <label>Traffic Details:</label>
                        <textarea name="traffic_details" cols="4"
                                  id="traffic_details"><?php echo $userdetails['traffic_details']; ?></textarea>
                    </div>
                    <div class="col-sm-12 nplr">
                        <input class="submit" type='submit' name="personal_details_submit" value='Update Details'>
                    </div>
                </form>

                <div class="mt-20 col-sm-12 nplr">
                    <h3 class="main-heading">Update password.</h3>
                    <p class="mb-20">Use the form below to update your password or email details. You must add your
                        current password to change your email address. Only enter a new password if you wish to
                        change.</p>
                    <form name='account_detail' id="account_detail" action='<?php $_SERVER['PHP_SELF'] ?>'
                          method='post'>
                        <div class="sidebar-form">
                            <input type="hidden" name="log_user_id" id="log_user_id"
                                   value="<?php echo $_SESSION["log_user_id"]; ?>">
                            <div class="comm-float">
                                <div class="col-sm-6 npl">
                                    <label>Current password:</label>
                                    <input class="form-control" type='password' name='password' id="password"
                                           placeholder="">
                                </div>
                                <div class="col-sm-6 npr">
                                    <label>Email:</label>
                                    <input class="form-control" type='text' name='email' id="email"
                                           value='<?php echo $loggedInUser->email ?>' placeholder="">
                                </div>
                            </div>
                            <div class="comm-float">
                                <div class="col-sm-6 npl">
                                    <label>New Password:</label>
                                    <input class="form-control" type='password' name='passwordc' id="passwordc"
                                           placeholder="">
                                </div>
                                <div class="col-sm-6 npr">
                                    <label>Confirm Password:</label>
                                    <input class="form-control" type='password' name='passwordcheck' id="passwordcheck"
                                           placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 nplr">
                            <input class="submit" type='submit' name="account_detail" value='Update Password'>
                        </div>
                    </form>


                </div>
            </div>
        </div>
</section>


<?php require_once("footer.php"); ?>
<script>

$().ready(function() {
		// validate signup form on keyup and submit
		$("#signupForm").validate({
			rules: {
				name: "required",
				address: "required",
				postal_code: "required",
				country: "required",
				traffic_details:"required",
				payment_details: "required",
				payment_method: "required",
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
			},
			messages: {
				name: "Please enter your full name.",
				address: "Please enter your address.",
				postal_code: "Please enter your postal code.",
				country: "Please select your country.",
				traffic_details:"Please enter your traffic & promotion details.",
				payment_details: "Please enter your payment details.",
				payment_method: "Please select a payment method.",
				telephone_number: {
					required: "Please enter your telephone number.",
					minlength: "Please enter a valid telephone number."
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
			}
		});
	});
</script>		