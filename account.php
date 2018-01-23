<?php require_once("dashboard-header.php"); ?>

<section class="dashboard-section">
    <div class="relative">
        <div class="wrapper">
            <div class="pt-30 pb-30 col-sm-9 nplr dashboad-right">
                <div id='main'>
               

                <!-- Content Wrapper. Contains page content -->
                <div class="graph-section">


                    <?php
                    if (isset($_GET['dt'])) {
                        $dt = $_GET['dt'];
                    } else {
                        $dt = '';
                    }
                    ?>
                    <!-- Main content -->
                    <div class="col-sm-12 nplr">
                    <div id="container" style="min-width: 310px; height: 300px; margin: 0 auto"></div>

                        <?php

                        $user_id = $loggedInUser->user_id;

                        $date_str = '';
                        $click_str = '';
                        $conversion_str = '';
                        $cost_str = '';
                        $payouts_str = '';
                        $profit_str = '';

                        if ($loggedInUser->checkPermission(array(1))) {

                            $graph_data = graph1($dt);

                            graph1JS($graph_data['date_str'], $graph_data['click_str'], $graph_data['conversion_str'], $graph_data['cost_str'], $graph_data['payouts_str'], $graph_data['profit_str']);
                        }
                        if ($loggedInUser->checkPermission(array(10))) {
                            $user_id = $loggedInUser->user_id;
                            $graph_data = graph2($dt, $user_id);
                            graph2JS($graph_data['date_str'], $graph_data['click_str'], $graph_data['conversion_str'], $graph_data['payouts_str']);

                        }

                        ?>

                        <select name="dt" style="width: 165px; margin-left:14px" id="time_range">
                            <option <?php if ($dt == 'Today') {
                                echo "selected";
                            } ?> value="Today">Today
                            </option>
                            <option <?php if ($dt == 'Yesterday') {
                                echo "selected";
                            } ?> value="Yesterday">Yesterday
                            </option>
                            <option <?php if ($dt == '' || $dt == 'CWeek') echo 'selected'; ?> <?php if ($dt == 'CWeek') {
                                echo "selected";
                            } ?> value="CWeek">This Week
                            </option>
                            <option <?php if ($dt == 'PWeek') {
                                echo "selected";
                            } ?> value="PWeek">Last Week
                            </option>
                            <option <?php if ($dt == 'CMonth') {
                                echo "selected";
                            } ?> value="CMonth">This Month
                            </option>
                            <option <?php if ($dt == 'PMonth') {
                                echo "selected";
                            } ?> value="PMonth">Last Month
                            </option>
                            <option <?php if ($dt == 'CYear') {
                                echo "selected";
                            } ?> value="CYear">This Year
                            </option>
                            <option <?php if ($dt == 'PYear') {
                                echo "selected";
                            } ?> value="PYear">Last Year
                            </option>
                        </select>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sidebar-right">
        <h4 class="heading-icon pt-30"><span><i class="fa fa-cog" aria-hidden="true"></i></span>Account Details</h4>
        <ul>
            <li><span class="bold">Account Status:</span>Affiliate Account</li>
            <li><span class="bold">Account Manager:</span>Account Manager</li>
            <li><span class="bold">Email:</span>contact@website.co.uk</li>
            <li><span class="bold">Skype:</span><a href="skype:skype?chat">Skype</a></li>
        </ul>
        <?php if ($loggedInUser->checkPermission(array(1))) { ?>
            <h4 class="heading-icon"><span><i class="fa fa-calendar" aria-hidden="true"></i></span><?php echo date('F'); ?> Statistics</h4>
            <ul>
                <li><span class="bold">Total Clicks: </span><span class="account-bottom-text">
      <?php getAdminClicks($loggedInUser->user_id); ?>
    </span></li>
                <li><span class="bold">Total Conversions: </span><span class="account-bottom-text">
      <?php getAdminConversions($loggedInUser->user_id); ?>
    </span></li>
                <li><span class="bold">Total Returns:</span><span class="account-bottom-text">
     <?php getAdminReturnConversions($loggedInUser->user_id); ?>
    </span></li>
                <li><span class="bold">Commission Earned: </span><span class="account-bottom-text">
      &pound;<?php getAdminCommission($loggedInUser->user_id); ?>
                </span>
                </li>
            </ul>

        <?php } else { ?>

            <h4 class="heading-icon"><span><i class="fa fa-calendar"
                                              aria-hidden="true"></i></span><?php echo date('F'); ?> Statistics</h4>
            <ul>
                <li><span class="bold">Total Clicks: </span><span class="account-bottom-text">
      <?php getUserClicks($loggedInUser->user_id); ?>
    </span></li>
                <li><span class="bold">Total Conversions: </span><span class="account-bottom-text">
      <?php getUserConversions($loggedInUser->user_id); ?>
    </span></li>
                <li><span class="bold">Total Returns:</span><span class="account-bottom-text">
     <?php getUserReturnConversions($loggedInUser->user_id); ?>
    </span></li>
                <li><span class="bold">Commission Earned: </span><span class="account-bottom-text">
      &pound;<?php getUserCommission($loggedInUser->user_id); ?>
    </span></li>

            </ul>
        <?php } ?>
    </div>
</section>
<div class="outer-wrapper">
    <section class="create-form">
        <div class="wrapper">
            <div class="pt-15 padding-0050 white_bg dashboard">
                <div class="pr-15 col-sm-6">
                    <div class="pb-30 blog-area">
                        <div class="right-img"><a href="publisher-campaign.php?offer=1021"><img src="marketing-materials/thumbnails/1021.jpeg"></a></div>
                        <div class="text-area">
                            <h4>Featured: Quidco Cashback </h4>
                            <p>Quidco is the smart and easy way to earn cashback when shopping online, in-store and on
                                mobile. With over 4,300 top retailers to choose from, Quidco gives its 5 million members
                                the chance to enjoy 8,000 offers with deals from all the big brands. <a
                                        href="publisher-campaign.php?offer=1020">Get Links</a></p>
                        </div>
                    </div>
                    <div class="mb-30 blog-area">
                        <div class="right-img"><a href="publisher-campaign.php?offer=1020"><img src="marketing-materials/thumbnails/1020.jpeg"></a></div>
                        <div class="text-area">
                            <h4>Featured: Booking.com </h4>
                            <p>Booking.com is one of the worlds largest hotel provider. Offer your visitors the chance
                                to make big savings on hotels in 118000 destinations worldwide. Browse hotel reviews and
                                find the guaranteed best price on hotels for all budgets. <a
                                        href="publisher-campaign.php?offer=1021">Get Links</a></p>
                        </div>
                    </div>
                    <div class="mb-30 blog-area">
                        <div class="right-img"><a href="publisher-campaign.php?offer=1022"><img src="marketing-materials/thumbnails/1022.jpeg"></a></div>
                        <div class="text-area">
                            <h4>Featured: Vanquis Bank </h4>
                            <p>Vanquis now offers affiliates the chance to earn commission from successful applications
                                for Vanquis credit cards. Vanquis Bank is a UK based credit card company, formed in 2002
                          and have now approved over 3 million customers in the UK. <a
                                        href="publisher-campaign.php?offer=1020">Get Links</a></p>
                        </div>
                    </div>
                </div>

                <div class="pl-15 col-sm-6">
                    <div class="monthly-advertiser">
                        <h2>Advertiser of the month</h2>
                        <div class="mb-30 blog-area">
                            <div class="right-img"><img src="/marketing-materials/thumbnails/1001.jpeg"></div>
                            <div class="text-area">
                                <h4>Sky Vegas - Slots & Casino Games</h4>
                                <p>Experience the real rush of Las Vegas in the comfort of your own home with our show
                                    stopping casino. ou can offer your first time members £10 completely free (no
                                    deposit required) plus a 200% matched 1st deposit bonus up to £1,000.</p>
                                <a class="apply-now" href="campaigns.php?offer=1001">Get Links</a>
                            </div>
                        </div>
                    </div>
                    <div class="monthly-advertiser">
                        <h2>Newest Campaign</h2>
                        <div class="mb-30 blog-area">
                            <div class="right-img"><img src="/models/site-templates/images/blog_img_1.jpg"></div>
                            <div class="text-area">
                                <h4>Philips UK</h4>
                                <p>TheSaaS is a responsive, professional, and multipurpose SaaS, Software, Startup and
                                    WebApp landing theme powered by Bootstrap 4. TheSaaS is a powerful and super
                                    flexible tool, which suits best for any kind of landing pages. TheSaaS is definitely
                                    a great kick starter for your web project.</p>    <a class="apply-now" href="#">Apply
                                    Now</a>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
    jQuery(function () {
        jQuery('#time_range').on('change', function () {
            var url = jQuery(this).val();
            if (url) {
                window.location = 'account.php?dt=' + this.value
            }
            return false;
        });
        $(".toggle-btn").click(function () {
            $(".toggle-menu").toggle();
        });
    });
</script>
<!-- <?php include "dashboard-footer.php"; ?> -->
