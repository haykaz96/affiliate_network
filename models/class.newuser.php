<?php

class User 
{
	public $user_active = 0;
	private $clean_email;
	public $status = false;
	private $clean_password;
	private $username;
	public $sql_failure = false;
	public $mail_failure = false;
	public $email_taken = false;
	public $username_taken = false;
	public $ref_id = 0;
	public $sub_id = 0;
	public $activation_token = 0;
	public $success = NULL;
	public $name;
	public $address;
	public $postal_code;
	public $country;
	public $payment_details;
	public $payment_method;
	public $traffic_details;
	public $telephone_number;

	function __construct($user,$pass,$email,$ref_id,$sub_id,$traffic_details,$payment_details,$payment_method,$telephone_number,$country,$postal_code,$address,$name)
	{
		
	    //Sanitize
		$this->clean_email = sanitize($email);
		$this->clean_password = trim($pass);
		$this->username = sanitize($user);
		$this->name = sanitize($name);
		$this->address = sanitize($address);
		$this->postal_code = sanitize($postal_code);
		$this->postal_code = sanitize($postal_code);
		$this->ref_id = sanitize($ref_id);
		$this->sub_id = sanitize($sub_id);
		$this->country = sanitize($country);
		$this->telephone_number = sanitize($telephone_number);
		$this->payment_method = sanitize($payment_method);
		$this->payment_details = sanitize($payment_details);
		$this->traffic_details = sanitize($traffic_details);
	
		if(usernameExists($this->username))
		{
			$this->username_taken = true;
		}
		else if(emailExists($this->clean_email))
		{
			$this->email_taken = true;
		}
		else
		{
			//No problems have been found.
			$this->status = true;
		}
	}
	
	public function membershipScriptAddUser()
	{
		$body =	file_get_contents("models/mail-templates/new-registration.php");
		
				global $mysqli,$emailActivation,$websiteUrl,$db_table_prefix,$websiteName,$emailAddress;
					
				//Prevent this function being called if there were construction errors
				if($this->status)
				{
					//Construct a secure hash for the plain text password
					$secure_pass = generateHash($this->clean_password);
					
					//Construct a unique activation token
					$this->activation_token = generateActivationToken();
					
					//Do we need to send out an activation email?
					if($emailActivation == "true")
					{
						//User must activate their account first
						$this->user_active = 0;
						
						$mail = new userCakeMail();
						
						//Build the activation message
						$activation_message = lang("ACCOUNT_ACTIVATION_MESSAGE",array($websiteUrl,$this->activation_token));
						
						
						/* Build the template - Optional, you can just use the sendMail function 
						Instead to pass a message. */
						
						if(empty($body))
						{
							$errors[] = lang("MAIL_TEMPLATE_BUILD_ERROR");
						}
						else
						{				
							$trans = array("#USERNAME#" => ucfirst($this->username), "#ACTIVATION-MESSAGE" => $activation_message,"#WEBSITENAME#"=>$websiteName,"#ACTIVATION-KEY#"=>$this->activation_token);
						
							$result = strtr($body,$trans);
						
							
							if($mail->sendMail($this->clean_email,"Verify your email",$result))
							{
								$this->success = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE2");
							}else{
								$this->mail_failure = true;
							}
						}
						
					}
					else
					{
						//Instant account activation
						$this->user_active = 1;
						$this->success = lang("ACCOUNT_REGISTRATION_COMPLETE_TYPE1");
					}	
					
					
					if(!$this->mail_failure)
					{
						$ip_address=$_SERVER['REMOTE_ADDR'];
						//Insert the user into the database providing no errors have been found.
						
						$rand = substr(md5(microtime()),rand(0,26),5);
						
						$query_add= "INSERT INTO ".$db_table_prefix."users (
							user_name,
							id_code,
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
							activation_token,
							last_activation_request,
							lost_password_request, 
							active,
							title,
							sign_up_stamp,
							last_sign_in_stamp,
							ref_id,
							sub_id,
							ip_address
							)
							VALUES (
							'".$this->username."',
							'".$rand."',
							'". $secure_pass."',
							'".$this->clean_email."',
							'".$this->name."',
							'".$this->address."',
							'".$this->postal_code."',
							'".$this->country."',
							'".$this->telephone_number."',
							'".$this->payment_method."',
							'".$this->payment_details."',
							'".$this->traffic_details."',
							'".$this->activation_token."',
							'".time()."',
							'0',
							'".$this->user_active."',
							'Affiliate Account',
							'".time()."',
							'0',
							'".$this->ref_id."',
							'".$this->sub_id."',
							'".$ip_address."'
							)";
						$result=mysqli_query($mysqli,$query_add);
						
						//Insert default permission into matches table
						$inserted_id = mysqli_insert_id($mysqli);
						$query=mysqli_query($mysqli,"INSERT INTO ".$db_table_prefix."user_permission_matches  (
							user_id,
							permission_id
							)
							VALUES (
							'$inserted_id',
							'10'
							)");
						
					}
				}
			}
		}