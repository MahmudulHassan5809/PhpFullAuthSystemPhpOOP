<?php
session_start();
$filepath=realpath(dirname(__FILE__));
include_once ($filepath.'../../lib/Database.php') ;
include_once ($filepath.'../../helpers/Format.php') ;
if (isset($_GET['function']) && isset($_GET['code'])) {
	$function = $_GET['function'];
	$code = $_GET['code'];
	$av = new AccountVerify();
	$av->$function($code);
}
?>
<?php
/**
 * Account Verify
 */
class AccountVerify
{

	private $db;
    private $fm;
    public function __construct()
	{
	  $this->db=new Database();
	  $this->fm=new Format();
	}

	public function AccountVerify($code)
	{
	   $code=$this->fm->validation($code);
       $code=mysqli_real_escape_string($this->db->link,$code);

       if ($this->checkUserByCode($code)) {
       		$this->verifyUserAccount($code);
       		$this->fm->setMsg('msg_notify','Your Account Has Been Activated,you Can Login');
       		$this->fm->redirect('../login.php');
       		exit();
       }else{
       		$this->fm->setMsg('msg','Invalid Activation Code','warning');
       		//echo $this->fm->getMsg('msg');
			$this->fm->redirect('../register.php');

       }

	}

	public function verifyUserAccount($code)
	{
		$query = "UPDATE users
	            SET is_active=1,
	            reset_code=''
	            WHERE reset_code='$code'";
	    $result = $this->db->update($query);
	    if ($result) {
	     return true;
	    }else{
	     return false;
	    }
	}

	public function checkUserByCode($code)
	{
		$codecheck="SELECT users.reset_code FROM users Where reset_code='$code'";
	    $coderes=$this->db->select($codecheck);
	    if ($coderes) {
	       return true;
	    }
	}
}
?>
