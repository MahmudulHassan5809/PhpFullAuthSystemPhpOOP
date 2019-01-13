<?php include 'include/inc.php'; ?>
<?php
if (isset($_GET['action']) && $_GET['action'] == "logout" ) {
	$user->logOut();

}else
{

echo "<script>window.location='profile.php'</script>";
  //header("Location:catlist.php");
}
