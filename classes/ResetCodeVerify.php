<?php
session_start();
$filepath=realpath(dirname(__FILE__));
include_once ($filepath.'../../lib/Database.php') ;
include_once ($filepath.'../../helpers/Format.php') ;
if (isset($_GET['reset_code'])) {

	$code = $_GET['reset_code'];
	$rcv = new ResetCodeVerify();
	$rcv->resetPassword($code);
}
?>

<?php
/**
 * Account Verify
 */
class ResetCodeVerify
{

	private $db;
    private $fm;
    public function __construct()
	{
	  $this->db=new Database();
	  $this->fm=new Format();
	}

	public function resetPassword($code)
	{
	   $code=$this->fm->validation($code);
       $code=mysqli_real_escape_string($this->db->link,$code);

       if ($this->checkUserByCode($code)) {
			$_SESSION['reset_code'] = $code;
       		$this->fm->redirect('../reset_password.php');

       }else{
       		$this->fm->setMsg('msg','Invalid Activation Code','warning');
       		//echo $this->fm->getMsg('msg');
			$this->fm->redirect('../register.php');

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
