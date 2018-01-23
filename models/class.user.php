<?php

class loggedInUser {
	public $email = NULL;
	public $hash_pw = NULL;
	public $user_id = NULL;
	
	//Simple function to update the last sign in of a user
	public function updateLastSignIn()
	{
		global $mysqli,$db_table_prefix;
		$time = time();
		$query=mysqli_query($mysqli,"UPDATE ".$db_table_prefix."users
			SET
			last_sign_in_stamp = $time
			WHERE
			id = ".$this->user_id);
	}
	
	//Return the timestamp when the user registered
	public function signupTimeStamp()
	{
		global $mysqli,$db_table_prefix;
		
		$query=mysqli_query($mysqli,"SELECT sign_up_stamp
			FROM ".$db_table_prefix."users
			WHERE id = ".$this->user_id);
		$temp_result=mysqli_fetch_assoc($query);
		return ($temp_result['sign_up_stamp']);
	}
	
	//Update a users password
	public function updatePassword($pass)
	{
		global $mysqli,$db_table_prefix;
		$secure_pass = generateHash($pass);
		$this->hash_pw = $secure_pass;
		$query=mysqli_query($mysqli,"UPDATE ".$db_table_prefix."users
			SET
			password = '$secure_pass' 
			WHERE
			id = ".$this->user_id);
		
	}
	
	//Update a users email
	public function updateEmail($email)
	{
		global $mysqli,$db_table_prefix;
		$this->email = $email;
		$query=mysqli_query($mysqli,"UPDATE ".$db_table_prefix."users
			SET 
			email = '$email'
			WHERE
			id = ".$this->user_id);
		
	}
	
	//Is a user has a permission
	public function checkPermission($permission)
	{
		global $mysqli,$db_table_prefix,$master_account;
		
		//Grant access if master user
		
		
		$stmt = $mysqli->prepare("
			");
		$access = 0;
		foreach($permission as $check){
			if ($access == 0){
				
				$query=mysqli_query($mysqli,"SELECT id 
			FROM ".$db_table_prefix."user_permission_matches
			WHERE user_id = ".$this->user_id."
			AND permission_id = '$check'
			LIMIT 1");
				$total_rows=mysqli_num_rows($query);
			
				if ($total_rows > 0){
					$access = 1;
				}
			}
		}
		if ($access == 1)
		{
			return true;
		}
		if ($this->user_id == $master_account){
			return true;	
		}
		else
		{
			return false;	
		}
		$stmt->close();
	}
	
	//Logout
	public function userLogOut()
	{
		destroySession("membershipScriptUser");
	}	
}

?>