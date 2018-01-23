<?php

require_once("dashboard-header.php");

$userId = $_GET['id'];

//Check if selected user exists
if (!userIdExists($userId)) {
    header("Location: admin_users.php");
    die();
}

$userdetails = fetchUserDetails(NULL, NULL, $userId); //Fetch user details

//Forms posted
if (!empty($_POST)) {
    //Delete selected account
    if (!empty($_POST['delete'])) {
        $deletions = $_POST['delete'];
        if ($deletion_count = deleteUsers($deletions)) {
            $successes[] = lang("ACCOUNT_DELETIONS_SUCCESSFUL", array($deletion_count));
        } else {
            $errors[] = lang("SQL_ERROR");
        }
    }

    //Activate account
    if (isset($_POST['activate']) && $_POST['activate'] == "activate") {
        if (setUserActive($userdetails['activation_token'])) {
            $successes[] = lang("ACCOUNT_MANUALLY_ACTIVATED", array($username));
        } else {
            $errors[] = lang("SQL_ERROR");
        }
    }

    $sql = '';

    if ($userdetails['id_code'] != $_POST['id_code']) {
        $id_code = filterInputs($_POST['id_code']);
        $sql .= " id_code = '$id_code' ";
    }

    //Update email
    if ($userdetails['email'] != $_POST['email']) {
        $email = trim($_POST["email"]);

        //Validate email
        if (!isValidEmail($email)) {
            $errors[] = lang("ACCOUNT_INVALID_EMAIL");
        } elseif (emailExists($email)) {
            $errors[] = lang("ACCOUNT_EMAIL_IN_USE", array($email));
        } else {
            $sql .= ($sql != '') ? ', ' : "";
            $sql .= " email = '$email' ";
        }
    }

    //Update title
    if ($userdetails['title'] != $_POST['title']) {
        $title = trim($_POST['title']);

        //Validate title
        if (minMaxRange(1, 50, $title)) {
            $errors[] = lang("ACCOUNT_TITLE_CHAR_LIMIT", array(1, 50));
        } else {
            $sql .= ($sql != '') ? ', ' : "";
            $sql .= " title = '$title' ";
        }
    }

    //Update ref id
    if ($userdetails['ref_id'] != $_POST['ref_id']) {
            $ref_id = (int)$_POST['ref_id'];
            $sql .= ($sql != '') ? ', ' : "";
            $sql .= " ref_id = '$ref_id' ";
    }
    //Update sub id
    if ($userdetails['sub_id'] != $_POST['sub_id']) {
        $sub_id = $_POST['sub_id'];
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " sub_id = '$sub_id' ";
    }
    //Update IP address
    if ($userdetails['ip_address'] != $_POST['ip_address']) {
        $ip_address = $_POST['ip_address'];
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " ip_address = '$ip_address' ";
    }
    //Updates user notes
    if ($userdetails['user_notes'] != $_POST['user_notes']) {
        $user_notes = filterInputs($_POST['user_notes']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " user_notes = '$user_notes' ";
    }
    if ($userdetails['name'] != $_POST['name']) {
        $name = trim($_POST['name']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " name = '$name' ";
    }
    if ($userdetails['address'] != $_POST['address']) {
        $address = trim($_POST['address']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " address = '$address' ";
    }
    if ($userdetails['postal_code'] != $_POST['postal_code']) {
        $postal_code = trim($_POST['postal_code']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " postal_code = '$address' ";
    }
    if ($userdetails['country'] != $_POST['country']) {
        $country = trim($_POST['country']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " country_id = '$country' ";
    }
    if ($userdetails['telephone'] != $_POST['telephone']) {
        $telephone = trim($_POST['telephone']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " telephone_number = '$telephone' ";
    }
    if ($userdetails['payment_method'] != $_POST['payment_method']) {
        $payment_method = trim($_POST['payment_method']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " payment_method = '$payment_method' ";
    }
    if ($userdetails['payment_details'] != $_POST['payment_details']) {
        $payment_details = trim($_POST['payment_details']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " payment_details = '$payment_details' ";
    }
    if ($userdetails['traffic_details'] != $_POST['traffic_details']) {
        $traffic_details = trim($_POST['traffic_details']);
        $sql .= ($sql != '') ? ', ' : "";
        $sql .= " traffic_details = '$traffic_details' ";
    }

    if ($sql != '' && count($errors) == 0) {
        $sql = "UPDATE uc_users SET $sql WHERE id ='$userId'";
        if (mysqli_query($mysqli, $sql)) {
            $successes[] = lang("DETAIL_UPDATED");
        } else {
            $errors[] = lang("SQL_ERROR");
        }
    }


    //Remove permission level
    if (!empty($_POST['removePermission'])) {
        $remove = $_POST['removePermission'];
        if ($deletion_count = removePermission($remove, $userId)) {
            $successes[] = lang("ACCOUNT_PERMISSION_REMOVED", array($deletion_count));
        } else {
            $errors[] = lang("SQL_ERROR");
        }
    }


    if (!empty($_POST['addPermission'])) {
        $add = $_POST['addPermission'];
        if ($addition_count = addPermission($add, $userId)) {
            $successes[] = lang("ACCOUNT_PERMISSION_ADDED", array($addition_count));
        } else {
            $errors[] = lang("SQL_ERROR");
        }
    }

    $userdetails = fetchUserDetails(NULL, NULL, $userId);


    if (count($errors) < 1) {
        $successes[] = lang("UPDATE_USER_SUCCESS");
    }
}


$userPermission = fetchUserPermissions($userId);
$permissionData = fetchAllPermissions();
$userdetails = fetchUserDetails(NULL, NULL, $userId);

$sql11 = "select * from uc_users where id='$userId'";
$result11 = mysqli_query($mysqli, $sql1);
$row = mysqli_fetch_assoc($result11);


?>

<section class="admin-report">
<div id='wrapper' class="wrapper">
<div id='content' class="content">
<div id='main' class="padding-5000 padding-0050 admin-content">
<div class="comm-float pt-20 pb-10">
                    <?php echo resultBlock($errors, $successes); ?>
                    <div class="comm-float nplm"><h3 class="main-heading">User Account Details</h3>

                        <form class="pt-10" name='adminUser' id="adminUser"
                              action='<?php $_SERVER['PHP_SELF'] ?>?id=<?php echo $userId ?>' method='post'>
                            <div class="col-sm-9 nplr">
                                <div id='regbox'>
                                    <div class="col-sm-12">
                                        <div class="col-sm-4 pb-15 npl">
                                            <span class="bold">ID:</span>
                                            <span><?php echo $userdetails['id'] ?></span>
                                        </div>
                                        <div class="col-sm-8 pb-15">
                                            <span class="bold">Username:</span>
                                            <span><?php echo $userdetails['user_name'] ?></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="col-sm-4 pb-15">
                                            <span class="bold">Active:</span>
                                            <?php
                                            //Display activation link, if account inactive
                                            if ($userdetails['active'] == '1') {
                                                echo "Yes";
                                            } else {
                                                ?>
                                                No
                                                <p>
                                                    <label>Activate:</label>
                                                    <input type='checkbox' name='activate' id='activate'
                                                           value='activate'>
                                                </p>

                                                <?php
                                            }

                                            ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <span class="bold">Sign Up:</span>
                                            <span><?php echo date("j M, Y", $userdetails['sign_up_stamp']) ?></span>
                                        </div>
                                        <div class="col-sm-4">
                                            <span class="bold">Last Sign In:</span>
                                            <span><?php
                                                if ($userdetails['last_sign_in_stamp'] == '0') {
                                                    echo "Never";
                                                } else {
                                                    echo date("j M, Y", $userdetails['last_sign_in_stamp']);
                                                }
                                                ?></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="col-sm-4 npl">
                                            <label>Name:</label>
                                            <input type='text' name='name' value='<?php echo $userdetails['name'] ?>'/>
                                        </div>
                                        <div class="col-sm-8">
                                            <label>Address:</label>
                                            <input type='text' name='address'
                                                   value='<?php echo $userdetails['address'] ?>'/>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="col-sm-4">
                                            <label>Postal Code:</label>
                                            <input type='text' name='postal_code'
                                                   value='<?php echo $userdetails['postal_code'] ?>'/>
                                        </div>
                                        <div class="col-sm-4 npl">
                                            <label>Country: </label>
                                            <select name="country" id="country">
                                                <option value="">Pick country</option>
                                                <?php
                                                $query2 = "SELECT * FROM uc_countries";
                                                $result2 = mysqli_query($mysqli, $query2);
                                                ?>
                                                <?php while ($row2 = mysqli_fetch_assoc($result2)) { ?>

                                                    <option <?php if ($row2['id'] == $userdetails['country']) {
                                                        echo 'selected';
                                                    } ?> value="<?php echo $row2['id']; ?>"><?php echo $row2['country_name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Telephone Number:</label>
                                            <input class="form-control" type='text' name='telephone'
                                                   value='<?php echo $userdetails['telephone_number']; ?>'
                                                   id="telephone_number">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="col-sm-4 npl">
                                            <label>Email:</label>
                                            <input type='text' name='email'
                                                   value='<?php echo $userdetails['email'] ?>'/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Title:</label>
                                            <input type='text' name='title'
                                                   value='<?php echo $userdetails['title'] ?>'/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Referrer ID:</label>
                                            <input type='text' name='ref_id'
                                                   value='<?php echo $userdetails['ref_id'] ?>'/>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="col-sm-6">
                                            <label>Sub ID:</label>
                                            <input type='text' name='sub_id'
                                                   value='<?php echo $userdetails['sub_id'] ?>'/>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>IP Address:</label>
                                            <input type='text' name='ip_address'
                                                   value='<?php echo $userdetails['ip_address'] ?>'/>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="col-sm-6 npl">
                                            <label>Payment Method: </label>
                                            <select name="payment_method" id="payment_method">
                                                <option value="Paypal" <?php if ('Paypal' == $userdetails['payment_method']) {
                                                    echo 'selected';
                                                } ?>>Paypal
                                                </option>
                                                <option value="Moneybookers" <?php if ('Moneybookers' == $userdetails['payment_method']) {
                                                    echo 'selected';
                                                } ?>>Moneybookers
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 nplr">
                                            <label>Payment Details:</label>
                                            <input class="form-control" type='text' name='payment_details'
                                                   value='<?php echo $userdetails['payment_details']; ?>'
                                                   id="payment_details">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 nplr">
                                        <label>Traffic Details:</label>
                                        <textarea name="traffic_details" cols="4"
                                                  id="traffic_details"><?php echo $userdetails['traffic_details']; ?></textarea>
                                    </div>
                                    <div class="col-sm-12 npl">
                                        <label>User Notes:</label>
                                        <textarea class="user-notes" rows="5" name='user_notes' type='text' size="40"
                                                  maxlength="1000"><?php echo $userdetails['user_notes']; ?></textarea>
                                    </div>
                                    <div class="col-sm-12 npl delete-input">
                                        <span>Delete User?</span>
                                        <input type='checkbox' name='delete[<?php echo $userdetails['id'] ?>]'
                                               id='delete[<?php echo $userdetails['id'] ?>]'
                                               value='<?php echo $userdetails['id'] ?>'>
                                    </div>
                                    <div class="col-sm-12 npl mt-20">
                                        <input type='submit' value='Update' class='submit'/>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-3">
                                <h5 class="bold main-heading">Permission Membership</h5>
                                <div id='regbox'>
                                    <p><label>Remove Permission:</label>
                                        <?php
                                        //List of permission levels user is apart of
                                        foreach ($permissionData as $v1) {
                                            if (isset($userPermission[$v1['id']])) {
                                                echo "<br><input type='checkbox' name='removePermission[" . $v1['id'] . "]' id='removePermission[" . $v1['id'] . "]' value='" . $v1['id'] . "'> " . $v1['name'];
                                            }
                                        }

                                        //List of permission levels user is not apart of
                                        echo "</p><p><label>Add Permission:</label>";
                                        foreach ($permissionData as $v1) {
                                            if (!isset($userPermission[$v1['id']])) {
                                                echo "<br><input type='checkbox' name='addPermission[" . $v1['id'] . "]' id='addPermission[" . $v1['id'] . "]' value='" . $v1['id'] . "'> " . $v1['name'];
                                            }
                                        }
                                        ?>

                                </div>
                            </div>

</section>

<?php require_once("dashboard-footer.php"); ?>

<script type="text/javascript">
    (function ($) {
        var rules = {
            name: {
                notEmpty: {
                    message: 'The name field is Required'
                }
            },
            address: {
                notEmpty: {
                    message: 'The address field is Required'
                }
            },
            postal_code: {
                notEmpty: {
                    message: 'The address field is Required'
                }
            },
            country: {
                notEmpty: {
                    message: 'Please select country.'
                }
            },
            telephone: {
                notEmpty: {
                    message: 'The telephone number field is Required'
                }
            },
            email: {
                notEmpty: {
                    message: 'The email field is Required'
                },
                email: {
                    message: 'The email is invalid!'
                }
            },
            title: {
                notEmpty: {
                    message: 'The title field is required'
                }
            },
            ref_id: {
                notEmpty: {
                    message: 'The referrer id field is required'
                }
            },
            sub_id: {
                notEmpty: {
                    message: 'The sub id field is required'
                }
            },
            ip_address: {
                notEmpty: {
                    message: 'The ip address field is required'
                }
            },
            payment_method: {
                notEmpty: {
                    message: 'The payment method field is Required'
                }
            },
            payment_details: {
                notEmpty: {
                    message: 'The payment detail field is Required'
                },
                email: {
                    message: 'The payment detail email is required'
                }
            },
            traffic_details: {
                notEmpty: {
                    message: 'The traffic detail field is Required'
                }
            }
        };
        smValidator('adminUser', rules, 1);
    })(jQuery)
</script>