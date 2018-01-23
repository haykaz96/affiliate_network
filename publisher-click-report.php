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
    $targetpage = "publisher-click-report.php?";
} elseif (!empty($_GET['dt']) && !empty($_GET['row'])) {
    $targetpage = "publisher-click-report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
} else {
    $targetpage = "publisher-click-report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
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
        $where = "where DATE(date_time)  between '$starts' and '$end' and user_id='$user_id' order by id desc LIMIT $start, $limit";
        $where1 = "where DATE(date_time)  between '$starts' and '$end' and user_id='$user_id'";
    } else {
        $where = "where  user_id='$user_id' LIMIT $start, $limit";
        $where1 = "where  user_id='$user_id'";
    }
} elseif ($dt == 'Today') {
    $StartDate = date('Y-m-d');
    $EndDate = date('Y-m-d');
    $where = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} elseif ($dt == 'Yesterday') {
    $StartDate = date('Y-m-d', strtotime("-1 days"));
    $EndDate = date('Y-m-d', strtotime("-1 days"));
    $where = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "where DATE(date_time)  between '$StartDate' and '$EndDate'and user_id='$user_id'";
} elseif ($dt == 'PMonth') {
    $StartDate = date('y-m-d', strtotime('first day of last month'));
    $EndDate = date('y-m-d', strtotime('last day of last month'));
    $where = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} elseif ($dt == 'CMonth') {
    $StartDate = date('Y-m-01');
    $EndDate = date('Y-m-d');
    $where = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} elseif ($dt == 'CYear') {
    $year = date('Y'); // Get current year and subtract 1
    $StartDate = $year . '-01-01';
    $EndDate = $year . '-12-31';
    $where = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "where DATE(date_time)  between '$StartDate' and '$EndDate' and user_id='$user_id'";
} else {
    $where = "where user_id='$user_id' order by id desc LIMIT $start, $limit";
    $where1 = "where  user_id='$user_id'";
}
//Gets data from selected database table
$sql = "select * from uc_clicks " . $where;
$result = mysqli_query($mysqli, $sql);

$sql1 = "select * from uc_clicks " . $where1;
$result1 = mysqli_query($mysqli, $sql1);

//Row counter & pagination
$count = mysqli_num_rows($result);
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
<p>Use the form below to view all clicks sent to our offers.</p>
<form method="POST" action="" name="query" id="query" class="status-form">
<div class="comm-float npl status-table">
<table width="100%">
<thead>
<tr>
<td>ID</td>
<td>Date / Time</td>
<td>Offer Id</td>
<td>Sub ID</td>
<td>IP Address</td>
<td>Status</td>
</tr>
</thead>
<tbody>
</tr>
<tr></tr>

<?php
 if (mysqli_num_rows($result) > 0) { 
 while ($row = mysqli_fetch_assoc($result)) { 
?>

<tr>
<?php if ($count != 0) { ?>
<td><?php echo $row['id']; ?></td>
<td><?php $date = date_create($row['date_time']); echo date_format($date, "H:i:s d/m/Y"); ?></td>
<td>
<?php 
 $offer_id = $row['offer_id'];
 $temp_q = mysqli_query($mysqli, "select id,offer_name from uc_offers where id='$offer_id';");
 $temp_row = mysqli_fetch_assoc($temp_q);
 echo substr($temp_row['offer_name'], 0, 30); 
?>
</td>
<td><?php echo substr($row['sub_id'], 0, 10); ?></td>
<td><?php echo substr($row['ip_address'], 0, 14); ?></td>
<td><?php echo getClickStatusColour($row['status']); ?></td>

<?php } ?>
</tr>
<?php } } else { ?>
<tr>
<td colspan="6">No Clicks Found.</td>
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
                window.location = 'publisher-click-report.php?dt=' + this.value
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
                window.location = 'publisher-click-report.php?dt=<?php echo $_GET['dt'];?>&start=<?php  echo $_GET['start']; ?>&end=<?php  echo $_GET['end']; ?>&row=' + this.value
            }
            return false;
        });
    });
</script>