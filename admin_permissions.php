<?php

require_once("dashboard-header.php");

//Forms posted
if(!empty($_POST))
{
	//Delete permission levels
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deletePermission($deletions)){
		$successes[] = lang("PERMISSION_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
	}
	
	//Create new permission level
	if(!empty($_POST['newPermission'])) {
		$permission = trim($_POST['newPermission']);
		
		//Validate request
		if (permissionNameExists($permission)){
			$errors[] = lang("PERMISSION_NAME_IN_USE", array($permission));
		}
		elseif (minMaxRange(1, 50, $permission)){
			$errors[] = lang("PERMISSION_CHAR_LIMIT", array(1, 50));	
		}
		else{
			if (createPermission($permission)) {
			$successes[] = lang("PERMISSION_CREATION_SUCCESSFUL", array($permission));
		}
			else {
				$errors[] = lang("SQL_ERROR");
			}
		}
	}
}

$permissionData = fetchAllPermissions(); //Retrieve list of all permission levels

?>

<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">
<div class="comm-float pt-20 pb-10">
<?php echo resultBlock($errors,$successes); ?>
<h3 class="main-heading">Administrator Pages.</h3>
<p>Use the form below to update the page information of the website.</p>	
<div class="pb-20 status-table admin-links">	

<table class='admin'>
<thead>
<tr>
<th width="70px">Delete</th>
<th>Permission Name</th>
</tr>
</thead>
<?php
//List each permission level
foreach ($permissionData as $v1) {
	?>
	<tbody>
	<tr>
	<td><input type='checkbox' name='delete[<?php echo $v1['id'] ?>]' id='delete[<?php echo $v1['id'] ?>]' value='<?php echo $v1['id']?>'></td>
	<td><a href='admin_permission.php?id=<?php echo $v1['id'] ?>'><?php echo $v1['name'] ?></a></td>
    <?php } ?>
	</tr>
	</tbody>
</table>
</div>
<div class="col-sm-12 npl"><label>Permission Name:</label></div>
<div class="col-sm-4 npl">
<input type='text' name='newPermission' />
</div>                                
<div class="col-sm-4 npl">
<input type='submit' name='Submit' value='Submit' />
</div>
</form>
</div>
</div>
</div>
</section>
<?php require_once ("dashboard-footer.php"); ?>