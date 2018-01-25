<?php
ini_set("display_startup_errors", 1);
ini_set("display_errors", 1);

/* Reports for either E_ERROR | E_WARNING | E_NOTICE  | Any Error*/
error_reporting(E_ALL);

require_once("../models/config.php");

// Check offer status
function GetOfferStatus($offer_id)
{
	global $mysqli,$db_table_prefix;
	
	$stmt = mysqli_query($mysqli,"SELECT id, offer_name, offer_url, affiliate_payout, country, status, private_offer FROM ".$db_table_prefix."offers WHERE id= '$offer_id' LIMIT 1");
	$row=array();
	while($temp_row=mysqli_fetch_assoc($stmt)){
		$row = array('offer_id' => $temp_row['id'], 'offer_url' => $temp_row['offer_url'], 'affiliate_payout' => $temp_row['affiliate_payout'], 'country' => $temp_row['country'], 'status' => $temp_row['status'],'private_offer' => $temp_row['private_offer']);
	}
	return $row;

}


// Gets users permission
function GetUserPermission($userid)
{
	global $mysqli,$db_table_prefix;
	
	$stmt = mysqli_query($mysqli,"SELECT um.permission_id, up.name FROM ".$db_table_prefix."user_permission_matches um, ".$db_table_prefix."permissions up WHERE um.user_id = '$userid' and up.id = um.permission_id LIMIT 1");
	while($temp_row=mysqli_fetch_assoc($stmt)){
		$row = array('permission_id' => $temp_row['permission_id'], 'name' => $temp_row['name']);
	}

	return $row;

}

//Checks user has permission for that campaign link
function checkUserOfferPermission($userId,$offerId){
	global $mysqli,$db_table_prefix;
	$query=mysqli_query($mysqli,"select id from uc_affiliate_permission_offer_matches where status='3' and user_id='$userId' and offer_id='$offerId'");
	if(mysqli_num_rows($query)>0){
		return 1;
	}else{
		return 0;
	}
}


// Adds clicks
function AddClick($date_time,$offer_id,$user_id,$sub_id,$sub_id_1,$status,$ip_address) {
	global $mysqli,$db_table_prefix;
	
	$stmt = mysqli_query($mysqli,"INSERT INTO ".$db_table_prefix."clicks (user_id, date_time, offer_id, sub_id, sub_id_1, status, ip_address)
	VALUES ('$user_id','$date_time','$offer_id','$sub_id','$sub_id_1','$status','$ip_address')");

}

$offer_id = (int)$_REQUEST['offer_id'];
$user_id = (int)$_REQUEST['user_id'];
if(isset($_REQUEST['sub_id'])){
	$sub_id = $_REQUEST['sub_id'];
}else{
	$sub_id="";
}
if(isset($_REQUEST['sub_id_1'])){
	$sub_id_1 = $_REQUEST['sub_id_1'];
}else{
	$sub_id_1="";
}
$userid = $loggedInUser->user_id;
$GetOfferStatus =  GetOfferStatus($offer_id);
$GetUserPermission =  GetUserPermission($user_id);

$userOfferPermission=1;
if($GetOfferStatus['private_offer']==1){
$userOfferPermission=checkUserOfferPermission($user_id,$offer_id);
}


if(!$user_id || !$offer_id || !$GetOfferStatus || $GetOfferStatus['status'] == '1' || $GetUserPermission['permission_id'] == '20' || $userOfferPermission==0 || $userOfferPermission==1 || $userOfferPermission==2)

{
	AddClick(date("Y-m-d H:i:s"),$offer_id,$user_id,$sub_id ,$sub_id_1 ,"1",$_SERVER['REMOTE_ADDR']);
	header("Location: $rejectUrl"); die();
}


else
{
	AddClick(date("Y-m-d H:i:s"),$offer_id,$user_id,$sub_id ,$sub_id_1 ,"2",$_SERVER['REMOTE_ADDR']);

    $offer = GetOfferStatus($offer_id);
    $offer_url = str_replace(array('{user_id}', '{sub_id}', '{sub_id_1}'), array($user_id, $sub_id, $sub_id_1), $offer['offer_url']);

	$offer_url=str_replace("{var_user_id}",$user_id,$offer_url);
	$offer_url=str_replace("{var_sub_id}",$sub_id,$offer_url);
	$offer_url=str_replace("{var_sub_id_1}",$sub_id_1,$offer_url);	
    header("Location: " .$offer_url); die();

}

?>
Redirecting...