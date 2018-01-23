<?php

$lang = array();

//Account
$lang = array_merge($lang,array(
	"ACCOUNT_SPECIFY_USERNAME" 		=> "Please enter your username.",
	"ACCOUNT_SPECIFY_PASSWORD" 		=> "Please enter your password.",
	"ACCOUNT_SPECIFY_EMAIL"			=> "Please enter your email address.",
	"ACCOUNT_INVALID_EMAIL"			=> "Invalid email address.",
	"ACCOUNT_USER_OR_EMAIL_INVALID"		=> "Username or email address is invalid.",
	"ACCOUNT_USER_OR_PASS_INVALID"		=> "Username or password is invalid.",
	"ACCOUNT_ALREADY_ACTIVE"		=> "Your account is already activated.",
	"ACCOUNT_INACTIVE"			=> "Your account is inactive. Check your emails for account activation instructions.",
	"ACCOUNT_USER_CHAR_LIMIT"		=> "Your username must be between %m1% and %m2% characters in length.",
	"ACCOUNT_PASS_CHAR_LIMIT"		=> "Your password must be between %m1% and %m2% characters in length.",
	"ACCOUNT_TITLE_CHAR_LIMIT"		=> "Titles must be between %m1% and %m2% characters in length.",
	"ACCOUNT_PASS_MISMATCH"			=> "Your password and confirmation password must match.",
	"ACCOUNT_USERNAME_IN_USE"		=> "Username %m1% is already in use.",
	"ACCOUNT_EMAIL_IN_USE"			=> "Email %m1% is already in use.",
	"ACCOUNT_LINK_ALREADY_SENT"		=> "An activation email has already been sent to this email address in the last %m1% hour.",
	"ACCOUNT_NEW_ACTIVATION_SENT"		=> "We have emailed you a new activation link, please check your email.",
	"ACCOUNT_SPECIFY_NEW_PASSWORD"		=> "Please enter your new password.",	
	"ACCOUNT_SPECIFY_CONFIRM_PASSWORD"	=> "Please confirm your new password.",
	"ACCOUNT_NEW_PASSWORD_LENGTH"		=> "New password must be between %m1% and %m2% characters in length.",	
	"ACCOUNT_PASSWORD_INVALID"		=> "Current password doesn't match the one we have on record.",	
	"ACCOUNT_DETAILS_UPDATED"		=> "Account details updated.",
	"ACCOUNT_ACTIVATION_MESSAGE"		=> "%m1%login.php?token=%m2%",							
	"ACCOUNT_ACTIVATION_COMPLETE"		=> "You have successfully activated your account. You can now login.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE1"	=> "You have successfully registered. You can now login <a href=\"login.php\">here</a>.",
	"ACCOUNT_REGISTRATION_COMPLETE_TYPE2"	=> "You have successfully registered. You will soon receive an activation email. You must activate your account before logging in.",
	"ACCOUNT_PASSWORD_NOTHING_TO_UPDATE"	=> "You cannot update with the same password.",
	"ACCOUNT_PASSWORD_UPDATED"		=> "Account password updated.",
	"ACCOUNT_EMAIL_UPDATED"			=> "Account email updated.",
	"ACCOUNT_TOKEN_NOT_FOUND"		=> "Token does not exist / Account is already activated.",
	"ACCOUNT_USER_INVALID_CHARACTERS"	=> "Username can only include alpha-numeric characters.",
	"ACCOUNT_DELETIONS_SUCCESSFUL"		=> "You have successfully deleted %m1% users.",
	"ACCOUNT_MANUALLY_ACTIVATED"		=> "%m1%'s account has been manually activated.",
	"ACCOUNT_TITLE_UPDATED"			=> "%m1%'s title changed to %m2%.",
	"ACCOUNT_COINS_UPDATED"			=> "%m1%'s coins changed to %m2%.",
	"ACCOUNT_PERMISSION_ADDED"		=> "Added access to %m1% permission levels.",
	"ACCOUNT_PERMISSION_REMOVED"		=> "Removed access from %m1% permission levels.",
	"ACCOUNT_INVALID_USERNAME"		=> "Invalid username.",
	"CAMPAIGN_APPROVAL_REQUEST"		=> "Your campaign approval request has been successfully submitted.",
	"PERSONAL_DETAIL_UPDATED"		=> "Personal detail updated successfully.",
	"FINANCIAL_DETAIL_UPDATED"		=> "Financial detail updated successfully.",
	"DETAIL_UPDATED"		=> "Account detail updated successfully.",
	"REF_NOT_NUMERIC"		=> "Reference id is not numeric.",

	));

//Configuration
$lang = array_merge($lang,array(
	"CONFIG_NAME_CHAR_LIMIT"		=> "Site name must be between %m1% and %m2% characters in length.",
	"CONFIG_URL_CHAR_LIMIT"			=> "Site name must be between %m1% and %m2% characters in length.",
	"CONFIG_SHORTLINK_URL_CHAR_LIMIT"			=> "Short Link must be between %m1% and %m2% characters in length.",
	"CONFIG_REJECT_URL_CHAR_LIMIT"			=> "Reject URL must be between %m1% and %m2% characters in length.",
	"CONFIG_GEO_URL_CHAR_LIMIT"			=> "Geo Link must be between %m1% and %m2% characters in length.",
	"CONFIG_EMAIL_CHAR_LIMIT"		=> "Site name must be between %m1% and %m2% characters in length.",
	"CONFIG_ACTIVATION_TRUE_FALSE"		=> "Email activation must be either `true` or `false`.",
	"CONFIG_ACTIVATION_RESEND_RANGE"	=> "Activation Threshold must be between %m1% and %m2% hours.",
	"CONFIG_LANGUAGE_CHAR_LIMIT"		=> "Language path must be between %m1% and %m2% characters in length.",
	"CONFIG_LANGUAGE_INVALID"		=> "There is no file for the language key `%m1%`.",
	"CONFIG_TEMPLATE_CHAR_LIMIT"		=> "Template path must be between %m1% and %m2% characters in length.",
	"CONFIG_TEMPLATE_INVALID"		=> "There is no file for the template key `%m1%`.",
	"CONFIG_EMAIL_INVALID"			=> "The email you have entered is not valid.",
	"CONFIG_INVALID_URL_END"		=> "Invalid URL.",
	"CONFIG_INVALID_PAID_URL_END"		=> "Please include a trailing slash in your site's URL.",
	"CONFIG_UPDATE_SUCCESSFUL"		=> "Your site's configuration has been updated. You may need to load a new page for all the settings to take effect.",
	));

//Forgot Password
$lang = array_merge($lang,array(
	"FORGOTPASS_INVALID_TOKEN"		=> "Your activation token is not valid.",
	"FORGOTPASS_NEW_PASS_EMAIL"		=> "We have emailed you a new password.",
	"FORGOTPASS_REQUEST_CANNED"		=> "Lost password request cancelled.",
	"FORGOTPASS_REQUEST_EXISTS"		=> "There is already a outstanding lost password request on this account.",
	"FORGOTPASS_REQUEST_SUCCESS"		=> "We have emailed you instructions on how to regain access to your account.",
	));

//Mail
$lang = array_merge($lang,array(
	"MAIL_ERROR"				=> "Fatal error attempting mail, contact your server administrator.",
	"MAIL_TEMPLATE_BUILD_ERROR"		=> "Error building email template.",
	"MAIL_TEMPLATE_DIRECTORY_ERROR"		=> "Unable to open mail-templates directory. Perhaps try setting the mail directory to %m1%.",
	"MAIL_TEMPLATE_FILE_EMPTY"		=> "Template file is empty... nothing to send.",
	));

//Miscellaneous
$lang = array_merge($lang,array(
	"CAPTCHA_FAIL"				=> "Failed security question.",
	"CONFIRM"				=> "Confirm",
	"DENY"					=> "Deny",
	"SUCCESS"				=> "Success",
	"ERROR"					=> "Error",
	"NOTHING_TO_UPDATE"			=> "Nothing to update.",
	"SQL_ERROR"				=> "Fatal SQL error.",
	"FEATURE_DISABLED"			=> "This feature is currently disabled.",
	"PAGE_PRIVATE_TOGGLED"			=> "This page is now %m1%.",
	"PAGE_ACCESS_REMOVED"			=> "Page access removed for %m1% permission level(s).",
	"PAGE_ACCESS_ADDED"			=> "Page access added for %m1% permission level(s).",
	"SOMETHING_WRONG"				=> "Something went wrong.",
	));

//Permissions
$lang = array_merge($lang,array(
	"PERMISSION_CHAR_LIMIT"			=> "Permission names must be between %m1% and %m2% characters in length.",
	"PERMISSION_NAME_IN_USE"		=> "Permission name %m1% is already in use.",
	"PERMISSION_DELETIONS_SUCCESSFUL"	=> "Successfully deleted %m1% permission level(s).",
	"PERMISSION_CREATION_SUCCESSFUL"	=> "Successfully created the permission level `%m1%`.",
	"PERMISSION_NAME_UPDATE"		=> "Permission level name changed to `%m1%`.",
	"PERMISSION_REMOVE_PAGES"		=> "Successfully removed access to %m1% page(s).",
	"PERMISSION_ADD_PAGES"			=> "Successfully added access to %m1% page(s).",
	"PERMISSION_REMOVE_USERS"		=> "Successfully removed %m1% user(s).",
	"PERMISSION_ADD_USERS"			=> "Successfully added %m1% user(s).",
	"CANNOT_DELETE_NEWUSERS"		=> "You cannot delete the default 'new user' group.",
	"CANNOT_DELETE_ADMIN"			=> "You cannot delete the default 'admin' group.",
	));
	
	
//---------- USER ACCOUNT LANGUAGE FILES ----------//
	
//Extra Registration Fields

$lang = array_merge($lang,array(
	"ACCOUNT_INVALID_ADDRESS"			=> "Please enter your address.",
	"ACCOUNT_POSTAL_CODE_INVAILD"		=> "Please enter a valid Postal Code.",
	"ACCOUNT_COUNTRY_INVAILD"	=> "Please select your country.",
	"ACCOUNT_TELEPHONE_INVAILD"	=> "Please enter your telephone number.",
	"ACCOUNT_PAYMENT_METHOD_INVAILD"		=> "Please select a payment method.",
	"ACCOUNT_PAYMENT_DETAIL_INVAILD"		=> "Please enter valid payment information.",
	"ACCOUNT_TRAFFIC_DETAIL_INVAILD"			=> "Please enter your traffic details.",
	));
	
	
	
//---------- NEW LANGUAGE FILES ----------//
		

//ADMIN CONVERSION REPORT
$lang = array_merge($lang,array(
	"ADMIN_DELETE_CONVERSION"			=> "Conversion(s) deleted successfully.",
	"ADMIN_CONVERSION_STATUS_CHANGED"			=> "Conversion(s) status changed successfully.",

));	

//ADMIN CLICK REPORT
$lang = array_merge($lang,array(
	"ADMIN_DELETE_CLICK"			=> "Click(s) deleted successfully.",
	"ADMIN_CLICK_STATUS_CHANGED"			=> "Click(s) status changed successfully.",

));	

//ADMIN CAMPAIGN APPROVALS
$lang = array_merge($lang,array(
	"DELETE_APPROVAL"			=> "Approval(s) deleted successfully.",
	"CHANGE_APPROVAL_STATUS"			=> "Approval(s) status changed successfully.",

));	

//ADMIN CREATIVES
$lang = array_merge($lang,array(
	"DELETE_CREATIVE"			=> "Creative(s) deleted successfully.",
	"CHANGE_CREATIVE_STATUS"			=> "Creative(s) status changed successfully.",

));

?>