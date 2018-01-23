<?php

require_once("dashboard-header.php");

$pages = getPageFiles(); //Retrieve list of pages in root usercake folder
$dbpages = fetchAllPages(); //Retrieve list of pages in pages table
$creations = array();
$deletions = array();

//Check if any pages exist which are not in DB
foreach ($pages as $page){
	if(!isset($dbpages[$page])){
		$creations[] = $page;	
	}
}

//Enter new pages in DB if found
if (count($creations) > 0) {
	createPages($creations)	;
}

if (count($dbpages) > 0){
	//Check if DB contains pages that don't exist
	foreach ($dbpages as $page){
		if(!isset($pages[$page['page']])){
			$deletions[] = $page['id'];	
		}
	}
}

//Delete pages from DB if not found
if (count($deletions) > 0) {
	deletePages($deletions);
}

//Update DB pages
$dbpages = fetchAllPages();

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
<th>Id</th>
<th>Page</th>
<th>Access</th>
</tr>
</thead>

<?php
//Display list of pages
foreach ($dbpages as $page){ ?>
<tbody>
<tr>
<td>
<?php echo $page['id'] ?>
</td>
<td>
<a href ='admin_page.php?id=<?php echo $page['id'] ?>' target="_blank"><?php echo $page['page'] ?></a>
</td>
<td>
<?php
	
//Show public/private setting of page
if($page['private'] == 0){
echo "Public";
}
else {
echo "Private";	
}
?>
</td>
</tr>
</tbody>
    
<?php } ?>

</table>

</div>
</div>
</section>

<?php require_once ("dashboard-footer.php"); ?>