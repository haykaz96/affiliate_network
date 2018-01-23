<?php

require_once("dashboard-header.php");

//Forms posted
if(!empty($_POST))
{
	$cfgId = array();
	$newSettings = $_POST['settings'];
	
	//Validate new site name
	if ($newSettings[1] != $websiteName) {
		$newWebsiteName = $newSettings[1];
		if(minMaxRange(1,150,$newWebsiteName))
		{
			$errors[] = lang("CONFIG_NAME_CHAR_LIMIT",array(1,150));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 1;
			$cfgValue[1] = $newWebsiteName;
			$websiteName = $newWebsiteName;
		}
	}
	
	//Validate new URL
	if ($newSettings[2] != $websiteUrl) {
		$newWebsiteUrl = $newSettings[2];
		if(minMaxRange(1,150,$newWebsiteUrl))
		{
			$errors[] = lang("CONFIG_URL_CHAR_LIMIT",array(1,150));
		}
		else if (substr($newWebsiteUrl, -1) != "/"){
			$errors[] = lang("CONFIG_INVALID_URL_END");
		}
		else if (count($errors) == 0) {
			$cfgId[] = 2;
			$cfgValue[2] = $newWebsiteUrl;
			$websiteUrl = $newWebsiteUrl;
		}
	}
	
	//Validate new site email address
	if ($newSettings[3] != $emailAddress) {
		$newEmail = $newSettings[3];
		if(minMaxRange(1,150,$newEmail))
		{
			$errors[] = lang("CONFIG_EMAIL_CHAR_LIMIT",array(1,150));
		}
		elseif(!isValidEmail($newEmail))
		{
			$errors[] = lang("CONFIG_EMAIL_INVALID");
		}
		else if (count($errors) == 0) {
			$cfgId[] = 3;
			$cfgValue[3] = $newEmail;
			$emailAddress = $newEmail;
		}
	}
	
	//Validate email activation selection
	if ($newSettings[4] != $emailActivation) {
		$newActivation = $newSettings[4];
		if($newActivation != "true" AND $newActivation != "false")
		{
			$errors[] = lang("CONFIG_ACTIVATION_TRUE_FALSE");
		}
		else if (count($errors) == 0) {
			$cfgId[] = 4;
			$cfgValue[4] = $newActivation;
			$emailActivation = $newActivation;
		}
	}
	
	//Validate new email activation resend threshold
	if ($newSettings[5] != $resend_activation_threshold) {
		$newResend_activation_threshold = $newSettings[5];
		if($newResend_activation_threshold > 72 OR $newResend_activation_threshold < 0)
		{
			$errors[] = lang("CONFIG_ACTIVATION_RESEND_RANGE",array(0,72));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 5;
			$cfgValue[5] = $newResend_activation_threshold;
			$resend_activation_threshold = $newResend_activation_threshold;
		}
	}
	
	//Validate new language selection
	if ($newSettings[6] != $language) {
		$newLanguage = $newSettings[6];
		if(minMaxRange(1,150,$language))
		{
			$errors[] = lang("CONFIG_LANGUAGE_CHAR_LIMIT",array(1,150));
		}
		elseif (!file_exists($newLanguage)) {
			$errors[] = lang("CONFIG_LANGUAGE_INVALID",array($newLanguage));				
		}
		else if (count($errors) == 0) {
			$cfgId[] = 6;
			$cfgValue[6] = $newLanguage;
			$language = $newLanguage;
		}
	}
	
	//Validate new template selection
	if ($newSettings[7] != $template) {
		$newTemplate = $newSettings[7];
		if(minMaxRange(1,150,$template))
		{
			$errors[] = lang("CONFIG_TEMPLATE_CHAR_LIMIT",array(1,150));
		}
		elseif (!file_exists($newTemplate)) {
			$errors[] = lang("CONFIG_TEMPLATE_INVALID",array($newTemplate));				
		}
		else if (count($errors) == 0) {
			$cfgId[] = 7;
			$cfgValue[7] = $newTemplate;
			$template = $newTemplate;
		}
	}
	
		//Validate new TRK URL
	if ($newSettings[9] != $trkUrl) {
		$newTrkUrl = $newSettings[9];
		if(minMaxRange(1,150,$newTrkUrl))
		{
			$errors[] = lang("CONFIG_TRK_URL_CHAR_LIMIT",array(1,150));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 9;
			$cfgValue[9] = $newTrkUrl;
			$trkUrl = $newTrkUrl;
		}
	}
		
	//Validate new Geo Locate URL
	if ($newSettings[10] != $geolocateUrl) {
		$newGeolocateUrl = $newSettings[10];
		if(minMaxRange(1,150,$newGeolocateUrl))
		{
			$errors[] = lang("CONFIG_GEO_URL_CHAR_LIMIT",array(1,150));
		}
		else if (count($errors) == 0) {
			$cfgId[] = 10;
			$cfgValue[10] = $newGeolocateUrl;
			$geolocateUrl = $newGeolocateUrl;
		}
	}
	
	//Update configuration table with new settings
	if (count($errors) == 0 AND count($cfgId) > 0) {
		updateConfig($cfgId, $cfgValue);
		$successes[] = lang("CONFIG_UPDATE_SUCCESSFUL");
	}
}

$languages = getLanguageFiles(); //Retrieve list of language files
$templates = getTemplateFiles(); //Retrieve list of template files
$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels

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
<form name='adminConfiguration' action='<?php $_SERVER['PHP_SELF'] ?>' method='post'>
<div>
<div class="col-sm-6">
<label>Website Name:</label>
<input type='text' name='settings[<?php echo $settings['website_name']['id'] ?>]' value='<?php echo $websiteName ?>' />
</div>
<div class="col-sm-6">
<label>Website URL:</label>
<input type='text' name='settings[<?php echo $settings['website_url']['id'] ?>]' value='<?php echo $websiteUrl ?>' />
</div>
<div class="col-sm-6">
<label>Email:</label>
<input type='text' name='settings[<?php echo $settings['email']['id'] ?>]' value='<?php echo $emailAddress ?>' />
</div>
<div class="col-sm-6">
<label>Activation Threshold:</label>
<input type='text' name='settings[<?php echo $settings['resend_activation_threshold']['id'] ?>]' value='<?php echo $resend_activation_threshold ?>' />
</div>
<div class="col-sm-6">
<label>Language:</label>
<select name='settings[<?php echo $settings['language']['id'] ?>]'>
<?php 
//Display language options
foreach ($languages as $optLang){
	if ($optLang == $language){
		echo "<option value='".$optLang."' selected>$optLang</option>";
	}
	else {
		echo "<option value='".$optLang."'>$optLang</option>";
	}
}

?>
</select>
</div>
<div class="col-sm-6">
<label>Email Activation:</label>
<select name='settings[<?php echo $settings['activation']['id'] ?>]'>
	<option value='true' <?php if($settings['activation']['value']=='true'){ echo 'selected'; } ?> >True</option>
	<option value='false' <?php if($settings['activation']['value']=='false'){ echo 'selected'; } ?>>False</option>
	</select>
</div>
<div class="col-sm-6">
<label>Template:</label>
<select name='settings[<?php echo $settings['template']['id'] ?>]'>
<?php 
//Display template options
foreach ($templates as $temp){
	if ($temp == $template){
		echo "<option value='".$temp."' selected>$temp</option>";
	}
	else {
		echo "<option value='".$temp."'>$temp</option>";
	}
}

?>
</select>
</div>
<div class="col-sm-6">
<label>Reject URL:</label>
<input type='text' name='settings[<?php echo $settings['reject_url']['id'] ?>]' value='<?php echo $rejectUrl ?>' />
</div>
<div class="col-sm-6">
<label>TRK URL:</label
><input type='text' name='settings[<?php echo $settings['trk_url']['id'] ?>]' value='<?php echo $trkUrl ?>' />
</div>
<div class="col-sm-6">
<label>Geo Locate URL:</label>
<input type='text' name='settings[<?php echo $settings['geolocate_url']['id'] ?>]' value='<?php echo $geolocateUrl ?>' />
</div>
<div class="col-sm-6">
  <input type='submit' name='Submit' value='Submit' />
</div>
</form>
</div>
</div>

</section>


<?php include("dashboard-footer.php"); ?>
