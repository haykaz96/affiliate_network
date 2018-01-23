<?php
require_once("dashboard-header.php");

//Row Counter
$row = $_GET['row'];

if (empty($row)) {
    $limit = 50; //how many items to show per page
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
    $targetpage = "admin_conversion_report.php?";
} elseif (!empty($_GET['dt']) && !empty($_GET['row'])) {
    $targetpage = "admin_conversion_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
} else {
    $targetpage = "admin_conversion_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'] . "&";
}

if (($_GET['start'] == "") && ($_GET['row'] == "") && ($_GET['end'] == "")) {
    $targetpage = "admin_conversion_report.php?";
} elseif (!empty($_GET['dt']) && !empty($_GET['row'])) {
    $link_url = "&row=" . $_GET['row'] . "&dt=" . $_GET['dt'];
    $targetpage = "admin_conversion_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'];

    if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
        $targetpage .= "&user_id=" . $_GET['user_id'];
    }
    if (isset($_GET['offer_id']) && $_GET['offer_id'] !== '') {
        $targetpage .= "&offer_id=" . $_GET['offer_id'];
    }
    if (isset($_GET['ip_address']) && $_GET['ip_address'] !== '') {
        $targetpage .= "&ip_address=" . $_GET['ip_address'];
    }
    if (isset($_GET['status']) && $_GET['status'] !== '') {
        $targetpage .= "&status=" . $_GET['status'];
    }

} else {
    $link_url = "&row=" . $_GET['row'] . "&dt=" . $_GET['dt'];
    $targetpage = "admin_conversion_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'];
    if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
        $targetpage .= "&user_id=" . $_GET['user_id'];
    }
    if (isset($_GET['offer_id']) && $_GET['offer_id'] !== '') {
        $targetpage .= "&offer_id=" . $_GET['offer_id'];
    }
    if (isset($_GET['ip_address']) && $_GET['ip_address'] !== '') {
        $targetpage .= "&ip_address=" . $_GET['ip_address'];
    }
    if (isset($_GET['status']) && $_GET['status'] !== '') {
        $targetpage .= "&status=" . $_GET['status'];
    }
    if (isset($_GET['start']) && $_GET['start'] !== '') {
        $targetpage .= "&start=" . $_GET['start'];
    }
    if (isset($_GET['end']) && $_GET['end'] !== '') {
        $targetpage .= "&end=" . $_GET['end'];
    }
    if (isset($_GET['sub_id']) && $_GET['sub_id'] !== '') {
        $targetpage .= "&sub_id=" . $_GET['sub_id'];
    }

}

$targetpage .= '&';
//Filters items by clicking on them
$where = '';
$temp_link = "&dt=" . $_GET['dt'];
if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
    $user_id = addslashes($_GET['user_id']);
    $where .= " user_id='$user_id'";
    $temp_link .= "&user_id=" . $_GET['user_id'];
}
if (isset($_GET['offer_id']) && $_GET['offer_id'] !== '') {
    $offer_id = addslashes($_GET['offer_id']);
    $where = ($where != '') ? $where . " and " : "";
    $where .= " offer_id='$offer_id'";
    $temp_link .= "&offer_id=" . $_GET['offer_id'];
}
if (isset($_GET['ip_address']) && $_GET['ip_address'] !== '') {
    $ip_address = addslashes($_GET['ip_address']);
    $where = ($where != '') ? $where . " and " : "";
    $where .= " ip_address='$ip_address'";
    $temp_link .= "&ip_address=" . $_GET['ip_address'];
}
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $status = addslashes($_GET['status']);
    $where = ($where != '') ? $where . " and " : "";
    $where .= " status='$status'";
    $temp_link .= "&status=" . $_GET['status'];
}
if (isset($_GET['start']) && $_GET['start'] !== ''){
    $starts = isset($_GET['start'])? $_GET['start'] : '';
    $end = isset($_GET['end'])? $_GET['end'] : '';
    $where = ($where != '') ? $where . " and " : "";
    $where .= " DATE(date_time)  between '$starts' and '$end'";
}


//Date picker
$dt = $_GET['dt'];
if ($dt == 'Custom') {
    if (!empty($_GET['start']) && !empty($_GET['start'])) {
        $starts = $_GET['start'];
        $end = $_GET['end'];
        $where = ($where != '') ? $where . " and " : "";
        $where .= " DATE(date_time)  between '$starts' and '$end'";
    }
} elseif ($dt == 'Today') {
    $StartDate = date('Y-m-d');
    $EndDate = date('Y-m-d');
    $where = ($where != '') ? $where . " and " : "";
    $where .= " DATE(date_time)  between '$StartDate' and '$EndDate'";
} elseif ($dt == 'Yesterday') {
    $StartDate = date('Y-m-d', strtotime("-1 days"));
    $EndDate = date('Y-m-d', strtotime("-1 days"));
    $where = ($where != '') ? $where . " and " : "";
    $where .= " DATE(date_time)  between '$StartDate' and '$EndDate'";
} elseif ($dt == 'PMonth') {
    $StartDate = date('y-m-d', strtotime('first day of last month'));
    $EndDate = date('y-m-d', strtotime('last day of last month'));
    $where = ($where != '') ? $where . " and " : "";
    $where .= " DATE(date_time)  between '$StartDate' and '$EndDate'";
} elseif ($dt == 'CMonth') {
    $StartDate = date('Y-m-01');
    $EndDate = date('Y-m-d');
    $where = ($where != '') ? $where . " and " : "";
    $where .= " DATE(date_time)  between '$StartDate' and '$EndDate'";
} elseif ($dt == 'CYear') {
    $year = date('Y'); // Get current year and subtract 1
    $StartDate = $year . '-01-01';
    $EndDate = $year . '-12-31';
    $where = ($where != '') ? $where . " and " : "";
    $where .= " DATE(date_time)  between '$StartDate' and '$EndDate'";
}
$where = ($where != '') ? " where " . $where : "";
$where1 = $where;
$where .= " order by id desc LIMIT $start, $limit";

//Delete function
if (isset($_POST['Submit']) && $_POST['Submit'] == 'Delete') {
    if (count($_POST["selected_id"]) > 0) {
        $idArr = $_POST['selected_id'];
        foreach ($idArr as $id) {
            $query3 = "delete from uc_conversions where id='$id'";
            $run = mysqli_query($mysqli, $query3);
        }
        if ($run > 0) {
            if (!empty($dt)) {

                echo '<script>window.location="admin_conversion_report.php?dt=' . $dt . '&status=delete_conversion";</script>';
            } else {
                echo '<script>window.location="admin_conversion_report.php?status=delete_conversion";</script>';
            }
        }
    }
}

//Mass change status
if (isset($_POST['btn_change'])) {
    if (count($_POST["selected_id"]) > 0) {
        $idArr = $_POST['selected_id'];
        $status_arr = $_POST['status'];
        foreach ($idArr as $id) {

            $delivered = "UPDATE uc_conversions SET status='$status_arr' WHERE  id='$id'";
            $run = mysqli_query($mysqli, $delivered);

        }
        if ($run == 1) {
            if (!empty($dt)) {

                echo '<script>window.location="admin_conversion_report.php?dt=' . $dt . '&status=change_conversion_status";</script>';
            } else {
                echo '<script>window.location="admin_conversion_report.php?status=change_conversion_status";</script>';
            }
        }
    }
}


//Gets data from selected database table
$sql = "select * from uc_conversions" . $where;
$result = mysqli_query($mysqli, $sql);

$sql1 = "select * from uc_conversions" . $where1;
$result1 = mysqli_query($mysqli, $sql1);

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

<?php if ($_GET['status'] == 'delete_conversion') { $successes[] = lang("ADMIN_DELETE_CONVERSION"); echo resultBlock($errors, $successes); } ?>
<?php if ($_GET['status'] == 'change_conversion_status') { $successes[] = lang("ADMIN_CONVERSION_STATUS_CHANGED"); echo resultBlock($errors, $successes); } ?>
           
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
<div class="float-left">
<?php
 $query1 = "SELECT * FROM uc_conversions_status";
 $result1 = mysqli_query($mysqli, $query1);
?>

<form method="POST" action="" name="query" id="query" class="status-form">
<div class="pick-status">
<div class="pick-select">

<select id="dynamic_select" name="status">
<option value="" >Pick Status</option>
                                    
<?php while ($row1 = mysqli_fetch_assoc($result1)) { ?>
<option value="<?php echo $row1['id']; ?>"><?php echo $row1['name']; ?></option>
<?php } ?>
</select>
</div>

<div class="submit-section">
<input type="submit" class="btn btn-primary" name="btn_change" value="Submit"/>
</div>
</div>

<div class="status-table">
<table width="100%">
<tr>
<td><input type="checkbox" name="check_all" id="check_all" value=""/></td>
<td>ID</td>
<td>User</td>
<td>Date/Time</td>
<td>Offer</td>
<td>Payout</td>
<td>Cost</td>
<td>Trans Id</td>
<td>Ip Address</td>
<td>Status</td>
</tr>
<?php if (mysqli_num_rows($result) > 0) { ?>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><input type="checkbox" name="selected_id[]" class="checkbox" value="<?php echo $row['id']; ?>"/></td>
<td><?php echo $row['id']; ?></td>
<td><a href="admin_conversion_report.php?user_id=<?php echo $row['user_id']; ?><?php echo $link_url; ?>"><?php echo $row['user_id']; ?></a></td>
<td><?php $date = date_create($row['date_time']); echo date_format($date, "H:i:s d/m/Y"); ?></td>
<td><a href="admin_conversion_report.php?offer_id=<?php echo $row['offer_id']; ?><?php echo $link_url; ?>"><?php echo $row['offer_id']; ?></a></td>
<td>&pound;<?php echo $row['advertiser_cost']; ?></td>
<td>&pound;<?php echo $row['affiliate_payout']; ?></td>
<td><?php echo $row['transaction_id']; ?></td>
<td><a href="admin_conversion_report.php?ip_address=<?php echo $row['ip_address']; ?><?php echo $link_url; ?>"><?php echo $row['ip_address']; ?></a></td>
<td><a href="admin_conversion_report.php?status=<?php echo $row['status']; ?><?php echo $link_url; ?>"><?php echo getConversionStatusColour($row['status']); ?></a></td>
</tr>
<?php } } else { ?>
<tr>
<td colspan="10">No Conversion Found.</td>
</tr>
<?php } ?>
</table>
</div>

<div class="submit-form-bottom">
<input type="submit" onClick="return confirm('Are you sure you want to delete this item?');" name='Submit' value='Delete'/>
</div>
<?php
 $query1 = "SELECT * FROM uc_clicks_status";
 $result1 = mysqli_query($mysqli, $query1);
?>
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
        jQuery('#row').on('change', function () {
            var url = jQuery(this).val();
            if (url) {
                window.location = 'admin_conversion_report.php?row=' + this.value + "<?php echo $temp_link; ?>"
            }
            return false;
        });
    });
</script>
<script>
    jQuery(function () {
        jQuery('#time_range').on('change', function () {
            var url = jQuery(this).val();
            if (url) {
                window.location = 'admin_conversion_report.php?dt=' + this.value
            }
            return false;
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#check_all').on('click', function () {
            if (this.checked) {
                $('.checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        $('.checkbox').on('click', function () {
            if ($('.checkbox:checked').length == $('.checkbox').length) {
                $('#check_all').prop('checked', true);
            } else {
                $('#check_all').prop('checked', false);
            }
        });
    });
</script>