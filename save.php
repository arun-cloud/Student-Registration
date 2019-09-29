<?php

include(getcwd(). '/db_connect.php'); 
date_default_timezone_set('Asia/Riyadh');
if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
      case "save": save_function(); break;
      case "search": search_function(); break;
      case "update": update_function(); break;
      case "get_fees": getfees_function(); break;
      case "get_semestername": getsemester_function(); break;
      case "admin_save": adminsave_function(); break;
      case "get_studyyear": getcourse_function(); break;
      case "get_addsubjects": getadd_credit_function(); break;
      case "get_subjects": getsubject_function(); break;
      case "search_student": searchbyaccno_function(); break;
      case "student_notice": sendnoticemsg(); break;
      case "update_studentdetails": update_studentdetails(); break;
      case "delete_registration": delete_registration(); break;
    }
  }
}

function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function save_function(){
	$return = array();
	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
}  
	
	
	$sqlquery = "SELECT * FROM academicdetails WHERE uid = " .$return["accuid"]. " and scholartypeuid>0 and statusflag='A'";
	$sql_customers = mysql_query($sqlquery);
	if(!$sql_customers):
       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
    else:
		$rowsreturn = mysql_num_rows($sql_customers);
	endif;
	if($rowsreturn == 1)  {
		while($fetchresult = mysql_fetch_array($sql_customers)) 
		$return["scholartypeuid"] = $fetchresult["scholartypeuid"];
	}else {
		$return["scholartypeuid"]=0;
	}	

	$sqlquery = "INSERT INTO registration (academicdetailsuid,studname ,year, programmasteruid,coursemasteruid,semesteruid, email, contactno, otherno,studenttype_uid,scheduleuid,add_subjects,add_credits,repeat_subjects,repeat_credits,semester_fees,scholartypeuid,completestatus, cuser, muser, cwhen, mwhen, statusflag) VALUES ('" . $return["accuid"] . "', '" . $return["studname"] . "', '" . $return["year"] . "', '" . $return["program"] . "', '" . $return["study_year1"] . "', '" . $return["semester"] . "', '" . $return["email"] . "', '" . $return["contactno"] . "','".$return["otherno"] ."', '" . $return["student_type1"] . "', '" . $return["schedule1"] . "', '" . $return["add_subjects"] . "', '" . $return["add_credits"] . "', '" . $return["repeat_subjects"] . "', '" . $return["repeat_credits"] . "', '" . $return["fees_amnt"] . "', '" . $return["scholartypeuid"] . "','0',1,1,'".date('Y/m/d H:i:s')."','".date('Y/m/d H:i:s')."','A')";
	$sql_customers = mysql_query($sqlquery);
if(mysql_affected_rows() > 0){
echo json_encode($return);
}else{
echo "MYSQL Error : ".die(mysql_error());
}
}
function your_filter($value) {
    $newVal = trim($value);
    $newVal = htmlspecialchars($newVal);
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
}

function update_function(){	
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
}  
	  $sqlquery = "UPDATE registration as a set a.programmasteruid = '". $return["course"] . "',a.coursemasteruid  = '". $return["study_year1"] . "',a.semesteruid = '". $return["semester"] . "',a.email='". $return["email"] ."',a.contactno='". $return["contactno"] ."',a.otherno='". $return["otherno"] ."',a.studenttype_uid  = '". $return["student_type1"] . "',a.scheduleuid  = '". $return["schedule1"] . "',a.add_subjects  = '". $return["add_subjects"] . "',a.add_credits  = '". $return["add_credits"] . "',a.repeat_subjects  = '". $return["repeat_subjects"] . "',a.repeat_credits  = '". $return["repeat_credits"] . "',a.semester_fees  = '". $return["fees_amnt"] . "'  where a.academicdetailsuid='". $return["accuid"] ."'";			
	$sql_customers = mysql_query($sqlquery);
if($sql_customers){
echo json_encode($return);
}else{
echo "MYSQL Error : ".die(mysql_error());
}
}


function adminsave_function(){	
		$return = array();			
		foreach($_POST as $key => $value) {    
	    $_POST[$key] = your_filter($value);
	    $return[$key]= $_POST[$key];
	}  
	  $sqlquery = "UPDATE registration as a set a.course = '". $return["program"] . "',a.year = '2018',a.email='". $return["email"] ."',a.contactno='". $return["contactno"] ."',a.otherno='". $return["otherno"] ."',a.studenttype_uid = '". $return["student_type"] . "',a.scheduleuid = '". $return["schedule"] . "',a.isno_objection = '". $return["noobjection"] . "',a.academic_officer = '". $return["academicofcname"] . "',a.academic_date = '". $return["academic_date"] . "',a.isscholar_avail = '". $return["scholar_avail"] . "',a.semester_fees = '". $return["fees_amnt"] . "',a.register_officer = '". $return["register_ofcname"] . "',a.register_date = '". $return["register_date"] . "',a.isfullypaid = '". $return["finance_recvamnt"] . "',a.fullypaid_date = '". $return["recvamntdate"] . "',a.isapproved = '". $return["register"] . "',a.dean_name = 'DR.RASHAD H.KASHGARI',a.approval_date = '". $return["approvedate"] . "',a.muser = '". $_SESSION["useruid"] . "',a.mwhen = '". date('Y/m/d H:i:s') . "' where a.uid='". $_POST["reguid"] ."' and a.statusflag='A'";			

	$result = mysql_query($sqlquery);
	if(mysql_affected_rows() > 0){
	return TRUE;
	}else{
	echo "MYSQL Error : ".die(mysql_error());		
	}
}


function search_function(){
	
 if(isset($_POST['accno']))
  {
	$search_keyword =your_filter($_POST['accno']);

	
	$query="SELECT uid,idno,engname,programmasteruid course,gender,IFNULL(student_typeuid,0)student_typeuid,IFNULL(coursemasteruid,0)coursemasteruid,IFNULL(nationalityuid,0)nationalityuid,IFNULL(studentstatusuid,'')studentstatusuid  FROM academicdetails  WHERE academicno  = '$search_keyword' AND statusflag='A'";
	if (isset($_POST["studentstatus"])){
		 $query = $query . " and StudentStatusuid='". $_POST["studentstatus"]."'";
		}
	
    $result=mysql_query($query);    
    if(!$result):
       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
    else:
		$rowsreturn = mysql_num_rows($result);
	endif;

	if($rowsreturn === 1){	
		while($rowCountries = mysql_fetch_array($result)) {	
		$search_keyword =mysql_real_escape_string($rowCountries['uid']);
		$updatequery="SELECT * FROM registration WHERE academicdetailsuid  = '$search_keyword'  and statusflag='A'";
		$updateresult=mysql_query($updatequery);
		$updaterowsreturn = mysql_num_rows($updateresult);
		if($updaterowsreturn === 1){	
			while($fetch = mysql_fetch_array($updateresult)){	
			$json= array('action'=>'update','uid'=>$fetch['academicdetailsuid'],'idno'=>$rowCountries['idno'],'name'=>$fetch['studname'], 'course'=>$fetch['programmasteruid'],'student_type'=>$fetch['studenttype_uid'],'schedule'=>$fetch['scheduleuid'],'study_year'=>$fetch['coursemasteruid'],'semester'=>$fetch['semesteruid'],'email'=>$fetch['email'], 'contactno'=>$fetch['contactno'], 'otherno'=>$fetch['otherno'], 'gender'=>$rowCountries['gender'],'nationalityuid'=>$rowCountries['nationalityuid'], 'add_credits'=>$fetch['add_credits'], 'repeat_credits'=>$fetch['repeat_credits'],'fees_amnt'=>$fetch['semester_fees'],'stud_typedb'=>$rowCountries['student_typeuid'],'stud_yeardb'=>$rowCountries['coursemasteruid'],'completestatus'=>$fetch['completestatus'],'studentstatusuid'=>$rowCountries['studentstatusuid']);
			}
		}
		else{
		$updatequery="SELECT * FROM registration_2019 WHERE academicdetailsuid  = '$search_keyword' and year='2018' and statusflag='A'";
		$updateresult=mysql_query($updatequery);
		$updaterowsreturn = mysql_num_rows($updateresult);
		if($updaterowsreturn === 1){	
			while($fetch = mysql_fetch_array($updateresult)){	
			$json= array('action'=>'new','uid'=>$fetch['academicdetailsuid'],'idno'=>$rowCountries['idno'],'name'=>$fetch['studname'], 'course'=>$fetch['programmasteruid'],'student_type'=>$rowCountries['student_typeuid'],'schedule'=>'0','email'=>$fetch['email'], 'contactno'=>$fetch['contactno'], 'otherno'=>$fetch['otherno'], 'gender'=>$rowCountries['gender'],'nationalityuid'=>$rowCountries['nationalityuid'],'stud_typedb'=>$rowCountries['student_typeuid'],'stud_yeardb'=>$rowCountries['coursemasteruid'],'studentstatusuid'=>$rowCountries['studentstatusuid']);
			}
		}
		else{
			$json= array('action'=>'new','uid'=>$rowCountries['uid'], 'name'=>$rowCountries['engname'], 'course'=>$rowCountries['course'],'gender'=>$rowCountries['gender'],'nationalityuid'=>$rowCountries['nationalityuid'],'idno'=>$rowCountries['idno'],'student_type'=>$rowCountries['student_typeuid'],'stud_typedb'=>$rowCountries['student_typeuid'],'stud_yeardb'=>$rowCountries['coursemasteruid'],'studentstatusuid'=>$rowCountries['studentstatusuid']);
		}
		
		
		
		}		
			echo json_encode($json);
		}
	}else{
		$json= array('name'=>"");
		echo json_encode($json);
	}
}	
}
 function get_allfees($program_uid,$accno){    
	    $semfees = $addfees = $repeatfees = 0;
	    	
	}

function getfees_function(){
	
 if(isset($_POST['programuid']) AND isset($_POST['accno']))
  {
  		$semfees = $addfees = $repeatfees = 0;
  		$program_uid = your_filter($_POST['programuid']);
  		$accno = your_filter($_POST['accno']);
  		$semester_name=your_filter($_POST['semester_name']);
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
			  if ($semester_name == "Ist Year" OR $semester_name == "2nd Year" or $semester_name == "3rd Year")
			  	$semfees = 25000;			   			  
			  else
			  	$semfees = 30000;			    
			  
			   $addfees = 1550;
			   $repeatfees = 1750; 
			}else if($accno == 12 OR $accno == 13){
			 	if ($semester_name == "Ist Year"){	$semfees= 25000;	}else{ $semfees= 30000; }
			    $addfees = 1550;
			    $repeatfees = 1750;
			 }else if ($accno>=14){
			 	$semfees=35000;
			 	$addfees=1950;
			 	$repeatfees=2200;
			 }
		  		break;
		  	case 2:
		  	if ($accno>=4 AND $accno <= 11){			  
			  if ($semester_name == "Ist Year" OR $semester_name == "2nd Year" or $semester_name == "3rd Year")
			  	$semfees = 25000;			   			  
			  else
			  	$semfees = 30000;			    
			  
			   $addfees = 1660;
			   $repeatfees = 1750; 
			}else if($accno == 12 OR $accno == 13){
			 	if ($semester_name == "Ist Year"){	$semfees= 25000;	}else{ $semfees= 30000; }
			    $addfees = 1660;
			    $repeatfees = 1750;
			 }else if ($accno>=14){
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
			 }else if ($accno>=14){
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
		$json= array('semester_fees'=>$semfees, 'add_subjectfees'=>$addfees, 'repeat_subjectfees'=>$repeatfees);

	  echo json_encode($json);
	
  }
}


function getsemester_function(){
	
 if(isset($_POST['programuid']) AND isset($_POST['courseuid']))
  {
	$program_uid =your_filter($_POST['programuid']);
	$course_uid =your_filter($_POST['courseuid']);
	$semester_value =your_filter($_POST['semester_value']);
	
	$query = "select uid,semester_name from semestermaster where program_masteruid ='".$program_uid."' and coursemasteruid = '".$course_uid."' and statusflag='A'";		
	$result=mysql_query($query); 
    if(!$result):
       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
    else:
		$rowsreturn = mysql_num_rows($result);
	endif;
		$json = '<option value="">Select Semester</option>';
	  while($fetch = mysql_fetch_array($result)){
	  	if ($fetch['uid'] === $semester_value)
	  	$json = $json . '<option value="'.$fetch["uid"].'" selected>'.$fetch['semester_name'].'</option>';		
	  	else
	  	$json = $json . '<option value="'.$fetch["uid"].'">'.$fetch['semester_name'].'</option>';
	  }
	  	
	  	
	  	
	  	
	  echo $json;
	  
  }
}



function getcourse_function(){
	
 if(isset($_POST['programuid']))
  {
	$program_uid =mysql_real_escape_string($_POST['programuid']);
	$year_value = mysql_real_escape_string($_POST['year_value']);
	$query = $query = "SELECT uid,coursename FROM coursemaster WHERE programmasteruid='".$program_uid."' and statusflag='A'";		
	$result=mysql_query($query); 
    if(!$result):
       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
    else:
		$rowsreturn = mysql_num_rows($result);
	endif;

		$json = '<option value="">Select Year</option>';
	  while($fetch = mysql_fetch_array($result)){
	  	if($fetch["uid"] === $year_value)
	  	$json = $json . '<option value="'.$fetch["uid"].'" selected>'.$fetch['coursename'].'</option>';		
	  	else
	  	$json = $json . '<option value="'.$fetch["uid"].'">'.$fetch['coursename'].'</option>';		
	  }
	  	
	  echo $json;
  }
}

	function get_courses_with_semestername($program_uid){    	
		$query = "select a.uid,b.coursename, a.semester_name from semestermaster as a, coursemaster as b where program_masteruid='".$program_uid."' and a.coursemasteruid = b.uid and a.statusflag='A' and b.statusflag='A'";
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

function getadd_credit_function(){
	if(isset($_POST['semester_value'])){
	  $allsemester = $subjectmaster = array();	
	  $edit_addsubj= $edit_repeatsubj=array();
	  $ref_result= get_courses_with_semestername($_POST['programuid']);	
	  while($fetchresult = mysql_fetch_array($ref_result))
	  $allsemester[] = $fetchresult;	
	  
	  $ref_result= get_subjects($_POST['programuid']);	
	  while($fetchresult = mysql_fetch_array($ref_result))
	  $subjectmaster[] = $fetchresult;	
	  $query = "select add_subjects,add_credits,repeat_subjects,repeat_credits from registration where academicdetailsuid= " .$_POST['reguid']." and statusflag='A'";
	  $result=mysql_query($query); 
	  if(!$result){ die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); }
	  else{
	  	while($fetchresult = mysql_fetch_array($result)){
			$edit_addsubj = explode(',', $fetchresult["add_subjects"]);
			$edit_repeatsubj = explode(',', $fetchresult["repeat_subjects"]);
		}
		
		if(isset($_POST['schedule']))
    {
		if($_POST['schedule'] == 9){
			$json= '<select class="show-tick form-control" name="add_subjects[]" id="add_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple>';
		}else{
		$json= '<select class="show-tick form-control" name="add_subjects[]" id="add_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled>';	
		}
		
	}
    else{
		$json= '<select class="show-tick form-control" name="add_subjects[]" id="add_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled>';
	}
   $add=$repeat='';
   if(!empty($allsemester) AND !empty($subjectmaster)){
			  	foreach($allsemester as $key => $value){
					$add= $add. '<optgroup label="'.$value["coursename"].'" data-subtext="'.$value["semester_name"].'">';
					foreach($subjectmaster as $key1 => $value1){
						if($value["uid"] === $value1["semestermasteruid"]){
						 $isfound = false;
						 for($i = 0; $i < count($edit_addsubj); $i++){
						 	if ($edit_addsubj[$i] === $value1["uid"]){ $isfound= true;
						 	break;  }else
						 	 { $isfound = false;}
						 }												
						if($isfound):
						$add = $add . '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'" selected>'.$value1["subjectname"].'</option>';
						else:
						$add = $add . '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'">'.$value1["subjectname"].'</option>';	
						endif;						
						}else{							
						}
					}
					$add= $add . '</optgroup>';				   
				   } //End Add_credit
				   
				   
				   foreach($allsemester as $key => $value){
					$repeat= $repeat. '<optgroup label="'.$value["coursename"].'" data-subtext="'.$value["semester_name"].'">';
					foreach($subjectmaster as $key1 => $value1){
						if($value["uid"] === $value1["semestermasteruid"]){
						 $isfound = false;
						 for($i = 0; $i < count($edit_repeatsubj); $i++){
						 	if ($edit_repeatsubj[$i] === $value1["uid"]){ $isfound= true;
						 	break;  }else
						 	 { $isfound = false;}
						 }												
						if($isfound):
						$repeat = $repeat . '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'" selected>'.$value1["subjectname"].'</option>';
						else:
						$repeat = $repeat . '<option title="'.$value1["subjectcode"].'" value="'.$value1["uid"].'-'.$value1["credithour"].'">'.$value1["subjectname"].'</option>';	
						endif;						
						}else{							
						}
					}
					$repeat= $repeat . '</optgroup>';				   
				   }
				   
				   
				  $json= array('add_subj'=>$add, 'repeat_subj'=>$repeat);
	  			  echo json_encode($json);
				}	
		
	  }//Else end
	  
	  
	  
	   
	}
}



function getsubject_function(){
	
 if(isset($_POST['programuid']))
  {
	$program_uid =mysql_real_escape_string($_POST['programuid']);
	$semesteruid = mysql_real_escape_string($_POST['semester']);
	$query = $query = "SELECT uid,subjectname FROM subjectmaster WHERE programmasteruid='".$program_uid."' and semestermasteruid='".$semesteruid."' and statusflag='A'";		
	$result=mysql_query($query); 
    if(!$result):
       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
    else:
		$rowsreturn = mysql_num_rows($result);
	endif;

		$json = '<option value="">Select Subject</option>';
	  while($fetch = mysql_fetch_array($result))
	  	$json = $json . '<option value="'.$fetch["uid"].'">'.$fetch['subjectname'].'</option>';		
	  	
	  echo $json;
  }
}


function searchbyaccno_function(){
	
 if(isset($_POST['accno']))
  {
	$search_keyword =your_filter($_POST['accno']);
	$query="SELECT a.uid,getProgramName(a.programmasteruid)program,a.engname studname,getYearnameByUid(b.coursemasteruid) AS courseyear,getSemesterNamebyUid(b.semesteruid)semestername,b.contactno FROM academicdetails as a,registration as b  WHERE a.uid=b.academicdetailsuid and a.academicno  = '$search_keyword' AND a.statusflag='A' and b.statusflag='A'";
    $result=mysql_query($query);    
    if(!$result):
       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
    else:
		$rowsreturn = mysql_num_rows($result);
	endif;

	if($rowsreturn === 1){	
		while($rowCountries = mysql_fetch_array($result)) {	
		$json= array('uid'=>$rowCountries['uid'],'program'=>$rowCountries['program'],'studname'=>$rowCountries['studname'], 'courseyear'=>$rowCountries['courseyear'],'semestername'=>$rowCountries['semestername'],'contactno'=>$rowCountries['contactno']);
		}
	}else{
		$json= array('name'=>"");
	}
	
	echo json_encode($json);
}	
}

function sendnoticemsg() {
	  $query="select Description from referencevalue where UID= ".$_POST['RefValueUID']." and Statusflag='A'";
	    $result=mysql_query($query);    
	    if(!$result):
	       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
		endif;
		while($rowCountries = mysql_fetch_array($result)) {	
		$_POST['content'] =   $rowCountries['Description'];
		}
		
		$json = sendSMS('osama03','a325646',$_POST['content'],$_POST['mobileno'],'Ibn Sina');
		
	    echo json_encode($json);
	   
	 
	 
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



function update_studentdetails(){	
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
}  
	$sqlquery = "update academicdetails set idno = " .$return["idno"]. ",nationalityuid =" .$return["nationality"]. "  where uid=".$return["accuid"];
	$sql_customers = mysql_query($sqlquery);
	if($sql_customers){
	echo json_encode($return);
	}else{
	echo "MYSQL Error : ".die(mysql_error());
	}
}


function delete_registration(){	
	$return = array();
	if (isset($_POST['accuid'])){
		foreach($_POST as $key => $value) {    
	    $_POST[$key] = your_filter($value);
	    $return[$key]= $_POST[$key];
	}  	
		/*if ($return["student_status"] != "21"){*/
		$sqlquery = "Delete from registration  where academicdetailsuid=".$return["accuid"];
		$sql_customers = mysql_query($sqlquery);
	/*	}*/
		
		$sqlquery = "update academicdetails set studentstatusuid = " .$return["student_status"]. " where uid=".$return["accuid"];
		$sql_customers = mysql_query($sqlquery);
		if($sql_customers){
		echo json_encode($return);
		}else{
		echo "MYSQL Error : ".die(mysql_error());
		}
	}
}


?>