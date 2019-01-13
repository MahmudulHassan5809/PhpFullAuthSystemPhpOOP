<?php
/**
 * Format Class For Creating Helpers Method
 */
class Format
{

	public function formatDate($date){
      return date('F j, Y, g:i a', strtotime($date));
    }

    public function redirect($location){
        header("Location: {$location}");
        exit;
    }

    public function setMsg($name,$value,$class='success'){
    	if (is_array($value)) {
    		$_SESSION[$name] = $value;
    	}else{
    		$_SESSION[$name] = "<div class='alert alert-$class text-center'>$value</div>";
    	}
    }

    public function getMsg($name)
    {
    	if (isset($_SESSION[$name])) {
    		$session = $_SESSION[$name];
    		unset($_SESSION[$name]);
    		return $session;
    	}
    }

    public function validation($data){
      $data = trim($data);
      $data = stripcslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    public function test()
    {
    	return "Hello";
    }
}
?>
