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
    $targetpage = "admin_offer_report.php?";
} elseif (!empty($_GET['dt']) && !empty($_GET['row'])) {
    $targetpage = "admin_offer_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
} else {
    $targetpage = "admin_offer_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
}
if (isset($_GET['start']) && $_GET['start'] !== '') {
    $targetpage .= "&start=" . $_GET['start'] . "&";
}
if (isset($_GET['end']) && $_GET['end'] !== '') {
    $targetpage .= "&end=" . $_GET['end'] . "&";
}

//Date picker
$dt = $_GET['dt'];
if (!empty($_GET['start']) && !empty($_GET['start'])) {
    $starts = $_GET['start'];
    $end = $_GET['end'];
    $where = " and DATE(date_time)  between '$starts' and '$end' LIMIT $start, $limit";
    $where1 = " and DATE(date_time)  between '$starts' and '$end'";
}

if (!empty($_GET['start']) && !empty($_GET['start'])) {
    $starts = $_GET['start'];
    $end = $_GET['end'];
    $where = " and DATE(date_time)  between '$starts' and '$end' LIMIT $start, $limit";
    $where1 = " and DATE(date_time)  between '$starts' and '$end'";
} elseif ($dt == 'Today') {
    $StartDate = date('Y-m-d');
    $EndDate = date('Y-m-d');
    $where = " and DATE(date_time)  between '$StartDate' and '$EndDate'  order by id desc LIMIT $start, $limit";
    $where1 = " and DATE(date_time)  between '$StartDate' and '$EndDate' ";
} elseif ($dt == 'Yesterday') {
    $StartDate = date('Y-m-d', strtotime("-1 days"));
    $EndDate = date('Y-m-d', strtotime("-1 days"));
    $where = " and DATE(date_time)  between '$StartDate' and '$EndDate' order by id desc LIMIT $start, $limit";
    $where1 = " and DATE(date_time)  between '$StartDate' and '$EndDate'";
} elseif ($dt == 'PMonth') {
    $StartDate = date('y-m-d', strtotime('first day of last month'));
    $EndDate = date('y-m-d', strtotime('last day of last month'));
    $where = " and DATE(date_time)  between '$StartDate' and '$EndDate' and order by id desc LIMIT $start, $limit";
    $where1 = " and DATE(date_time)  between '$StartDate' and '$EndDate' ";
} elseif ($dt == 'CMonth') {
    $StartDate = date('Y-m-01');
    $EndDate = date('Y-m-d');
    $where = " and DATE(date_time)  between '$StartDate' and '$EndDate'  order by id desc LIMIT $start, $limit";
    $where1 = " and DATE(date_time)  between '$StartDate' and '$EndDate' ";
} elseif ($dt == 'CYear') {
    $year = date('Y'); // Get current year and subtract 1
    $StartDate = $year . '-01-01';
    $EndDate = $year . '-12-31';
    $where = " and DATE(date_time)  between '$StartDate' and '$EndDate'  order by id desc LIMIT $start, $limit";
    $where1 = " and DATE(date_time)  between '$StartDate' and '$EndDate' ";
} else {
    $where = " order by id desc LIMIT $start, $limit";
    $where1 = " ";
}


$sm ="select *, 
(select count(id) from uc_clicks where offer_id=uc_offers.id and (status='2') $where1) as total_clicks, 
 (select count(id) from uc_conversions where offer_id=uc_offers.id and (status='2' || 
 status='3' || status='4') $where1) as total_conversions, 
 (select sum(affiliate_payout) from uc_conversions where offer_id=uc_offers.id and (status='2' || 
 status='3' || status='4') $where1) as total_payouts 
 from uc_offers
  where (select count(id) from uc_clicks where offer_id=uc_offers.id and (status='2') $where1) > 0 
 or  (select count(id) from uc_conversions where offer_id=uc_offers.id and (status='2' || 
 status='3' || status='4') $where1) > 0";

//Gets data from selected database table
$sm1= $sm." LIMIT $start, $limit ";
$result = mysqli_query($mysqli, $sm1);
$result1 = mysqli_query($mysqli, $sm);
$count = mysqli_num_rows($result);

//Row counter & pagination
$total_pages = mysqli_num_rows($result1);
$pagination = paginationShow($total_pages, $targetpage, $limit);

?>

<div id='left-nav'>
</div>
<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">

<div id="dateSelect">
<form method="get" action="" name="query" id="query">
<div class="click-left">
<div class="comm-float">
<p class="blue-text">Click History Report</p>

<div class="col-xs-12 col-sm-4">
<p><span class="bold">Select Start Date:</span></p>
</div>

<div class="col-xs-12 col-sm-8">
<input type="text" name="start" value="<?php echo $_GET['start']; ?>" id="datepicker">
</div>
</div>

<div class="comm-float">
<div class="col-xs-12 col-sm-4">
<p><span class="bold"> Select End Date:</span></p>
</div>

<div class="col-xs-12 col-sm-8">
<input type="text" name="end" value="<?php echo $_GET['end']; ?>" id="datepicker1">
</div>
</div>

<div class="comm-float">
<div class="filter"><input type="submit" id="submit" value="Filter"></div>
</div>
</div>

<div class="click-left click-right">
<p class="blue-text">Quick Report Links</p>
<ul class="months-links">
<li><a href="?dt=Today">Today</a></li>
<li><a href="?dt=Yesterday">Yesterday</a></li>
<li><a href="?dt=CMonth">This Month</a></li>
<li><a href="?dt=PMonth">Last Month</a></li>
<li><a href="?dt=CYear">This Year</a></li>
</ul>
<p class="blue-text">Click History Report</p>
<p>This report allows you to export details for each click sent to the campaigns.</p>
</div>
</form>
</div>

<div class="mb-20 status-table">
<div>
<div class="col-sm-12">
<div class=" mb-20 status-table">
<table width="100%">
<tr>
<td>Campaign</td>
<td>Clicks</td>
<td>Conversions</td>
<td>Payout</td>
<td>EPC</td>
</tr>
<tr></tr>

<?php
 $total_rows = mysqli_num_rows($result);
 $flag = 0;
 if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_assoc($result)) {
 $temp_offer_id = $row['id'];
 $total_clicks = $row['total_clicks'];
 $total_conversions = $row['total_conversions'];
 $total_payouts = $row['total_payouts'];
 if ($total_clicks == 0) {
 $total_epc = 0;
 } else {
 $total_epc = $total_payouts/ $total_clicks;
 }

 $flag = 1;
 ?>

<tr>
<td><?php echo $row['offer_name']; ?></td>
<td><?php echo $total_clicks; ?></td>
<td><?php echo $total_conversions; ?></td>
<td>&pound;<?php echo number_format($total_payouts, 2); ?></td>
<td>&pound;<?php echo number_format($total_epc, 2); ?></td>

</tr>
<?php } } if ($flag == 0) { ?>
<tr>

<td colspan="5">No Offers Found</td>
</tr>
<?php } ?>
</table>
</div>
</div>

</form>
</div>

<div class="pagination-section">
<span>Showing <?php echo $start; ?> - <?php $endNo =$limit + $start; echo $endNo < $total_pages ? $endNo : $total_pages; ?> of <?php echo $total_pages; ?>  Results &nbsp;</span>
<select id="row" name="row">
<option <?php if ($limit == 50) { echo 'selected'; } ?> value="50">50</option>
<option <?php if ($limit == 100) { echo 'selected'; } ?> value="100">100</option>
<option <?php if ($limit == 200) { echo 'selected'; } ?> value="200">200</option>
<option <?php if ($limit == 500) { echo 'selected'; } ?> value="500">500</option>
</select>
<?php echo $pagination; ?>

<div class="col-sm-3">

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
                window.location = 'admin_offer_report.php?dt=' + this.value
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
                window.location = 'admin_offer_report.php?dt=<?php echo $_GET['dt'];?>&start=<?php  echo $_GET['start']; ?>&end=<?php  echo $_GET['end']; ?>&row=' + this.value
            }
            return false;
        });
    });
</script>