<?php

require_once("dashboard-header.php");


//Row Counter
$row = $_GET['row'];

if (empty($row)) {
    $limit = 20; //how many items to show per page
} else {
    $limit = $row;
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 0;
}
if ($page <= '1') {
    $start = 0;
} else {
    $start = ($limit * ($page - 1));
}
if (($_GET['dt'] == "") && ($_GET['row'] == "")) {
    $targetpage = "publisher-offer-report.php?";
} elseif (!empty($_GET['dt']) && !empty($_GET['row'])) {
    $targetpage = "publisher-offer-report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
} else {
    $targetpage = "publisher-offer-report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
}
if (isset($_GET['start']) && $_GET['start'] !== '') {
    $targetpage .= "&start=" . $_GET['start'] . "&";
}
if (isset($_GET['end']) && $_GET['end'] !== '') {
    $targetpage .= "&end=" . $_GET['end'] . "&";
}

//Date picker
$dt = $_GET['dt'];

if ($dt == 'Custom') {
    if (!empty($_GET['start']) && !empty($_GET['start'])) {
        $starts = $_GET['start'];
        $end = $_GET['end'];
        $where = " DATE(date_time)  between '$starts' and '$end' and user_id='$user_id' order by id LIMIT $start, $limit";
        $where1 = " DATE(date_time)  between '$starts' and '$end' and user_id='$user_id'";
    } else {
        $where = "  user_id='$user_id' LIMIT $start, $limit";
        $where1 = "  user_id='$user_id'";
    }

} elseif ($dt == 'Today') {
    $StartDate = date('Y-m-d');
    $EndDate = date('Y-m-d');
    $where = "  DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "  DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} elseif ($dt == 'Yesterday') {
    $StartDate = date('Y-m-d', strtotime("-1 days"));
    $EndDate = date('Y-m-d', strtotime("-1 days"));
    $where = " DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = " DATE(date_time)  between '$StartDate' and '$EndDate'and user_id='$user_id'";
} elseif ($dt == 'PMonth') {
    $StartDate = date('y-m-d', strtotime('first day of last month'));
    $EndDate = date('y-m-d', strtotime('last day of last month'));
    $where = " DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = " DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} elseif ($dt == 'CMonth') {
    $StartDate = date('Y-m-01');
    $EndDate = date('Y-m-d');
    $where = " DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = " DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} elseif ($dt == 'CYear') {
    $year = date('Y'); // Get current year and subtract 1
    $StartDate = $year . '-01-01';
    $EndDate = $year . '-12-31';
    $where = "  DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "   DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} else {
    $where = " user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = " user_id='$user_id'";
}



$sm ="select *, 
(select count(id) from uc_clicks where offer_id=uc_offers.id and (status='2') and 
 $where1) as total_clicks, 
 (select count(id) from uc_conversions where offer_id=uc_offers.id and (status='2' || 
 status='3' || status='4') and $where1) as total_conversions, 
 (select sum(affiliate_payout) from uc_conversions where offer_id=uc_offers.id and (status='2' || 
 status='3' || status='4') and $where1) as total_payouts 
 from uc_offers
  where (select count(id) from uc_clicks where offer_id=uc_offers.id and (status='2') and 
 $where1) > 0 
 or  (select count(id) from uc_conversions where offer_id=uc_offers.id and (status='2' || 
 status='3' || status='4') and $where1) > 0";


//Fetches data from database
$result1 = mysqli_query($mysqli, $sm);
$sm1= $sm." LIMIT $start, $limit ";
$result = mysqli_query($mysqli, $sm1);
$count = mysqli_num_rows($result);


//Row counter & pagination
$total_pages = mysqli_num_rows($result1);
$pagination = paginationShow($total_pages, $targetpage, $limit);


?>
<div class="outer-wrapper">
<section class="create-form">
<div class="wrapper">
<div class="padding-5000 padding-0050 white_bg dashboard">
<div class="side-right">
<form method="get" action="" name="query" id="query" class="status-form">
			
<label for="title">Filter Report:</label>
<select name="dt" id="time_range">
<option  value="All">All</option>
<option <?php if($dt =='Today' ){ echo "selected"; } ?> value="Today">Today</option>
<option <?php if($dt =='Yesterday' ){ echo "selected"; } ?> value="Yesterday">Yesterday</option>
<option <?php if($dt =='CMonth' ){ echo "selected"; } ?> value="CMonth">This Month</option>
<option <?php if($dt =='PMonth' ){ echo "selected"; } ?> value="PMonth">Last Month</option>
<option <?php if($dt =='CYear' ){ echo "selected"; } ?> value="CYear">This Year</option>
<option <?php if($dt =='Custom' ){ echo "selected"; } ?> value="Custom">Custom</option>
</select>		
<input type="text" name="start" <?php if($dt !='Custom' ){ ?> disabled <?php } ?> value="<?php echo $starts; ?>" id="datepicker">
<input type="text" name="end" <?php if($dt !='Custom' ){ ?> disabled <?php } ?> value="<?php echo $end; ?>"  id="datepicker1">

<input type="submit" class="btn btn-primary filter-btn" <?php if($dt !='Custom' ){ ?> disabled <?php } ?>  value="Filter"/>
</form>
</div>

<div class="dashboard-right">
<h2>Publisher Click Report</h2>
<p>Use the form below to view all clicks sent to our offers</p>
<form method="POST" action="" name="query" id="query" class="status-form">

<div class="comm-float npl status-table">
<table width="100%">
<thead>
<tr>
<td width="40%">Campaign</td>
<td width="20%">Clicks</td>
<td width="20%">Conversions</td>
<td width="10%">Commission</td>
<td width="10%">EPC</td>
</tr>
</thead>
<tbody>
</tr>
<tr></tr>

<?php
 $total_rows = mysqli_num_rows($result);
 $flag = 0;
 $counter = 0;
 
 if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_assoc($result)) {
 $temp_offer_id = $row['id'];
 $total_clicks = $row['total_clicks'];
 $total_conversions = $row['total_conversions'];
 $total_payouts = $row['total_payouts'];
 
 if ($total_clicks == 0) {
 $total_epc = 0;
 } else {
 $total_epc = $total_payouts / $total_clicks;
 }
 
 $flag = 1;
 $counter = $counter + 1;
?>

<?php if ($count != 0) { ?>

<tr>
<td><?php echo substr($row['offer_name'], 0, 40); ?></td>
<td><?php echo $total_clicks; ?></td>
<td><?php echo $total_conversions; ?></td>
<td>&pound;<?php echo number_format($total_payouts, 2); ?></td>
<td>&pound;<?php echo number_format($total_epc, 2); ?></td>

<?php } ?>
</tr>
<?php } } else { ?>
<tr>
<td colspan="6">No Offers Found.</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
<div class="pagination-section">
<span>Showing <?php echo $start; ?> - <?php $endNo = $limit + $start; echo $endNo < $total_pages ? $endNo : $total_pages; ?> of <?php echo $total_pages; ?> Results &nbsp;</span>
<select id="row" name="row">
<option <?php if ($limit == 20) { echo 'selected'; } ?> value="20">20</option>
<option <?php if ($limit == 50) { echo 'selected'; } ?> value="50">50</option>
<option <?php if ($limit == 100) { echo 'selected'; } ?> value="100">100</option>
<option <?php if ($limit == 200) { echo 'selected'; } ?> value="200">200</option>
</select>
<?php echo $pagination; ?>

</div>
</div>
</div>
</div>
</div>
</section>

<?php include("dashboard-footer.php"); ?>


<script>
    jQuery(function () {
        jQuery('#time_range').on('change', function () {
            var url = jQuery(this).val();
            if (url) {
                window.location = 'publisher-offer-report.php?dt=' + this.value
            }
            return false;
        });
    });
</script>
<script>
    jQuery(function () {
        jQuery('#row').on('change', function () {
            var url = jQuery(this).val();
            if (url) {
                window.location = 'publisher-offer-report.php?dt=<?php echo $_GET['dt'];?>&start=<?php  echo $_GET['start']; ?>&end=<?php  echo $_GET['end']; ?>&row=' + this.value
            }
            return false;
        });
    });
</script>