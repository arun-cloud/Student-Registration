<?php

include(getcwd().'/db_connect.php'); 

if (is_ajax()) {
  if (isset($_POST["action"]) && !empty($_POST["action"])) { //Checks if action value exists
    $action = $_POST["action"];
    switch($action) { //Switch case for value of action
      case "report_not_register": search_not_register_function(); break;
      case "report_registered": search_report_registered_function(); break;
      case "report_register_process": search_report_register_process(); break;
      case "report_register_status": search_register_status(); break;
      case "report_student_subjects": search_subject_studentwise(); break;
      case "report_attendance": search_student_attendance(); break;
      case "report_vatpaid": search_vatpaid(); break;
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

function search_not_register_function(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
    
	$query = "select a.academicno,a.idno,a.engname,	getProgramName(a.programmasteruid)program,case a.gender when 'M' then 'Male' when 'F' then 'Female' end as gender,getReferenceValueByUID(a.studentstatusuid)studentstatus from academicdetails as a where a.uid not in (select academicdetailsuid from registration as b where b.statusflag= 'A') and a.statusflag='A' and a.academicno like '".$return['accno']."%' and a.engname like '".$return['studname']."%' and a.idno like '".$return['idno']."%'";
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid='".$return['program']."'";
	}
	if ($return["studentstatusuid"] > 0){
		$query = $query . " AND a.studentstatusuid='".$return['studentstatusuid']."'";
	}
	if ($return["gender"] != ""){
		$query = $query . " AND a.gender='".$return['gender']."'";
	}
 $query = $query." ORDER BY a.programmasteruid,engname";
  $result=mysql_query($query);    
    if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    $rowsreturn = mysql_num_rows($result);
	if($rowsreturn >= 1){ 	
		$create_table = '<div class="table-responsive"><table class="table table-bordered" id="searchresult"><thead><tr><th>S.No</th><th>Acc No</th><th>ID No</th><th>Student Name</th><th>Gender</th><th>Program</th><th>Status</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
		
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['idno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['gender'].'</td><td>'.$fetchresult['program'].'</td><td>'.$fetchresult['studentstatus'].'</td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	}else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table; }
	
	
	function search_report_registered_function(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
    
	$query = "select a.academicno,a.idno,a.engname,	getProgramName(a.programmasteruid)program,case a.gender when 'M' then 'Male' when 'F' then 'Female' end as gender from academicdetails as a where a.uid  in (select academicdetailsuid from registration as b where b.statusflag= 'A') and a.statusflag='A' and a.academicno like '".$return['accno']."%' and a.engname like '".$return['studname']."%' and a.idno like '".$return['idno']."%'";
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
	}
	if ($return["gender"] != ""){
		$query = $query . " AND a.gender='".$return['gender']."'";
	}
 $query = $query." ORDER BY a.programmasteruid,engname";
  $result=mysql_query($query);    
    if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    $rowsreturn = mysql_num_rows($result);
	if($rowsreturn >= 1){	
		$create_table = '<div class="table-responsive"><table class="table table-bordered" id="searchresult"><thead><tr><th>S.No</th><th>Acc. No</th><th>ID No</th><th>Student Name</th><th>Gender</th><th>Program</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
		
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['idno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['gender'].'</td><td>'.$fetchresult['program'].'</td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	}else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table; }
	
	
	function search_report_register_process(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
	
	$fromdate = DateTime::createFromFormat('d/m/Y', $return['fromdate']);
	$todate = DateTime::createFromFormat('d/m/Y', $return['todate']);
	
	$query = "select a.uid,DATE_FORMAT(a.cwhen,'%d/%m/%Y') AS entrydate ,b.academicno,b.engname,case b.gender when 'M' then 'Male' when 'F' then 'Female' end as gender,getProgramName(b.programmasteruid) course,a.contactno,case a.completestatus when '0'  THEN 'Not Started' when '1' THEN 'Academic Affairs' when '2' THEN 'Registration' when '3' THEN 'Financial Department' end as completestatus from registration AS a, academicdetails as b where a.academicdetailsuid = b.uid AND a.statusflag='A' and b.statusflag='A' AND b.academicno LIKE '".$return['accno']."%' AND b.idno LIKE '".$return['idno']."%' AND b.engname LIKE '".$return['studname']."%' AND a.contactno LIKE '".$return['contactno']."%' and cwhen >= '".date_format($fromdate,"Y-m-d 00:00:00")."' and cwhen <= '".date_format($todate,"Y-m-d 23:59:59")."' ";
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
	}
	if ($return["completestatus"] != ""){
		$query = $query . " AND a.completestatus=".$return['completestatus'];
	}
	
	if ($return["register"] != ""){
		$query = $query . " AND a.isapproved= '".$return['register']. "'";
	}
	
	if ($return["study_year"] > 0){
		$query = $query . " AND a.coursemasteruid= '".$return['study_year']. "'";
	}
	
	if ($return["semester"] > 0){
		$query = $query . " AND a.semesteruid= '".$return['semester']. "'";
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
		$create_table = '<div class="table-responsive"><table  class="table table-bordered" id="searchresult"><thead><tr><th>S.No</th><th>Reg.Date</th><th>Acc No</th><th>Student Name</th><th>Gender</th><th>Program</th><th>Status</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
		 
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['entrydate'].'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['gender'].'</td><td>'.$fetchresult['course'].'</td><td>'.$fetchresult['completestatus'].'</td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	}else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table;	}
	
	function search_register_status(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
	
	$fromdate = DateTime::createFromFormat('d/m/Y', $return['fromdate']);
	$todate = DateTime::createFromFormat('d/m/Y', $return['todate']);
	
	$query = "select a.uid,DATE_FORMAT(a.cwhen,'%d/%m/%Y') AS entrydate ,b.academicno,b.engname,case b.gender when 'M' then 'Male' when 'F' then 'Female' end as gender,getProgramName(b.programmasteruid)course,a.contactno,case a.completestatus when '0'  THEN 'Not Started' when '1' THEN 'Academic Affairs' when '2' THEN 'Registration' when '3' THEN 'Financial Department' end as completestatus,CASE a.isapproved when 'N' then 'Rejected' when 'Y' then 'Approved' else 'Not Started' end as register from registration AS a, academicdetails as b where a.academicdetailsuid = b.uid AND a.statusflag='A' and b.statusflag='A' AND b.academicno LIKE '".$return['accno']."%' AND b.idno LIKE '".$return['idno']."%' AND b.engname LIKE '".$return['studname']."%' AND a.contactno LIKE '".$return['contactno']."%' and cwhen >= '".date_format($fromdate,"Y-m-d 00:00:00")."' and cwhen <= '".date_format($todate,"Y-m-d 23:59:59")."' AND a.completestatus=3 ";
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
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
		$create_table = '<div class="table-responsive"><table  class="table table-bordered" id="searchresult"><thead><tr><th>S.No</th><th>Reg.Date</th><th>Acc No</th><th>Student Name</th><th>Gender</th><th>Program</th><th>Register</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
	
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['entrydate'].'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['gender'].'</td><td>'.$fetchresult['course'].'</td><td>'.$fetchresult['register'].'</td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	} else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table;
	}



function search_subject_studentwise(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
	
	$fromdate = DateTime::createFromFormat('d/m/Y', $return['fromdate']);
	$todate = DateTime::createFromFormat('d/m/Y', $return['todate']);
	
	$query = "select 	b.academicno,b.engname,a.studenttype_uid,getProgramName(a.programmasteruid)programname,getYearnameByUid(a.coursemasteruid)courseyear,case gender when 'M' then 'Male' else 'Female' end as gender,a.add_subjects,a.repeat_subjects from registration as a, academicdetails as b where a.academicdetailsuid = b.uid and a.statusflag='A' and b.statusflag='A' and a.isapproved='Y' and b.academicno like '".$return['accno']."%' AND b.engname LIKE '".$return['studname']."%' AND a.contactno LIKE '".$return['contactno']."%' ";
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
	}
	
	if ($return["study_year"] > 0){
		$query = $query . " AND a.coursemasteruid= '".$return['study_year']. "'";
	}
	
	if ($return["semester"] > 0){
		$query = $query . " AND a.semesteruid= '".$return['semester']. "'";
	}
	
	
	if ($return["gender"] != ""){
		$query = $query . " AND b.gender= '".$return['gender']. "'";
	}
	$query = $query . " order by a.programmasteruid,a.coursemasteruid";
    $result=mysql_query($query);    
    if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    }else{
		$rowsreturn = mysql_num_rows($result);
	}

	if($rowsreturn >= 1){	
		$create_table = '<div class="table-responsive"><table  class="table table-bordered" id="searchresult"><thead><tr><th>S.No</th><th>Acc.No</th><th>Student Name</th><th>Program</th><th>Year</th><th>Gender</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		$isfound = false;
		while($fetchresult = mysql_fetch_array($result)) {	
		if($fetchresult['studenttype_uid'] == 5 || $fetchresult['studenttype_uid'] == 6){
		$isfound = true;
		}
		if($fetchresult['add_subjects'] != ""){
		$edit_addsubj = explode(',', $fetchresult["add_subjects"]);
		for($i = 0; $i < count($edit_addsubj); $i++){
		if ($edit_addsubj[$i] === $return["subject"]){ $isfound= true;	break;}else{ $isfound = false;}
		}
		}
		if($fetchresult['repeat_subjects'] != ""){
		$edit_repeatsubj = explode(',', $fetchresult["repeat_subjects"]);
		for($i = 0; $i < count($edit_repeatsubj); $i++){
		if ($edit_repeatsubj[$i] === $return["subject"]){ $isfound= true;	break;}else{ $isfound = false;}
		}
		}
		
		if($isfound === TRUE){
			$rows_number= $rows_number+1;
	
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['programname'].'</td><td>'.$fetchresult['courseyear'].'</td><td>'.$fetchresult['gender'].'</td></tr>';
		}
		
		
		
							
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	} else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table;
	}


function search_student_attendance(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
	
	$query = "select 	b.academicno,b.engname,a.studenttype_uid,getProgramName(a.programmasteruid)programname,getYearnameByUid(a.coursemasteruid)courseyear,case gender when 'M' then 'Male' else 'Female' end as gender,a.add_subjects,a.repeat_subjects from registration as a, academicdetails as b where a.academicdetailsuid = b.uid and a.statusflag='A' and b.statusflag='A' and a.isapproved='Y' and b.academicno like '".$return['accno']."%' AND b.engname LIKE '".$return['studname']."%' AND a.contactno LIKE '".$return['contactno']."%' ";
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid=".$return['program'];
	}
	
	if ($return["study_year"] > 0){
		$query = $query . " AND a.coursemasteruid= '".$return['study_year']. "'";
	}
	
	if ($return["semester"] > 0){
		$query = $query . " AND a.semesteruid= '".$return['semester']. "'";
	}
	
	
	if ($return["gender"] != ""){
		$query = $query . " AND b.gender= '".$return['gender']. "'";
	}
	$query = $query . " order by a.programmasteruid,a.coursemasteruid";
    $result=mysql_query($query);    
    if(!$result) {
        die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    }else{
		$rowsreturn = mysql_num_rows($result);
	}

	if($rowsreturn >= 1){	
		$create_table = '<div class="table-responsive"><table  class="table table-bordered" id="searchresult"><thead><tr><th>S.No</th><th>Acc.No</th><th>Student Name</th>';
		
		for($i = 1; $i <=15 ; $i++){
			$create_table = $create_table . '<th>'.$i.'</th>';
		}
		$create_table = $create_table .'</tr></thead><tbody>';
		
		$tablerows ='';
		$rows_number =0;
		$isfound = false;
		while($fetchresult = mysql_fetch_array($result)) {	
		if($fetchresult['studenttype_uid'] == 5 || $fetchresult['studenttype_uid'] == 6){
		$isfound = true;
		}
		if($fetchresult['add_subjects'] != ""){
		$edit_addsubj = explode(',', $fetchresult["add_subjects"]);
		for($i = 0; $i < count($edit_addsubj); $i++){
		if ($edit_addsubj[$i] === $return["subject"]){ $isfound= true;	break;}else{ $isfound = false;}
		}
		}
		if($fetchresult['repeat_subjects'] != ""){
		$edit_repeatsubj = explode(',', $fetchresult["repeat_subjects"]);
		for($i = 0; $i < count($edit_repeatsubj); $i++){
		if ($edit_repeatsubj[$i] === $return["subject"]){ $isfound= true;	break;}else{ $isfound = false;}
		}
		}
		
		if($isfound === TRUE){
			$rows_number= $rows_number+1;
	
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['engname'].'</td>';
		for($i = 1; $i <=15 ; $i++){
			$tablerows = $tablerows . '<td> &nbsp;&nbsp;&nbsp;</td>';
		}
		$tablerows = $tablerows. '</tr>';
		}
		
		
		
							
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	} else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table;
	}

function search_vatpaid(){
	$return = array();	
	foreach($_POST as $key => $value) {    
    $_POST[$key] = your_filter($value);
    $return[$key]= $_POST[$key];
    }
    
	$query = "select a.academicno,a.idno,a.engname,	getProgramName(a.programmasteruid)program,case a.gender when 'M' then 'Male' when 'F' then 'Female' end as gender from academicdetails as a where a.uid not in (select academicdetailsuid from registration as b where b.statusflag= 'A') and a.statusflag='A' and a.academicno like '".$return['accno']."%' and a.engname like '".$return['studname']."%' and a.idno like '".$return['idno']."%'";
	if ($return["program"] > 0){
		$query = $query . " AND a.programmasteruid='".$return['program']."'";
	}
	if ($return["gender"] != ""){
		$query = $query . " AND a.gender='".$return['gender']."'";
	}
 $query = $query." ORDER BY a.programmasteruid,engname";
  $result=mysql_query($query);    
    if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error());
    $rowsreturn = mysql_num_rows($result);
	if($rowsreturn >= 1){ 	
		$create_table = '<div class="table-responsive"><table class="table table-bordered" id="searchresult"><thead><tr><th>S.No</th><th>Acc No</th><th>ID No</th><th>Student Name</th><th>Gender</th><th>Program</th></tr></thead><tbody>';
		$tablerows ='';
		$rows_number =0;
		while($fetchresult = mysql_fetch_array($result)) {	
		$rows_number= $rows_number+1;
		
		$tablerows = $tablerows . '<tr><td>'.$rows_number.'</td><td>'.$fetchresult['academicno'].'</td><td>'.$fetchresult['idno'].'</td><td>'.$fetchresult['engname'].'</td><td>'.$fetchresult['gender'].'</td><td>'.$fetchresult['program'].'</td></tr>';					
		}
		$create_table = $create_table . $tablerows .'</tbody></table></div>';
		
	}else{		
		$create_table = ' <h2 class="sub-header">0 Results Found...</h2>';
	}	
	echo $create_table; }

?>