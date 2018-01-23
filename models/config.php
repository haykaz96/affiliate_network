<?php ob_start(); date_default_timezone_set('Europe/London');

require_once("db-settings.php"); //Require DB connection

//Retrieve settings
$query=mysqli_query($mysqli,"SELECT id, name, value
	FROM ".$db_table_prefix."configuration");
	while($temp_row=mysqli_fetch_assoc($query)){
		$settings[$temp_row['name']] = array('id' => $temp_row['id'], 'name' => $temp_row['name'], 'value' => $temp_row['value']);
	}

//Settings
$emailActivation = $settings['activation']['value'];
$mail_templates_dir = "models/mail-templates/";
$websiteName = $settings['website_name']['value'];
$websiteUrl = $settings['website_url']['value'];
$emailAddress = $settings['email']['value'];
$resend_activation_threshold = $settings['resend_activation_threshold']['value'];
$emailDate = date('dmy');
$language = $settings['language']['value'];
$template = $settings['template']['value'];
$rejectUrl = $settings['reject_url']['value'];
$trkUrl = $settings['trk_url']['value'];
$geolocateToken = $settings['geolocate_token']['value'];
$geolocateUrl = $settings['geolocate_url']['value'];

$master_account = -1;

$default_hooks = array("#WEBSITENAME#","#WEBSITEURL#");
$default_replace = array($websiteName,$websiteUrl);

if (!file_exists($language)) {
	$language = "languages/en.php";
}

if(!isset($language)) $language = "languages/en.php";

//Pages to require
require_once($language);
require_once("class.mail.php");
require_once("class.user.php");
require_once("class.newuser.php");
require_once("funcs.php");

session_start();

//Global User Object Var
//loggedInUser can be used globally if constructed
if(isset($_SESSION["membershipScriptUser"]) && is_object($_SESSION["membershipScriptUser"]))
{

$loggedInUser = $_SESSION["membershipScriptUser"];

}
if(isset($loggedInUser->user_id)){
	$query=mysqli_query($mysqli,"select * from uc_users where id=".$loggedInUser->user_id);
	$temp_user=mysqli_fetch_assoc($query);
	$_SESSION["log_user_id"]=$temp_user['session_token'];
}
?>