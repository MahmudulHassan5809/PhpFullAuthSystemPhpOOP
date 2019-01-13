<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$filepath=realpath(dirname(__FILE__));
include_once ($filepath.'../../lib/Database.php') ;
include_once ($filepath.'../../helpers/Format.php') ;

//Load Composer's autoloader
require 'vendor/autoload.php';
?>
<?php
class Account
{
    private $db;
    private $fm;
    private $ac;
    public function __construct()
	{
	  $this->db=new Database();
	  $this->fm=new Format();
	}

	public function userRegistration($data)
	{

	   //Main Errors Array
	   $errors = array();

       //Get Values & Sanitize
	   $name=$this->fm->validation($data['name']);
       $name=mysqli_real_escape_string($this->db->link,$data['name']);

       $username=$this->fm->validation($data['username']);
       $username=mysqli_real_escape_string($this->db->link,$data['username']);

       $email=$this->fm->validation($data['email']);
       $email=mysqli_real_escape_string($this->db->link,$data['email']);

       $website=$this->fm->validation($data['website']);
       $website=mysqli_real_escape_string($this->db->link,$data['website']);

       $password=$this->fm->validation($data['password']);
       $password=mysqli_real_escape_string($this->db->link,$data['password']);

       $confirm_password=$this->fm->validation($data['confirm_password']);
       $confirm_password=mysqli_real_escape_string($this->db->link,$data['confirm_password']);

       //Name Error
	   if (strlen($name)>50 || strlen($name)<6) {
	    	$errors['name_error'] = "Name Min Limit is 6 & max is 50 Characters";
	    }

	   //UserName Error
	   if (strlen($username)>15 || strlen($username)<5) {
	   		$errors['username_error'] = "UserName Min Limit is 5 & Max Limit is 15 Characters";
	   }elseif ($this->checkUserByUserName($username)) {
	   		$errors['username_error'] = "UserName Already Exists";
	   }

	  //Email Errors
	  if (filter_var($email, FILTER_VALIDATE_EMAIL)==false) {
	  		$errors['email_error'] = "Invalid Email";
	  }elseif ($this->checkUserByEmail($email)) {
	  		$errors['email_error'] = "Email Already Exists";
	  }

	  //WebSite Errors
	  if (empty($website)) {
	  		$errors['website_error'] = "Invalid Entry";
	  }

	  //Password Error
	  if (strlen($password)>20 || strlen($password)<5) {
	  		$errors['password_error'] = "Password Min Limit is 5 & Max Limit is 20 Characters";
	  }

	  //Cofirm Password
	  if ($password != $confirm_password || empty($confirm_password)) {
	  		$errors['confirm_password_error'] = "Password DoesNot Match Or Empty";
	  }

	  if (count($errors) == 0) {
        //Password Hashing
		$password = password_hash($password,PASSWORD_DEFAULT);
		//Generate Unique Random Reset Code
		$code = md5(crypt(rand(),'aa'));

		//Store To the Database
		$query = "INSERT INTO users(
                name,email,username,password,website,created_at,reset_code)
	            VALUES('$name','$email','$username','$password','$website','time()','$code')";
	    $result   = $this->db->insert($query);
	    if ($result) {
	    	$this->fm->setMsg('msg','Your Account Has Been Created SuccessFully.Please,Check Your Email To Verify','warning');

	    	$message = "Hi! You requested an account on our website, in order to use this account. You need to click here to <a href='".URLROOT."/classes/AccountVerify.php?function=AccountVerify&code=$code'>Verify</a> it.";
            $this->send_mail([

                'to' => $email,
                'message' => $message,
                'subject' => 'Account Verficiation',
                'from' => 'eProfile System',

            ]);
	    } }else{
	    	$data = [
				'name' => $name,
				'username' => $username,
				'email' => $email,
				'website' => $website,
				'password' => $password,
				'confirm_password' => $confirm_password,
	    	];


			$this->fm->setMsg('form_data',$data);
	    	$this->fm->setMsg('errors',$errors);
		}

	}

	public function resetPassword($data)
	{
	   $errors = array();
	   $old_password=$this->fm->validation($data['old_password']);
       $old_password=mysqli_real_escape_string($this->db->link,$old_password);

       $password=$this->fm->validation($data['password']);
       $password=mysqli_real_escape_string($this->db->link,$password);

       $confirm_password=$this->fm->validation($data['confirm_password']);
       $confirm_password=mysqli_real_escape_string($this->db->link,$confirm_password);

       //Password Error
	  if (strlen($old_password)>20 || strlen($old_password)<5 || empty($old_password)) {
	  		$errors['old_password_error'] = "Password Min Limit is 5 & Max Limit is 20 Characters";
	  }
	  //Cofirm Password
	  if ($password != $confirm_password || empty($confirm_password)) {
	  		$errors['confirm_password_error'] = "Password DoesNot Match Or Empty";
	  }

	  if (count($errors) == 0) {
		  	if (isset($_COOKIE['user'])) {
				$data = unserialize($_COOKIE['user']);
				$id = $data['id'];
			}
			$currentUserID = (Session::get('userid') !== false) ? Session::get('userid') : $id;
			$query="SELECT * FROM users Where id='$currentUserID'";
	    	$result=$this->db->select($query);
	    	if ($result) {
	    		$value=$result->fetch_assoc();
	    		if (password_verify($old_password,$value['password'])) {
	    			$password = password_hash($password,PASSWORD_DEFAULT);
					$query = "UPDATE users
				            SET password='$password'
							WHERE id='$currentUserID'";
				    $result = $this->db->update($query);
				    $this->fm->setMsg('msg_notify','Password Changed SuccessFully','info');
	    			$this->fm->redirect('change_password.php');
	    		}else{
	    			$this->fm->setMsg('msg_notify','Incorrect credentials','warning');
	    		}
	    	}
	  }else{
			$data = [
				'old_password' => $old_password,
				'password' => $password,
				'confirm_password' => $confirm_password,

	    	];


			$this->fm->setMsg('form_data',$data);
	    	$this->fm->setMsg('errors',$errors);
	  }
	}

	public function checkUserByEmail($email)
	{
		$mailcheck="SELECT users.email FROM users Where email='$email'";
	    $mailres=$this->db->select($mailcheck);
	    if ($mailres) {
	       return true;
	    }
	}

	public function checkUserByUserName($username)
	{
		$usernamecheck="SELECT users.username FROM users Where username='$username'";
	    $usernameres=$this->db->select($usernamecheck);
	    if ($usernameres) {
	       return true;
	    }
	}

	public function send_mail($detail=array())
	{
		if (!empty($detail['to']) && !empty($detail['message']) && !empty($detail['from'])) {
			$mail = new PHPMailer(true);
			//$mail->SMTPDebug = 2;
		    $mail->isSMTP();
		    $mail->Host = 'smtp.gmail.com';
		    $mail->SMTPAuth = true;
		    $mail->Username = USERNAME;
		    $mail->Password = PASSWORD;
		    $mail->SMTPSecure = 'tls';
		    $mail->Port = 587;

		    $mail->setFrom('no-reply@proteinwriter.com', $detail['from']);
    		$mail->addAddress($detail['to'], '');

    		$mail->isHTML(true);
		    $mail->Subject = $detail['subject'];
		    $mail->Body    = $detail['message'];

		    if (!$mail->send()) {
		    	return false;
		    }else{
		    	return true;
		    }
		}
	}


	public function userLogin($data)
	{
	   $errors = array();

	   $email=$this->fm->validation($data['email']);
       $email=mysqli_real_escape_string($this->db->link,$email);

       $password=$this->fm->validation($data['password']);
       $password=mysqli_real_escape_string($this->db->link,$password);

       $remember = isset($_POST['remember-me']) ? 'Yes' : '';

       if (!$this->checkUserByEmail($email)) {
       		$errors['email_error'] = 'Email Not Exits';
       }elseif (!$this->checkUserActivation($email)) {
       		$errors['email_error'] = "Your Account Is Not Verified";
       }

       if (count($errors) == 0) {
       		$query="SELECT * FROM users Where email='$email'";
	    	$result=$this->db->select($query);
	    	if ($result) {
	    		$value=$result->fetch_assoc();
	    		if (password_verify($password,$value['password'])) {
	    			if ($remember === 'Yes') {
	    				setcookie('user',serialize($value),time() + (86400 * 30),'/');
	    			}else{
	    				Session::set("userlogin",true);
		                Session::set("userid",$value['id']);
		                Session::set("username",$value['username']);
	    			}
	    			$this->fm->redirect('profile.php');
	    		}else{
	    			$this->fm->setMsg('msg_notify','Incorrect credentials','warning');
	    		}
	    	}else{
	    		$this->fm->setMsg('msg_notify','User Not Found','warning');
	    	}
       }else{
       		$data = [
				'email' => $email,
				'password' => $password
       		];

       		$this->fm->setMsg('form_data',$data);
       		$this->fm->setMsg('errors',$errors);
       }
	}

	public function checkUserActivation($email)
	{
		$mailcheck="SELECT users.email FROM users Where email='$email' AND is_active=1";
	    $mailres=$this->db->select($mailcheck);
	    if ($mailres) {
	       return true;
	    }
	}

	public function logout()
	{
		if(isset($_COOKIE['user'])){
          setcookie('user', '', time() - (86400 * 30), '/');
     	}
		Session::destroy();
	}

	public function getUserData()
	{
		if (isset($_COOKIE['user'])) {
			$data = unserialize($_COOKIE['user']);
			$id = $data['id'];
		}
		$currentUserID = (Session::get('userid') !== false) ? Session::get('userid') : $id;
		$query = "SELECT * FROM users WHERE id='$currentUserID'";
		$result = $this->db->select($query);
		if ($result) {
			return $result;
		}
	}


	public function updateProfile($data)
	{
	   $errors = array();
	   $name=$this->fm->validation($data['name']);
       $name=mysqli_real_escape_string($this->db->link,$name);

       if (strlen($name)>50 || strlen($name)<6) {
	    	$errors['name_error'] = "Name Min Limit is 6 & max is 50 Characters";
	    }
	   if (count($errors) == 0) {
				if (isset($_COOKIE['user'])) {
				$data = unserialize($_COOKIE['user']);
				$id = $data['id'];
			}
			$currentUserID = (Session::get('userid') !== false) ? Session::get('userid') : $id;
			$query = "UPDATE users
		            SET name='$name'
					WHERE id='$currentUserID'";
		    $result = $this->db->update($query);
		    $this->fm->setMsg('msg_notify','Password Changed SuccessFully','info');
			$this->fm->redirect('profile.php');

	   }else{
	   	$data = [
				'name' => $name,
			];
			$this->fm->setMsg('form_data',$data);
	    	$this->fm->setMsg('errors',$errors);
	   }
	}

	public function forgetPassword($data)
	{
	  $errors = array();
	  $email=$this->fm->validation($data['email']);
      $email=mysqli_real_escape_string($this->db->link,$data['email']);

	  if (filter_var($email, FILTER_VALIDATE_EMAIL)==false) {
	  		$errors['email_error'] = "Invalid Email";
	  }elseif ($this->checkUserByEmail($email) !== true) {
	  		$errors['email_error'] = "Email Not Exists";
	  }

	  if(count($errors) == 0){
		$code = md5(crypt(rand(), 'aa'));
		$query = "UPDATE users
				SET reset_code = '$code',
				is_active=0
				WHERE email='$email'
				";
		$result = $this->db->update($query);
		if($result){
			$this->fm->setMsg('msg', 'You made a password request, please check email to reset your password.', 'warning');



            $message = "Hi! You requested password reset, . You need to click here to <a href='".URLROOT."/classes/ResetCodeVerify.php?reset_code=$code'>Reset your password.</a>";

            $this->send_mail([

                'to' => $email,
                'message' => $message,
                'subject' => 'Reset Password Requested',
                'from' => 'eProfile System',

            ]);
		}
	  }else{
		$data = [
			'email' => $email,
		];
			$this->fm->setMsg('form_data',$data);
	    	$this->fm->setMsg('errors',$errors);
	   }
	}

  public function resetForgetPassword($data)
  {
		$errors = array();
	  $password=$this->fm->validation($data['password']);
      $password=mysqli_real_escape_string($this->db->link,$password);

      $confirm_password=$this->fm->validation($data['confirm_password']);
      $confirm_password=mysqli_real_escape_string($this->db->link,$confirm_password);

      //password
     if(strlen($password)>20 || strlen($password)<5){
         $errors['password_error'] = 'Password min limit is 5 & max is 20 characters';
     }

     //confirm password
     if($password!=$confirm_password || empty($confirm_password)){
         $errors['confirm_password_error'] = 'Password does not match or empty';
     }
     if(count($errors) == 0){
     	$code = $_SESSION['reset_code'];
     	$password = password_hash($password,PASSWORD_DEFAULT);
     	$query = "UPDATE users SET
     			is_active=1,
     			reset_code='',
     			password='$password'
     			WHERE reset_code='$code'";
     	$result = $this->db->update($query);
     	if ($result) {
     		unset($_SESSION['reset_code']);
			$this->fm->setMsg('msg_notify', 'Your account password has been reset, you can login now.');
            $this->fm->redirect('login.php');
     	}
     }else{
     	$data = [
            'password' => $password,
            'confirm_password' => $confirm_password,
        ];

        $this->fm->setMsg('form_data', $data);
        $this->fm->setMsg('errors', $errors);
        $this->fm->redirect('reset_password.php');
     }
  }



}
?>
