<?php

if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

//Links for logged in user
if (isUserLoggedIn()) { ?>

<section class="box-shadows blue-bg dashboard-menu">
    <div class="wrapper">
		<a class="btn btn-default" href="#" style="width: 50px;height:50px;">
            <i class="fa fa-align-justify open-menu" title="Align Justify"></i>
          </a>
        <div class="navigation">
            <div class="toggle-menu">			 
                <div class="left-menu">
                    <?php if ($loggedInUser->checkPermission(array(1))){ ?>
                    <ul>
                        <li><a class="account" href="account.php"><span class="menu-icon"><i class="fa fa-area-chart" aria-hidden="true"></i></span> Dashboard </a>
                        </li>
                        <li><a class="dropdown-toggle"><span class="menu-icon"><i class="fa fa-tasks"></i></span> Publisher Menu <span class="caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
                            <div class="sub-menu dropdown-menu">
                                <ul class="inner-menu">
                                    <li><a class="publisher-campaigns" href="publisher-campaigns.php"><span class="menu-icon"><i
                                                        class="fa fa-clone"></i></span> Campaigns</a></li>
                                    <li><a class="publisher-click-report" href="publisher-click-report.php?dt=CMonth"><span class="menu-icon"><i
                                                        class="fa fa-clone"></i></span> Click Report</a></li>
                                    <li><a class="publisher-conversion-report" href="publisher-conversion-report.php?dt=CMonth"><span class="menu-icon"><i
                                                        class="fa fa-bar-chart"></i></span> Conversion Report</a></li>
                                    <li><a class="publisher-offer-report" href="publisher-offer-report.php?dt=CMonth"><span class="menu-icon"><i
                                                        class="fa fa-bar-chart"></i></span> Offer Report</a></li>
                                    <li><a class="publisher-account-settings" href='publisher-account-settings.php'><span class="menu-icon"><i
                                                        class="fa fa-cog"></i></span> Account Settings</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a class="dropdown-toggle"><span class="menu-icon"><i class="fa fa-bar-chart"></i></span> Reports
                                & Statistics <span class="caret"><i class="fa fa-caret-down"
                                                                   aria-hidden="true"></i></span></a>
                            <div class="sub-menu dropdown-menu">
                                <ul class="inner-menu">
                                    <li><a class="admin_click_report" href='admin_click_report.php'><span class="menu-icon"><i
                                                        class="fa fa-check"></i></span> Click Report</a></li>
                                    <li><a class="admin_conversion_report" href='admin_conversion_report.php?dt=CMonth'><span class="menu-icon"><i
                                                        class="fa fa-link"></i></span> Conversion Report</a></li>
                                    <li><a class="admin_offer_report" href='admin_offer_report.php?dt=CMonth'><span class="menu-icon"><i
                                                        class="fa fa-ticket"></i></span> Offer Report</a></li>
                                </ul>
                            </div>
                        <li><a class="dropdown-toggle"><span class="menu-icon"><i class="fa fa-usd"></i></span> Admin
                                Menu <span class="caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
                            <div class="sub-menu dropdown-menu">
                                <ul class="inner-menu">
                                    <li><a class="admin_users" href="admin_users.php"><span class="menu-icon"><i
                                                        class="fa fa-gift"></i></span> User Accounts</a></li>
                                    <li><a class="admin_campaign_approvals" href='admin_campaign_approvals.php'><span class="menu-icon"><i
                                                        class="fa fa-usd"></i></span> Campaign Approvals</a></li>
                                    <li><a class="admin_creative_files" href='admin_creative_files.php'><span class="menu-icon"><i
                                                        class="fa fa-usd"></i></span> Creative Files</a></li>

                                </ul>
                            </div>
                    </ul>
                </div>
                <div class="left-menu right-menu">
                    <ul>
                        <li><a class="dropdown-toggle"><span class="menu-icon"><i class="fa fa-cogs"></i></span> Website
                                Menu <span class="caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
                            <div class="sub-menu dropdown-menu">
                                <ul class="inner-menu">
                                    <li><a class="admin_countries" href='admin_countries.php'><span class="menu-icon"><i
                                                        class="fa fa-globe"></i></span> Countries</a></li>
                                    <li><a class="admin_pages" href='admin_pages.php'><span class="menu-icon"><i
                                                        class="fa fa-clone"></i></span> Website Pages</a></li>
                                    <li><a class="admin_permissions" href='admin_permissions.php'><span class="menu-icon"><i
                                                        class="fa fa-clone"></i></span> User Permissions</a></li>
                                    <li><a class="admin_configuration" href='admin_configuration.php'><span class="menu-icon"><i
                                                        class="fa fa-cog"></i></span> Configuration</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a class="logout" href="logout.php"><span class="menu-icon"><i class="fa
                        fa-sign-in"></i></span> Logout</a>
                        </li>
                    </ul>

                    <?php } else { ?>
                    
                        <ul>
                        <li><a class="account" href="account.php"><span class="menu-icon"><i class="fa fa-area-chart"  aria-hidden="true"></i></span> Dashboard</a></li>
                        <li><a class="dropdown-toggle"><span class="menu-icon"><i class="fa fa-tasks"></i></span> Campaigns <span class="caret"><i class="fa fa-caret-down" aria-hidden="true"></i></span></a>
                            <div class="sub-menu dropdown-menu">
                                <ul class="inner-menu">
                                    <li><a class="publisher-campaigns" href="publisher-campaigns.php"><span class="menu-icon"><i
                                                            class="fa fa-clone"></i></span> All Campaigns</a></li>
                                        <li><a class="publisher-campaigns" href="publisher-campaigns.php?list=Featured"><span class="menu-icon"><i
                                                            class="fa fa-clone"></i></span> Featured Campaigns</a></li>
                                        <li><a class="publisher-campaigns" href="publisher-campaigns.php?list=Top-Campaigns"><span
                                                            class="menu-icon"><i class="fa fa-clone"></i></span> Top Campaigns</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a class="dropdown-toggle"><span class="menu-icon"><i class="fa fa-bar-chart"></i></span> Reports
                                & Statistics <span class="caret"><i class="fa fa-caret-down"
                                                                   aria-hidden="true"></i></span></a>
                            <div class="sub-menu dropdown-menu">
                                <ul class="inner-menu">
                                    <li><a class="publisher-click-report" href="publisher-click-report.php?dt=CMonth"><span class="menu-icon"><i
                                                            class="fa fa-check"></i></span> Click Report</a></li>
                                        <li><a class="publisher-conversion-report" href="publisher-conversion-report.php?dt=CMonth"><span class="menu-icon"><i
                                                            class="fa fa-bar-chart"></i></span> Conversion Report</a></li>
                                        <li><a class="publisher-offer-report" href="publisher-offer-report.php?dt=CMonth"><span class="menu-icon"><i
                                                            class="fa fa-clone"></i></span> Offer Report</a></li>
                                </ul>
                            </div>
                       <li><a class="publisher-payment-centre" href="publisher-payment-centre.php"><span class="menu-icon"><i
                                                class="fa fa-dollar"></i></span> Payment Centre</a></li>
                    </ul>
                </div>
                <div class="left-menu right-menu">
                    <ul>
                       <li><a class="publisher-account-settings" href="publisher-account-settings.php"><span class="menu-icon"><i
                                                class="fa fa-cogs"></i></span> Account Settings</a></li>
                            <li><a class="logout" href="logout.php"><span class="menu-icon"><i class="fa fa-sign-in"></i></span> Logout</a>
                            </li>
                    </ul>
  <?php } } ?>

                </div>
            </div>
        </div>
    </div>

</section>
<script type="text/javascript">
    $(document).ready(function () {
		$(".open-menu").click(function(){
			$(".toggle-menu").toggle();
		});        
    });
</script>