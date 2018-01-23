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

if (($_GET['start'] == "") && ($_GET['row'] == "") && ($_GET['end'] == "") && ($_GET['dt'] == "")) {
    $targetpage = "admin_click_report.php?";
} elseif (!empty($_GET['dt']) && !empty($_GET['row'])) {
    $link_url = "&row=" . $_GET['row'] . "&dt=" . $_GET['dt'];
    $targetpage = "admin_click_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'];

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
    $targetpage = "admin_click_report.php?row=" . $_GET['row'] . "&dt=" . $_GET['dt'];
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

//Delete function
if (isset($_POST['Submit']) && $_POST['Submit'] =='Delete') {
    if (count($_POST["selected_id"]) > 0) {
        $idArr = $_POST['selected_id'];
        foreach ($idArr as $id) {
            $temp_query = mysqli_query($mysqli, "select banner_id from uc_banner_creatives where id='$id'");
            $temp_row = mysqli_fetch_assoc($temp_query);
            @unlink('marketing-materials/offer_creatives/' . $temp_row['banner_id']);
            $query3 = "delete from uc_banner_creatives where id='$id'";
            $run = mysqli_query($mysqli, $query3);
        }
        if ($run == 1) {
            if (!empty($dt)) {

                echo '<script>window.location="admin_creative_files.php?dt=' . $dt . '&update_status=delete-creative";</script>';
            } else {
                echo '<script>window.location="admin_creative_files.php?update_status=delete-creative";</script>';
            }
        }
    }
}

// Mass change status from db
if (isset($_POST['btn_change'])) {
    if (count($_POST["selected_id"]) > 0) {
        $idArr = $_POST['selected_id'];
        $status_arr = $_POST['status'];
        foreach ($idArr as $id) {

            $delivered = "UPDATE uc_banner_creatives SET banner_status='$status_arr' WHERE  id='$id'";
            $run = mysqli_query($mysqli, $delivered);

        }
        if ($run == 1) {
            if (!empty($dt)) {

                echo '<script>window.location="admin_creative_files.php?dt=' . $dt . '&update_status=change-creative-status";</script>';
            } else {
                echo '<script>window.location="admin_creative_files.php?update_status=change-creative-status";</script>';
            }
        }
    }
}


//Gets data from selected database table
$sql = "select * from uc_banner_creatives " . $sort_filter." order by id desc limit $start, $limit";
$result = mysqli_query($mysqli, $sql);

$sql1 = "select * from uc_banner_creatives " . $sort_filter;
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

<?php if ($_GET['update_status'] == 'change-creative-status') { $successes[] = lang("CHANGE_CREATIVE_STATUS"); echo resultBlock($errors, $successes); } ?>
<?php if ($_GET['update_status'] == 'delete-creative') { $successes[] = lang("DELETE_CREATIVE"); echo resultBlock($errors, $successes); } ?>

</div>
<?php
 $query1 = "SELECT * FROM uc_banner_creatives_status";
 $result1 = mysqli_query($mysqli, $query1);
?>
</div>
<div class="float-left">
<?php
 $query1 = "SELECT * FROM uc_banner_creatives_status";
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
<td>Banner Type</td>
<td>Date/Time</td>
<td>Offer Id</td>
<td>Status</td>
</tr>
<tr></tr>

<?php if (mysqli_num_rows($result) > 0) { ?>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>

<tr>
<td><input type="checkbox" name="selected_id[]" class="checkbox" value="<?php echo $row['id']; ?>"/></td>
<td><?php echo $row['id']; ?></td>
<td>
<?php
 $banner_type_id = $row['banner_creatives_type_id'];
 $temp_query = mysqli_query($mysqli, "select * from uc_banner_creatives_type where id='$banner_type_id'");
 $temp_row = mysqli_fetch_assoc($temp_query);
 echo $temp_row['banner_type'];
?>
</td>
<td><?php $date = date_create($row['date_time']); echo date_format($date, "H:i:s d/m/Y"); ?></td>
<td><a href="admin_creative_files.php?offer_id=<?php echo $row['offer_id']; ?><?php echo $link_url; ?>"><?php echo $row['offer_id']; ?></a></td>
<td><?php echo getBannerStatusColor($row['banner_status']); ?></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img onClick="return shownote(<?php echo $row['id']; ?>)" src="models/site-templates/images/arrow-slt.png"/></td>
</tr>

<tr id="note_<?php echo $row['id']; ?>" style="display:none">
<td colspan="8"><img src="marketing-materials/offer_creatives/<?php echo $row['banner_id']; ?>"></td>
</tr>

<?php } } else { ?>

<tr>
<td colspan="8">No Creatives Found.</td>
</tr>
<?php } ?>

</table>
</div>
</div>

<div class="col-sm-2 npl submit-form-bottom">
<input type="submit" onClick="return confirm('Are you sure you want to delete this item?');"
name='Submit' value='Delete'/>
<?php
 $query1 = "SELECT * FROM uc_creatives_status";
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
    function shownote(id) {
        $("#note_" + id).toggle();
    }
    jQuery(function () {
        jQuery('#time_range').on('change', function () {
            var url = jQuery(this).val();
            if (url) {
                window.location = 'admin_creative_files.php?dt=' + this.value
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
                window.location = 'admin_creative_files.php?row=' + this.value + "<?php echo $temp_link; ?>";
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