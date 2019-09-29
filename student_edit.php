<?php
$message ='';
session_start(); //Start the session
date_default_timezone_set('Asia/Riyadh');
if(!isset($_SESSION['username'])){ //If session not registered
header("location:signin.php"); // Redirect to login.php page
}
else //Continue to current page
{
	if($_SERVER['REQUEST_METHOD'] == "POST") {
		$message='';
			
		if($message == "") {
		if(!isset($_POST["reguid"])) {
		$message = " Unable to retreive data";
		}
		}
		
		switch($_POST["submit"]){
			case "save_accaffair":
			
			if(!isset($_POST["accno"])) {
			$message = " Academic number is required";
			}
			
			if($message == "") {
			if(!isset($_POST["accuid"])) {
			$message = " Academic number is required";
			}
			}

			if($message == "") {
			if(!isset($_POST["studname"])) {
			$message = " Student name is required";
			}
			}	
			
			if($message == "") {
			if(!isset($_POST["program"])) {
			$message = " Select Program";
			}
			}
			
			if($message == "") {
			if(!isset($_POST["contactno"])) {
			$message = " Contact number is required";
			}
			}
			
			/*if($message == "") {
			if(!isset($_POST["idno"])) {
			$message = " Iqama No is required";
			}
			}
			
			if($message == "") {
			if(strlen($_POST["idno"]) < 10) {
			$message = " Invalid Iqama No";
			}
			}*/
			
			if($message == "") {
			if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			$message = "Invalid Email";
			}
			}
			
			if($message == "") {
			if(!isset($_POST["student_type"])) {
			$message = " Student type is required";
			}
			}
			
			if($message == "") {
			if(!isset($_POST["schedule"])) {
				if($_POST['student_type'] == 6) $_POST["schedule"] =8;
				if($_POST['student_type'] == 7) $_POST["schedule"] =9;
			/*$message = " Schedule type required";*/
			}
			}
			
			break;
			case "save_registration":
			if($message == "") {
			if(!isset($_POST["scholar_avail"])) {
			$message = " Select Scholar Avail or Not";
			}
			}
			break;
			
			
		
			default:
				break;
		}
		
	if($message == "") {
		include(getcwd().'/db_connect.php'); 
	if(update_student($_POST["reguid"],$_POST["submit"])){				
		  $message = 'Information Updated successfully';
		  unset($_POST);
		  header("location:student_search.php");		  
		}
		else{		  
		  $message= 'Problem in updating';
		}
	}
		
		
		
	} /* end of $_POST['submit'] button */
	elseif (isset($_GET['reguid']))
{	
	$reguid = (isset($_GET['reguid']) ? $_GET['reguid'] : null);	
	
	include(getcwd().'/db_connect.php'); 
	$reguid = your_filter($reguid);
	$program = $student_type = $schedule_type = $yes_no = $coursemaster = $subjectmaster = $feesmaster = $semester_name = $study_year = $allsemester = array();
	$courseuid ="";
	$completestatus ="";
	
	
	
	$ref_result= get_referencevalue("COURSE");	
	while($fetchresult = mysql_fetch_array($ref_result))
		$program[] = $fetchresult;
	
	$ref_result= get_referencevalue("STUDENTTYP");	
	while($fetchresult = mysql_fetch_array($ref_result))
		$student_type[] = $fetchresult;
		
	$ref_result= get_referencevalue("SCHEDULETYP");	
	while($fetchresult = mysql_fetch_array($ref_result))
		$schedule_type[] = $fetchresult;
		
	$ref_result= get_referencevalue("ISBOOL");	
	while($fetchresult = mysql_fetch_array($ref_result))
		$yes_no[] = $fetchresult;
				
	edit_student($reguid);
	
	if(isset($_POST['program'])){
		$ref_result= get_courses($_POST['program']);	
		while($fetchresult = mysql_fetch_array($ref_result))
		$coursemaster[] = $fetchresult;	
		
		$ref_result= get_courses_with_semestername($_POST['program']);	
		while($fetchresult = mysql_fetch_array($ref_result))
		$allsemester[] = $fetchresult;	
		
		if($_POST['coursemasteruid'] != 0){	
		$ref_result= get_semester($_POST['program'],$_POST['coursemasteruid']);	
		while($fetchresult = mysql_fetch_array($ref_result))
		$semester_name[] = $fetchresult;	
		}
		
		
		
		$ref_result= get_subjects($_POST['program']);	
		while($fetchresult = mysql_fetch_array($ref_result))
		$subjectmaster[] = $fetchresult;	
		

	}
	if(isset($_POST['add_subjects'])){ 
	
			
			$edit_addsubj = explode(',', $_POST["add_subjects"]);

	
	/*$str = str_replace(',', '', your_filter($_POST["add_subjects"]));
	$strlen = strlen($str);
	$edit_addsubj = str_split($str);*/
	}
	if(isset($_POST['repeat_subjects']))
	$edit_repeatsubj = explode(',', $_POST["repeat_subjects"]);
	/*if(isset($_POST['repeat_subjects'])){ 
	$str = str_replace(',', '', your_filter($_POST["repeat_subjects"]));
	$strlen = strlen($str);
	$edit_repeatsubj = str_split($str);
	}*/

    header( 'Content-Type: text/html; charset=utf-8' );    
}
}
function your_filter($value) {
    $newVal = trim($value);
    $newVal = htmlspecialchars($newVal);
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
   }
 
 
    
 function get_courses($program_uid){    	
		$query = "SELECT uid,coursename FROM coursemaster WHERE programmasteruid='".$program_uid."' and statusflag='A'";
		$result=mysql_query($query); 
		if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
		return $result;		
	}
	
	
function get_courses_with_semestername($program_uid){    	
		$query = "select a.uid,b.coursename, a.semester_name from semestermaster as a, coursemaster as b where program_masteruid='".$program_uid."' and a.coursemasteruid = b.uid and a.statusflag='A' and b.statusflag='A'";
		$result=mysql_query($query); 
		if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
		return $result;		
	}
	
	   
  function get_semester($program_uid,$courseuid){    	
		$query = "SELECT uid,semester_name FROM semestermaster WHERE program_masteruid='".$program_uid."' and coursemasteruid='".$courseuid."' and statusflag='A'";
		$result=mysql_query($query); 
		if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
		return $result;		
	}
	
	 function get_subjects($program_uid){    	
		$query = "SELECT a.uid,a.semestermasteruid,a.subjectcode,a.subjectname,a.credithour FROM subjectmaster as a WHERE  a.programmasteruid='".$program_uid."' and a.statusflag = 'A'";
		$result=mysql_query($query); 
		if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
		return $result;		
	}
	  function get_allfees($program_uid,$accno){    
	    
	    $accno = substr($accno,0,strlen($accno)-5);
	  	switch($program_uid){
		  	case 1:
		  	if ($accno>=4 AND $accno <= 11){			  
			  if ($_POST["semestername"] == "Ist Year" OR $_POST["semestername"] == "2nd Year" or $_POST["semestername"] == "3rd Year")
			  	$semfees = 25000;			   			  
			  else
			  	$semfees = 30000;			    
			  
			   $addfees = 1550;
			   $repeatfees = 1750; 
			}else if($accno === 12 OR $accno === 13){
			 	if ($_POST["semestername"] == "Ist Year"){	$semfees= 25000;	}else{ $semfees= 30000; }
			    $addfees = 1550;
			    $repeatfees = 1750;
			 }else if ($accno>=14 AND $accno <= 17){
			 	$semfees=35000;
			 	$addfees=1950;
			 	$repeatfees=2200;
			 }
		  		break;
		  	case 2:
		  	if ($accno>=4 AND $accno <= 11){			  
			  if ($_POST["semestername"] == "Ist Year" OR $_POST["semestername"] == "2nd Year" or $_POST["semestername"] == "3rd Year")
			  	$semfees = 25000;			   			  
			  else
			  	$semfees = 30000;			    
			  
			   $addfees = 1660;
			   $repeatfees = 1750; 
			}else if($accno === 12 OR $accno === 13){
			 	if ($_POST["semestername"] == "Ist Year"){	$semfees= 25000;	}else{ $semfees= 30000; }
			    $addfees = 1660;
			    $repeatfees = 1750;
			 }else if ($accno>=14 AND $accno <= 17){
			 	$semfees=35000;
			 	$addfees=1950;
			 	$repeatfees=2200;
			 }
		  		break;
		  	case 3:
		  	if ($accno>=4 AND $accno <= 13){			  
			   $semfees = 25000;			    			  
			   $addfees = 1550;
			   $repeatfees = 1750; 			
			 }else if ($accno>=14 AND $accno <= 17){
			 	$semfees=30000;
			 	$addfees=1800;
			 	$repeatfees=1950;
			 }
		  		break;
		  	case 4:
		  				  
			 	$semfees=25000;
			 	$addfees=1500;
			 	$repeatfees=1750;
			
		  		break;
		
		  	
		  	default:
		  		break;
		  }			
	}
	
	

	
		function update_student($uid,$formname){	
		
		$return= $addsub = array();	
		$totalfees = $semfees = $addfees = $repeatfees = 0;
		$yearname= "";
		
		$program_uid = $_POST['program'];
		$accno = $_POST['accno'];
		$sqlquery = "select coursename from coursemaster where uid ='".$_POST["study_year"]."' and statusflag='A' ";
		$result = mysql_query($sqlquery);
		while($fetchcourse = mysql_fetch_array($result)) 
			$yearname = $fetchcourse['coursename'];
		
		
		$accno = substr($accno,0,strlen($accno)-5);
		
		/*FOR EXCEPTIONAL STUDENTS*/
  		$query="SELECT semfees,addfees,repeatfees FROM `special_student` WHERE academicno  = '".$_POST['accno']."' AND statusflag='A'";
  		$result=mysql_query($query);  
    	 if(!$result):
       	die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
    	else:
		$rowsreturn = mysql_num_rows($result);
		endif;
  			
  		if ($rowsreturn === 1 ){
			while($fetchresult = mysql_fetch_array($result)) {	
			  if ($program_uid == 1 && $semester_name == 'Ist Year' ) $semfees = 25000; else $semfees = $fetchresult['semfees'];
			  $addfees = $fetchresult['addfees'];
			  $repeatfees = $fetchresult['repeatfees'];
			}
		}else{				  	
		
	  	switch($program_uid){
		  	case 1:
		  	if ($accno>=4 AND $accno <= 11){			  
			  if ($yearname == "Ist Year" OR $yearname == "2nd Year" or $yearname == "3rd Year")
			  	$semfees = 25000;			   			  
			  else
			  	$semfees = 30000;			    
			  
			   $addfees = 1550;
			   $repeatfees = 1750; 
			}else if($accno == 12 OR $accno == 13){
			 	if ($yearname == "Ist Year"){	$semfees= 25000;	}else{ $semfees= 30000; }
			    $addfees = 1550;
			    $repeatfees = 1750;
			 }else if ($accno>=14 AND $accno <= 17){
			 	$semfees=35000;
			 	$addfees=1950;
			 	$repeatfees=2200;
			 }
		  		break;
		  	case 2:
		  	if ($accno>=4 AND $accno <= 11){			  
			  if ($yearname == "Ist Year" OR $yearname == "2nd Year" or $yearname == "3rd Year")
			  	$semfees = 25000;			   			  
			  else
			  	$semfees = 30000;			    
			  
			   $addfees = 1660;
			   $repeatfees = 1750; 
			}else if($accno == 12 OR $accno == 13){
			 	if ($yearname == "Ist Year"){	$semfees= 25000;	}else{ $semfees= 30000; }
			    $addfees = 1660;
			    $repeatfees = 1750;
			 }else if ($accno>=14 AND $accno <= 17){
			 	$semfees=35000;
			 	$addfees=1950;
			 	$repeatfees=2200;
			 }
		  		break;
		  	case 3:
		  	if ($accno>=4 AND $accno <= 13){			  
			   $semfees = 25000;			    			  
			   $addfees = 1550;
			   $repeatfees = 1750; 			
			 }else if ($accno>=14 AND $accno <= 17){
			 	$semfees=30000;
			 	$addfees=1800;
			 	$repeatfees=1950;
			 }
		  		break;
		  	case 4:
		  			  
			 	$semfees=25000;
			 	$addfees=1500;
			 	$repeatfees=1750;
					  		
				break;
		
		  	
		  	default:
		  		break;
		  }			
		
		}
		
		$addsubj = $repeatsubj ="";
		$addcredit = $repeatcredit = $count = 0;
		if(isset($_POST["add_subjects"])){
		for($i = 0; $i < sizeof($_POST["add_subjects"]); $i++){
			$addcredit = $addcredit + intval(substr($_POST["add_subjects"][$i],strpos($_POST["add_subjects"][$i], '-')+1));
			$addsubj = $addsubj . substr($_POST["add_subjects"][$i],0,strpos($_POST["add_subjects"][$i], '-')) .',';
			$count = $count + 1;
		}
		$addsubj = substr($addsubj,0,-1);
		if ( $yearname == "6th Year" && $program_uid == 1) {$addfees = $count *20000; $semfees=0;	 }
		else
		$addfees = $addcredit * $addfees;
		}
		else{
		$addsubj="";	
		$addfees=0;
		}
		
		
		if(isset($_POST["repeat_subjects"])){
		for($i = 0; $i < sizeof($_POST["repeat_subjects"]); $i++){
			$repeatcredit = $repeatcredit + intval (substr($_POST["repeat_subjects"][$i],strpos($_POST["repeat_subjects"][$i], '-')+1));
			$repeatsubj = $repeatsubj . substr($_POST["repeat_subjects"][$i],0,strpos($_POST["repeat_subjects"][$i], '-')) .',';
		}		
		$repeatsubj = substr($repeatsubj,0,-1);
		$repeatfees = $repeatcredit * $repeatfees;
		}
		else{
			$repeatsubj= "";
			$repeatfees=0;
		}
		if ( $_POST['student_type'] == 7) $semfees = 0;
		if ( $_POST['student_type'] == 6) $semfees = $semfees + 1000;
		if ( $_POST['schedule'] == 8) { $addfees=0; $repeatfees=0; }
		
		
		
		$totalfees = $semfees + $addfees + $repeatfees;
		foreach($_POST as $key => $value) {    
		if ($key !="add_subjects"){
	    $_POST[$key] = your_filter($value);
	    $return[$key]= $_POST[$key];
	    }
	}
	
	  if ($formname === "save_accaffair"){
	  $sqlquery = "UPDATE registration as a set a.programmasteruid = '". $return["program"] . "',a.coursemasteruid = '". $return["study_year"] . "',a.semesteruid = '". $return["semesteruid"] . "',a.year = '2018',a.email='". $return["email"] ."',a.contactno='". $return["contactno"] ."',a.otherno='". $return["otherno"] ."',a.studenttype_uid = '". $return["student_type"] . "',a.scheduleuid = '". $return["schedule"] . "',a.isno_objection = '". $return["noobjection"] . "',a.add_subjects = '". $addsubj . "',a.add_credits = '". $addcredit . "',a.repeat_subjects = '". $repeatsubj . "',a.repeat_credits = '". $repeatcredit . "',a.studaffairnotes='".$return["studaffairnotes"]."', a.academic_officer = '". ucfirst($_SESSION["username"]) . "',a.academic_date = '". date('d/m/Y H:i:s') . "',a.semester_fees = '". $totalfees . "',a.adjustfees = '',a.notes = '',a.register_officer = '',a.register_date = '',a.isfullypaid = '',a.concessionfees = '0',a.totalfees = 0,a.vatamt=0,a.notcoveredfees = 0,a.feespaid = 0,a.finance_officer = '',a.fullypaid_date = '',a.isapproved = '',a.dean_name = '',a.approval_date = '',a.completestatus = '1',a.msgcount = '0'";	 
	  
	  $sqlquery = $sqlquery . ",a.muser = '". $_SESSION["useruid"] . "',a.mwhen = '". date('Y/m/d H:i:s') . "' where a.uid='". $uid ."' and a.statusflag='A'";		
	  	
	  }elseif ($formname === "save_registration"){
	  	
	  	$sqlquery = "UPDATE registration as a set a.scholartypeuid = '". $return["scholar_avail"] . "',a.semester_fees = '". $return["fees_amnt"] . "',a.vatamt='". round($return["fees_amnt"] * ($return["vatpercent"]/100),2). "',a.adjustfees = '". $return["adjustfees"] . "',a.notes = '". $return["notes"] . "',isapproved = 'P',a.register_officer = '". $_SESSION["username"] . "',a.register_date = '". date('d/m/Y H:i:s') . "',a.isfullypaid = '',a.concessionfees = 0,a.totalfees = 0,a.notcoveredfees = 0,a.feespaid = 0,a.finance_officer = '',a.fullypaid_date = '',a.isapproved = '',a.dean_name = '',a.approval_date = '',a.completestatus = '2',a.msgcount = '0'";		  
	  	  $sqlquery = $sqlquery . ",a.muser = '". $_SESSION["useruid"] . "',a.mwhen = '". date('Y/m/d H:i:s') . "' where a.uid='". $uid ."' and a.statusflag='A'";		
	  	
	  
	}elseif ($formname === "save_finance"){
	  	
	 
	  	$json = array();
	  	$sqlquery = "UPDATE registration as a set a.concessionfees = '". $return["concessionfees"] . "',a.totalfees = '". $return["totalfees"] . "',a.feespaid = '". $return["feespaid"] . "',a.finance_officer = '". $_SESSION["username"] . "',a.fullypaid_date = '". date('d/m/Y H:i:s') . "',a.isapproved = '". $return["register"] . "',a.dean_name = 'DR.RASHAD H.KASHGARI'";	
	  	 if ($return["completestatus"] == 2){
	  	$sqlquery = $sqlquery . ",a.completestatus = '3'";
	  }
	  
	 if ($return["register"] == "Y" && $return["invoiceno"] == ""){
	  	
	  	$invoiceno ="";
	  	$sqlinvno = "SELECT max(invoiceno)+1 invoiceno FROM registration ";
		$invresult = mysql_query($sqlinvno);
		while($fetchcourse = mysql_fetch_array($invresult)) 
			$invoiceno = $fetchcourse['invoiceno'];
			
	  	$sqlquery = $sqlquery . ",a.invoiceno = '" .$invoiceno. "'";
	  }	  
	  
	  	  $sqlquery = $sqlquery . ",a.muser = '". $_SESSION["useruid"] . "',a.mwhen = '". date('Y/m/d H:i:s') . "' where a.uid='". $uid ."' and a.statusflag='A'";		
	  	
	  	
	  }
	$result = mysql_query($sqlquery);
	if($result){
		if ($formname === "save_finance"){
			
		/*$sqlquery1 = "update academicdetails set prevpendingfees= '". $return["pendingfees"] . "' where academicno='". $return["accno"] . "'";
	  	$result1 = mysql_query($sqlquery1);*/
	  	
	  	if ($return["msgcount"] == 0){
		
	  	 if ($return["scholar_avail"] == 17 || $return["scholar_avail"] == 16){
		if ($return["pendingfees"] == 0){
			$_POST['content'] = "Greetings Your registration was examined and is complete Please report to registration dept to collect the ID";
		}elseif($return["pendingfees"] > 0){
			$_POST['content'] = "Greetings Your registration was examined Please report to finance dept to pay the pending fees:".$return["pendingfees"]." SR within 48Hrs";
		}
	}elseif($return["scholar_avail"] == 18){
		$return['grandtotal'] =$return["semesterfees"]+$return['vatamt'];
		if ($return["pendingfees"] == 0){
			if ($return["nationality"] == "Saudi"){
				$_POST['content'] = "Greetings, Your registration was examined.Please report to finance dept. to pay the semester fees:".$return["grandtotal"]." SR within 48Hrs.";
			}else{
				$_POST['content'] = "Greetings, Your registration was examined.Please report to finance dept. to pay the semester fees:".$return["grandtotal"]." SR(Incl VAT 5%) within 48Hrs.";
			}
			
		}elseif($return["pendingfees"] > 0){
			if ($return["nationality"] == "Saudi"){
			$_POST['content'] = "Greetings, Your registration was examined. Please report to finance dept. to pay the semester fees:".$return["grandtotal"]."SR and pending fees:".$return["pendingfees"]." SR within 48Hrs.";
			}else{
				$_POST['content'] = "Greetings, Your registration was examined. Please report to finance dept. to pay the semester fees:".$return["grandtotal"]."SR(Incl VAT 5%) and pending fees:".$return["pendingfees"]." SR within 48Hrs.";
			}
		}
		
	}

//$_POST['content'] =  "Greetings Your registration was examined Please report to finance dept to pay the pending fees:".$_POST["pendingfees"]." SR within 48Hrs";
		IF($_POST['student_type'] == 6) {
			$_POST['content'] = $_POST['content']." The fees of the carried subject is not finalized and the actual fees will be charged later";
		}
		
		$json = sendSMS('osama03','a325646',$_POST['content'],$return['contactno'],'IBN SINA-');
		$json = substr($json, 6, 8);
		
    if ($json == 100){
		$msgcount = $return["msgcount"]+1;
		$query = "update registration set msgcount= ".$msgcount." where uid= ".$return["reguid"]." and statusflag='A'";	
		$result=mysql_query($query); 
	}
	  	
	 } //msg finish
	 } // Only after Finance Finish	
	return TRUE;
	}else{
	echo "MYSQL Error : ".die(mysql_error());	
	return FALSE;
	}
	}
        
    
function sendSMS($oursmsusername,$oursmspassword,$messageContent,$mobileNumber,$senderName)
{
$user = urlencode($oursmsusername);
$password = urlencode($oursmspassword);
$sendername = urlencode($senderName);
$text = urlencode($messageContent);
$to = urlencode($mobileNumber);
// auth call

//php
//$url = "http://www.oursms.net/api/sendsms.php?username=osama03&password=a325646&numbers=0576814364&message=test&sender=Ibn Sina&unicode=E&return=full";
$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=full";

//?????? ?????? json
//$url = "http://www.oursms.net/api/sendsms.php?username=".$user."&password=".$password."&numbers=".$to."&message=".$text."&sender=".$sendername."&unicode=E&return=json";
// ?????? ?????? xml
//$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=xml";
// ?????? ?????? string 
//$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E";
// Call API and get return message
//$handle = fopen($url,"r");
$ret = file_get_contents($url);
return nl2br($ret);

}
    
    
    
    
    function get_referencevalue($domain_name){    	
		$query = "SELECT a.uid,a.description FROM referencevalue as a, referencedomain as b WHERE a.referencedomainuid = b.uid and b.domaincode= '".$domain_name."' AND b.statusflag='A' order by a.displayorder";
		$result=mysql_query($query); 
		if(!$result):
		       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
 	    else:
				$rowsreturn = mysql_num_rows($result);
		endif;				
		return $result;		
	}
    
    function edit_student($reguid){
	$query = "select b.academicno,b.engname,b.prevpendingfees,b.student_typeuid stud_type,b.idno,getReferenceValueByUID(b.nationalityuid)nationality,a.* from registration AS a, academicdetails as b where a.academicdetailsuid = b.uid AND a.statusflag='A' and b.statusflag='A' AND a.uid = ".$reguid;
	$result=mysql_query($query);    
    if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    }else{
		$rowsreturn = mysql_num_rows($result);
	}
	
	if($rowsreturn === 1){
		while($fetchresult = mysql_fetch_array($result)) {
			$_POST['accno'] = your_filter($fetchresult['academicno']);
			$_POST['accuid'] = your_filter($fetchresult['academicdetailsuid']);
			$_POST['reguid'] = your_filter($fetchresult['uid']);
			$_POST['invoiceno'] = your_filter($fetchresult['invoiceno']);
			$_POST['studname'] = your_filter($fetchresult['engname']);
			$_POST['year'] = your_filter($fetchresult['year']);
			$_POST['program'] = your_filter($fetchresult['programmasteruid']);
			$_POST['coursemasteruid'] = your_filter($fetchresult['coursemasteruid']);
			$_POST['semesteruid'] = your_filter($fetchresult['semesteruid']);
			$_POST['email'] = your_filter($fetchresult['email']);
			$_POST['contactno'] = your_filter($fetchresult['contactno']);
			$_POST['otherno'] = your_filter($fetchresult['otherno']);		
			$_POST['idno'] = your_filter($fetchresult['idno']);
			$_POST['nationality'] = your_filter($fetchresult['nationality']);				
			$_POST['student_type'] = your_filter($fetchresult['studenttype_uid']);
			$_POST['schedule'] = your_filter($fetchresult['scheduleuid']);
			$_POST['noobjection'] = your_filter($fetchresult['isno_objection']);
			$_POST['add_subjects'] = your_filter($fetchresult['add_subjects']);
			$_POST['add_credits'] = your_filter($fetchresult['add_credits']);
			$_POST['repeat_subjects'] = your_filter($fetchresult['repeat_subjects']);
			$_POST['repeat_credits'] = your_filter($fetchresult['repeat_credits']);
			$_POST['studaffairnotes'] = your_filter($fetchresult['studaffairnotes']);
			$_POST['academicofcname'] = your_filter($fetchresult['academic_officer']);
			$_POST['academic_date'] = your_filter($fetchresult['academic_date']);
			$_POST['scholar_avail'] = your_filter($fetchresult['scholartypeuid']);
			$_POST['fees_amnt'] = your_filter($fetchresult['semester_fees']);
			$_POST['adjustfees'] = your_filter($fetchresult['adjustfees']);
			$_POST['notes'] = your_filter($fetchresult['notes']);
			$_POST['register_ofcname'] = your_filter($fetchresult['register_officer']);
			$_POST['register_date'] = your_filter($fetchresult['register_date']);
			
			$_POST['pendingfees'] = your_filter($fetchresult['prevpendingfees']);
			$_POST['concessionfees'] = your_filter($fetchresult['concessionfees']);
			$_POST['finance_officer'] = your_filter($fetchresult['finance_officer']);
			$_POST['totalfees'] = your_filter($fetchresult['totalfees']);
			$_POST['vatamt'] = your_filter($fetchresult['vatamt']);
			$_POST['feespaid'] = your_filter($fetchresult['feespaid']);
			$_POST['recvamntdate'] = your_filter($fetchresult['fullypaid_date']);
			$_POST['register'] = your_filter($fetchresult['isapproved']);
			$_POST['dean_name'] = your_filter($fetchresult['dean_name']);	
			$_POST['completestatus'] = your_filter($fetchresult['completestatus']);							
			$_POST['msgcount'] = your_filter($fetchresult['msgcount']);							
			$_POST['stud_type'] = your_filter($fetchresult['stud_type']);
			
			$total =0;
			if(isset($_POST['scholar_avail'])){
				if($_POST['scholar_avail'] == 18) {
				$total = get_totalfees($_POST['fees_amnt'],$_POST['vatamt'],$_POST['pendingfees'],$_POST['concessionfees']);	
				}else $total = get_totalfees(0,0,$_POST['pendingfees'],$_POST['concessionfees']);	
			}
			$_POST['totalfees'] = $total; 
		}
	}
}


function get_totalfees($semfees,$vatamt,$pendingfees,$concessionfees){
	$totalfees = ($semfees + $vatamt + $pendingfees)-$concessionfees;
	return $totalfees;
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Edit Student</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/bootstrap-select.min.css">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
     <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/custom_css/navbar.css" rel="stylesheet">
    <link href="dist/custom_css/daterangepicker.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">IBN Sina College</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="#">Home</a></li>
	     <li><a href="student_search.php">Search</a></li>
             <li><a href="reports.php">Reports</a></li>
	     <li><a href="register.php">Student Registration</a></li>
	     <li><a href="student_notice.php">Student Notices</a></li>
	     <li><a href="studentupdateupdate.php">Student ID</a></li>
	     <li><a href="search_archieve.php">Archive</a></li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php if(isset($_SESSION['username'])){ ?>
			<li class="active"><a href="#">Welcome <?php if(isset($_SESSION['username'])) echo htmlspecialchars(ucfirst($_SESSION['username'])); ?> <span class="sr-only">(current)</span></a></li>              	
			<li><a href="signout.php">Sign Out</a></li>
			<?php } else { echo '<li><a href="#">Signin</a></li>'; } ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

	 
      <!-- Main component for a primary marketing message or call to action -->
       <div class="panel panel-primary">
  		<div class="panel-heading">
    		<h3 class="panel-title">Search Filter</h3>
  		</div>
  		<div class="panel-body">    		
  	<?php if($message != ""): ?>	 
  	<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Note:</strong><?php echo $message; ?> </div> <?php endif; ?>
  <form id="academic_affairs" method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" accept-charset="utf-8">
  <h4 class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-user"></span> Student Information <?php if(isset($_POST['completestatus'])){if($_POST['completestatus'] == 0){echo '<span class="glyphicon glyphicon-remove"></span>';} else { echo '<span class="glyphicon glyphicon-ok"></span>';}} ?></h4>
  <div class="form-group">
    <label for="accno" class="col-sm-2 control-label">Academic Number</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="accno" name="accno" placeholder="Give Academic Number" value="<?php if(isset($_POST['accno'])) echo htmlspecialchars($_POST['accno']); ?>">
      <input type="hidden" id="accuid" name="accuid" value="<?php if(isset($_POST['accuid'])) echo htmlspecialchars($_POST['accuid']); ?>">	  
      <input type="hidden" name="reguid" value="<?php if(isset($_POST['reguid'])) echo $_POST['reguid']; ?>">
    </div>
    
    <label for="studname" class="col-sm-1 control-label">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" name="studname" id="studname" placeholder="Give Student Name" value="<?php if(isset($_POST['studname'])) echo htmlspecialchars($_POST['studname']); ?>">
    </div>   
  </div>
	  
  <div class="form-group">
    <label for="program" class="col-sm-2 control-label">Program &amp; Year</label>
    <div class="col-sm-2">
      <select class="form-control" name="program" id="program">	
      <option value="">Select Program</option>
      <?php if(!empty($program)){
	  	foreach($program as $key => $value){
		   if($value["uid"] == htmlspecialchars($_POST["program"])){
		   	echo '<option value="'.$value["uid"].'" selected>'.$value["description"].'</option>';}
		   	else {
			echo '<option value="'.$value["uid"].'">'.$value["description"].'</option>';}
		   }		
		}	  
     ?> 	      
       </select>
    </div>
    
   
    <div class="col-sm-1 col-md-1">
    <input type="text" class="form-control" name="year" value="2018" disabled>
    </div>
    
    <label for="email" class="col-sm-1 control-label">Email</label>
    <div class="col-sm-4">
      <input type="email" class="form-control" name="email" placeholder="Give Valid Email" value="<?php if(isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>">
    </div>
    </div>
	  
	   <div class="form-group">
    <label for="contactno" class="col-sm-2 control-label">Contact No</label>
    <div class="col-sm-3">
      <input type="tel" class="form-control" name="contactno" id="contactno" placeholder="Give Mobile Number" value="<?php if(isset($_POST['contactno'])) echo htmlspecialchars($_POST['contactno']); ?>">
    </div>
    
     <label for="otherno" class="col-sm-1 control-label">Other No</label>
    <div class="col-sm-4">
      <input type="tel" class="form-control" name="otherno" placeholder="Other Contact Number" value="<?php if(isset($_POST['otherno'])) echo htmlspecialchars($_POST['otherno']); ?>">
    </div>
   
	  </div>
	  
	<div class="form-group">
    <label for="contactno" class="col-sm-2 control-label">Iqama No</label>
    <div class="col-sm-3">
    <input type="text" class="form-control" name="idno" id="idno" value="<?php if(isset($_POST['idno'])) echo htmlspecialchars($_POST['idno']); ?>">
    </div>
    
     <label for="otherno" class="col-sm-1 control-label">Nationality</label>
    <div class="col-sm-4">
    <input type="text" class="form-control" name="nationality" id="nationality" value="<?php if(isset($_POST['nationality'])) echo htmlspecialchars($_POST['nationality']);  ?>" readonly>
    </div>
	</div>
	 
	<h4 class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-duplicate"></span> Academic Affairs &nbsp; <?php if(isset($_POST['completestatus'])){if($_POST['completestatus'] == 0){echo '<span class="glyphicon glyphicon-remove"></span>';} else { echo '<span class="glyphicon glyphicon-ok"></span>';}} ?></h4>
	
	<div class="form-group">
    <label for="study_year" class="col-sm-2 control-label">Study Year</label>
    <div class="col-sm-3">
      <select class="form-control" name="study_year" id="study_year">				                
       		<option value="">Select Student Type</option>
			<?php if(!empty($coursemaster)){
				  	foreach($coursemaster as $key => $value){
					   if($value["uid"] == htmlspecialchars($_POST["coursemasteruid"])){
					   	echo '<option value="'.$value["uid"].'" selected>'.$value["coursename"].'</option>';}
					   	else {
						echo '<option value="'.$value["uid"].'">'.$value["coursename"].'</option>';}
					   }		
					}	  
			 ?> 	   
       </select>
    </div>
    
     <label for="semesteruid" class="col-sm-1 control-label">Semester</label>    
    <div class="col-sm-4">
      <select class="form-control" id="semesteruid" name="semesteruid">				                
       		<option value="">Select Semester</option>
			<?php if(!empty($semester_name)){
				  foreach($semester_name as $key => $value){
				   if($value["uid"] === htmlspecialchars($_POST["semesteruid"])){
				    	echo '<option value="'.$value["uid"].'" selected>'.$value["semester_name"].'</option>';}
					   	else {
						echo '<option value="'.$value["uid"].'">'.$value["semester_name"].'</option>';}
					   }		
				  }	  
			 ?> 	   
       </select>
    </div>
    </div>
    
	<div class="form-group">
    <label for="student_type" class="col-sm-2 control-label">Student Type</label>
    <div class="col-sm-3">
    	     
    	      	  <select class="form-control" name="student_type" id="student_type" >
       		<option value="">Select Student Type</option>
			<?php if(!empty($student_type)){
				  	foreach($student_type as $key => $value){
					   if($value["uid"] == htmlspecialchars($_POST["student_type"])){
					   	echo '<option value="'.$value["uid"].'" selected>'.$value["description"].'</option>';}
					   	else {
						echo '<option value="'.$value["uid"].'">'.$value["description"].'</option>';}
					   }		
					}	  
			 ?> 	   
       </select>
    </div>
    
     <label for="schedule" class="col-sm-1 control-label">Schedule</label>    
    <div class="col-sm-4">
      <select class="form-control" name="schedule" id="schedule">				                
       		<option value="">Select Schedule</option>
			<?php if(!empty($schedule_type)){
				  foreach($schedule_type as $key => $value){
				   if($value["uid"] == htmlspecialchars($_POST["schedule"])){
				    	echo '<option value="'.$value["uid"].'" selected>'.$value["description"].'</option>';}
					   	else {
						echo '<option value="'.$value["uid"].'">'.$value["description"].'</option>';}
					   }		
				  }	  
			 ?> 	   
       </select>
    </div>
    </div>						
	
							
	
	
	<div class="form-group">
    <label for="subjects" class="col-sm-2 control-label"> Add Subjects </label>
    <div class="col-sm-5">
    <?php if(isset($_POST['schedule']))
    {
		if($_POST['schedule'] == 9 || ($_POST['student_type'] == 6 && $_POST['schedule'] == 8)){
			echo '<select class="show-tick form-control" name="add_subjects[]" id="add_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple>';
		}else{
		echo '<select class="show-tick form-control" name="add_subjects[]" id="add_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled>';	
		}
		
	}
    else{
		echo '<select class="show-tick form-control" name="add_subjects[]" id="add_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled>';
	}
   
   if(!empty($allsemester) AND !empty($subjectmaster)){
			  	foreach($allsemester as $key => $value){
					echo '<optgroup label="'.$value["coursename"].'" data-subtext="'.$value["semester_name"].'">';
					foreach($subjectmaster as $key1 => $value1){
						if($value["uid"] === $value1["semestermasteruid"]){
						 $isfound = false;
						 for($i = 0; $i < count($edit_addsubj); $i++){
						 	if ($edit_addsubj[$i] === $value1["uid"]){ $isfound= true;
						 	$ss= $value1["uid"]."/".$edit_addsubj[$i];
						 	break;  }else
						 	 { $isfound = false;}
						 }												
						if($isfound):
						echo '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'" selected>'.$value1["subjectname"].'</option>';
						else:
						echo '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'">'.$value1["subjectname"].'</option>';	
						endif;						
						}else{							
						}
					}
					echo '</optgroup>';				   
				   }		
				}	  
		 ?> 	            		            			                     
       </select>
    </div>
    
     <label for="add_credit" class="col-sm-1 control-label">Credits</label>    
    <div class="col-sm-2">
      <input type="number" class="form-control" name="add_credits" id="add_credits"  value="<?php if(isset($_POST['add_credits'])) echo htmlspecialchars($_POST['add_credits']); ?>" disabled>
    </div>
    </div>	
    
    <div class="form-group">
    <label for="repeat_subjects" class="col-sm-2 control-label">Repeat Subjects </label>
    <div class="col-sm-5">
    <?php if(isset($_POST['schedule']))
    {
		if($_POST['schedule'] == 9){
			echo '<select class="show-tick form-control" name="repeat_subjects[]" id="repeat_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple>';
		}
		else {
			echo '<select class="show-tick form-control" name="repeat_subjects[]" id="repeat_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled>';
		}
		
	}else{
		echo '<select class="show-tick form-control" name="repeat_subjects[]" id="repeat_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled>';
	}
	?>
      	
		 <?php if(!empty($allsemester) AND !empty($subjectmaster)){
			  	foreach($allsemester as $key => $value){
					echo '<optgroup  label="'.$value["coursename"].'" data-subtext="'.$value["semester_name"].'">';
					foreach($subjectmaster as $key1 => $value1){
						if($value["uid"] === $value1["semestermasteruid"]){		
						$isfound = false;	
						 for($i = 0; $i < count($edit_repeatsubj); $i++){
						 	if ($edit_repeatsubj[$i] === $value1["uid"]){ $isfound= true;
						 	$ss= $value1["uid"]."/".$edit_repeatsubj[$i]; 
						 	break;  }else
						 	 { $isfound = false;	}
						 }	
						if($isfound):										
						echo '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'" selected>'.$value1["subjectname"].'</option>';
						else:
						echo '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'">'.$value1["subjectname"].'</option>';
						endif;
						}else{							
						}
					}
					echo '</optgroup>';				   
				   }		
				}	  
		 ?> 	            		            			                     
       </select>
    </div>
    
     <label for="repeat_credit" class="col-sm-1 control-label">Credits</label>    
    <div class="col-sm-2">
      <input type="number" class="form-control" name="repeat_credits" id="repeat_credits"  value="<?php if(isset($_POST['repeat_credits'])) echo htmlspecialchars($_POST['repeat_credits']); ?>" disabled>
    </div>
    </div>	
    	 
    <div class="form-group">	
     <label for="notes" class="col-sm-2 control-label">Notes</label>
    <div class="col-sm-5">
      <textarea class="form-control" name="studaffairnotes" id="studaffairnotes" placeholder="Notes from Academic Affairs"><?php if(isset($_POST['studaffairnotes'])) echo htmlspecialchars($_POST['studaffairnotes']); ?></textarea>
    </div>
    </div>
	
	 <div class="form-group">		
		 <div class="checkbox col-sm-offset-1">
		   	<label style="font-weight: 700;">
		   	<input type="checkbox" id="noobjection" name="noobjection" <?php if(isset($_POST['noobjection'])){ if($_POST['noobjection'] == "Y") echo "checked"; }  ?> value="Y"  />No objection to register the student in the study year 2018 - 2019
		   	</label> &nbsp;&nbsp;
		</div>		  
    </div>
    
    	<div class="form-group">
    <label for="academicofcname" class="col-sm-2 control-label">Officer Name</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="academicofcname" placeholder="Give Officer Name" value="<?php if(isset($_POST['academicofcname'])){ if($_POST['academicofcname'] !== '') echo htmlspecialchars($_POST['academicofcname']); else echo ucfirst($_SESSION["username"]); } ?>" disabled>
    </div>
    
     <label for="academic_date" class="col-sm-1 control-label">Date</label>
    <div class="col-sm-2">
      <input type="text" class="form-control" name="academic_date" placeholder="Select Date" value="<?php if(isset($_POST['academic_date'])) echo htmlspecialchars($_POST['academic_date']); ?>" disabled >
    </div>
    
    <div class="col-sm-1 col-md-1">
			    	<button type="submit"  class="btn btn-primary search_acc" id="save_accaffair" name="submit"  value="save_accaffair" <?php if(substr($_SESSION['adminlevel'],0,1) === '0'){ if($_POST['completestatus'] == 0){ if(substr($_SESSION['userlevel'],0,1) === '0') echo 'disabled'; }else echo 'disabled'; } ?>>
			 		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp; Save
					</button>
				</div>
				<!--<div class="col-sm-2 col-md-2">
			    	<button type="submit" class="btn btn-primary search_acc" id="cancel_accaffair" >
			 		<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>&nbsp; Cancel
					</button>
				</div>-->
	  </div>
	  <input type="hidden" name="completestatus" value="<?php if(isset($_POST['completestatus'])) echo $_POST['completestatus']; ?>"/>
	</form>	
	
	<form id="registration" method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" accept-charset="utf-8">				
	   <h4 class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-paste"></span> Registration <?php if(isset($_POST['completestatus'])){if($_POST['completestatus'] >= 2){echo '<span class="glyphicon glyphicon-ok"></span>';} else { echo '<span class="glyphicon glyphicon-remove"></span>';}} ?></h4>
	   	<div class="form-group">						
	   		<label for="scholar_avail" class="col-sm-4 control-label">After reviewing the student data above:</label>
	   			<div class="col-sm-8">								
	   				<div class="radio">
	   					
	   					<label style="font-weight: 700;">	   					   					
	   					<input type="radio" id="hr_avail" name="scholar_avail" class="scholar_avail" <?php if(isset($_POST['scholar_avail'])){ if($_POST['scholar_avail'] == 16) echo "checked"; }  ?> value="16" required />موارد 
	   					</label> &nbsp;&nbsp;
	   					<label style="font-weight: 700;">	   					   					
	   					<input type="radio" id="scholar_avail" name="scholar_avail" class="scholar_avail" <?php if(isset($_POST['scholar_avail'])){ if($_POST['scholar_avail'] == 17) echo "checked"; }  ?> value="17" required />Scholarship Available 
	   					</label> &nbsp;&nbsp;
						<label style="font-weight: 700;">
						<input type="radio"  id="scholar_notavail" name="scholar_avail" class="scholar_avail" <?php if(isset($_POST['scholar_avail'])){ if($_POST['scholar_avail'] == 18) echo "checked"; }  ?> value="18" />Student should pay the semester fees :
						</label>										
						<input type="text" name="fees_amnt" id="fees_amnt"  value="<?php if(isset($_POST['fees_amnt'])) echo htmlspecialchars($_POST['fees_amnt']); ?>" readonly> 
						<input type="hidden" id="fees_amnt1" value="<?php if(isset($_POST['fees_amnt'])) echo htmlspecialchars($_POST['fees_amnt']); ?>"> 
					</div>									
				</div>
		</div>
		 <input type="hidden" name="completestatus" value="<?php if(isset($_POST['completestatus'])) echo $_POST['completestatus']; ?>">
	   <input type="hidden" name="reguid" value="<?php if(isset($_POST['reguid'])) echo $_POST['reguid']; ?>">
	     <input type="hidden" name="vatpercent" value="<?php if($_POST['nationality']=="Saudi"){ echo '0'; } else { echo $_SESSION['vatpercent'];} ?>"/>
	     
	   <div class="form-group">
    <label for="adjustfees" class="col-sm-2 control-label">Adjust Fees</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="adjustfees" name="adjustfees" placeholder="Add/Reduce Fees" value="<?php if(isset($_POST['adjustfees'])) echo htmlspecialchars($_POST['adjustfees']); ?>">
      
       <input type="hidden" class="form-control" id="adjustfees1" value="<?php if(isset($_POST['adjustfees'])) echo htmlspecialchars($_POST['adjustfees']); ?>">
    </div>
    
     <label for="notes" class="col-sm-1 control-label">Notes</label>
    <div class="col-sm-5">
      <textarea class="form-control" name="notes" id="notes" placeholder="Write Notes to Finance Department"><?php if(isset($_POST['notes'])) echo htmlspecialchars($_POST['notes']); ?></textarea>
    </div>
   				
				
	  </div>
	  
	  	<div class="form-group">
    <label for="register_ofcname" class="col-sm-2 control-label">Officer Name</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="register_ofcname" placeholder="Give Officer Name" value="<?php if(isset($_POST['register_ofcname'])){  if ($_POST['register_ofcname'] !== '') echo htmlspecialchars($_POST['register_ofcname']); else echo ucfirst($_SESSION["username"]); } ?>" disabled>
    </div>
    
     <label for="register_date" class="col-sm-1 control-label">Date</label>
    <div class="col-sm-2">
      <input type="text" class="form-control" name="register_date" placeholder="Select Date" value="<?php if(isset($_POST['register_date'])) echo htmlspecialchars($_POST['register_date']); ?>" disabled>
    </div>
   				<div class="col-sm-1 col-md-1">
			    	<button type="submit"  class="btn btn-primary search_acc" id="save_registration" name="submit"  value="save_registration" <?php if($_POST['completestatus'] >= 1){ if(substr($_SESSION['adminlevel'],1,1) === '0'){ if($_POST['completestatus'] == 1){ if(substr($_SESSION['userlevel'],1,1) === '0') echo 'disabled'; }else echo 'disabled'; } } else echo 'disabled';?>>
			 		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp; Save
					</button>
				</div>
				
				<div class="col-sm-1 col-md-1">				
			    	<a href=""   class="btn btn-primary printme" <?php if($_POST['completestatus'] == 3){  if(substr($_SESSION['userlevel'],4,1) === '0') echo 'disabled'; } else echo 'disabled'; ?>>
			 		<span class="glyphicon glyphicon-phone" aria-hidden="true"></span>&nbsp; Print
					</a>
				</div>

	<div class="col-sm-1 col-md-1">				
			    	<a href=""   class="btn btn-primary printministry" <?php if($_POST['completestatus'] < 3)  echo 'disabled';  ?>>
			 		<span class="glyphicon glyphicon-book" aria-hidden="true"></span>&nbsp; Print Subjects
					</a>
				</div>


				
	  </div>
	  <input type="hidden" name="completestatus" value="<?php if(isset($_POST['completestatus'])) echo $_POST['completestatus']; ?>"/>
	
	</form>
	<form id="financedepartment" method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" accept-charset="utf-8">	  
	  <h4 class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-hourglass"></span> Financial Department <?php if(isset($_POST['completestatus'])){if($_POST['completestatus'] >= 3){echo '<span class="glyphicon glyphicon-ok"></span>';} else { echo '<span class="glyphicon glyphicon-remove"></span>';}} ?></h4>
	   <input type="hidden" name="reguid" id="reguid" value="<?php if(isset($_POST['reguid'])) echo $_POST['reguid']; ?>">
	   
	   
	   
	   <input type="hidden" name="accno" value="<?php if(isset($_POST['accno'])) echo htmlspecialchars($_POST['accno']); ?>">
	   <input type="hidden" name="student_type" value="<?php if(isset($_POST['student_type'])) echo htmlspecialchars($_POST['student_type']); ?>">
	  <div class="form-group">
			<!--<div class="checkbox col-sm-1">
		  <label><input type="checkbox" value="Y">Not Covered</label>
		</div>-->
	  	<label for="pendingfees" class="col-sm-2 control-label" >Pending Fees</label>
	  	
    <div class="col-sm-2" style="width: 14%;">
     
      <input type="text" class="form-control" name="pendingfees"  id="pendingfees"  placeholder="Previous Pending Fees" value="<?php if(isset($_POST['pendingfees'])) echo $_POST['pendingfees']; ?>">
    </div>
    
    	  	<label for="concessionfees" class="col-sm-1 control-label">Concession</label>
    <div class="col-sm-2">
      <input type="number" class="form-control" name="concessionfees"  id="concessionfees" placeholder="Concession Fees (Optional)" value="<?php if(isset($_POST['concessionfees'])) echo htmlspecialchars($_POST['concessionfees']); ?>">
    </div>
    
    <label for="vatamt" class="col-sm-1 control-label">VAT</label>
    <div class="col-sm-2" style="width: 11%;">
      <input type="number" class="form-control" name="vatamt" id="vatamt"  value="<?php if(isset($_POST['vatamt'])) echo htmlspecialchars($_POST['vatamt']); ?>" readonly>
    </div>
    
    <label for="totalfees" class="col-sm-1 control-label">Total</label>
    <div class="col-sm-2">
      <input type="number" class="form-control" name="totalfees" id="totalfees" placeholder="Total Fees" value="<?php if(isset($_POST['totalfees'])) echo htmlspecialchars($_POST['totalfees']); ?>" readonly>
    </div>
	  </div>
	   <div class="form-group">
<!--    <label for="finance_recvamnt" class="col-sm-3 control-label">Received the full amount</label>
    <div class="col-sm-2">
      <select class="form-control" id="finance_recvamnt" name="finance_recvamnt" >		      	
       		<?php if(!empty($yes_no)){
				  foreach($yes_no as $key => $value){
				   if($value["uid"] == htmlspecialchars($_POST["finance_recvamnt"])){
				    	echo '<option value="'.$value["uid"].'" selected>'.$value["description"].'</option>';}
					   	else {
						echo '<option value="'.$value["uid"].'">'.$value["description"].'</option>';}
					}		
				  }	  
			 ?>
       </select>
    </div>-->
    
    	  	
    
    <label for="totalfees" class="col-sm-2 control-label " >Paid Fees</label>
    <div class="col-sm-2">
      <input type="number" class="form-control" name="feespaid" id="feespaid" placeholder="Paid Fees" value="<?php if(isset($_POST['feespaid'])) echo htmlspecialchars($_POST['feespaid']); ?>" >
    </div>
    
    
				
				
    
	  </div>
	  	   	<div class="form-group">						
	   		<label for="register" class="col-sm-5 control-label">After checking the full details above mentioned, we recommend:</label>
	   			<div class="col-sm-7 ">								
	   				<div class="radio">
	   					<label style="font-weight: 700;">
	   					<input type="radio"  name="register" id="register" <?php if(isset($_POST['register'])){ if($_POST['register'] == "Y") echo "checked"; }  ?> value="Y" required />Register
	   					</label> &nbsp;&nbsp;
						<label style="font-weight: 700;">
						<input type="radio"  name="register" id="not_register" <?php if(isset($_POST['register'])){ if($_POST['register'] == "N") echo "checked"; }  ?> value="N" />Not Register
						</label>&nbsp;&nbsp;
						<label style="font-weight: 700;">
						<input type="radio"  name="register" id="pending" <?php if(isset($_POST['register'])){ if($_POST['register'] == "P") echo "checked"; }  ?> value="P" /> Pending
						</label>										
						
					</div>									
				</div>
		</div>
	  
	  <div class="form-group">
	  	<label for="finance_ofcname" class="col-sm-2 control-label">Officer Name</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="finance_ofcname" placeholder="Give Officer Name" value="<?php if(isset($_POST['finance_officer'])){  if ($_POST['finance_officer'] !== '') echo htmlspecialchars($_POST['finance_officer']);  else echo ucfirst($_SESSION["username"]); } ?>" disabled>
    </div>
    
         <label for="recvamntdate" class="col-sm-1 control-label">Date</label>
    <div class="col-sm-2">
      <input type="text" class="form-control"  name="recvamntdate" placeholder="Select Date" value="<?php if(isset($_POST['recvamntdate'])) echo htmlspecialchars($_POST['recvamntdate']); ?>" disabled>
    </div>
 <div class="col-sm-1 col-md-1">
			    	<button type="submit"  class="btn btn-primary search_acc" id="save_finance" name="submit"  value="save_finance" <?php if($_POST['completestatus'] >= 2){ if(substr($_SESSION['adminlevel'],2,1) === '0'){ if($_POST['completestatus'] >= 2){ if(substr($_SESSION['userlevel'],2,1) === '0') echo 'disabled'; }else echo 'disabled'; } }else echo 'disabled'; ?>>
			 		<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>&nbsp; Save
					</button >
				</div>
				<div class="col-sm-1 col-md-2" style="width: 10.6%;">
			    	<button type="button" class="btn btn-primary search_acc" id="sendmsg"  <?php if($_POST['completestatus'] >= 2){ if(substr($_SESSION['userlevel'],3,1) === '0') echo 'disabled';} else echo 'disabled'; ?>>
			 		<span class="glyphicon glyphicon-phone" aria-hidden="true"></span>&nbsp; Send Msg
					</button>
				</div>
				
				<div class="col-sm-1 col-md-1">				
			    	<a href=""   class="btn btn-primary printinvoice" <?php if($_POST['completestatus'] < 3 || $_POST['register']  <> 'Y')  echo 'disabled';   ?>>
			 		<span class="glyphicon glyphicon-book" aria-hidden="true"></span>&nbsp; Print Invoice
					</a>
				</div>				
				
				<!--<div class="col-sm-1 col-md-1">				
			    	<a href=""   class="btn btn-primary printme" <?php if($_POST['completestatus'] >= 2){  if(substr($_SESSION['userlevel'],4,1) === '0') echo 'disabled'; } else echo 'disabled'; ?>>
			 		<span class="glyphicon glyphicon-phone" aria-hidden="true"></span>&nbsp; Print
					</a>
				</div>-->
	  </div>
	  
	  
	  <input type="hidden" name="completestatus" id="completestatus"  value="<?php if(isset($_POST['completestatus'])) echo $_POST['completestatus']; ?>"/>
	   <input type="hidden" name="nationality"   value="<?php if(isset($_POST['nationality'])) echo $_POST['nationality']; ?>"/>
	   <input type="hidden" name="invoiceno"   value="<?php if(isset($_POST['invoiceno'])) echo $_POST['invoiceno']; ?>"/>
	   <input type="hidden" name="approvedvalue" id="approvedvalue"  value="<?php if(isset($_POST['register'])) echo $_POST['register']; ?>"/>
	  <input type="hidden" name="msgcount" id="msgcount" value="<?php if(isset($_POST['msgcount'])) echo $_POST['msgcount']; ?>"/>
	  <input type="hidden" name="contactno" value="<?php if(isset($_POST['contactno'])) echo htmlspecialchars($_POST['contactno']); ?>">
	  <input type="hidden" name="semesterfees" value="<?php if(isset($_POST['fees_amnt'])) echo htmlspecialchars($_POST['fees_amnt']); ?>">
	  <input type="hidden" name="scholar_avail" value="<?php if(isset($_POST['scholar_avail'])) echo $_POST['scholar_avail'];  ?>"/>
	  </form>
	  
	
	</div>
	</div>
	
		
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <script type="text/javascript" src="dist/js/jquery-1.11.1.js"></script>    
   <script src="dist/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="dist/js/jquery.validate.js"></script>
   <script type="text/javascript" src="dist/js/moment.min.js"></script>       
    
    <script src="dist/js/daterangepicker.js"></script>
    <script src="dist/js/bootstrap-select.min.js"></script>
    <script src="dist/js/bootbox.min.js"></script>
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    
   <script type="text/javascript">
   
   var vatpercent=5;
   if ($("#nationality").val() == "Saudi") {
   	vatpercent = 0;}
   	else{
		vatpercent = 5;
	}
   function validation(){
   	var msg='';
   						if ($("#idno").val().length == 0){
						 	msg='Iqama no is required';
							return msg;
						}
   						
   						if ($("#idno").val().length != 10){
						 	msg='Invalid iqama no';
							return msg;
						}
						
   				if($("#study_year option:selected").text() == "6th Year" && $("#student_type").val() == 5 && $("#program").val() == 1 ){
						
						if ($("#schedule").val() != 9){
						 	msg='Select Special Schedule';
							return msg;
						}
						
						if ($("#add_subjects").val() == "" || $("#add_subjects").val() == null){
						msg='Add subjects';
						return msg;
						}
					}
					if ($("#student_type").val() == 7 && $("#schedule").val() != 9){
						msg='Repeater student should seelct special schedule(attached) for add/repeat subjects';
						//bootbox.alert("Repeater student should seelct special schedule(attached) for add/repeat subjects", function(){  });
						return msg;
					}
					if ($("#student_type").val() == 6 && $("#schedule").val() != 8){
						msg='Only Normal Schedule is allowed for Regular + 1 Test';
						//bootbox.alert("Regular + 1 Test student should choose 'Normal Schedule' in schedlue option", function(){  });
						return msg;
					}
					
					if ($("#student_type").val() == 6 && $("#schedule").val() == 8){
						var str1 =   $("#add_subjects").val();	
						var repeat=0;					
						
						if($('#program').val() != 1 ){
							msg='Regular + 1 Test is available only for MBBS';
							return msg;
						}
						
						if($("#study_year option:selected").text() != "3rd Year" && $("#study_year option:selected").text() != "4th Year"){
							msg='Select MBBS 3rd or 4th year for Regular + 1 Test';
							return msg;
						}
												
						if(str1 == null || str1 == ''){
							msg='Add 1 subject for Regular + 1 Test';
							return msg;						    
						}
						$.each( str1, function( key, value ) {
							repeat= repeat +1;
						});
						if(repeat == 0) {
							msg='Select 1 Add Subject';
							return msg;
						}else if(repeat > 1)  {
							msg='Should not add more than one subject to Normal + 1 Test';
							
							return msg;
						}
						
					}
					
					if ($("#schedule").val() == 9 && ($("#add_subjects").val() == "" || $("#add_subjects").val() == null) && ($("#repeat_subjects").val() == "" || $("#repeat_subjects").val() == null)){
						msg='Add/ Repeat Subjects should not be empty for special schedule';
						//bootbox.alert("Add/ Repeat Subjects should not be empty for special schedule", function(){  });
						return msg;
					}
					if ($("#adjustfees").val() != "" && $("#notes").val() == "" ){
						msg='Write notes to Finance Department';
						//bootbox.alert("Write notes to Finance Department", function(){  });
						return msg;
					}
					
					
					return msg;
   }
   
   $.validator.setDefaults( {
   	submitHandler: function () {
   		
   		var tt;
   		tt = validation();
   		if (tt == '')	return true; else{ bootbox.alert(tt, function(){  }); return false;}
	  }
	});			
   
 	 function baseUrl() {
   var href = window.location.href.split('/');
   return href[0]+'//'+href[2]+'/';
}
   	$( document ).ready( function () {
   		
   		$("#sendmsg").click(function() 
   		{
   			if ($("#completestatus").val() >=2){
   		 if ($('#msgcount').val() > 0) {
		 	bootbox.confirm("Already SMS sent to student, Are you sure to send SMS again?", function(result) {
 			if(result){
 				var ischecked= 18;
 				if($("#scholar_avail").is(':checked')) ischecked=17;
				var dataString = { 'action':'sendmsg','mobileno' : $("#contactno").val(),'scholarship': ischecked,'pendingfees':$('#pendingfees').val(),'semesterfees':$('#fees_amnt').val(),'vatamt':$('#vatamt').val(),'studname':$("#studname").val(),'msgcount':$('#msgcount').val(),'reguid':$('#reguid').val(),'email':$('#email').val(),'nationality':$('#nationality').val() };
			 	$.ajax({
				type: "POST",
				datatype: "json",
				url: baseUrl() +"studentreg/sendmsg.php",
				data: dataString,
				cache: false,
				success: function(data)
				{	
				bootbox.alert("SMS Sent successfully..", function() { 
				});
				
				}
			  });
			}
			}); 
		 }else{
		 	var ischecked= 18;
 				if($("#scholar_avail").is(':checked')) ischecked=17;
		 var dataString = { 'action':'sendmsg','mobileno' : $("#contactno").val(),'scholarship': ischecked,'pendingfees':$('#pendingfees').val(),'semesterfees':$('#fees_amnt').val(),'studname':$("#studname").val(),'msgcount':$('#msgcount').val(),'reguid':$('#reguid').val() };
		 $.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/sendmsg.php",
			data: dataString,
			cache: false,
			success: function(data)
			{	
			bootbox.alert("SMS Sent successfully..", function() { 
				});
			
			}
		  });
		  }
		  }
		 
		  return false; 
		  
		});
		
		
		function get_totalfees(){
			var pendingfees= parseFloat($('#pendingfees').val())||0;
		 	var concessionfees= parseFloat($('#concessionfees').val())||0;
		 	var semfees = 0;
		 	var vatamt=parseFloat($('#vatamt').val())||0;;
		 	if($("#scholar_notavail").is(':checked')) semfees = parseFloat($('#fees_amnt').val())||0;

		 	var total = (semfees + vatamt + pendingfees) - concessionfees; 	
		 	$('#totalfees').val(total);
		 	$('#feespaid').val(0);
		 	
		}
		
		
		
		 
		 $("#pendingfees").on('change keyup paste',function () {
		 //if ($(this).val() >=0)
		 	get_totalfees();
		 });
		 

		 
		$("#adjustfees").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)|| (e.keyCode === 109)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });


		$("#adjustfees").on('change keyup paste',function () {
			
			if($("#fees_amnt1").val() > 0){
				
				var tt= $(this).val();
				adjust_fees();
				var value1= $("#fees_amnt1").val();
				if (tt.charAt(0) == "-" && parseFloat(tt.substr(tt.indexOf("-")+1)) > 0){
					//tt = parseFloat($("#fees_amnt").val()) - parseFloat(tt.substr(tt.indexOf("-")+1));
					
					$("#fees_amnt").val(value1 - parseFloat(tt.substr(tt.indexOf("-")+1)));
				}else if(parseFloat(tt)>0){
					tt = parseFloat(value1) + parseFloat(tt);
					$("#fees_amnt").val(tt);
				}else{
					$("#fees_amnt").val(value1);
				}
				$("#vatamt").val(($("#fees_amnt").val()*(vatpercent/100)).toFixed(2));
			}
		});
		 
		 $("#concessionfees").on('change keyup paste',function () {
		 	var concessionfees= parseFloat($('#concessionfees').val())||0;
		 	var pendingfees= parseFloat($('#pendingfees').val())||0;
		 	if (($(this).val() <= (concessionfees + pendingfees)) && $(this).val()>=0){	
			get_totalfees();		 	
		 	}else{
				alert("Value should not greater than fees");
				$(this).val(0);
			}
		 });	
		
		$("#semesteruid").change(function () {
		 if ($(this).val() >=1)
		  change_fees();
		});
		
		$("#student_type").change(function () {
		 if ($("#student_type").val() >=1)
		  { 
		  	$("#schedule").attr('disabled',false);
		    if($("#student_type").val() == 6) {$("#schedule").val(8);$("#schedule").attr('disabled',true);}
		    if($("#student_type").val() == 7) {$("#schedule").val(9);$("#schedule").attr('disabled',true);}
		    $("#schedule").change();
		  }
		  else {$("#fees_amnt").val('');$("#schedule").val('');$("#study_year").val('');$("#semesteruid").val(''); }
		  change_fees();
		});
		
	   $("#schedule").on("change",function () {
	   	
	   	if ($("#schedule").val() != ""){
	   	if ( $('#schedule option:selected').text() == 'Normal Schedule' && $('#student_type option:selected').text() == 'Regular Student' ){
			$("#add_subjects").selectpicker('deselectAll');
			$("#add_subjects").attr('disabled', true).selectpicker('refresh');
			$("#repeat_subjects").selectpicker('deselectAll');
			$("#repeat_subjects").attr('disabled', true).selectpicker('refresh');
			$("#add_credits").val('');
		  	$("#repeat_credits").val('');
		  	change_fees(); 
		}else{
			var dataString = {"action": "get_addsubjects","reguid": $("#accuid").val(),"programuid": $("#program").val(),"courseuid":$("#study_year").val(),"semester_value": $('#semesteruid').val(),"schedule": $("#schedule").val()};
				
					dataString = $.param(dataString);
					$.ajax({
					type: "POST",
					datatype: "json",
					url: baseUrl() +"studentreg/save.php",
					data:  dataString,
					cache: false,
					success: function(data)
					{	
					
					var json = jQuery.parseJSON(data);
					$('#add_subjects').html(json.add_subj);
    				$("#add_subjects").attr('disabled', false).selectpicker('refresh');
					 var str =   $('#add_subjects').val();
							var totalcredits=0;
							if(str != null){
							$.each( str, function( key, value ) {
							  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
							  totalcredits = totalcredits + credithour;		  
							});
							$("#add_credits").val(totalcredits);
							}else{
							$("#add_credits").val('');
							}
					
					if ($("#student_type option:selected").text() == 'Regular + 1 Test'){
					  $('#repeat_subjects').selectpicker('deselectAll');	
					  $("#repeat_subjects").attr('disabled', true).selectpicker('refresh');
					  $("#repeat_credits").val('');
					}else{
							$('#repeat_subjects').html(json.repeat_subj);
    						$("#repeat_subjects").attr('disabled', false).selectpicker('refresh');
    						 str =   $('#repeat_subjects').val();
							var totalcredits=0;
							if(str != null){
							$.each( str, function( key, value ) {
							  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
							  totalcredits = totalcredits + credithour;		  
							});
							$("#repeat_credits").val(totalcredits);
							}else{
							$("#repeat_credits").val('');
							}
						}
						
						change_fees(); 
					}
				  	});	
			
		}
		 	
		}
	   	});
	   	
	   	
	   	
	  $("#study_year").change(function () {

			var dataString = {"action": "get_semestername","programuid": $("#program").val(),"courseuid": $(this).val()};
		    dataString = $.param(dataString);
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data:  dataString,
			cache: false,
			success: function(data)
			{	
				$('#semesteruid').html(data);
				$('#fees_amnt').val('');
			}
		
		  });
		  		   
        
      });

		$('.printme').click(function(){
//     var divContents = $('#printcontent').html();
		if($('#completestatus').val() >= 2 ) window.open(document.location.origin+"/studentreg/printreg.php?reguid="+<?php echo $reguid; ?>,null,"status=yes,toolbar=no,menubar=no,location=no"); else bootbox.alert("Finance Department process is not completed", function() { });
   
});


$('.printministry').click(function(){
//     var divContents = $('#printcontent').html();
		if($('#completestatus').val() >= 2 ) window.open(document.location.origin+"/studentreg/printministry.php?reguid="+<?php echo $reguid; ?>,null,"status=yes,toolbar=no,menubar=no,location=no"); else bootbox.alert("Finance Department process is not completed", function() { });
   
});



$('.printinvoice').click(function(){
//     var divContents = $('#printcontent').html();
		if($('#completestatus').val() >= 2 && $('#approvedvalue').val() == 'Y') window.open(document.location.origin+"/studentreg/printinvoice.php?reguid="+<?php echo $reguid; ?>,null,"status=yes,toolbar=no,menubar=no,location=no"); else bootbox.alert("Finance Department process is not completed", function() { });   
});





		function change_fees(){
			var dataString = {"action": "get_fees","programuid": $("#program").val(),"accno": $("#accno").val(),"semester_name": $("#study_year option:selected").text()};
			var total_fees = 0;	
		    dataString = $.param(dataString);
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data:  dataString,
			cache: false,
			success: function(data)
			{	
				var json = jQuery.parseJSON(data);	
							
				total_fees = parseFloat(json.semester_fees);
				if ($("#student_type option:selected").text() == "Repeater Student"){
				total_fees = 0;	
				}if ($("#student_type option:selected").text() == "Regular + 1 Test"){
				total_fees = total_fees + 1000;	
				}
				
				var add_subject=0;
				if( $("#study_year option:selected").text() == "6th Year"  && $("#program").val() == 1){
					if($("#add_subjects").val() != "" && $("#add_subjects").val() != null){
						var str1 =   $("#add_subjects").val();	
						var repeat=0;					
						$.each( str1, function( key, value ) {
							repeat= repeat +1;
						});
					add_subject = 20000 * repeat;
					total_fees=0;
					}else{
						add_subjects=0;
					}
				   total_fees=0; 
				}else{
				add_subject = $("#add_credits").val() * json.add_subjectfees;				
				}
				//var add_subject = $("#add_credits").val() * json.add_subjectfees;			
				var repeat_subject = $("#repeat_credits").val() * json.repeat_subjectfees;	
				if ($("#schedule option:selected").text() == "Normal Schedule"){
				repeat_subject = add_subject = 0;	
				}
				total_fees = 	total_fees + repeat_subject + add_subject
				$("#fees_amnt").val(total_fees);	
				$("#vatamt").val(total_fees*(vatpercent/100).toFixed(2));	
			}
		  });
		 }
		 
		 
		 
		 function adjust_fees(){
			var dataString = {"action": "get_fees","programuid": $("#program").val(),"accno": $("#accno").val(),"semester_name": $("#study_year option:selected").text()};
			var total_fees = 0;
		    dataString = $.param(dataString);
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data:  dataString,
			cache: false,
			success: function(data)
			{	
				var json = jQuery.parseJSON(data);	
									
				total_fees = parseFloat(json.semester_fees);
				if ($("#student_type option:selected").text() == "Repeater Student"){
				total_fees = 0;	
				}if ($("#student_type option:selected").text() == "Regular + 1 Test"){
				total_fees = total_fees + 1000;	
				}
				
				var add_subject=0;
				if( $("#study_year option:selected").text() == "6th Year"  && $("#program").val() == 1){
					if($("#add_subjects").val() != "" && $("#add_subjects").val() != null){
						var str1 =   $("#add_subjects").val();	
						var repeat=0;					
						$.each( str1, function( key, value ) {
							repeat= repeat +1;
						});
					add_subject = 20000 * repeat;
					total_fees=0;
					}else{
						add_subjects=0;
					}
				   total_fees=0; 
				}else{
				add_subject = $("#add_credits").val() * json.add_subjectfees;				
				}
				//var add_subject = $("#add_credits").val() * json.add_subjectfees;			
				var repeat_subject = $("#repeat_credits").val() * json.repeat_subjectfees;	
				if ($("#schedule option:selected").text() == "Normal Schedule"){
				repeat_subject = add_subject = 0;	
				}
				total_fees = 	total_fees + repeat_subject + add_subject
				$("#fees_amnt1").val(total_fees);
				   
				
			}
		  });
		  
		 }
		 
		 
		
		

		
		
		$("#program").change(function () {		    	
		     change_fees(); 
   		});
    
    
   	  /*$("#fees_amnt").numeric();*/		
		/*$("#registerform").submit(function(){
		)};*/
	$('input[name="approvedate"]').daterangepicker({	
		locale: {
		format: 'DD/MM/YYYY' },	
        singleDatePicker: true,
        showDropdowns: true,
         "drops": "up"
    });
    $('input[name="recvamntdate"]').daterangepicker({
    	locale: {
		format: 'DD/MM/YYYY' },	
        singleDatePicker: true,
        showDropdowns: true,
         "drops": "down"
    });
    $('input[name="register_date"]').daterangepicker({
    	locale: {
		format: 'DD/MM/YYYY' },	
        singleDatePicker: true,
        showDropdowns: true,
         "drops": "down"
    });
    $('input[name="academic_date"]').daterangepicker({
    	locale: {
		format: 'DD/MM/YYYY' },	
        singleDatePicker: true,
        showDropdowns: true,
         "drops": "down"
    });
    
    
    $('#add_subjects').selectpicker({
      liveSearch: true
    });
    $('#repeat_subjects').selectpicker({
      liveSearch: true         
    });
    


    $('#add_subjects').on('hidden.bs.select', function (e) {
		var str =   $(this).val();
		var totalcredits=0;
		if(str != null){
		$.each( str, function( key, value ) {
		  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
		  totalcredits = totalcredits + credithour;		  
		});
		$("#add_credits").val(totalcredits);
		}else{
		$("#add_credits").val('');
		}
		 change_fees(); 
	});
	
	 $('#repeat_subjects').on('hidden.bs.select', function (e) {
		var str =   $(this).val();
		var totalcredits=0;
		if(str != null){
		$.each( str, function( key, value ) {
		  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
		  totalcredits = totalcredits + credithour;		  
		});
		$("#repeat_credits").val(totalcredits);
		}else{ $("#repeat_credits").val('');}
		change_fees();
	});


    $("#academic_affairs" ).validate( {
				rules: {					
					accno: "required",
					studname: {
						required: true,
						minlength: 2
					},
					program: "required",
					email: {
						required: true,
						email: true
					},
					contactno: {
						required: true,
						minlength: 5
					},
					student_type: "required",
					schedule: "required",
					academicofcname: {
						required: true,
						minlength: 2
					}
				},
				messages: {
					accno: "Please enter a valid academic number",					
					name: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					program: "Select a Program",
					email: "Please enter a valid email address",
					contactno: {
						required: "Please provide your contact no",
						minlength: "Your contact no must be at least 5 characters long"
					},			
					student_type: "Select student type",
					schedule: "Select schedule type",
					academicofcname: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					}
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
				},
				unhighlight: function (element, errorClass, validClass) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
				}
			});
			
			
			$("#registration" ).validate( {
				rules: {					
					accno: "required",
					studname: {
						required: true,
						minlength: 2
					},
					program: "required",
					email: {
						required: true,
						email: true
					},
					contactno: {
						required: true,
						minlength: 5
					},
					student_type: "required",
					schedule: "required",
					academicofcname: {
						required: true,
						minlength: 2
					},					
					scholar_avail: {
						required: true	
					},
					register_ofcname: {
						required: true,
						minlength: 2
					},
					register_date: {
						required: true						
					},					
					fees_amnt: {						
						number: true,
						min: 1
					}
				},
				messages: {
					accno: "Please enter a valid academic number",					
					name: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					program: "Select a Program",
					email: "Please enter a valid email address",
					contactno: {
						required: "Please provide your contact no",
						minlength: "Your contact no must be at least 5 characters long"
					},			
					student_type: "Select student type",
					schedule: "Select schedule type",
					academicofcname: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					/*academic_date: {
						required: "Please enter a date",
						date: "Provide valid date eg:01/12/2015"
					},*/
					scholar_avail: {
						required: "Select any option"	
					},
					register_ofcname: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					register_date: {
						required: "Please enter a date"						
					}
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
				},
				unhighlight: function (element, errorClass, validClass) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
				}
			});
			
			$("#financedepartment" ).validate( {
				rules: {					
					accno: "required",
					studname: {
						required: true,
						minlength: 2
					},
					program: "required",
					email: {
						required: true,
						email: true
					},
					contactno: {
						required: true,
						minlength: 5
					},
					student_type: "required",
					schedule: "required",
					academicofcname: {
						required: true,
						minlength: 2
					},					
					scholar_avail: {
						required: true	
					},
					register_ofcname: {
						required: true,
						minlength: 2
					},
					register_date: {
						required: true						
					},					
					fees_amnt: {						
						number: true,
						min: 1
					},					
					recvamntdate: {
						required: true						
					},					
					pendingfees: {						
						number: true
					},					
					concessionfees: {						
						number: true
					},					
					totalfees: {						
						number: true
					},
					feespaid: {						
						number: true,
						min: 0
					}
				},
				messages: {
					accno: "Please enter a valid academic number",					
					name: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					program: "Select a Program",
					email: "Please enter a valid email address",
					contactno: {
						required: "Please provide your contact no",
						minlength: "Your contact no must be at least 5 characters long"
					},			
					student_type: "Select student type",
					schedule: "Select schedule type",
					academicofcname: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					/*academic_date: {
						required: "Please enter a date",
						date: "Provide valid date eg:01/12/2015"
					},*/
					scholar_avail: {
						required: "Select any option"	
					},
					register_ofcname: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					register_date: {
						required: "Please enter a date"						
					}
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
				},
				unhighlight: function (element, errorClass, validClass) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
				}
			});
			
			
			

});
   </script>
  </body>
</html>
