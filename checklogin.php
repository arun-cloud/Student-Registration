<?php
// checkLogin.php
if(count($_POST)>0) {
	 ob_start();
session_start(); // Start a new session
include('db_connect.php');   // Holds all of our database connection information

function your_filter($value) {
    $newVal = stripslashes($value);
    $newVal = htmlspecialchars($newVal);
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
}

	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
}  



$sql = "SELECT * FROM userlogin WHERE username ='".$return['username']."' and password= PASSWORD('".$return['userpassword']."') and statusflag='A'";
$result=mysql_query($sql);    
if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
}else{
		$rowsreturn = mysql_num_rows($result);
}

if ($rowsreturn === 1) {
	
	while($fetchresult = mysql_fetch_array($result)) {	
	$_SESSION['username'] = $fetchresult['loginname'];
	$_SESSION['useruid'] = $fetchresult['uid'];
	$_SESSION['userlevel'] = $fetchresult['userlevel'];
	$_SESSION['adminlevel'] = $fetchresult['adminlevel'];
	$_SESSION['work_section'] = $fetchresult['work_section'];
	$_SESSION['course'] = $fetchresult['course'];
	$_SESSION['isprofessor'] = $fetchresult['isprofessor'];
	$_SESSION['vatpercent']="5";
	}
	$_SESSION['loggedIn'] = "true";
	if($_SESSION['isprofessor'] == 'Y'){
	header("Location: advisor_search.php"); // This is wherever you want to redirect the user to	
	}else{
	header("Location: student_search.php"); // This is wherever you want to redirect the user to	
	}
	
} else {
	$_SESSION['loggedIn'] = "false";
	$msg = "Wrong Username and Password";
	header("Location: signin.php?msg=$msg"); // Wherever you want the user to go when they fail the login
}
ob_end_flush();
}
else {
    header("location:signin.php?msg=Please enter username and password");
}
?>
