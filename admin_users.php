<?php require_once("dashboard-header.php");

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
if (($_GET['user_status'] == "") && ($_GET['row'] == "") && ($_GET['ref_id'] == "")) {
    $targetpage = "admin_users.php?";
} else {
    $targetpage = "admin_users.php?row=" . $_GET['row'] . "&user_status=" . $_GET['user_status'] . "&ref_id=" . $_GET['ref_id'] . "&";
}

//Forms posted
if (!empty($_POST)) {
    $deletions = $_POST['delete'];
    if ($deletion_count = deleteUsers($deletions)) {
        $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
    } else {
        $errors[] = lang("SQL_ERROR");
    }
}

$status = $_GET['user_status'];
$username = $_GET['user_id'];
$country = $_GET['country'];
$ip = $_GET['ip'];
$date = $_GET['date'];


if (!empty($status)) {
    $query1 = "and upm.permission_id = '$status' ";
}
if (!empty($ip)) {
    $query1 .= " and us.ip_address = '$ip' ";
}
if (!empty($country)) {
    $query1 .= " and us.country_id = '$country' ";
}

if (!empty($username)) {
    $query1 .= " and ( us.user_name = '$username' or us.id = '$username' )";
}


//Gets data from selected database table
$sql = "SELECT * FROM uc_users us INNER JOIN uc_user_permission_matches upm on us.id =upm.user_id INNER JOIN uc_permissions up on upm.permission_id = up.id $query1 LIMIT $start,$limit";
$userData = mysqli_query($mysqli, $sql);

$sql1 = "SELECT * FROM uc_users us INNER JOIN uc_user_permission_matches upm on us.id =upm.user_id INNER JOIN uc_permissions up on upm.permission_id = up.id $query1 ORDER BY upm.user_id DESC";
$result1 = mysqli_query($mysqli, $sql1);

//Row counter & pagination
$total_pages = mysqli_num_rows($result1);
$pagination = paginationShow($total_pages, $targetpage, $limit);
?>

<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">
<div class="comm-float pt-20 pb-10">
<form method="get" action="" name="query" id="query" class="status-form">
<div class="comm-float">
<div class="col-xs-12 col-sm-2 npl">
<label>Click Report Refine: </label>
<select name="user_status" id="user_status">
<option value="">Pick Status</option>
<?php
 $query11 = "SELECT * FROM uc_permissions";
 $result11 = mysqli_query($mysqli, $query11);
?>
<?php while ($row11 = mysqli_fetch_assoc($result11)) { ?>

<option <?php if ($row11['id'] == $_GET['user_status']) { echo 'selected'; } ?> value="<?php echo $row11['id']; ?>"><?php echo $row11['name']; ?></option>

<?php } ?>

</select>
</div>
                                <div class="col-xs-12 col-sm-2 npl">
                                    <label>Country: </label>
                                    <select name="country" id="country">
                                        <option value="">Pick country</option>
                                        <?php
                                        $query12 = "SELECT * FROM uc_countries";
                                        $result12 = mysqli_query($mysqli, $query12);
                                        ?>
                                        <?php while ($row12 = mysqli_fetch_assoc($result12)) { ?>

                                            <option <?php if ($row12['id'] == $_GET['country']) {
                                                echo 'selected';
                                            } ?> value="<?php echo $row12['id']; ?>"><?php echo $row12['country_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-xs-12 col-sm-2 npl">
                                    <label> User ID Search:</label>
                                    <input type="text" name="user_id" id="user_id"
                                           value="<?php echo $_GET['user_id']; ?>" placeholder="Enter User ID">

                                </div>
                                <div class="col-xs-12 col-sm-2 npl">
                                    <label>Ip Address</label>
                                    <input type="text" name="ip" id="ip" value="<?php echo $_GET['ip']; ?>"
                                           placeholder="Enter IP Address">

                                </div>

                                <div class="col-xs-12 col-sm-3 npl">
                                    <div class="admin-filter"><input type="submit" id="submit" value="Filter">
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>
                    <?php
                    //                    $query1 = "SELECT * FROM uc_clicks_status ";
                    //                    $result1 = mysqli_query($mysqli, $query1);

                    ?>

                </div>
                <div class="float-left">
                    <form method="POST" action="" name="query" id="query" class="status-form">

                        <div class="col-sm-12 mb-20 nplr status-table">
                            <?php echo resultBlock($errors, $successes); ?>
                            <table width="100%">
                                <tr>
                                    <td><input type="checkbox" name="check_all" id="check_all" value=""/></td>
                                    <td>ID</td>
                                    <td>Username</td>
                                    <td>Name</td>
                                    <td>Title</td>
                                    <td>Ref ID</td>
                                    <td>Signup</td>
                                    <td>Last Sign In</td>
                                    <td>IP</td>
                                </tr>
                                <?php
                                if (mysqli_num_rows($userData) > 0) {
                                    foreach ($userData as $v1) {
                                        ?>
                                        <tr>
                                            <td><input type='checkbox' class="checkbox"
                                                       name='delete[<?php echo $v1['user_id'] ?>]'
                                                       id='delete[<?php echo $v1['id'] ?>]'
                                                       value='<?php echo $v1['user_id'] ?>'>
                                            </td>
                                            <td><?php echo $v1['user_id'] ?></td>
                                            <td>
                                                <a href='admin_user.php?id=<?php echo $v1['user_id'] ?>'><?php echo $v1['user_name'] ?></a>
                                            </td>
                                            <td><?php echo $v1['name'] ?></td>
                                            <td><?php echo $v1['title'] ?></td>
                                            <td><?php echo $v1['ref_id'] ?></td>
                                            <td>
                                                <?php echo date("j M, Y", $v1['sign_up_stamp']); ?></td>

                                            <td>
                                                <?php
                                                //Interprety last login
                                                if ($v1['last_sign_in_stamp'] == '0') {
                                                    echo "Never";
                                                } else {
                                                    echo date("j M, Y", $v1['last_sign_in_stamp']);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $v1['ip_address'] ?>
                                            </td>
                                        </tr>

                                    <?php }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="9">
                                            No Members Found.
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                        <div class="col-sm-2 npl submit-form-bottom">
                            <input type="submit" onClick="return confirm('Are you sure you want to delete this item?');"
                                   name='Submit' value='Delete'/>
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
<script>
    jQuery(function () {
        jQuery('#row').on('change', function () {
            var url = jQuery(this).val();
            if (url) {
                window.location = 'admin_users.php?row=' + this.value
            }
            return false;
        });
    });
</script>