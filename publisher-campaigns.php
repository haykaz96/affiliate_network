<?php include 'dashboard-header.php';
error_reporting(0);
$search = addslashes($_GET['search']);
$country_id = addslashes($_GET['select_country']);
$category_id = addslashes($_GET['select_category']);
$status_id = addslashes($_GET['status2']);
$get_list = addslashes($_GET['list']);
//Row Counter & Pagination
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

if (($row == "") && ($search == "") && ($country_id == "") && ($category_id == "") && ($status_id == "") && ($get_list == "")) {
    $targetpage = "publisher-campaigns.php?";
} else {
    $targetpage = "publisher-campaigns.php?row=" . $row . "&search=" . $search . "&select_country=" . $country_id . "&category=" . $category_id . "&status2=" . $status_id . "&list=" . $get_list . "&";
}

$query_str = '  where 1';

if (!empty($search)) {
    $query_str .= "  and offer_name like '%$search%'";
}
if (!empty($country_id)) {
    if ($country_id != 0) {
        $query_str .= " and find_in_set('$country_id', country)";
    }
}
if (!empty($category_id)) {
    $query_str .= " and category='$category_id'";
}
if (!empty($status_id)) {
    //If status is public (4) then show all non private offers
    if ($status_id == 4) {
            $query_str .= " and private_offer=0";
    } else {
        $temp_query = mysqli_query($mysqli, "select offer_id from uc_affiliate_permission_offer_matches where status='$status_id'");
        $temp_offer_id = array(0);

        while ($temp_row = mysqli_fetch_assoc($temp_query)) {
            $temp_offer_id[] = $temp_row['offer_id'];
        }

        $str_offer_ids = implode(',', $temp_offer_id);
        $query_str .= " and id in($str_offer_ids)";
    }
}

$query_str .= "";

if (!empty($get_list)) {
    if ($get_list == 'Featured') {
        $query_str .= " and featured_offer='1'";
    }
    if ($get_list == 'Newest-Campaigns') {
        $query_str .= " order by id desc";
    }
    if ($get_list == 'Order-Ascending') {
        $query_str .= " order by offer_name asc";
    }
    if ($get_list == 'Order-Descending') {
        $query_str .= " order by offer_name desc";
    }
    if ($get_list == 'Order-EPC') {
        $query_str .= " order by epc desc";
    }
    if ($get_list == 'Order-CPA') {
        $query_str .= " order by affiliate_payout desc";
    }
}

$where = " $query_str LIMIT $start, $limit";
$where1 = " $query_str";
$sql1 = "select * from uc_offers " . $where1;

$result1 = mysqli_query($mysqli, $sql1);

if (!empty($get_list) && $get_list == 'Top-Campaigns') { $sql = "SELECT o.*,count(c.id) as total_con FROM `uc_offers` as o left join uc_conversions as c on o.id=c.offer_id group by o.id order by total_con desc LIMIT $start, $limit";
} else { $sql = "select * from uc_offers " . $where; }
    $result = mysqli_query($mysqli, $sql);
    $total_pages = $result1->num_rows;
    $pagination = paginationShow($total_pages, $targetpage, $limit);
?>
<section class="admin-report">
    <div id='wrapper' class="wrapper">
        <div id='content' class="content">
            <div id='main' class="padding-5000 padding-0050 admin-content">
                <div class="search-offers mar-20">
                    <div id="dateSelect">
                        <form name="form" method="get" action="">
                            <div class="comm-float">
                                <label for="search">Search Offers:</label>
                            </div>
                            <div class="col-xs-12 col-sm-6 pad-r-15">
                                <?php
                                if( isset($_GET['search']) ) {
                                    $reseivedSearch = $_GET['search'];
                                }
                                ?>
                                <input type="text" placeholder="Offer name" name="search" id="search" value="<?php echo $reseivedSearch ?>">
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <select id="select_category" name="select_category">
                                    <option value="" >Pick Category</option>
                                    <?php

                                        $query14 = "SELECT * FROM uc_offers_categories";
                                        $result14 = mysqli_query($mysqli, $query14);

                                    ?>
                                    <?php while ($row14 = mysqli_fetch_assoc($result14)) { ?>
                                        <option value="<?php echo $row14['id']; ?>"
                                            <?php echo ($row14['id'] == $category_id) ? "selected='selected'" : '' ?>
                                        >
                                            <?php echo $row14['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-6 pad-r-15">
                                <select id="select_country" name="select_country">
                                    <option value="" >Pick Country</option>
                                    <?php
                                    $query13 = "SELECT * FROM uc_countries order by country_name";
                                    $result13 = mysqli_query($mysqli, $query13);
                                    ?>
                                    <?php while ($row13 = mysqli_fetch_assoc($result13)) { ?>
                                    <option value="<?php echo $row13['id']; ?>"
                                        <?php echo ($row13['id'] == $country_id) ? "selected='selected'" : '' ?>
                                    >
                                        <?php echo $row13['country_name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <select id="status" name="status2">
                                    <option value="" >Pick Status</option>
                                    <?php
                                        $query12 = "SELECT * FROM uc_affiliate_permission_offer_status";
                                        $result12 = mysqli_query($mysqli, $query12);
                                    ?>
                                    <?php while ($row12 = mysqli_fetch_assoc($result12)) { ?>
                                    <option value="<?php echo $row12['id']; ?>"
                                        <?php echo ($row12['id'] == $status_id) ? "selected='selected'" : '' ?>
                                    >
                                        <?php echo $row12['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="comm-float">
                                <input type="submit" class="btn btn-primary filter-btn"  value="Filter"/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="search-offers search-right mar-20">
                    <h4 class="main-text">Affiliate Campaigns</h4>
                    <p>You can view all the offers we have available in the table below, you can filter offers based on Category, Country or search for a specific offer using the form on the left.</p>
                    <p>Select a campaign below to get affiliate links & banners or to apply to run a campaign. Contact your affiliate manager for any help or information.</p>
                </div>
                <form method="POST" action="" name="query" id="query" class="status-form">
                    <div class="status-table">
                        <div class="col-sm-6 ">
                        </div>
                        <div class="col-sm-6 advertisers-results" >
                            <select id="sorting" name="list" style="float: right;">
                                <option <?php if ($get_list == "Newest-Campaigns") { echo 'selected'; } ?> value="Newest-Campaigns">Newest Campaigns</option>
                                <option <?php if ($get_list == "Featured") { echo 'selected'; } ?> value="Featured">Featured Campaigns</option>
                                <option <?php if ($get_list == "Order-CPA") { echo 'selected'; } ?> value="Order-CPA">Highest CPA</option>
                                <option <?php if ($get_list == "Order-EPC") {echo 'selected'; } ?> value="Order-EPC">Highest EPC</option>
                                <option <?php if ($get_list == "Order-Ascending") { echo 'selected'; } ?> value="Order-Ascending">Campaigns A-Z</option>
                                <option <?php if ($get_list == "Order-Descending") { echo 'selected'; } ?> value="Order-Descending">Campaigns Z-A</option>
                            </select>
                        </div>
                        <div class="comm-float npl status-table">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <td>ID</td>
                                        <td>Offer Name</td>
                                        <td>Country</td>
                                        <td>Affiliate Payout</td>
                                        <td>Links</td>
                                    </tr>
                                    <?php
                                    if (mysqli_num_rows($result) > 0) { while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><a href="publisher-campaign.php?offer=<?php echo $row['id']; ?>"><img src="marketing-materials/thumbnails/<?php echo $row['thumb_id']; ?>" width="100" height="40" alt="campaign_logo"></a></td>
                                        <td><a href="publisher-campaign.php?offer=<?php echo $row['id']; ?>"><?php echo substr($row['offer_name'], 0, 48); ?></a>
                                    </div>
                                    <div>
                                        <?php $cat = $row['category'];
                                        $query1 = "SELECT * FROM uc_offers_categories where id ='$cat'";
                                        $result1 = $mysqli->query($query1);
                                        $row1 = mysqli_fetch_assoc($result1);
                                        echo $row1['name'];
                                        ?>
                                    </div>
                                </div>
                                <a href="?offer=10011"></a>
                            </td>
                            <td>
                                <?php $country = $row['country'];
                                $query2 = "SELECT * FROM uc_countries where id IN($country)";
                                $result2 = mysqli_query($mysqli, $query2);
                                if (mysqli_num_rows($result2) > 3) {
                                echo "3+ Countries";
                                } else {
                                $code_arr = array();
                                while ($row2 = mysqli_fetch_assoc($result2)) {
                                $code_arr[] = $row2['country_code']; }
                                echo $arr_str = implode(", ", $code_arr); }
                                ?>
                            </td>
                            <td>
                                <div>
                                    <div>3 Months EPC: &pound;<?php echo $row['epc']; ?> GBP</div>
                                    <div><strong>CPA: &pound;<?php echo $row['affiliate_payout']; ?> GBP</strong></div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div>
                                        <?php if ($row['private_offer'] == 1) {
                                        $temp_offer_id = $row['id'];
                                        $temp_query = mysqli_query($mysqli, "select status from uc_affiliate_permission_offer_matches  where user_id='$user_id' and offer_id='$temp_offer_id'");
                                        $temp_row = mysqli_fetch_assoc($temp_query);
                                        $temp_status = $temp_row['status']; if ($temp_status == 1 || $temp_status == 4) {
                                        echo "<a href='publisher-campaign.php?offer=" . $row['id'] . "'>Rejected</a>"; } elseif ($temp_status == 2) {
                                        echo "<a href='publisher-campaign.php?offer=" . $row['id'] . "'>Pending Approval</a>"; } elseif ($temp_status == 3) {
                                        echo "<a href='publisher-campaign.php?offer=" . $row['id'] . "'>Get Links</a>";
                                        } else {
                                        echo "<a href='publisher-campaign.php?offer=" . $row['id'] . "'>Request Approval</a>"; }
                                        } else {
                                        ?>
                                        <a href="publisher-campaign.php?offer=<?php echo $row['id']; ?>">Get  Links</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php } } else { ?>
                        <tr>
                            <td colspan="5">No Campaigns Found</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6 advertisers-results">
            </div>
            <div class="col-sm-6">
                <div class="pagination-section">
                    <span>Showing <?php echo $start; ?> - <?php echo $limit + $start; ?> of <?php echo $total_pages; ?> Results &nbsp;</span>
                    <select id="row" name="row">
                        <option <?php if ($limit == 20) { echo 'selected'; } ?> value="20">20</option>
                        <option <?php if ($limit == 50) { echo 'selected'; } ?> value="50">50</option>
                        <option <?php if ($limit == 100) { echo 'selected'; } ?> value="100">100 </option>
                        <option <?php if ($limit == 200) { echo 'selected'; } ?> value="200">200</option>
                    </select>
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</section>
</div>
<?php include("dashboard-footer.php"); ?>
<script>
jQuery(function () {
    jQuery('#row').on('change', function () {
        var url = jQuery(this).val();
        if (url) {
            window.location = 'publisher-campaigns.php?list=<?php echo $get_list; ?>&search=<?php echo $search; ?>&country_id=<?php echo $country_id; ?>&category_id=<?php echo $category_id; ?>&status_id=<?php echo $status_id; ?>&row=' + this.value
        }

        return false;
    });
});

jQuery(function () {
    jQuery('#sorting').on('change', function () {
    var url = jQuery(this).val();

    if (url) {
        window.location = 'publisher-campaigns.php?row=<?php echo $row; ?>&search=<?php echo $search; ?>&select_country=<?php echo $country_id; ?>&category=<?php echo $category_id; ?>&status2=<?php echo $status_id; ?>&list=' + this.value
    }

        return false;
    });
});
</script>