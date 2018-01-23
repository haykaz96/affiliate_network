<?php

//Functions that do not interact with DB
//------------------------------------------------------------------------------

//Retrieve a list of all .php files in models/languages
function getLanguageFiles()
{
    $directory = "models/languages/";
    $languages = glob($directory . "*.php");
    //print each file name
    return $languages;
}

//Retrieve a list of all .css files in models/site-templates
function getTemplateFiles()
{
    $directory = "models/site-templates/";
    $languages = glob($directory . "*.css");
    //print each file name
    return $languages;
}

//Retrieve a list of all .php files in root files folder
function getPageFiles()
{
    $directory = "";
    $pages = glob($directory . "*.php");
    //print each file name
    foreach ($pages as $page) {
        $row[$page] = $page;
    }
    return $row;
}

//Destroys a session as part of logout
function destroySession($name)
{
    if (isset($_SESSION[$name])) {
        $_SESSION[$name] = NULL;
        unset($_SESSION[$name]);
    }
}

//Generate a unique code
function getUniqueCode($length = "")
{
    $code = md5(uniqid(rand(), true));
    if ($length != "") return substr($code, 0, $length);
    else return $code;
}

//Generate an activation key
function generateActivationToken($gen = null)
{
    do {
        $gen = md5(uniqid(mt_rand(), false));
    } while (validateActivationToken($gen));
    return $gen;
}

//@ Thanks to - http://phpsec.org
function generateHash($plainText, $salt = null)
{
    if ($salt === null) {
        $salt = substr(md5(uniqid(rand(), true)), 0, 25);
    } else {
        $salt = substr($salt, 0, 25);
    }

    return $salt . sha1($salt . $plainText);
}

//Checks if an email is valid
function isValidEmail($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

//Inputs language strings from selected language.
function lang($key, $markers = NULL)
{
    global $lang;
    if ($markers == NULL) {
        $str = $lang[$key];
    } else {
        //Replace any dyamic markers
        $str = $lang[$key];
        $iteration = 1;
        foreach ($markers as $marker) {
            $str = str_replace("%m" . $iteration . "%", $marker, $str);
            $iteration++;
        }
    }
    //Ensure we have something to return
    if ($str == "") {
        return ("No language key found");
    } else {
        return $str;
    }
}

//Checks if a string is within a min and max length
function minMaxRange($min, $max, $what)
{
    if (strlen(trim($what)) < $min)
        return true;
    else if (strlen(trim($what)) > $max)
        return true;
    else
        return false;
}

//Replaces hooks with specified text
function replaceDefaultHook($str)
{
    global $default_hooks, $default_replace;
    return (str_replace($default_hooks, $default_replace, $str));
}

//Displays error and success messages
function resultBlock($errors, $successes)
{
    //Error block
    if (count($errors) > 0) {
        echo '<div class="success-message error-message">
		<p><span><i class="fa fa-exclamation-circle" aria-hidden="true"></i></span>';
        foreach ($errors as $error) {
            echo $error . '</br>';
        }
        echo "</p>";
        echo "</div>";
    }
    //Success block
    if (count($successes) > 0) {
        echo '<div class="success-message"><p><span><i class="fa fa-check-circle"></i></span>';
        foreach ($successes as $success) {
            echo $success . '</br>';
        }
        echo "</p>";
        echo "</div>";
    }
}

//Completely sanitizes text
function sanitize($str)
{
    return strtolower(strip_tags(trim(($str))));
}

//Functions that interact mainly with .users table
//------------------------------------------------------------------------------

//Delete a defined array of users
function deleteUsers($users)
{
    global $mysqli, $db_table_prefix;
    $i = 0;

    foreach ($users as $id) {
        $id = addslashes($id);
        $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "users 
		WHERE id = '$id'");

        $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "user_permission_matches 
		WHERE user_id = '$id'");

        $i++;
    }

    return $i;
}

//Check if a display name exists in the DB
function displayNameExists($displayname)
{
    global $mysqli, $db_table_prefix;
    $displayname = addslashes($displayname);
    $query = mysqli_query($mysqli, "SELECT active
		FROM " . $db_table_prefix . "users
		WHERE
		display_name = '$displayname'
		LIMIT 1");
    $num_returns = mysqli_num_rows($query);

    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Check if an email exists in the DB
function emailExists($email)
{
    global $mysqli, $db_table_prefix;
    $email = addslashes($email);
    $query = mysqli_query($mysqli, "SELECT active
		FROM " . $db_table_prefix . "users
		WHERE
		email = '$email'
		LIMIT 1");

    $num_returns = mysqli_num_rows($query);
    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Check if a user name and email belong to the same user
function emailUsernameLinked($email, $username)
{
    $username = addslashes($username);
    $email = addslashes($email);
    global $mysqli, $db_table_prefix;
    $query = mysqli_query($mysqli, "SELECT active
		FROM " . $db_table_prefix . "users
		WHERE user_name = '$username'
		AND
		email = '$email'
		LIMIT 1");
    $num_returns = mysqli_num_rows($num_returns);
    if ($query->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}


//Retrieve information for all users
function fetchAllUsers()
{
    global $mysqli, $db_table_prefix;
    $query = mysqli_query($mysqli, "SELECT 
		id,
		id_code,
		user_name,
		password,
		email,
		name,
		address,
		postal_code,
		country_id,
		telephone_number,
		payment_method,
		payment_details,
		traffic_details,
		title,
		activation_token,
		last_activation_request,
		lost_password_request,
		active,
		sign_up_stamp,
		last_sign_in_stamp,	
		ref_id,
		sub_id,
		ip_address,
		user_notes
		FROM " . $db_table_prefix . "users");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row = array('id' => $temp_row['id'], 'id_code' => $temp_row['id_code'], 'user_name' => $temp_row['user_name'], 'password' => $temp_row['password'], 'email' => $temp_row['email'], 'name' => $temp_row['name'], 'address' => $temp_row['address'], 'postal_code' => $temp_row['postal_code'], 'country' => $temp_row['country'], 'telephone_number' => $temp_row['telephone_number'], 'payment_method' => $temp_row['payment_method'], 'payment_details' => $temp_row['payment_details'], 'traffic_details' => $temp_row['traffic_details'], 'title' => $temp_row['title'], 'activation_token' => $temp_row['activation_token'], 'last_activation_request' => $temp_row['last_activation_request'], 'lost_password_request' => $temp_row['lost_password_request'], 'active' => $temp_row['active'], 'sign_up_stamp' => $temp_row['sign_up_stamp'], 'last_sign_in_stamp' => $temp_row['last_sign_in_stamp'], 'ref_id' => $temp_row['ref_id'], 'sub_id' => $temp_row['sub_id'], 'ip_address' => $temp_row['ip_address'], 'user_notes' => $temp_row['user_notes']);
    }

    return ($row);
}

//Retrieve complete user information by username, token or ID
function fetchUserDetails($username = NULL, $token = NULL, $id = NULL)
{
    if ($username != NULL) {
        $column = "user_name";
        $data = addslashes($username);
    } elseif ($token != NULL) {
        $column = "activation_token";
        $data = addslashes($token);
    } elseif ($id != NULL) {
        $column = "id";
        $data = addslashes($id);
    }
    global $mysqli, $db_table_prefix;
    $query = mysqli_query($mysqli, "SELECT 
		id,
		id_code,
		user_name,
		password,
		email,
		name,
		address,
		postal_code,
		country_id,
		telephone_number,
		payment_method,
		payment_details,
		traffic_details,
		title,
		activation_token,
		last_activation_request,
		lost_password_request,
		active,
		sign_up_stamp,
		last_sign_in_stamp,	
		ref_id,
		sub_id,
		ip_address,
		user_notes
		FROM " . $db_table_prefix . "users
		WHERE
		$column = '$data'
		LIMIT 1");
    $query = mysqli_query($mysqli, "SELECT * FROM " . $db_table_prefix . "users WHERE $column = '$data' LIMIT 1");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row = array('id' => $temp_row['id'], 'id_code' => $temp_row['id_code'], 'user_name' => $temp_row['user_name'], 'password' => $temp_row['password'], 'email' => $temp_row['email'], 'name' => $temp_row['name'], 'address' => $temp_row['address'], 'postal_code' => $temp_row['postal_code'], 'country' => $temp_row['country_id'], 'telephone_number' => $temp_row['telephone_number'], 'payment_method' => $temp_row['payment_method'], 'payment_details' => $temp_row['payment_details'], 'traffic_details' => $temp_row['traffic_details'], 'title' => $temp_row['title'], 'activation_token' => $temp_row['activation_token'], 'last_activation_request' => $temp_row['last_activation_request'], 'lost_password_request' => $temp_row['lost_password_request'], 'active' => $temp_row['active'], 'sign_up_stamp' => $temp_row['sign_up_stamp'], 'last_sign_in_stamp' => $temp_row['last_sign_in_stamp'], 'ref_id' => $temp_row['ref_id'], 'sub_id' => $temp_row['sub_id'], 'ip_address' => $temp_row['ip_address'], 'user_notes' => $temp_row['user_notes']);
    }

    return ($row);
}

//Toggle if lost password request flag on or off
function flagLostPasswordRequest($username, $value)
{
    global $mysqli, $db_table_prefix;
    $username = addslashes($username);
    $value = addslashes($value);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET lost_password_request = '$value'
		WHERE
		user_name = '$username'
		LIMIT 1");


    return $query;
}

//Check if a user is logged in
function isUserLoggedIn()
{
    global $loggedInUser, $mysqli, $db_table_prefix;
    $num_returns = 0;
    if (isset($loggedInUser)) {
        $query = mysqli_query($mysqli, "SELECT 
		id,
		password
		FROM " . $db_table_prefix . "users
		WHERE
		id = " . $loggedInUser->user_id . "
		AND 
		password = '" . $loggedInUser->hash_pw . "'
		AND
		active = 1
		LIMIT 1");
        $num_returns = mysqli_num_rows($query);
    }


    if ($loggedInUser == NULL) {
        return false;
    } else {
        if ($num_returns > 0) {
            return true;
        } else {
            destroySession("membershipScriptUser");
            return false;
        }
    }
}

//Change a user from inactive to active
function setUserActive($token)
{
    global $mysqli, $db_table_prefix;
    $token = addslashes($token);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET active = 1
		WHERE
		activation_token = '$token'
		LIMIT 1");

    return $query;
}

//Change a user's display name
function updateDisplayName($id, $display)
{
    global $mysqli, $db_table_prefix;
    $display = addslashes($display);
    $id = addslashes($id);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET display_name = '$display'
		WHERE
		id = '$id'
		LIMIT 1");

    return $query;
}

//Update a user's email
function updateEmail($id, $email)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $email = addslashes($email);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		email = '$email'
		WHERE
		id = '$id'");

    return $query;
}

//Update a user's Account detail
function updateAccountDetails($id, $name, $address, $postal_code, $country, $telephone_number, $payment_method, $payment_details, $traffic_details)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $name = addslashes($name);
    $address = addslashes($address);
    $postal_code = addslashes($postal_code);
    $country = addslashes($country);
    $telephone_number = addslashes($telephone_number);
    $payment_method = addslashes($payment_method);
    $payment_details = addslashes($payment_details);
    $traffic_details = addslashes($traffic_details);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		name = '$name',
		address = '$address',
		postal_code = '$postal_code',
		country_id = '$country',
		telephone_number='$telephone_number',
		payment_method = '$payment_method',
		payment_details='$payment_details',
		traffic_details='$traffic_details'
		WHERE
		id = '$id'");

    return $query;
}

//Input new activation token, and update the time of the most recent activation request
function updateLastActivationRequest($new_activation_token, $username, $email)
{
    global $mysqli, $db_table_prefix;
    $new_activation_token = addslashes($new_activation_token);
    $username = addslashes($username);
    $email = addslashes($email);

    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET activation_token = '$new_activation_token',
		last_activation_request = '" . time() . "'
		WHERE email = '$email'
		AND
		user_name = '$username'");

    return $query;
}

//Generate a random password, and new token
function updatePasswordFromToken($pass, $token)
{
    global $mysqli, $db_table_prefix;

    $new_activation_token = generateActivationToken();
    $token = addslashes($token);
    $pass = addslashes($pass);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET password = '$pass',
		activation_token = '$new_activation_token'
		WHERE
		activation_token = '$token'");

    return $query;
}

//Update a user's title
function updateTitle($id, $title)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $title = addslashes($title);

    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		title = '$title'
		WHERE
		id = '$id'");

    return $query;
}

/*update reference id*/
function updateRefId($id, $ref_id)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $ref_id = addslashes($ref_id);

    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		ref_id = '$ref_id'
		WHERE
		id = '$id'");

    return $query;
}

function updateSubId($id, $sub_id)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $sub_id = addslashes($sub_id);

    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		sub_id = '$sub_id'
		WHERE
		id = '$id'");

    return $query;
}

// update id_code for user  by jassi
function updateIdCode($id, $id_code)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $id_code = addslashes($id_code);

    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		id_code = '$id_code'
		WHERE
		id = '$id'");

    return $query;
}

// update user_notes for user by jassi
function updateusernotes($id, $user_notes)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $user_notes = addslashes($user_notes);

    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		user_notes = '$user_notes'
		WHERE
		id = '$id'");

    return $query;
}

function updateIpAdress($id, $ip_address)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $ip_address = addslashes($ip_address);

    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "users
		SET 
		ip_address = '$ip_address'
		WHERE
		id = '$id'");

    return $query;
}

//Check if a user ID exists in the DB
function userIdExists($id)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $query = mysqli_query($mysqli, "SELECT active
		FROM " . $db_table_prefix . "users
		WHERE
		id = '$id'
		LIMIT 1");

    $num_returns = mysqli_num_rows($query);
    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Checks if a username exists in the DB
function usernameExists($username)
{
    global $mysqli, $db_table_prefix;
    $username = addslashes($username);
    $query = mysqli_query($mysqli, "SELECT active
		FROM " . $db_table_prefix . "users
		WHERE
		user_name = '$username'
		LIMIT 1");
    $num_returns = mysqli_num_rows($query);

    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Check if activation token exists in DB
function validateActivationToken($token, $lostpass = NULL)
{
    global $mysqli, $db_table_prefix;
    $token = addslashes($token);

    if ($lostpass == NULL) {
        $query = "SELECT active
			FROM " . $db_table_prefix . "users
			WHERE active = 0
			AND
			activation_token = '$token'
			LIMIT 1";

    } else {
        $query = "SELECT active
			FROM " . $db_table_prefix . "users
			WHERE active = 1
			AND
			activation_token = '$token'
			AND
			lost_password_request = 1 
			LIMIT 1";

    }
    $run_query = mysqli_query($mysqli, $query);

    $num_returns = mysqli_num_rows($run_query);

    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Functions that interact mainly with .permissions table
//------------------------------------------------------------------------------

//Create a permission level in DB
function createPermission($permission)
{
    global $mysqli, $db_table_prefix;
    $permission = addslashes($permission);
    $query = mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "permissions (
		name
		)
		VALUES (
		'$permission'
		)");

    return $query;
}

//Delete a permission level from the DB
function deletePermission($permission)
{
    global $mysqli, $db_table_prefix, $errors;
    $i = 0;

    foreach ($permission as $id) {
        $id = addslashes($id);
        if ($id == 1) {
            $errors[] = lang("CANNOT_DELETE_NEWUSERS");
        } elseif ($id == 2) {
            $errors[] = lang("CANNOT_DELETE_ADMIN");
        } else {
            $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "user_permission_matches 
		WHERE permission_id = '$id'");

            $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "permissions 
		WHERE id = '$id'");

            $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "permission_page_matches 
		WHERE permission_id = '$id'");


            $i++;
        }
    }

    return $i;
}

//Retrieve information for all permission levels
function fetchAllPermissions()
{
    global $mysqli, $db_table_prefix;
    $query = mysqli_query($mysqli, "SELECT 
		id,
		name
		FROM " . $db_table_prefix . "permissions");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row[] = array('id' => $temp_row['id'], 'name' => $temp_row['name']);
    }

    return ($row);
}

//Retrieve information for a single permission level
function fetchPermissionDetails($id)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $query = mysqli_query($mysqli, "SELECT 
		id,
		name
		FROM " . $db_table_prefix . "permissions
		WHERE
		id = '$id'
		LIMIT 1");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row = array('id' => $temp_row['id'], 'name' => $temp_row['name']);
    }

    return ($row);
}

//Check if a permission level ID exists in the DB
function permissionIdExists($id)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $query = mysqli_query($mysqli, "SELECT id
		FROM " . $db_table_prefix . "permissions
		WHERE
		id = '$id'
		LIMIT 1");
    $num_returns = mysqli_num_rows($query);

    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Check if a permission level name exists in the DB
function permissionNameExists($permission)
{
    global $mysqli, $db_table_prefix;
    $permission = addslashes($permission);
    $query = mysqli_query($mysqli, "SELECT id
		FROM " . $db_table_prefix . "permissions
		WHERE
		name = '$permission'
		LIMIT 1");
    $num_returns = mysqli_num_rows($query);

    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Change a permission level's name
function updatePermissionName($id, $name)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $name = addslashes($name);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "permissions
		SET name = '$name'
		WHERE
		id = '$id'
		LIMIT 1");

    return $query;
}

//Functions that interact mainly with .user_permission_matches table
//------------------------------------------------------------------------------

//Match permission level(s) with user(s)
function addPermission($permission, $user)
{
    global $mysqli, $db_table_prefix;
    $i = 0;

    if (is_array($permission)) {
        foreach ($permission as $id) {
            $id = addslashes($id);
            $query = mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "user_permission_matches (
		permission_id,
		user_id
		)
		VALUES (
		'$id',
		'$user'
		)");

            $i++;
        }
    } elseif (is_array($user)) {
        foreach ($user as $id) {
            $id = addslashes($id);
            $permission = addslashes($permission);
            $query = mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "user_permission_matches (
		permission_id,
		user_id
		)
		VALUES (
		'$permission',
		'$id'
		)");

            $i++;
        }
    } else {
        $user = addslashes($user);
        $permission = addslashes($permission);
        $query = mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "user_permission_matches (
		permission_id,
		user_id
		)
		VALUES (
		'$permission',
		'$user'
		)");

        $i++;
    }

    return $i;
}

//Retrieve information for all user/permission level matches
function fetchAllMatches()
{
    global $mysqli, $db_table_prefix;
    $query = mysqli_query($mysqli, "SELECT 
		id,
		user_id,
		permission_id
		FROM " . $db_table_prefix . "user_permission_matches");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row[] = array('id' => $temp_row['id'], 'user_id' => $temp_row['user_id'], 'permission_id' => $temp_row['permission_id']);
    }

    return ($row);
}

//Retrieve list of permission levels a user has
function fetchUserPermissions($user_id)
{
    global $mysqli, $db_table_prefix;
    $user_id = addslashes($user_id);
    $query = mysqli_query($mysqli, "SELECT
		id,
		permission_id
		FROM " . $db_table_prefix . "user_permission_matches
		WHERE user_id = '$user_id'");

    While ($temp_row = mysqli_fetch_assoc($query)) {
        $row[$temp_row['permission_id']] = array('id' => $temp_row['id'], 'permission_id' => $temp_row['permission_id']);
    }

    if (isset($row)) {
        return ($row);
    }
}

//Retrieve list of users who have a permission level
function fetchPermissionUsers($permission_id)
{
    global $mysqli, $db_table_prefix;
    $permission_id = addslashes($permission_id);
    $query = mysqli_query($mysqli, "SELECT id, user_id
		FROM " . $db_table_prefix . "user_permission_matches
		WHERE permission_id = '$permission_id'");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row[$temp_row['user_id']] = array('id' => $temp_row['id'], 'user_id' => $temp_row['user_id']);
    }

    if (isset($row)) {
        return ($row);
    }
}

//Unmatch permission level(s) from user(s)
function removePermission($permission, $user)
{
    global $mysqli, $db_table_prefix;
    $i = 0;


    if (is_array($permission)) {
        foreach ($permission as $id) {
            $id = addslashes($id);
            $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "user_permission_matches 
		WHERE permission_id = '$id'
		AND user_id ='$user'");
            $i++;
        }
    } elseif (is_array($user)) {
        foreach ($user as $id) {
            $id = addslashes($id);
            $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "user_permission_matches 
		WHERE permission_id = '$permission'
		AND user_id ='$id'");
            $i++;
        }
    } else {
        $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "user_permission_matches 
		WHERE permission_id = '$permission'
		AND user_id ='$user'");
        $i++;
    }

    return $i;
}

//Functions that interact mainly with .configuration table
//------------------------------------------------------------------------------

//Update configuration table
function updateConfig($id, $value)
{
    global $mysqli, $db_table_prefix;

    foreach ($id as $cfg) {

        $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "configuration
		SET 
		value = '" . $value[$cfg] . "'
		WHERE
		id = '$cfg'");


    }

}

//Functions that interact mainly with .pages table
//------------------------------------------------------------------------------

//Add a page to the DB
function createPages($pages)
{
    global $mysqli, $db_table_prefix;

    foreach ($pages as $page) {
        $page = addslashes($page);
        $query = mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "pages (
		page
		)
		VALUES (
		'$page'
		)");

    }

}

//Delete a page from the DB
function deletePages($pages)
{
    global $mysqli, $db_table_prefix;

    foreach ($pages as $id) {
        $id = addslashes($id);
        $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "pages 
		WHERE id = '$id'");

        $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "permission_page_matches 
		WHERE page_id = '$id'");

    }

}

//Fetch information on all pages
function fetchAllPages()
{
    global $mysqli, $db_table_prefix;
    $query = mysqli_query($mysqli, "SELECT 
		id,
		page,
		private
		FROM " . $db_table_prefix . "pages");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row[$temp_row['page']] = array('id' => $temp_row['id'], 'page' => $temp_row['page'], 'private' => $temp_row['private']);
    }

    if (isset($row)) {
        return ($row);
    }
}

//Fetch information for a specific page
function fetchPageDetails($id)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $query = mysqli_query($mysqli, "SELECT 
		id,
		page,
		private
		FROM " . $db_table_prefix . "pages
		WHERE
		id = '$id'
		LIMIT 1");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row = array('id' => $temp_row['id'], 'page' => $temp_row['page'], 'private' => $temp_row['private']);
    }

    return ($row);
}

//Check if a page ID exists
function pageIdExists($id)
{
    global $mysqli, $db_table_prefix;
    $id = addslashes($id);
    $query = mysqli_query($mysqli, "SELECT private
		FROM " . $db_table_prefix . "pages
		WHERE
		id = '$id'
		LIMIT 1");
    $num_returns = mysqli_num_rows($query);

    if ($num_returns > 0) {
        return true;
    } else {
        return false;
    }
}

//Toggle private/public setting of a page
function updatePrivate($id, $private)
{
    global $mysqli, $db_table_prefix;
    $private = addslashes($private);
    $id = addslashes($id);
    $query = mysqli_query($mysqli, "UPDATE " . $db_table_prefix . "pages
		SET 
		private = '$private'
		WHERE
		id = '$id'");

    return $query;
}

//Functions that interact mainly with .permission_page_matches table
//------------------------------------------------------------------------------

//Match permission level(s) with page(s)
function addPage($page, $permission)
{
    global $mysqli, $db_table_prefix;
    $i = 0;

    if (is_array($permission)) {
        foreach ($permission as $id) {
            $query - mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "permission_page_matches (
		permission_id,
		page_id
		)
		VALUES (
		'$id',
		'$page'
		)");

            $i++;
        }
    } elseif (is_array($page)) {
        foreach ($page as $id) {
            $query - mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "permission_page_matches (
		permission_id,
		page_id
		)
		VALUES (
		'$permission',
		'$id'
		)");

            $i++;
        }
    } else {
        $query - mysqli_query($mysqli, "INSERT INTO " . $db_table_prefix . "permission_page_matches (
		permission_id,
		page_id
		)
		VALUES (
		'$permission',
		'$page'
		)");

        $i++;
    }

    return $i;
}

//Retrieve list of permission levels that can access a page
function fetchPagePermissions($page_id)
{
    global $mysqli, $db_table_prefix;
    $page_id = addslashes($page_id);
    $query = mysqli_query($mysqli, "SELECT
		id,
		permission_id
		FROM " . $db_table_prefix . "permission_page_matches
		WHERE page_id = '$page_id'");

    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row[$temp_row['permission_id']] = array('id' => $temp_row['id'], 'permission_id' => $temp_row['permission_id']);
    }

    if (isset($row)) {
        return ($row);
    }
}

//Retrieve list of pages that a permission level can access
function fetchPermissionPages($permission_id)
{
    global $mysqli, $db_table_prefix;
    $permission_id = addslashes($permission_id);
    $query = mysqli_query($mysqli, "SELECT
		id,
		page_id
		FROM " . $db_table_prefix . "permission_page_matches
		WHERE permission_id = '$permission_id'");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $row[$temp_row['page_id']] = array('id' => $temp_row['id'], 'permission_id' => $temp_row['page_id']);
    }

    if (isset($row)) {
        return ($row);
    }
}

//Unmatched permission and page
function removePage($page, $permission)
{
    global $mysqli, $db_table_prefix;
    $i = 0;

    if (is_array($page)) {
        foreach ($page as $id) {
            $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "permission_page_matches 
		WHERE page_id = '$id'
		AND permission_id ='$permission'");
            $i++;
        }
    } elseif (is_array($permission)) {
        foreach ($permission as $id) {
            $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "permission_page_matches 
		WHERE page_id = '$page'
		AND permission_id ='$id'");
            $i++;
        }
    } else {
        $query = mysqli_query($mysqli, "DELETE FROM " . $db_table_prefix . "permission_page_matches 
		WHERE page_id = '$permission'
		AND permission_id ='$user'");
        $i++;
    }

    return $i;
}

//Check if a user has access to a page
function securePage($uri)
{

    //Separate document name from uri
    $tokens = explode('/', $uri);
    $page = $tokens[sizeof($tokens) - 1];
    global $mysqli, $db_table_prefix, $loggedInUser;
    //retrieve page details
    $query = mysqli_query($mysqli, "SELECT 
		id,
		page,
		private
		FROM " . $db_table_prefix . "pages
		WHERE
		page = '$page'
		LIMIT 1");
    while ($temp_row = mysqli_fetch_assoc($query)) {
        $pageDetails = array('id' => $temp_row['id'], 'page' => $temp_row['page'], 'private' => $temp_row['private']);
    }

    //If page does not exist in DB, allow access
    if (empty($pageDetails)) {
        return true;
    } //If page is public, allow access
    elseif ($pageDetails['private'] == 0) {
        return true;
    } //If user is not logged in, deny access
    elseif (!isUserLoggedIn()) {
        header("Location: login.php");
        return false;
    } else {

        //Retrieve list of permission levels with access to page

        $query = mysqli_query($mysqli, "SELECT
			permission_id
			FROM " . $db_table_prefix . "permission_page_matches
			WHERE page_id = " . $pageDetails['id']);
        while ($temp_row = mysqli_fetch_assoc($query)) {
            $pagePermissions[] = $temp_row['permission_id'];
        }

        //Check if user's permission levels allow access to page
        if ($loggedInUser->checkPermission($pagePermissions)) {
            return true;
        } //Grant access if master user
        elseif ($loggedInUser->user_id == $master_account) {
            return true;
        } else {
            header("Location: account.php");
            return false;
        }
    }
}


//ALL COMMON FUNCTIONS//

/*Filter inputs */
function filterInputs($str)
{
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    return addslashes(trim($str));

}

/*Custom Query*/
function customQuery($query)
{
    global $mysqli, $db_table_prefix;
    $exe_query = mysqli_query($mysqli, $query);
    return true;
}

/*Common function to delete record from table using single value*/
function commonDelete($table, $column, $value)
{
    global $mysqli, $db_table_prefix;
    $query = "delete from " . $table . " where " . $column . "='" . $value . "'";
    $exe_query = mysqli_query($mysqli, $query);
    return true;
}

/*Common function for updating record from table using single value*/
function commonUpdate($table, $setColumn, $setValue, $whereColumn, $whereValue)
{
    global $mysqli, $db_table_prefix;
    $query = "update " . $table . " set " . $setColumn . "='$setValue' where " . $whereColumn . "='" . $whereValue . "'";
    $exe_query = mysqli_query($mysqli, $query);
    return true;
}

//Pagination script
function paginationShow($total_pages, $targetpage, $limit)
{
    //$total_pages = $total_pages[num];
    /* Setup vars for query. */
    //$targetpage = "filename.php"; 	//your file name  (the name of this file)
    if (isset($_GET['page'])) {
        $page = $_GET['page'];    //how many items to show per page
    } else {
        $page = 0;
    }
    $adjacents = 1;
    if ($page != 0)
        $start = ($page - 1) * $limit;            //first item to display on this page
    else
        $start = 0;                                //if no page var is given, set start to 0


    /* Setup page vars for display. */
    if ($page == 0) $page = 1;                    //if no page var is given, default to 1.
    $prev = $page - 1;                            //previous page is page - 1
    $next = $page + 1;                            //next page is page + 1
    $lastpage = ceil($total_pages / $limit);        //lastpage is = total pages / items per page, rounded up.
    $lpm1 = $lastpage - 1;                        //last page minus 1

    /*
		Now we apply our rules and draw the pagination object.
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
    //echo $lastpage;
    $pagination = "";
    if ($lastpage > 1) {    //pagination
        $pagination .= "<div class=\"pagin\">";
        //previous button
        if ($page > 1)
            $pagination .= "<a href=\"" . $targetpage . "page=$prev\"> &laquo;</a>";
        else
            $pagination .= "<a class=\"disabled\"> &laquo;</a>";

        //pages
        if ($lastpage <= 3 + ($adjacents * 2))    //not enough pages to bother breaking it up
        {
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)//active
                    $pagination .= "<a class=\"current\">$counter</a>";
                else
                    $pagination .= "<a href=\"" . $targetpage . "page=$counter\">$counter</a>";
            }
        } elseif ($lastpage > 3 + ($adjacents * 2))    //enough pages to hide some
        {
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 3 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination .= "<a class=\"current\">$counter</a>";//active
                    else
                        $pagination .= "<a href=\"" . $targetpage . "page=$counter\">$counter</a>";
                }
                $pagination .= "...";
                $pagination .= "<a href=\"" . $targetpage . "page=$lpm1\">$lpm1</a>";
                $pagination .= "<a href=\"" . $targetpage . "page=$lastpage\">$lastpage</a>";
            } //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 3) > $page && $page > ($adjacents * 2)) {
                $pagination .= "<a href=\"" . $targetpage . "page=1\">1</a>";
                $pagination .= "<a href=\"" . $targetpage . "page=2\">2</a>";
                $pagination .= "...";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<a class=\"current\">$counter</a>";//active
                    else
                        $pagination .= "<a href=\"" . $targetpage . "page=$counter\">$counter</a>";
                }
                $pagination .= "...";
                $pagination .= "<a href=\"" . $targetpage . "page=$lpm1\">$lpm1</a>";
                $pagination .= "<a href=\"" . $targetpage . "page=$lastpage\">$lastpage</a>";
            } //close to end; only hide early pages
            else {
                $pagination .= "<a href=\"" . $targetpage . "page=1\">1</a>";
                $pagination .= "<a href=\"" . $targetpage . "page=2\">2</a>";
                $pagination .= "...";
                for ($counter = $lastpage - (2 + ($adjacents * 1)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<a class=\"current\">$counter</a>";//active
                    else
                        $pagination .= "<a href=\"" . $targetpage . "page=$counter\">$counter</a>";
                }
            }
        }

        //next button
        if ($page < $counter - 1)
            $pagination .= "<a href=\"" . $targetpage . "page=$next\">&raquo;</a>";
        else
            $pagination .= "<a class=\"disabled\">&raquo;</a>";
        $pagination .= "</div>\n";
    }
    if ($pagination == '') {//pagination
        $pagination .= "<div class=\"pagin\">";
        $pagination .= "<a class=\"disabled\"> &laquo;</a>";
        $pagination .= "<a class=\"current\">1</a>";//active
        $pagination .= "<a class=\"disabled\">&raquo;</a>";
        $pagination .= "</div>";
    }
    return $pagination;
}

function paginate_two($reload, $page, $tpages, $adjacents)
{
    $firstlabel = "&laquo;&nbsp;";
    $prevlabel = "&lsaquo;&nbsp;";
    $nextlabel = "&nbsp;&rsaquo;";
    $lastlabel = "&nbsp;&raquo;";
    $out = "<div class=\"pagin\">\n";
    // first
    if ($page > ($adjacents + 1)) {
        $out .= "<a href=\"" . $reload . "\">" . $firstlabel . "</a>\n";
    } else {
        $out .= "<span>" . $firstlabel . "</span>\n";
    }
    // previous
    if ($page == 1) {
        $out .= "<span>" . $prevlabel . "</span>\n";
    } elseif ($page == 2) {
        $out .= "<a href=\"" . $reload . "\">" . $prevlabel . "</a>\n";
    } else {
        $out .= "<a href=\"" . $reload . "&amp;page=" . ($page - 1) . "\">" . $prevlabel . "</a>\n";
    }
    // 1 2 3 4 etc
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out .= "<span class=\"current\">" . $i . "</span>\n";
        } elseif ($i == 1) {
            $out .= "<a href=\"" . $reload . "\">" . $i . "</a>\n";
        } else {
            $out .= "<a href=\"" . $reload . "&amp;page=" . $i . "\">" . $i . "</a>\n";
        }
    }
    // next
    if ($page < $tpages) {
        $out .= "<a href=\"" . $reload . "&amp;page=" . ($page + 1) . "\">" . $nextlabel . "</a>\n";
    } else {
        $out .= "<span>" . $nextlabel . "</span>\n";
    }
    // last
    if ($page < ($tpages - $adjacents)) {
        $out .= "<a href=\"" . $reload . "&amp;page=" . $tpages . "\">" . $lastlabel . "</a>\n";
    } else {
        $out .= "<span>" . $lastlabel . "</span>\n";
    }

    $out .= "</div>";

    return $out;
}

/*Set calendar filters*/
function calendarFilters($fildterType, $dateColumn, $startDate = NULL, $endDate = NULL)
{
    $where = '';
    if ($fildterType == 'custom') {
        if (!is_null($startDate) && !is_null($endDate)) {
            $start = $startDate;
            $end = $endDate;
            $where = " where DATE(date_time)  between '$start' and '$end'";

        }

    } elseif ($fildterType == 'Today') {
        $StartDate = date('Y-m-d');
        $EndDate = date('Y-m-d');
        $where = " where DATE(date_time)  between '$StartDate' and '$EndDate'";

    } elseif ($fildterType == 'Yesterday') {
        $StartDate = date('Y-m-d', strtotime("-1 days"));
        $EndDate = date('Y-m-d', strtotime("-1 days"));
        $where = " where DATE(date_time)  between '$StartDate' and '$EndDate'";

    } elseif ($fildterType == 'PMonth') {
        $year = date(Y);
        $pmonth = sprintf("%02d", date(m) - 01);
        $StartDate = $year . "-" . $pmonth . "-01";
        $EndDate = $year . "-" . $pmonth . "-31";
        $where = " where DATE(date_time)  between '$StartDate' and '$EndDate'";

    } elseif ($fildterType == 'CMonth') {
        $StartDate = date('Y-m-01');
        $EndDate = date('Y-m-d');
        $where = " where DATE(date_time)  between '$StartDate' and '$EndDate'";

    } elseif ($fildterType == 'CYear') {
        $year = date('Y'); // Get current year and subtract 1
        $StartDate = $year . '-01-01';
        $EndDate = $year . '-12-31';
        $where = " where DATE(date_time)  between '$StartDate' and '$EndDate'";

    }
    return $where;
}


function getLastMonthDays()
{
    $temp_last_month = date('Y-m-d', strtotime(date('Y-m') . " -1 month"));
    $expl_last_month = explode('-', $temp_last_month);
    $year = $expl_last_month['0'];
    $month = $expl_last_month['1'];

    $start_date = "01-" . $month . "-" . $year;
    $start_time = strtotime($start_date);

    $end_time = strtotime("+1 month", $start_time);

    for ($i = $start_time; $i < $end_time; $i += 86400) {
        $list[] = date('Y-m-d', $i);
    }

    return ($list);
}

function getCurrentMonthDays()
{
    $month = date('m');
    $year = date('Y');

    $start_date = "01-" . $month . "-" . $year;
    $start_time = strtotime($start_date);

    $end_time = strtotime("+1 month", $start_time);

    for ($i = $start_time; $i < $end_time; $i += 86400) {
        $list[] = date('Y-m-d', $i);
    }

    return ($list);
}

function getlastWeekMonday()
{
    $previous_week = strtotime("-1 week +1 day");
    $start_week = strtotime("last sunday midnight", $previous_week);
    $end_week = strtotime("next saturday", $start_week);

    $start_week = date("Y-m-d", $start_week);
    $end_week = date("Y-m-d", $end_week);

    $start_week = date('Y-m-d', strtotime('+1 day', strtotime($start_week)));
    return $start_week;
}

function getDateOfWeekDay($day)
{
    $weekDays = array(
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    );

    $dayNumber = array_search($day, $weekDays);
    $currentDayNumber = date('w', strtotime('Today'));

    if ($dayNumber > $currentDayNumber) {
        return date('Y-m-d', strtotime($day));
    } else {
        return date('Y-m-d', strtotime($day) - 604800);
    }
}

/*Get graph if admin(permission =1) is logged in*/
function graph1($dt)
{
    global $mysqli, $db_table_prefix;
    $date_str = '';
    $click_str = '';
    $conversion_str = '';
    $cost_str = '';
    $payouts_str = '';
    $profit_str = '';
    if ($dt == '' || $dt == 'CWeek') {

        $today_date = date('Y-m-d');

        $date = strtotime("-7 day");
        $previuos_7_day = date('Y-m-d', $date);
        $i = 1;

        $current_date = getDateOfWeekDay('Monday');
        $monday = date('Y-m-d', strtotime('monday this week'));
        $current_date = $monday;
        for ($k = 0; $k < 7; $k++) {


            $day_name = date('l', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where (status='2') and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }
            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payouts_conversion = mysqli_fetch_assoc($payouts_query)) {
                $payouts_to_int = $payouts_conversion['affiliate_payout'];
                $temp_payout_ctotal = $payouts_to_int + $temp_payout_ctotal;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";

            $temp_date = strtotime("-1 day");
            $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);
        $cost_str = substr($cost_str, 0, -1);
        $payouts_str = substr($payouts_str, 0, -1);
        $profit_str = substr($profit_str, 0, -1);
    }
    if ($dt == 'Today') {
        $today_date = date('Y-m-d');

        $current_date = $today_date;

        for ($k = 1; $k <= 24; $k++) {
            $k = sprintf("%02d", $k);

            $p = $k - 1;
            $p = sprintf("%02d", $p);

            $date_str .= "'" . $k . "'" . ",";

            $start_current_date = $today_date . " " . $p;
            $end_current_date = $today_date . " " . $k;

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and date_time between '$start_current_date' and '$end_current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and date_time between '$start_current_date' and '$end_current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4') and date_time between '$start_current_date' and '$end_current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }
            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and date_time between '$start_current_date' and '$end_current_date'");
            $temp_payout_ctotal = 0;
            while ($payouts_conversion = mysqli_fetch_assoc($payouts_query)) {
                $payout_to_int = $payouts_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $payout_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";
        }

    }

    if ($dt == 'Yesterday') {
        $today_date = date('Y-m-d');
        $today_date = date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        $current_date = $today_date;

        for ($k = 1; $k <= 24; $k++) {
            $k = sprintf("%02d", $k);

            $p = $k - 1;
            $p = sprintf("%02d", $p);

            $date_str .= "'" . $k . "'" . ",";

            $start_current_date = $today_date . " " . $p;
            $end_current_date = $today_date . " " . $k;

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2'  and date_time between '$start_current_date' and '$end_current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and date_time between '$start_current_date' and '$end_current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4') and date_time between '$start_current_date' and '$end_current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }

            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4')  and date_time between '$start_current_date' and '$end_current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payouts_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";
        }


    }

    if ($dt == 'PWeek') {

        //$current_date= getlastWeekMonday();
        $current_date = date("Y-m-d", strtotime("last week monday"));
        for ($k = 0; $k < 7; $k++) {

            $day_name = date('l', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2'  and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4')  and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4')  and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }
            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4')  and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payouts_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";

            $temp_date = strtotime("-1 day");
            $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);
        $cost_str = substr($cost_str, 0, -1);
        $payouts_str = substr($payouts_str, 0, -1);
        $profit_str = substr($profit_str, 0, -1);
    }

    if ($dt == 'CMonth') {

        $monthDays = getCurrentMonthDays();
        foreach ($monthDays as $value) {
            $current_date = $value;
            $day_name = date('d', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2'  and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4')  and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4') and  date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }

            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and  date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payouts_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }

            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";

            $temp_date = strtotime("-1 day");
            //$current_date= date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);
        $cost_str = substr($cost_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);
        $profit_str = substr($profit_str, 0, -1);
    }

    if ($dt == 'PMonth') {

        $monthDays = getLastMonthDays();
        foreach ($monthDays as $value) {
            $current_date = $value;
            $day_name = date('d', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and  date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }
            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payout_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payout_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";

            $temp_date = strtotime("-1 day");
            //$current_date= date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);
        $cost_str = substr($cost_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);
        $profit_str = substr($profit_str, 0, -1);
    }

    if ($dt == 'CYear') {

        $today_date = date('Y-m-d');

        $date = strtotime("-7 day");
        $previuos_7_day = date('Y-m-d', $date);
        $i = 1;

        for ($k = 1; $k <= 12; $k++) {
            $k = sprintf("%02d", $k);
            $today_date = date('Y-' . $k);
            $current_date = $today_date;

            $month_name = date('F', strtotime($current_date));
            $date_str .= "'" . $month_name . "'" . ',';


            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2'  and date_format(date_time,'%Y-%m')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4')  and date_format(date_time,'%Y-%m')='$current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }
            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payout_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and  date_format(date_time,'%Y-%m')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payout_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";

            $temp_date = strtotime("-1 day");
            $today_date = date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);
        $cost_str = substr($cost_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);
        $profit_str = substr($profit_str, 0, -1);
    }

    if ($dt == 'PYear') {

        $today_date = date('Y-m-d');

        $date = strtotime("-7 day");
        $previuos_7_day = date('Y-m-d', $date);
        $i = 1;


        for ($k = 1; $k <= 12; $k++) {
            $k = sprintf("%02d", $k);
            $last_year = date("Y", strtotime("-1 year"));
            $today_date = date($last_year . '-' . $k);
            $current_date = $today_date;

            $month_name = date('F', strtotime($current_date));
            $date_str .= "'" . $month_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2'  and date_format(date_time,'%Y-%m')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and  date_format(date_time,'%Y-%m')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $cost_query = mysqli_query($mysqli, "select advertiser_cost from uc_conversions where (status='2' || status='3' || status='4')  and date_format(date_time,'%Y-%m')='$current_date'");
            $temp_ctotal = 0;
            while ($run_conversion = mysqli_fetch_assoc($cost_query)) {
                $cost_to_int = $run_conversion['advertiser_cost'];
                $temp_ctotal = $temp_ctotal + $cost_to_int;
            }
            $temp_ctotal = $temp_ctotal;
            $cost_str .= $temp_ctotal . ",";

            $payout_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and date_format(date_time,'%Y-%m')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payout_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_profit = $temp_ctotal - $temp_payout_ctotal;
            $temp_profit = $temp_profit;
            $profit_str .= $temp_profit . ",";

            $temp_date = strtotime("-1 day");
            $today_date = date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);
        $cost_str = substr($cost_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);
        $profit_str = substr($profit_str, 0, -1);
    }

    $return_data['date_str'] = $date_str;
    $return_data['click_str'] = $click_str;
    $return_data['conversion_str'] = $conversion_str;
    $return_data['cost_str'] = $cost_str;
    $return_data['payouts_str'] = $payouts_str;
    $return_data['profit_str'] = $profit_str;
    return $return_data;
}

/*javascript function for show admin graph */
function graph1JS($date_str, $click_str, $conversion_str, $cost_str, $payouts_str, $profit_str)
{
    echo "<script type='text/javascript'>

Highcharts.chart('container', {
chart: {
        alignTicks: true,
        type: 'line',
		height: 300,
    },
    title: {
        text: ''
    },

    subtitle: {
        text: ''
    },
	xAxis: {
        categories: [" . $date_str . "]
    },
    yAxis: [{
        title: {
            text: 'Total'
        }
    }],
    legend: {
         align: 'center',
        verticalAlign: 'bottom',
        x: 50,
        y: 10
    },
	credits: {
      enabled: false
	},
	dataLabels: {
                enabled: true,
                crop: false,
                overflow: 'none'
            },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: false
            },
            enableMouseTracking: true
        }
    },tooltip: {
    crosshairs: true,
    animation: true,
    shared: true,
    formatter: function() {
        return this.x + '<br>'
            + this.points[0].series.name + ': ' + this.points[0].y + '<br>'
            + this.points[1].series.name + ': ' + this.points[1].y + '<br>'
			+ this.points[2].series.name + ': ' + (this.points[2].y).toFixed(2) + '<br>'
			+ this.points[3].series.name + ': ' + (this.points[3].y).toFixed(2) + '<br>'
			+ this.points[4].series.name + ': ' + (this.points[4].y).toFixed(2);
        }
},
    series: [{
        name: 'Clicks',
        data: [" . $click_str . "]
    }, {
        name: 'Conversions',
        data: [" . $conversion_str . "]
    },{
        name: 'Profit',
        data: [" . $profit_str . "]
    }, {
        name: 'Cost',  
        data: [" . $cost_str . "]
    }, {
        name: 'Payouts',
        data: [" . $payouts_str . "]
    }]

});
		</script>";
}

/*Get graph if Pending Affiliate,Affiliate Account and Account Suspended(permission =2,3 and 6) is logged in*/
function graph2($dt, $user_id)
{
    global $mysqli, $db_table_prefix;

    $date_str = '';
    $click_str = '';
    $conversion_str = '';
    $payouts_str = '';
    if ($dt == '' || $dt == 'CWeek') {


        $today_date = date('Y-m-d');

        $date = strtotime("-7 day");
        $previuos_7_day = date('Y-m-d', $date);
        $i = 1;

        //$current_date= getDateOfWeekDay('Monday');
        $monday = date('Y-m-d', strtotime('monday this week'));
        $current_date = $monday;
        for ($k = 0; $k < 7; $k++) {


            $day_name = date('l', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where (status='2') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payouts_conversion = mysqli_fetch_assoc($payouts_query)) {
                $payouts_to_int = $payouts_conversion['affiliate_payout'];
                $temp_payout_ctotal = $payouts_to_int + $temp_payout_ctotal;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";


            $temp_date = strtotime("-1 day");
            $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);

    }
    if ($dt == 'Today') {
        $today_date = date('Y-m-d');

        $current_date = $today_date;

        for ($k = 1; $k <= 24; $k++) {
            $k = sprintf("%02d", $k);

            $p = $k - 1;
            $p = sprintf("%02d", $p);

            $date_str .= "'" . $k . "'" . ",";

            $start_current_date = $today_date . " " . $p;
            $end_current_date = $today_date . " " . $k;

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and user_id='$user_id' and date_time between '$start_current_date' and '$end_current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_time between '$start_current_date' and '$end_current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_time between '$start_current_date' and '$end_current_date'");
            $temp_payout_ctotal = 0;
            while ($payouts_conversion = mysqli_fetch_assoc($payouts_query)) {
                $payout_to_int = $payouts_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $payout_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

        }

    }

    if ($dt == 'Yesterday') {
        $today_date = date('Y-m-d');
        $today_date = date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        $current_date = $today_date;

        for ($k = 1; $k <= 24; $k++) {
            $k = sprintf("%02d", $k);

            $p = $k - 1;
            $p = sprintf("%02d", $p);

            $date_str .= "'" . $k . "'" . ",";

            $start_current_date = $today_date . " " . $p;
            $end_current_date = $today_date . " " . $k;

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and user_id='$user_id' and date_time between '$start_current_date' and '$end_current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_time between '$start_current_date' and '$end_current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_time between '$start_current_date' and '$end_current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payouts_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

        }


    }

    if ($dt == 'PWeek') {

        $current_date = getlastWeekMonday();
        for ($k = 0; $k < 7; $k++) {

            $day_name = date('l', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payouts_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_date = strtotime("-1 day");
            $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);
        $cost_str = substr($cost_str, 0, -1);
        $payouts_str = substr($payouts_str, 0, -1);
        $profit_str = substr($profit_str, 0, -1);
    }

    if ($dt == 'CMonth') {

        $monthDays = getCurrentMonthDays();
        foreach ($monthDays as $value) {
            $current_date = $value;
            $day_name = date('d', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";

            $payouts_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payouts_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }

            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_date = strtotime("-1 day");
            //$current_date= date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);

    }

    if ($dt == 'PMonth') {

        $monthDays = getLastMonthDays();
        foreach ($monthDays as $value) {
            $current_date = $value;
            $day_name = date('d', strtotime($current_date));
            $date_str .= "'" . $day_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";


            $payout_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m-%d')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payout_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_date = strtotime("-1 day");
            //$current_date= date('Y-m-d', strtotime('+1 day', strtotime($current_date)));
            //$today_date= date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);

    }

    if ($dt == 'CYear') {

        $today_date = date('Y-m-d');

        $date = strtotime("-7 day");
        $previuos_7_day = date('Y-m-d', $date);
        $i = 1;

        for ($k = 1; $k <= 12; $k++) {
            $k = sprintf("%02d", $k);
            $today_date = date('Y-' . $k);
            $current_date = $today_date;

            $month_name = date('F', strtotime($current_date));
            $date_str .= "'" . $month_name . "'" . ',';


            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and user_id='$user_id' and date_format(date_time,'%Y-%m')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";


            $payout_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payout_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_date = strtotime("-1 day");
            $today_date = date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);

    }

    if ($dt == 'PYear') {

        $today_date = date('Y-m-d');

        $date = strtotime("-7 day");
        $previuos_7_day = date('Y-m-d', $date);
        $i = 1;


        for ($k = 1; $k <= 12; $k++) {
            $k = sprintf("%02d", $k);
            $last_year = date("Y", strtotime("-1 year"));
            $today_date = date($last_year . '-' . $k);
            $current_date = $today_date;

            $month_name = date('F', strtotime($current_date));
            $date_str .= "'" . $month_name . "'" . ',';

            $click_query = mysqli_query($mysqli, "select * from uc_clicks where status='2' and user_id='$user_id' and date_format(date_time,'%Y-%m')='$current_date'");
            $total_clicks = mysqli_num_rows($click_query);
            $click_str .= $total_clicks . ",";

            $conversion_query = mysqli_query($mysqli, "select * from uc_conversions where (status='2' || status='3' || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m')='$current_date'");
            $total_conversion = mysqli_num_rows($conversion_query);
            $conversion_str .= $total_conversion . ",";


            $payout_query = mysqli_query($mysqli, "select affiliate_payout from uc_conversions where (status='2' || status='3 || status='4') and user_id='$user_id' and date_format(date_time,'%Y-%m')='$current_date'");
            $temp_payout_ctotal = 0;
            while ($payout_conversion = mysqli_fetch_assoc($payout_query)) {
                $cost_to_int = $payout_conversion['affiliate_payout'];
                $temp_payout_ctotal = $temp_payout_ctotal + $cost_to_int;
            }
            $temp_payout_ctotal = $temp_payout_ctotal;
            $payouts_str .= $temp_payout_ctotal . ",";

            $temp_date = strtotime("-1 day");
            $today_date = date('Y-m-d', strtotime('-1 day', strtotime($today_date)));

        }
        $date_str = substr($date_str, 0, -1);
        $click_str = substr($click_str, 0, -1);
        $conversion_str = substr($conversion_str, 0, -1);

        $payouts_str = substr($payouts_str, 0, -1);

    }

    $return_data['date_str'] = $date_str;
    $return_data['click_str'] = $click_str;
    $return_data['conversion_str'] = $conversion_str;
    $return_data['payouts_str'] = $payouts_str;

    return $return_data;
}

/*javascript function for show Pending Affiliate,Affiliate Account and Account Suspended graph */
function graph2JS($date_str, $click_str, $conversion_str, $payouts_str)
{
    echo "<script type='text/javascript'>

Highcharts.chart('container', {
chart: {
        alignTicks: true,
        type: 'line',
		height: 300,
    },
    title: {
        text: ''
    },

    subtitle: {
        text: ''
    },
	xAxis: {
        categories: [" . $date_str . "]
    },
    yAxis: [{
        title: {
            text: 'Total'
        }
    }],
    legend: {
         align: 'center',
        verticalAlign: 'bottom',
        x: 50,
        y: 10
    },
	credits: {
      enabled: false
	},
	dataLabels: {
                enabled: true,
                crop: false,
                overflow: 'none'
            },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: false
            },
            enableMouseTracking: true
        }
    },tooltip: {
    crosshairs: true,
    animation: true,
    shared: true,
    formatter: function() {
        return this.x + '<br>'
            + this.points[0].series.name + ': ' + this.points[0].y + '<br>'
            + this.points[1].series.name + ': ' + this.points[1].y + '<br>'
			+ this.points[2].series.name + ': ' + (this.points[2].y).toFixed(2);
        }
},
    series: [{
        name: 'Clicks',
        data: [" . $click_str . "]
    }, {
        name: 'Conversions',
        data: [" . $conversion_str . "]
    }, {
        name: 'Payouts',
        data: [" . $payouts_str . "],
		color: '#8085e9',
		 marker : {symbol : 'triangle-down' }

    }]

});
		</script>";
}


//ALL NEW FUNCTIONS//

//Checks if user has permission to view a specific offer
function getPermissionOfferMatches($status)
{
    global $mysqli, $db_table_prefix;

    $query1 = "SELECT * FROM uc_affiliate_permission_offer_status where id ='$status'";
    $result1 = mysqli_query($mysqli, $query1);
    $row1 = mysqli_fetch_assoc($result1);


    if ($status == 1) {
        $statusOutput = '<font style="color:red!important;">' . $row1['name'] . "</font>";
    } else if ($status == 2) {
        $statusOutput = '<font style="color:GoldenRod!important;">' . $row1['name'] . "</font>";
    } else if ($status == 3 || $status == 4) {
        $statusOutput = '<font style="color:Green!important;">' . $row1['name'] . "</font>";
    }

    return $statusOutput;
}

//Shows clicks status as a colour
function getClickStatusColour($status)
{
    global $mysqli, $db_table_prefix;

    $query1 = "SELECT * FROM uc_clicks_status where id ='$status'";
    $result1 = mysqli_query($mysqli, $query1);
    $row1 = mysqli_fetch_assoc($result1);


    if ($status == '1') {
        $statusOutput = '<font style="color:red!important;">' . $row1['name'] . "</font>";
    } else if ($status == '2') {
        $statusOutput = '<font style="color:green!important;">' . $row1['name'] . "</font>";
    }

    return $statusOutput;
}

//Shows conversion status as a colour
function getConversionStatusColour($status)
{
    global $mysqli, $db_table_prefix;

    $query = mysqli_query($mysqli, "SELECT * FROM uc_conversions_status where id ='$status'");
    $result = mysqli_fetch_assoc($query);

    if ($status == 1) {
        $statusOutput = '<font style="color:red!important;">' . $result['name'] . '</font>';
    } else if ($status == 2) {
        $statusOutput = '<font style="color:Goldenrod!important;">' . $result['name'] . '</font>';
    } else if ($status == 3 || $status == 4) {
        $statusOutput = '<font style="color:green!important;">' . $result['name'] . '</font>';
    }

    return $statusOutput;
}

//Shows banner status as a colour
function getBannerStatusColor($status)
{
    global $mysqli, $db_table_prefix;

    $query1 = "SELECT * FROM uc_banner_creatives_status where id ='$status'";
    $result1 = mysqli_query($mysqli, $query1);
    $row1 = mysqli_fetch_assoc($result1);


    if ($status == 1) {
        $statusOutput = '<font style="color:red!important;">' . $row1['name'] . "</font>";
    } else if ($status == 2) {
        $statusOutput = '<font style="color:goldenRod!important;">' . $row1['name'] . "</font>";
    } else if ($status == 3) {
        $statusOutput = '<font style="color:green!important;">' . $row1['name'] . "</font>";
    }

    return $statusOutput;
}

//Counts total amount of clicks for a specific user
function getUserClicks($userID)
{

    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");

    $query = mysqli_query($mysqli, "SELECT count(id) as total_rec  FROM uc_clicks WHERE user_id='$userID' and status in(2) and YEAR(date_time) ='$year' AND MONTH(date_time) ='$month'");
    $temp_row = mysqli_fetch_assoc($query);
    echo $temp_row['total_rec'];
}


//Counts total amount of conversions for a specific user
function getUserConversions($userID)
{
    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");
    $query = mysqli_query($mysqli, "SELECT count(id) as total_rec  FROM uc_conversions WHERE user_id='$userID' and status in(2,4,6) and YEAR(date_time) ='$year' AND MONTH(date_time) ='$month'");
    $temp_row = mysqli_fetch_assoc($query);
    echo $temp_row['total_rec'];

}

//Counts total amount of rejected conversions for a specific user
function getUserReturnConversions($userID)
{
    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");

    $query = mysqli_query($mysqli, "SELECT count(id) as total_rec FROM uc_conversions WHERE user_id='$userID' and status in(1) and YEAR(date_time) ='$year' AND MONTH(date_time) ='$month'");
    $temp_row = mysqli_fetch_assoc($query);
    echo $temp_row['total_rec'];

}


//Counts total commission earned for a specific user
function getUserCommission($userID)
{

    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");
    $query = mysqli_query($mysqli, "SELECT SUM(affiliate_payout) as total_sum FROM uc_conversions WHERE user_id='$userID' and YEAR(date_time) ='$year' AND MONTH(date_time) ='$month' and status in(2,4,6)");
    $temp_row = mysqli_fetch_assoc($query);

    $payout = number_format($temp_row['total_sum'], 2);
    echo $payout;

}


//Counts total amount of clicks for admin
function getAdminClicks($userID)
{

    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");

    $query = mysqli_query($mysqli, "SELECT count(id) as total_rec FROM uc_clicks WHERE YEAR(date_time) ='$year' and status in(2) and MONTH(date_time) ='$month'");
    $temp_row = mysqli_fetch_assoc($query);
    echo $temp_row['total_rec'];
}

//Counts total amount of conversions for admin
function getAdminConversions($userID)
{
    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");
    $query = mysqli_query($mysqli, "SELECT count(id) as total_rec  FROM uc_conversions WHERE status in(2,3,4) and YEAR(date_time) ='$year' AND MONTH(date_time) ='$month'");
    $temp_row = mysqli_fetch_assoc($query);
    echo $temp_row['total_rec'];

}

//Counts total amount of rejected conversions for admin
function getAdminReturnConversions($userID)
{
    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");

    $query = mysqli_query($mysqli, "SELECT count(id) as total_rec FROM uc_conversions WHERE status in(1) and YEAR(date_time) ='$year' AND MONTH(date_time) ='$month'");
    $temp_row = mysqli_fetch_assoc($query);
    echo $temp_row['total_rec'];

}

//Counts total commission earned for admin
function getAdminCommission($userID)
{

    global $mysqli, $db_table_prefix;
    $year = date("Y");
    $month = date("m");
    $query = mysqli_query($mysqli, "SELECT SUM(advertiser_cost) as total_sum FROM uc_conversions WHERE YEAR(date_time) ='$year' AND MONTH(date_time) ='$month' and status in(2,3,4)");
    $temp_row = mysqli_fetch_assoc($query);

    $payout = number_format($temp_row['total_sum'], 2);
    echo $payout;

}

//Counts total affiliate accounts
function totalAccounts()
{
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare("SELECT count(id) FROM uc_user_permission_matches");
    $stmt->execute();
    $stmt->bind_result($id);

    while ($stmt->fetch()) {
        $row[] = array('id' => $id);
    }

    $stmt->close();
    echo $row[0]['id'];
    unset($row);
}


//Counts total pending campaign approvals
function pendingApprovals()
{
    global $mysqli, $db_table_prefix;
    $stmt = $mysqli->prepare("SELECT count(site_id) FROM uc_affiliate_permission_offer_matches WHERE site_status = '2'");
    $stmt->execute();
    $stmt->bind_result($id);

    while ($stmt->fetch()) {
        $row[] = array('site_id' => $id);
    }

    $stmt->close();
    echo $row[0]['site_id'];
    unset($row);
}


?>