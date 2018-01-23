<?php include 'dashboard-header.php';

if(isset($_POST['save'])){
$offer_id=mysqli_real_escape_string($mysqli,$_POST['offer_id']);
$offer_id=addslashes(trim($offer_id));
$notes=addslashes($_POST['promotion_notes']);
 if($notes==''){
$errors[] =  lang("PROMOTION_NOTE_EMPTY");
}
$ip_address=$_SERVER['REMOTE_ADDR'];
$user_id = $loggedInUser->user_id;
 if(count($errors) == 0) {
$query="insert into uc_affiliate_permission_offer_matches(user_id,status,offer_id,promotion_notes,ip_address,date_time)values('$user_id','2','$offer_id','$notes','$ip_address',now())";
$run_query=mysqli_query($mysqli,$query);
header("location:publisher-campaign.php?offer=$offer_id&status=campaign-approval");exit;
	}
}

$offer_id=mysqli_real_escape_string($mysqli,$_GET['offer']);
$offer_id=addslashes(trim($offer_id));
$query=mysqli_query($mysqli,"select * from uc_offers where id='$offer_id'");
$total_rows=mysqli_num_rows($query);
 if($total_rows<1){
 header("location:publisher-campaigns.php");exit;
 }
$offer_data=mysqli_fetch_assoc($query);
$offer_private_status=$offer_data['private_offer'];
	
?> 

<style type="text/css"> .active_campaign { color: #090; } </style>

</div>
<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">
<div style="margin:0px" class="row">
<div class="contaigns-left">
<?php if($_GET['status']=='campaign-approval'){ $successes[]  = lang("CAMPAIGN_APPROVAL_REQUEST"); echo resultBlock($errors,$successes); } ?>

<h4 class="main-heading"><span><img src="marketing-materials/thumbnails/<?php echo $offer_data['thumb_id']; ?>" alt="campaign_logo" height="40" width="100"></span> <?php echo $offer_data['offer_name']; ?></h4>
<p><strong>Description:</strong> <?php echo $offer_data['description']; ?></p>

<p><strong>Countries:</strong> 
<?php $country =  $offer_data['country'];  
 $query2 = "SELECT * FROM uc_countries where id IN($country)";
 $result2 = mysqli_query($mysqli,$query2);
  if(mysqli_num_rows($result2)>3){
  echo "3+ Countries";
  } else {
  $code_arr=array();
  while($row2 = mysqli_fetch_assoc($result2)){
  $code_arr[]=$row2['country_code']; 
  } echo $arr_str=implode(", ",$code_arr); }
?>

</p>
<p><strong>Preview:</strong> <a href="<?php echo $offer_data['preview_url']; ?>" target="_blank"><?php echo $offer_data['preview_url']; ?></a></p>
<p><strong>Restrictions:</strong> <?php echo $offer_data['restrictions']; ?></p>
<p><strong>Payable Action:</strong> <?php echo $offer_data['payable_action']; ?></p>
<p>&nbsp;</p>
            
<h3 class="main-heading">Affiliate Link</h3>
<?php if($offer_private_status==1) {	
 $temp_query=mysqli_query($mysqli,"select * from uc_affiliate_permission_offer_matches where user_id='$user_id' and offer_id='$offer_id'");
 $total_rec=mysqli_num_rows($temp_query);
 $temp_row=mysqli_fetch_assoc($temp_query);
 $matches_status=$temp_row['status'];
?>
				
<?php if ($total_rec>0 && $matches_status==3) { ?>
<p>Use the contextual link below to send traffic to the campaign. </p>
<p>You can add a Sub Id to add extra tracking.</p>
<p>
<input name="affiliate_link" type="text" id="affiliate_link" value="<?php echo $trkUrl ?>?offer_id=<?php echo $offer_id; ?>&amp;user_id=<?php echo $user_id; ?>&amp;sub_id=" size="60">
</p>
<h3 class="main-heading">Other Promotion Materials</h3>
<p>We have multiple options for advertising this website.</p>
<p>Below are the available banners for this campaign. Copy and paste the code below to add the banner to your site. If you require a specific banner type or size, contact your affiliate manager who will be happy to help.</p>
<p>&nbsp;</p>
					
<?php
 $offer_id=mysqli_real_escape_string($mysqli,$_GET['offer']);
 $query=mysqli_query($mysqli,"select bc.banner_id,bct.banner_type from uc_banner_creatives as bc inner join uc_banner_creatives_type as bct on bc.banner_creatives_type_id=bct.id where bc.offer_id='$offer_id'"); while($row=mysqli_fetch_assoc($query)){ 
?>

<p>
<div><?php echo $row['banner_type']; ?> <b> Banner</b></div>
<div>
<a href='<?php echo $trkUrl ?>?offer_id=<?php echo $offer_id; ?>&user_id=<?php echo $user_id; ?>&amp;sub_id=' target='_new' ><img src='<?php echo $websiteUrl; ?>marketing-materials/offer_creatives/<?php echo $row['banner_id']; ?>'/></a>
</div>
<div>
<textarea id="promotion_methods" cols="80" rows="5"><a href='<?php echo $trkUrl ?>?offer_id=<?php echo $offer_id; ?>&user_id=<?php echo $user_id; ?>&amp;sub_id=' target='_new' ><img src='<?php echo $websiteUrl; ?>marketing-materials/offer_creatives/<?php echo $row['banner_id']; ?>'/></a></textarea>
</div>
</p>
<?php } ?>
	
<?php } else if($total_rec>0 && $matches_status==2) { ?>
<div class="permission_warning">Your application to run this campaign is currently pending.</div>
<?php } else if($total_rec>0 && $matches_status==1) { ?>
<div class="permission_error">Your application to run this campaign has been declined. Please contact your affiliate manager for further information.</div>

<?php } else { ?>
<div class="run-campaign">
<div class="permission_warning">You are not currently approved to run this campaign. </div>
<p>
</p>
<a class="links" onClick="return show_note()">Request approval</a>
<br><br>
<?php echo resultBlock($errors,$successes); ?>
<div class="note_block" style="<?php if(count($errors)<1){ ?>display:none<?php } ?>">
<form method="post" action="" id="signupForm">
<input type="hidden" name="offer_id" id="offer_id" value="<?php echo $offer_id; ?>"> <textarea id="promotion_notes" placeholder="Please explain in detail how you indend to promote this campaign. Please include all websites you plan on using when advertising this campaign." name="promotion_notes" id="promotion_notes">
</textarea>
<input name="save" class="btn btn-primary filter-btn" value="Submit" type="submit">
</form>		

</div>
</div>
<?php } } else { ?>
<p>Use the contextual link below to send traffic to the campaign. </p>
<p>You can add a Sub Id to add extra tracking.</p>
<input name="affiliate_link" type="text" id="affiliate_link" value="<?php echo $trkUrl ?>?offer_id=<?php echo $offer_id; ?>&amp;user_id=<?php echo $user_id; ?>&amp;sub_id=" size="60">
                
<div class="innerInfo2 mar-40">
<h4 class="main-heading">Other Promotion Materials</h4>
<p>We have multiple options for advertising this website.</p>
<div class="slctHolder" style='min-height:0px !important;'>
<p>Below are the available banners for this campaign. Copy and paste the code below to add the banner to your site. If you require a specific banner type or size, contact your affiliate manager who will be happy to help.</p>
<p>&nbsp;</p>

<?php
$offer_id=mysqli_real_escape_string($mysqli,$_GET['offer']);
$query=mysqli_query($mysqli,"select bc.banner_id,bct.banner_type from uc_banner_creatives as bc inner join uc_banner_creatives_type as bct on bc.banner_creatives_type_id=bct.id where bc.offer_id='$offer_id'"); while($row=mysqli_fetch_assoc($query)) {
?>

<p>
<div><?php echo $row['banner_type']; ?> <b> Banner</b></div>
<div>
<a href='<?php echo $trkUrl ?>?offer_id=<?php echo $offer_id; ?>&user_id=<?php echo $user_id; ?>&amp;sub_id=' target='_new' ><img src='<?php echo $websiteUrl; ?>marketing-materials/offer_creatives/<?php echo $row['banner_id']; ?>'/></a>
</div>
<div>

<textarea name="affiliate_creatives_link" id="affiliate_creatives_link" cols="80" rows="5"><a href='<?php echo $trkUrl ?>?offer_id=<?php echo $offer_id; ?>&user_id=<?php echo $user_id; ?>&amp;sub_id=' target='_new' ><img src='<?php echo $websiteUrl; ?>marketing-materials/offer_creatives/<?php echo $row['banner_id']; ?>'/></a></textarea>
</div>
</p>
<?php }	?>
                 
</div>
</div>
<?php } ?>
</div>
</div>

<div class="sidebar-right">
<h4 class="heading-icon"><span><i class="fa fa-cog" aria-hidden="true"></i></span>Commission Structure</h4>	
<ul>
<li><span class="bold">Commission:</span> £<?php echo $offer_data['affiliate_payout']; ?></li>
<li><span class="bold">Second Rate:</span> £<?php echo $offer_data['affiliate_payout_second']; ?></li>
<li><span class="bold">3 Month EPC:</span> <?php echo $offer_data['epc']; ?> GBP</li>
<li><span class="bold">Current Status:</span> <span class="active_campaign">Active Campaign</span></li>
<p>
<p>Second Rate commission structure is <?php echo $offer_data['affiliate_payout_second_rate'];  ?>+ conversions on this campaign within the current month.</p>
<p> Please contact your <a href="#">Affiliate Manager</a> for further information.</p>
</ul>
</div>
</div>       		
</div>
</div>
</section>

<?php include("dashboard-footer.php"); ?>

<script>
function show_note(){
	$(".note_block").show();
}
</script>

<script>
$().ready(function() {
		$("#signupForm").validate({
			rules: {
				promotion_notes: "required",
			},
			messages: {
				promotion_notes: {
					required: "Please enter your promotion methods.",
				},
			}
		});
});
</script>