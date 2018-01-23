<?php

require_once("dashboard-header.php");

$pageId = $_GET['id'];

//Check if selected pages exist
if(!pageIdExists($pageId)){
	header("Location: admin_pages.php"); die();	
}

$pageDetails = fetchPageDetails($pageId); //Fetch information specific to page

//Forms posted
if(!empty($_POST)){
	$update = 0;
	
	if(!empty($_POST['private'])){ $private = $_POST['private']; }
	
	//Toggle private page setting
	if (isset($private) AND $private == 'Yes'){
		if ($pageDetails['private'] == 0){
			if (updatePrivate($pageId, 1)){
				$successes[] = lang("PAGE_PRIVATE_TOGGLED", array("private"));
			}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
	}
	elseif ($pageDetails['private'] == 1){
		if (updatePrivate($pageId, 0)){
			$successes[] = lang("PAGE_PRIVATE_TOGGLED", array("public"));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
	}
	
	//Remove permission level(s) access to page
	if(!empty($_POST['removePermission'])){
		$remove = $_POST['removePermission'];
		if ($deletion_count = removePage($pageId, $remove)){
			$successes[] = lang("PAGE_ACCESS_REMOVED", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
		
	}
	
	//Add permission level(s) access to page
	if(!empty($_POST['addPermission'])){
		$add = $_POST['addPermission'];
		if ($addition_count = addPage($pageId, $add)){
			$successes[] = lang("PAGE_ACCESS_ADDED", array($addition_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
	}
	
	$pageDetails = fetchPageDetails($pageId);
}

$pagePermissions = fetchPagePermissions($pageId);
$permissionData = fetchAllPermissions();


?>

<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">
<div class="comm-float pt-20 pb-10">
<?php echo resultBlock($errors,$successes); ?>

<h3 class="main-heading">Adminisrator Page.</h3>
<p>Use the form below to update the page information of the website.</p>	
<form name='adminPage' action='<?php $_SERVER['PHP_SELF']?>?id=<?php echo $pageId ?>' method='post'>
<input type='hidden' name='process' value='1'>
<div class="mt-30 mb-20 regbox-table">
<table>
<tr><td>
<h3 class="main-heading">Page Information</h3>
<div id='regbox'>
<p>
<span class="bold">ID:</span>
<?php echo $pageDetails['id'] ?>
</p>
<p>
<span class="bold">Name:</span>
<?php echo $pageDetails['page'] ?>
</p>
<p>
<span class="bold">Private:</span>

<?php 
//Display private checkbox
if ($pageDetails['private'] == 1){
	echo "<input type='checkbox' name='private' id='private' value='Yes' checked>";
}
else {
	echo "<input type='checkbox' name='private' id='private' value='Yes'>";	
}

?>
</p>
</div></td><td>
<h3 class="main-heading">Page Access</h3>
<div id='regbox'>
<p> Access:</p>
<p>
<?php
//Display list of permission levels with access
foreach ($permissionData as $v1) {
	if(isset($pagePermissions[$v1['id']])){
		echo "<br><input type='checkbox' name='removePermission[".$v1['id']."]' id='removePermission[".$v1['id']."]' value='".$v1['id']."'> ".$v1['name'];
	}
}

?>
</p><span class="bold access-title">Add Access:</span>
<?php
//Display list of permission levels without access
foreach ($permissionData as $v1) {
	if(!isset($pagePermissions[$v1['id']])){
		echo "<br><input type='checkbox' name='addPermission[".$v1['id']."]' id='addPermission[".$v1['id']."]' value='".$v1['id']."'>".$v1['name'];
	}
}

?>
</p>
</div>
</td>
</tr>
</table>
</div>
<p>

<input type='submit' value='Update' class='submit' />
</p>
</form>

</div>
</div>
</div>

</section>

<?php require_once ("dashboard-footer.php"); ?>