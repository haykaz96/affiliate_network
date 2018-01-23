<?php
 
 require_once("models/config.php");
 if (!securePage($_SERVER['PHP_SELF'])){die();}
 



   	
	if(!empty($_REQUEST['offer_id'])){$offer_id = addslashes($_REQUEST['offer_id']); } 
	if(!empty($_REQUEST['user_id'])){$user_id = addslashes($_REQUEST['user_id']); } else { $user_id=0; }
	if(!empty($_REQUEST['tracking_id'])){$tracking_id = addslashes($_REQUEST['tracking_id']); } else { $tracking_id=0; }
	if(!empty($_REQUEST['sub_id'])){$sub_id = addslashes($_REQUEST['sub_id']); } else { $sub_id=0; }
	if(!empty($_REQUEST['sub_id1'])){ $sub_id1 = addslashes($_REQUEST['sub_id1']); } else { $sub_id1=0; }
if(!empty($offer_id)){
	 $sql12 = "select * from uc_network_offers where id='$offer_id' ";
	$result12 = mysqli_query($mysqli,$sql12);
	$row12 = mysqli_fetch_array($result12);
	$payout = $row12['default_payout'];
	if(!empty($payout)){
	$date_time = date("Y-m-d H:i:s");
	$ip_address = $_SERVER['REMOTE_ADDR'];
	 $sql = "UPDATE uc_users  SET account_balance=account_balance+'$payout' WHERE id=$user_id";
	$amount = $mysqli->query($sql);
	$stmt = mysqli_query($mysqli,"INSERT INTO ".$db_table_prefix."conversions (user_id,date_time,offer_id,payout,tracking_id,sub_id,sub_id_1,status,ip_address)
		VALUES ('$user_id','$date_time','$offer_id','$payout','$tracking_id','$sub_id','$sub_id1','3','$ip_address')");
	 $conversion_id=mysqli_insert_id($mysqli);	

         

echo '<br>&lt;?xml version="1.0" encoding="UTF-8"?&gt;
<br>&lt;note>
<br>&lt;id>'.$conversion_id.'&lt;/id&gt;
<br>  &lt;result>Submitted&lt;/result&gt;
<br>  &lt;Offer ID>'.$offer_id.'&lt;/offer_id&gt;
<br>  &lt;Tracking ID>'.$tracking_id.'&lt;/tracking_id&gt;
<br>  &lt;Sub ID>'.$sub_id.'&lt;/sub_id&gt;
<br>  &lt;Payout>&dollar;'.$payout.'&lt;/payout&gt;
<br>  &lt;IP Address>'.$ip_address.'&lt;/ip_address&gt;

<br>&lt;/note&gt;';
}else{
	echo "No offer found";
}
}else{
	echo "No offer found";
}
?>