<?php

require_once("../models/config.php");

// Check offer status
function GetOfferStatus($oid)
{
    global $mysqli, $db_table_prefix;

    $stmt = mysqli_query($mysqli, "SELECT id,offer_name,description,preview_url,offer_url,affiliate_payout,category,category,status,country,private_offer FROM " . $db_table_prefix . "offers WHERE id= '$oid'  LIMIT 1");
    $row = array();
    while ($temp_row = mysqli_fetch_assoc($stmt)) {
        $row = array('oid' => $temp_row['id'], 'offer_name' => $temp_row['offer_name'], 'description' => $temp_row['description'], 'preview_url' => $temp_row['preview_url'], 'offer_url' => $temp_row['offer_url'], 'payout' => $temp_row['affiliate_payout'], 'category' => $temp_row['category'], 'country' => $temp_row['country'], 'status' => $temp_row['status'], 'private_offer' => $temp_row['private_offer']);
    }
    return $row;

}

// Gets users permission
function GetUserPermission($userid)
{
    global $mysqli, $db_table_prefix;


    $stmt = mysqli_query($mysqli, "SELECT um.permission_id, up.name FROM " . $db_table_prefix . "user_permission_matches um, " . $db_table_prefix . "permissions up WHERE um.user_id = '$userid' and up.id = um.permission_id LIMIT 1");
    while ($temp_row = mysqli_fetch_assoc($stmt)) {
        $row = array('permission_id' => $temp_row['permission_id'], 'name' => $temp_row['name']);
    }

    return $row;

}

// Adds clicks
function AddClick($date_time, $oid, $aid, $sid, $sid1, $status, $ip_address)
{
    global $mysqli, $db_table_prefix;

    $stmt = mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "clicks (date_time,offer_id,user_id,sub_id,sub_id_1,status,ip_address)
		VALUES ('$date_time','$oid','$aid','$sid','$sid1','$status','$ip_address')");

}

//Checks if user has permission for the campaign
function checkUserOfferPermission($userId, $offerId)
{
    global $mysqli, $db_table_prefix;
    $query = mysqli_query($mysqli, "select id from uc_affiliate_permission_offer_matches where status='3' and user_id='$userId' and offer_id='$offerId'");
    if (mysqli_num_rows($query) > 0) {
        return 1;
    } else {
        return 0;
    }
}


$oid = (int)$_REQUEST['offer_id'];
$aid = (int)$_REQUEST['user_id'];
if (isset($_REQUEST['sub_id'])) {
    $sid = $_REQUEST['sub_id'];
} else {
    $sid = "";
}
if (isset($_REQUEST['sub_id1'])) {
    $sid1 = $_REQUEST['sub_id1'];
} else {
    $sid1 = "";
}
$userid = $loggedInUser->user_id;
$GetOfferStatus = GetOfferStatus($oid);
$GetUserPermission = GetUserPermission($aid);

$userOfferPermission = 1;
if ($GetOfferStatus['private_offer'] == 1) {
    $userOfferPermission = checkUserOfferPermission($aid, $oid);
}
echo "<pre>";
print_r("oid $oid<br>");
print_r("aid $aid<br>");
print_r("userid $userid<br>");
print_r("GetOfferStatus <br>");
print_r($GetOfferStatus);
print_r("userOfferPermission $userOfferPermission<br>");
print_r("GetUserPermission<br>");
print_r($GetOfferStatus);
print_r($userOfferPermission);
echo "</pre>";
exit();

if (!$aid || !$oid || !$GetOfferStatus || $GetOfferStatus['status'] == '1' || $GetUserPermission['permission_id'] == '20' || $GetUserPermission['permission_id'] == '21' || $userOfferPermission == 1 || $userOfferPermission == 2) {
    AddClick(date("Y-m-d H:i:s"), $oid, $aid, $sid, $sid1, "1", $_SERVER['REMOTE_ADDR']);
    header("Location: $rejectUrl");
    die();
} else {
    AddClick(date("Y-m-d H:i:s"), $oid, $aid, $sid, $sid1, "2", $_SERVER['REMOTE_ADDR']);

    $offer = GetOfferStatus($oid);
    $offer_url = str_replace(array('{aid}', '{sid}', '{sid1}'), array($aid, $sid, $sid1), $offer['offer_url']);

    $offer_url = str_replace("{var_user_id}", $aid, $offer_url);
    $offer_url = str_replace("{var_sub_id}", $sid, $offer_url);
    $offer_url = str_replace("{var_sub_id1}", $sid1, $offer_url);
    header("Location: " . $offer_url);
    die();

}

?>
Redirecting...