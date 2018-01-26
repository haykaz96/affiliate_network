<?php
include("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

if (empty($_SESSION)) {
    echo '<script>window.location="' . $websiteUrl . '";</script>';
}

//Prevent the user visiting the logged in page if he is not logged in
if (!isUserLoggedIn()) {
    header("Location: login.php");
    die();
}

$user_id = $loggedInUser->user_id;
$temp_query = mysqli_query($mysqli, "select * from uc_users where id='$user_id'");
$user_data = mysqli_fetch_assoc($temp_query);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $websiteName ?></title>
<link href="<?php echo $template ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="models/site-templates/css/font-awesome.min.css">
<link rel="stylesheet" href="models/site-templates/css/jquery-ui.css">
<link rel="stylesheet" href="models/site-templates/css/main1.css">

<script src='models/funcs.js' type='text/javascript'></script>
<script src="models/site-templates/js/jquery.min.js"></script>
<script src="models/site-templates/js/jquery-1.12.4.js"></script>
<script src="models/site-templates/js/jquery.validate.js"></script>
<script src="models/site-templates/js/jquery.validate.min.js"></script>
<script src='models/site-templates/js/highcharts.js' type='text/javascript'></script>
<script src='models/site-templates/js/exporting.js' type='text/javascript'></script>

<!-- Date Picker -->
<script src="models/site-templates/js/jquery-ui.js"></script>
<script>
    $(function () {
     $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
     $("#datepicker1").datepicker({dateFormat: 'yy-mm-dd'});
   });
</script>
</head>

<body>
<div class="outer-wrapper">
    <section class="menu-section">
        <div class="wrapper">
            <div class="logo dashboard-logo">
                <a href="account.php">
                    <ul>
                        <li><img src="models/site-templates/images/logo.jpg">
                        <li>Performance Marketing & Lead Generation</li>
                    </ul>
                </a>
            </div>
            <div class="dash-right">
                <ul>
                    <?php
                    $query = mysqli_query($mysqli, "select name from uc_users where id=" . $loggedInUser->user_id);
                    $user_data = mysqli_fetch_assoc($query);
                    $date = date_create(date("d-m-Y"));
                    $todays_date = date_format($date, "d M, Y");
                    ?>
                    <li><a><span><i class="fa fa-user" aria-hidden="true"></i></span>Welcome, <?php echo substr($user_data['name'], 0, 20) ?></a></li>
                    <li><a><span><i class="fa fa-calendar" aria-hidden="true"></i></span> <?php echo $todays_date; ?></a></li>
                    <li><a><span><i class="fa fa-map-marker" aria-hidden="true"></i></span> <?php echo substr($_SERVER['REMOTE_ADDR'], 0, 14) ?></a></li>
                </ul>
            </div>

        </div>
    </section>
</div>
<?php include "menu.php"; ?>