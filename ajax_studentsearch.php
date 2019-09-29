<?php

include(getcwd().'/db_connect.php'); 

if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { 
      case "search": search_function(); break;
      case "archieve_search": search_archieve(); break;
      case "assigned_staffsearch": search_assignedstaff(); break;
      case "advisor_search": advisor_search(); break;
    }
  }
}

function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

function your_filter($value) {
    $newVal = trim($value);
    $newVal = htmlspecialchars($newVal);
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
}

function search_function(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
    setcookie("academicno", $return["accno"], time() + (86400 * 30), "/");
    setcookie("studname", $return["studname"], time() + (86400 * 30), "/");
    setcookie("program", $return["program"], time() + (86400 * 30), "/");
    setcookie("contactno", $return["contactno"], time() + (86400 * 30), "/");
    setcookie("fromdate", $return["fromdate"], time() + (86400 * 30), "/");
    setcookie("todate", $return["todate"], time() + (86400 * 30), "/");
    setcookie("completestatus", $return["completestatus"], time() + (86400 * 30), "/");
    setcookie("register", $return["register"], time() + (86400 * 30), "/");
    setcookie("idno", $return["idno"], time() + (86400 * 30), "/");
    setcookie("gender", $return["gender"], time() + (86400 * 30), "/");
    setcookie("studenttype", $return["student_type"], time() + (86400 * 30), "/");
    setcookie("scholarship", $return["scholarship"], time() + (86400 * 30), "/");

	$fromdate= $todate ="";
	if ($return['fromdate'] != "")	$fromdate = DateTime::createFromFormat('d/m/Y', $return['fromdate']);
	if ($return['todate'] != "")  $todate = DateTime::createFromFormat('d/m/Y', $return['todate']);
	
	$query = "select a.uid,DATE_FORMAT(a.mwhen,'%d/%m/%Y') AS entrydate ,b.academicno,b.engname,case b.gender when 'M' then 'Male' when 'F' then 'Female' end as gender,CASE a.programmasteruid WHEN 1 THEN 'M.B.B.S' WHEN 2 THEN 'B.D.S'  WHEN 3 THEN 'Pharmacy'  WHEN 4 THEN 'Nursing' END as 'course',getReferenceValueByUID(studenttype_uid)student_type,a.contactno,case a.completestatus when '0'  THEN 'Not Started' when '1' THEN 'Academic Affairs' when '2' THEN 'Registration' when '3' THEN 'Financial Department' end as completestatus,CASE a.isapproved when 'N' then 'Rejected' when 'Y' then 'Approved' when 'P' then 'Pending' else 'Not Started' end as register from registration AS a, academicdetails as b where a.academicdetailsuid = b.uid AND a.statusflag='A' and b.statusflag='A' AND b.academicno LIKE '".$return['accno']."%' AND b.idno LIKE '".$return['idno']."%' AND b.engname LIKE '".$return['studname']."%' AND a.contactno LIKE '".$return['contactno']."%' ";
	
	if ($fromdate != ""){
		$query = $query . " AND mwhen >= '".date_format($fromdate,"Y-m-d 00:00:00")."'";
	}
	
	if ($todate != ""){
		$query = $query . " AND mwhen <= '".date_format($todate,"Y-m-d 23:59:59")."'";
	}
	
	if ($return["scholarship"] > 0){
		$query = $query . " AND a.scholartypeuid=".$return['scholarship'];
	}
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
	}
	if ($return["student_type"] > 0){
		$query = $query . " AND a.studenttype_uid =".$return['student_type'];
	}
	if ($return["completestatus"] != ""){
		$query = $query . " AND a.completestatus=".$return['completestatus'];
	}
	
	if ($return["register"] != ""){
		$query = $query . " AND a.isapproved= '".$return['register']. "'";
	}
	if ($return["gender"] != ""){
		$query = $query . " AND b.gender= '".$return['gender']. "'";
	}
    $result=mysql_query($query);    
    if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    }else{
		$rowsreturn = mysql_num_rows($result);
	}

	if($rowsreturn >= 1){	
		$create_table = '<div class="table-responsive"><h2 class="sub-header">'.$rowsreturn.' Results Found</h2><table class="table table-striped" id="searchresult"><thead><tr><th>S.No</th><th>Reg.Date</th><th>Academic No</th><th>Student Name</th><th>Gender</th><th>Program</th><th>Student Type</th><th>Status</th><th>Register</th><th>Action</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
		
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['entrydate'].'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['gender'].'</td><td>'.$fetchresult['course'].'</td><td>'.$fetchresult['student_type'].'</td><td>'.$fetchresult['completestatus'].'</td><td>'.$fetchresult['register'].'</td><td><a href="student_edit.php?reguid='.$fetchresult['uid'].'">Edit</a></td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	}else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table;
}



function search_archieve(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
   /* setcookie("academicno", $return["accno"], time() + (86400 * 30), "/");
    setcookie("studname", $return["studname"], time() + (86400 * 30), "/");
    setcookie("program", $return["program"], time() + (86400 * 30), "/");
    setcookie("contactno", $return["contactno"], time() + (86400 * 30), "/");
    setcookie("fromdate", $return["fromdate"], time() + (86400 * 30), "/");
    setcookie("todate", $return["todate"], time() + (86400 * 30), "/");
    setcookie("completestatus", $return["completestatus"], time() + (86400 * 30), "/");
    setcookie("register", $return["register"], time() + (86400 * 30), "/");
    setcookie("idno", $return["idno"], time() + (86400 * 30), "/");
    setcookie("gender", $return["gender"], time() + (86400 * 30), "/");
    setcookie("studenttype", $return["student_type"], time() + (86400 * 30), "/");
    setcookie("scholarship", $return["scholarship"], time() + (86400 * 30), "/");*/

	
	
	$query = "select a.uid,DATE_FORMAT(a.mwhen,'%d/%m/%Y') AS entrydate ,b.academicno,b.engname,case b.gender when 'M' then 'Male' when 'F' then 'Female' end as gender,CASE a.programmasteruid WHEN 1 THEN 'M.B.B.S' WHEN 2 THEN 'B.D.S'  WHEN 3 THEN 'Pharmacy'  WHEN 4 THEN 'Nursing' END as 'course',getReferenceValueByUID(studenttype_uid)student_type,a.contactno,case a.completestatus when '0'  THEN 'Not Started' when '1' THEN 'Academic Affairs' when '2' THEN 'Registration' when '3' THEN 'Financial Department' end as completestatus,CASE a.isapproved when 'N' then 'Rejected' when 'Y' then 'Approved' when 'P' then 'Pending' else 'Not Started' end as register from registration_2019 AS a, academicdetails as b where a.academicdetailsuid = b.uid AND a.statusflag='A' and b.statusflag='A' AND b.academicno LIKE '".$return['accno']."%' AND b.idno LIKE '".$return['idno']."%' AND b.engname LIKE '".$return['studname']."%' AND a.contactno LIKE '".$return['contactno']."%' ";
	
	
	if ($return["scholarship"] > 0){
		$query = $query . " AND a.scholartypeuid=".$return['scholarship'];
	}
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
	}
	if ($return["student_type"] > 0){
		$query = $query . " AND a.studenttype_uid =".$return['student_type'];
	}
	if ($return["completestatus"] != ""){
		$query = $query . " AND a.completestatus=".$return['completestatus'];
	}
	
	if ($return["register"] != ""){
		$query = $query . " AND a.isapproved= '".$return['register']. "'";
	}
	if ($return["gender"] != ""){
		$query = $query . " AND b.gender= '".$return['gender']. "'";
	}
    $result=mysql_query($query);    
    if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    }else{
		$rowsreturn = mysql_num_rows($result);
	}

	if($rowsreturn >= 1){	
		$create_table = '<div class="table-responsive"><h2 class="sub-header">'.$rowsreturn.' Results Found</h2><table class="table table-striped" id="searchresult"><thead><tr><th>S.No</th><th>Reg.Date</th><th>Academic No</th><th>Student Name</th><th>Gender</th><th>Program</th><th>Student Type</th><th>Status</th><th>Register</th><th>Action</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
		
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['entrydate'].'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['gender'].'</td><td>'.$fetchresult['course'].'</td><td>'.$fetchresult['student_type'].'</td><td>'.$fetchresult['completestatus'].'</td><td>'.$fetchresult['register'].'</td><td><a target="_blank" href="stud_archieve.php?reguid='.$fetchresult['uid'].'">View</a></td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	}else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table;
}



function search_assignedstaff(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
  
	$query = "SELECT a.uid,a.academicno,a.engname,getProgramname(a.programmasteruid)program,getYearnameByUid(yearuid)yearname,getSemesterNamebyUid(semesteruid)semester,b.staffname,b.extno,b.emailid,b.roomno,c.sunday,c.monday,c.tuesday,c.wednesday,c.thursday FROM `academicdetails` as a,staffs as b,staff_workinghrs as c where a.staffuid=b.uid  and c.staffuid =b.uid and a.academicno = '".$return['accno']."'";
    $result=mysql_query($query);    
    if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    }else{
		$rowsreturn = mysql_num_rows($result);
	}

	if($rowsreturn = 1){	
		
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;		
		$create_table = '<div class="col-sm-6 list-group col-sm-offset-3"><a href="#" class="list-group-item active" style="background-color: #d2271a;"><h4 class="list-group-item-heading">Name: '.$fetchresult['engname'].'</h4><p class="list-group-item-text" style="color: #ffffff;"> Program: '.$fetchresult['program'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Year: '.$fetchresult['yearname'].' &nbsp;&nbsp;&nbsp; Semester:'.$fetchresult['semester'].'</p></a><a href="#" class="list-group-item list-group-item" style="color:#000000;"><h4 class="list-group-item-heading ">Advisor: '.$fetchresult['staffname'].'</h4><p class="list-group-item-text"> Room No: '.$fetchresult['roomno'].' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Extn No: '.$fetchresult['extno'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Email:'.$fetchresult['emailid'].' </p></a></div>';		
		//WORKING HOURS
		$create_table = $create_table .'<h2 class="col-sm-5 sub-header ">Advisor&#39s Office Hours</h2><table class="table table-bordered" id="workinghrs"><thead><tr style="color: #000000;"><th>Sunday</th><th>Monday</th><th>Tuesday</th><th>Wednesday</th><th>Thursday</th></tr></thead><tbody><tr><td>'.$fetchresult['sunday'].'</td><td>'.$fetchresult['monday'].'</td><td>'.$fetchresult['tuesday'].'</td><td>'.$fetchresult['wednesday'].'</td><td>'.$fetchresult['thursday'].'</td></tr></tbody></table></div>';
		
		
		}
		
		
	}else{		
		$create_table = ' <h2 class="sub-header">No Results Found...</h2>';
	}	
	echo $create_table;
}

function advisor_search(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
  
	$query = "SELECT a.uid,a.academicno,a.engname,getProgramname(a.programmasteruid)program,getYearnameByUid(yearuid)yearname,getSemesterNamebyUid(semesteruid)semester,b.staffname,b.extno,b.emailid,b.roomno FROM `academicdetails` as a,staffs as b where a.staffuid=b.uid and a.academicno LIKE '".$return['accno']."%'";
	
	
	if ($return["staffuid"] > 0){
		$query = $query . " AND a.staffuid=".$return['staffuid'];
	}
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
	}
	if ($return["study_year"] > 0){
		$query = $query . " AND a.yearuid =".$return['study_year'];
	}
	if ($return["semester"] != ""){
		$query = $query . " AND a.semesteruid=".$return['semester'];
	}
	
	if ($return["gender"] != ""){
		$query = $query . " AND a.gender= '".$return['gender']. "'";
	}
    $result=mysql_query($query);    
    if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    }else{
		$rowsreturn = mysql_num_rows($result);
	}

	if($rowsreturn >= 1){	
		$create_table = '<div class="table-responsive"><h2 class="sub-header">'.$rowsreturn.' Results Found</h2><table class="table table-striped" id="searchresult"><thead><tr><th>S.No</th><th>Academic No</th><th>Student Name</th><th>Program</th><th>Year</th><th>Semester</th><th>Advisor Name</th><th>Extn.No</th><th>Room.No</th><th>Email</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
		
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['program'].'</td><td>'.$fetchresult['yearname'].'</td><td>'.$fetchresult['semester'].'</td><td>'.$fetchresult['staffname'].'</td><td>'.$fetchresult['extno'].'</td><td>'.$fetchresult['roomno'].'</td><td>'.$fetchresult['emailid'].'</td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	}else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table;
}


?>