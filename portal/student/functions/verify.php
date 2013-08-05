<?php include '../config.php';?>
<?php
//mysql_query("update account set status='1' where verification_code='".filt($_GET['code'])."' and username='".filt($_GET['verify'])."'");
//$_SESSION['student'] = $_GET['verify'];
	//header("Location:../student");
	
//	$uname=$_POST['username'];
	//$pass=$_POST['password'];

//mysql_query("SELECT * FROM raccounts WHERE username = '$uname' AND password= '$pass'") or exit(mysql_error()); //check for duplicates
	//$_SESSION['student'] = $_GET['password'];
	//header("Location:../student");
	
	

	$username = filt($_GET['username']);
	$password=	filt($_GET['password']);
	$q=mysql_query("select username,password,status from raccounts where username='$username' and password='$password' and status='1' and type='Student'") or die(mysql_error());	
	header("Location:../grades");
?>
