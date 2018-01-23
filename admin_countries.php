<?php

require_once("dashboard-header.php");

//Function fetches data for link form
$query = "select * from uc_countries". $where;
$result = mysqli_query($mysqli,$query);

$sql = "select * from uc_countries ". $where1;
$result1 = $mysqli->query($sql);


//Function submits the form to DB
 if(isset($_POST['submit'])){	 
	 $code = $_POST['code'];
	 $name = $_POST['name'];	
	 $link_payment = $_POST['link_payment'];	
	 $sql = "insert into uc_countries(country_code,country_name)values('".$code."','".$name."')";
     if(count($errors) == 0) {
		customQuery($sql);
		header('location:admin_countries.php?status=add');
	}
 }
 
 
//Function Delete&edit From DB
if($status == 'delete'){
	if(!empty($id)){
		commonDelete('uc_countries','id',$id);
		header('location:admin_countries.php?status=delete');
	}
}elseif ($status == 'edit') {
	$query4 = "SELECT * FROM uc_countries where id='$id'";
	$result4 = mysqli_query($mysqli,$query4);
	$row4 = mysqli_fetch_assoc($result4);
	if(isset($_POST['edit'])){
		$code = $_POST['code'];
		$name = $_POST['name'];
		$sql1 = "update uc_countries set country_code='".$code."', country_name='".$name."' WHERE id = '$id'";
		customQuery($sql1);
		header('location:admin_countries.php?status=edits');
		
	}
}
?>

<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">
<div class="comm-float pt-20 pb-10">

<?php echo resultBlock($errors,$successes); ?>
<?php if($_GET['status']=='add'){ $successes[]  = lang("COUNTRY_ADD"); echo resultBlock($errors,$successes); } ?>
<?php if($_GET['status']=='edits'){ $successes[]  = lang("COUNTRY_EDIT"); echo resultBlock($errors,$successes); } ?>
<?php if($_GET['status']=='delete'){ $successes[]  = lang("COUNTRY_DELETE"); echo resultBlock($errors,$successes); } ?>


<div id="dateSelect">
  <form name="form1" method="post" action="" class="status-form">

  <div class="comm-float">
  <div class="col-xs-12 col-sm-8">
            <p><span class="bold">Country Code:	</span></p>	
		</div>
   
	<div class="col-xs-12 col-sm-8">
    <input name="code" type="text" value="<?php echo $row4['country_code']; ?>" placeholder="Enter country code">
	</div>
	<div class="col-xs-12 col-sm-8">
            <p><span class="bold">Country Name:	</span></p>	
		</div>
	<div class="col-xs-12 col-sm-8">
    <input name="name" type="text" value="<?php echo $row4['country_name']; ?>" placeholder="Enter country name">
	</div>
	<?php if($status == 'edit'){?>    

<div class="filter"><input type="submit" name="edit" id="submit" value="Edit"></div>
	

	</div>
    <?php }else{ ?>

<div class="filter"><input type="submit" name="submit" id="submit" value="Add"></div>


	</div>
	<?php }?>
    
    
    
    
    
    
</form>
</div>
</div>
<div class="res-mp-15">
 <div class="status-table">
 <table width="100%">
    <tr>
      <td>ID</td>      
      <td>Country Name</td>
	  <td>Country Code</td>       
      <td>Action</td>      
    </tr>

	<?php while($row = mysqli_fetch_assoc($result)){?>
    <tr>	
      <td><?php echo $row['id'];?></td>
      <td><?php echo $row['country_name']; ?></td>
      <td><?php echo $row['country_code']; ?></td>
	  <td>
	  <a href="admin_countries.php?id=<?php echo urlencode( base64_encode($row['id'])); ?>&status=edit">Edit</a>
	  <a href="admin_countries.php?id=<?php echo urlencode( base64_encode($row['id'])); ?>&status=delete" onClick="return confirm('Are you sure you want to delete this link?')">Delete</a>
	  </td>
    </tr>  <?php }?>

 </table>
</div>
</div>
</div>
</div>
</div>

</section>



<?php require_once("dashboard-footer.php"); ?>

<script>
  jQuery(function(){
    jQuery('#row').on('change', function () {
      var url = jQuery(this).val();
      if (url) {
         window.location='admin_countries?row=' + this.value
      }
      return false;
    });
  });
</script>