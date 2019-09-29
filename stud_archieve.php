<?php 
session_start(); //Start the session
date_default_timezone_set('Asia/Riyadh');  
if(!isset($_SESSION['username'])){ //If session not registered
header("location:".getcwd()."/signin.php"); // Redirect to login.php page
}
else //Continue to current page
{	
	if (isset($_GET['reguid']))
	{	
	  $reguid = (isset($_GET['reguid']) ? $_GET['reguid'] : null);	
	  $reguid = your_filter($reguid);
	  include(getcwd().'/db_connect.php'); 
	  $rows = $return = array(); 
	    $rowscount =0;
	  $query = "SELECT b.engname,b.academicno,a.add_subjects,a.repeat_subjects,a.programmasteruid,a.semesteruid,a.studenttype_uid,getProgramName(a.programmasteruid) AS programname,getYearnameByUid(a.coursemasteruid) AS courseyear,getSemesterNamebyUid(a.semesteruid)semestername,a.email,a.contactno,a.otherno,getReferenceValueByUID(a.studenttype_uid)studenttype,getReferenceValueByUID(a.scheduleuid)scheduletype,a.academic_officer,a.academic_date,case a.isno_objection when 'Y' then 'No Objection to register the student above mentioned name in the study year 2018' when 'N' then 'Student above mentioned name is not allowed to register in the study year 2017' else 'Student above mentioned name is not allowed to register in the study year 2018' end as isnoobjection,a.scholartypeuid,a.semester_fees,a.register_officer,a.register_date,a.concessionfees,b.prevpendingfees,a.feespaid,getReferenceValueByUID(a.isfullypaid)fullypaid,a.fullypaid_date,a.finance_officer,a.isapproved,a.dean_name FROM registration_2019 as a, academicdetails as b where a.academicdetailsuid = b.uid and a.statusflag='A' and b.statusflag='A' and a.uid= ".$_GET['reguid'];
	  $result=mysql_query($query); 
	  if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
	  while($fetchresult = mysql_fetch_array($result)){
	  	$_POST['engname'] = your_filter($fetchresult['engname']);
	  	$_POST['academicno'] = your_filter($fetchresult['academicno']);
	  	$_POST['programname'] = your_filter($fetchresult['programname']);
	  	$_POST['programmasteruid'] = your_filter($fetchresult['programmasteruid']);
	  	$_POST['courseyear'] = your_filter($fetchresult['courseyear']);
	  	$_POST['semesteruid'] = your_filter($fetchresult['semesteruid']);
	  	$_POST['semestername'] = your_filter($fetchresult['semestername']);
	  	$_POST['studenttype_uid'] = your_filter($fetchresult['studenttype_uid']);
	  	$_POST['add_subjects']= your_filter($fetchresult['add_subjects']);
	  	$_POST['repeat_subjects']= your_filter($fetchresult['repeat_subjects']);
	  	$_POST['email'] = your_filter($fetchresult['email']);
	  	$_POST['contactno'] = your_filter($fetchresult['contactno']);
	  	$_POST['otherno'] = your_filter($fetchresult['otherno']);
	  	$_POST['studenttype'] = your_filter($fetchresult['studenttype']);
	  	$_POST['scheduletype'] = your_filter($fetchresult['scheduletype']);
	  	$_POST['academic_officer'] = your_filter($fetchresult['academic_officer']);
	  	$_POST['academic_date'] = your_filter($fetchresult['academic_date']);
	  	$_POST['isnoobjection'] = your_filter($fetchresult['isnoobjection']);
	  	$_POST['isscholar_avail'] = your_filter($fetchresult['scholartypeuid']);
	  	$_POST['semester_fees'] = your_filter($fetchresult['semester_fees']);
	  	$_POST['register_officer'] = your_filter($fetchresult['register_officer']);
	  	$_POST['register_date'] = your_filter($fetchresult['register_date']);
	  	$_POST['concessionfees'] = your_filter($fetchresult['concessionfees']);
	  	$_POST['prevpendingfees'] = your_filter($fetchresult['prevpendingfees']);
	  	$_POST['feespaid'] = your_filter($fetchresult['feespaid']);
	  	$_POST['fullypaid'] = your_filter($fetchresult['fullypaid']);
	  	$_POST['fullypaid_date'] = your_filter($fetchresult['fullypaid_date']);
	  	$_POST['finance_officer'] = your_filter($fetchresult['finance_officer']);
	  	$_POST['isapproved'] = your_filter($fetchresult['isapproved']);
	  	$_POST['dean_name'] = your_filter($fetchresult['dean_name']);
	  }
		  
		if($_POST['studenttype_uid'] == 5 || $_POST['studenttype_uid'] == 6){
		$query = "select subjectcode,subjectname,credithour from subjectmaster where programmasteruid='".$_POST['programmasteruid']."' and semestermasteruid ='".$_POST['semesteruid']."' and Statusflag = 'A' order by displayorder";	
		
		  $result1=mysql_query($query); 
	  if(!$result1) { die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); } else{ $rowscount = $rowscount + mysql_num_rows($result1);}
	  
	  while($fetchresult1 = mysql_fetch_array($result1))
		$subjects[] = $fetchresult1;	
	}
   	if($_POST['add_subjects'] != '' || $_POST['repeat_subjects'] != ''){
   			if($_POST['add_subjects'] != '' && $_POST['repeat_subjects'] != '') $totalsubj = $_POST['add_subjects'].','.$_POST['repeat_subjects'];
   			
   			if($_POST['add_subjects'] == '' && $_POST['repeat_subjects'] != '') $totalsubj = $_POST['repeat_subjects'];
   			if($_POST['add_subjects'] != '' && $_POST['repeat_subjects'] == '') $totalsubj = $_POST['add_subjects'];
   			
   		//$totalsubj = $_POST['add_subjects'].','.$_POST['repeat_subjects'];
   		
   		$query1 = "select subjectcode,subjectname,credithour from subjectmaster where UID in (".$totalsubj.") and Statusflag = 'A' order by displayorder";
		$addresult=mysql_query($query1); 
		if(!$addresult){ die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); }else{ $rowscount = $rowscount + mysql_num_rows($addresult);}
		while($fetchresult1 = mysql_fetch_array($addresult))
		$addrepeatsubj[] = $fetchresult1;	
   		}  
	
		
	    
	}
	
}

function your_filter($value) {
    $newVal = trim($value);
    $newVal = htmlspecialchars($newVal);
    $newVal = mysql_real_escape_string($newVal);
    return $newVal;
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

    <title>Reciept</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- <link href="dist/custom_css/print.css" media="print" type="text/css" rel="stylesheet">-->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    

	<style>
	.box{ border: 1px solid black;
   }	
 .borderright{
   	 border-right:1px solid #16222c;
   	}
   .box span,p{ margin: 5px; 	}
   
   hr { 
    display: block;
    margin-top: 0.2em;
    margin-bottom: 0.2em;
    margin-left: auto;
    margin-right: auto;
    border-style: inset;
    border-width: 1px;
    border-color: #000000;
}

		
	</style>
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<!--onload="window.print()-->
  <body>
    <div class="container">

      <div class="page-header text-center">
        <h1>Student Registration Form - 2018</h1>
      </div>
	   
	   
	   <div class="box">
	   <big><center><b>Student Information</b></center></big>
	   <hr />
	   <div class="row">
	   <span class="col-sm-6"><b>Name:</b> <?php echo ucfirst($_POST['engname']); ?></span>
	   <span class=" col-sm-5"><b>Academic Number:</b> <?php echo $_POST['academicno']; ?></span>
	   </div>
	   <hr />
	   <div class="row">
	   <span class="col-sm-6"><b>Program:</b> <?php echo $_POST['programname']; ?></span>
	   <span class="col-sm-3"><b>Year:</b> <?php echo $_POST['courseyear']; ?></span>
	   <span class="col-sm-2"><b>Semester:</b> <?php echo $_POST['semestername']; ?></span>
	   </div>
	   <hr />
	   <div class="row">
	   <span class="col-sm-6"><b>E-Mail:</b> <?php echo $_POST['email']; ?></span>
	   <span class=" col-sm-3"><b>Contact No:</b> <?php echo $_POST['contactno']; ?></span>
	   <span class=" col-sm-2"><b>Other No:</b> <?php echo $_POST['otherno']; ?></span>
	   </div>
	   <hr />
	   <div class="row">
	   <p class="col-sm-12 col-md-11 text-justify">I am the student, my date shown above; request the registration in the second term for the study year 2017/2018. <br /><br /> I acknowledged the validity of my information above mentioned and the college has right to cancel my request it proved otherwise and i promise to pay the fees within three dya and to complete the registration. The college has right to stop me from studying in case of non-payment of fees.</p>
	   </div>
	   <hr />
	   <div class="row">
	   <br />
	   	<span class="col-sm-3"><b>Date: </b><?php  echo date('d/m/Y'); ?></span>
	   	<span class="col-sm-2" style="float: right;"><b>Student Signature</b></span>
	   	
	   </div>
	   </div>
	   <br />
	   <!--Academic Affairs-->
	   <div class="box">
	   <big><center><b>Academic Affairs</b></center></big>
	   <hr />
	   <div class="row">
	   <span class="col-sm-3"><b>Student Type:</b> <?php echo $_POST['studenttype']; ?></span>
	   <span class=" col-sm-3" ><b>Schedule:</b> <?php echo $_POST['scheduletype']; ?></span>
	   </div><br />
	   <div class="form-group ">
	   	<?php 
	  
	   		
	   	  $tablerows ='';
		  $rows_number =0;
		  $totalcredit =0;
		  echo '<div class="table-responsive"><table class="table table-bordered" style="color:black;"><thead><tr><th>Student Type</th><th>Subj.Code</th><th>Subject Name</th><th>Credit Hrs</th></tr></thead><tbody>';
		  if(!empty($subjects)){
		  
		  	foreach($subjects as $key => $value){
		  	$rows_number= $rows_number+1;
		  	$totalcredit = $totalcredit + $value["credithour"];
		  	$tablerows = $tablerows . '<tr>';
		  	if($rows_number == 1) $tablerows = $tablerows . '<td rowspan="'.$rowscount.'">'.$_POST['studenttype'].'</td>';
		  	$tablerows = $tablerows . '<td>'.$value["subjectcode"].'</td><td>'.$value["subjectname"].'</td><td>'.$value["credithour"].'</td></tr>';
		  	}
		  	
		  	}
		  	
		  	  if(!empty($addrepeatsubj)){
		  	
		  	foreach($addrepeatsubj as $key => $value){
		  	$rows_number= $rows_number+1;
		  	$totalcredit = $totalcredit + $value["credithour"];
		  	$tablerows = $tablerows . '<tr>';
		  	if($rows_number == 1) $tablerows = $tablerows . '<td rowspan="'.$rowscount.'">'.$_POST['studenttype'].'</td>';
		  	if($_POST['studenttype_uid'] == 6) $tablerows = $tablerows . '<td>'.$value["subjectcode"].'</td><td><b>* </b>'.$value["subjectname"].'</td><td>'.$value["credithour"].'</td></tr>'; else $tablerows = $tablerows . '<td>'.$value["subjectcode"].'</td><td>'.$value["subjectname"].'</td><td>'.$value["credithour"].'</td></tr>';
		  	
		  	}
		  	
		  	
		  	
		  	
		  
			
		}
		$tablerows = $tablerows . '<td colspan="3">Total</td><td>'.$totalcredit.'</td></tr>';
		$tablerows =  $tablerows .'</tbody></table></div>';
		if($_POST['studenttype_uid'] == 6) $tablerows =  $tablerows .'<span><i>Note: * - Test Only</i></span>';
		echo $tablerows;
	   	?>
	   		
	   	</div>
	   <div class="row">
	   <span class="col-sm-11"><?php echo $_POST['isnoobjection']; ?></span>	   	   
	   </div>
	<br />
	   <div class="row">
	   <span class="col-sm-6"><b>Officer Name:</b> <?php echo $_POST['academic_officer']; ?></span>
	   <span class=" col-sm-3"><b>Date: </b><?php echo $_POST['academic_date']; ?></span>
	   <span class=" col-sm-2"  style="float: right;"><b>Officer Sign &amp; Stamp</b></span>
	   </div>
	   
	   </div>
	   <br />
	   <!--Registration-->
	   <div class="box">
	   <big><center><b>Registration</b></center></big>
	   <hr />
	   <div class="row">
	   <span class="col-sm-11">After reviewing the student data above, &nbsp; <?php if($_POST['isscholar_avail'] == 16){ echo 'موارد';} elseif($_POST['isscholar_avail'] == 17){ echo 'Scholarship is available for the student';} elseif($_POST['isscholar_avail'] == 18) { echo 'Student should pay the semester fees: <b>'.$_POST['semester_fees'].'</b>';}?></span>	   
	   </div>
	   
	   
	   <br />
	   <div class="row">
	   <span class="col-sm-6"><b>Officer Name:</b> <?php echo $_POST['register_officer']; ?></span>
	   <span class=" col-sm-3"><b>Date: </b><?php echo $_POST['register_date']; ?></span>
	   <span class=" col-sm-2" style="float: right;"><b>Officer Sign &amp; Stamp</b></span>
	   </div>
	   
	   </div>
	   <br />

	    <!--Financial Department-->
	   <div class="box">
	   <big><center><b>Financial Department</b></center></big>
	   <hr />
	   <div class="row">
	   <span class="col-sm-11">Recieved the amount of: <b><?php echo $_POST['feespaid']; ?></b></span>	   
	   </div>
	   <br />
	   <div class="row">
	   <span class="col-sm-6"><b>Officer Name:</b> <?php echo $_POST['finance_officer']; ?></span>
	   <span class=" col-sm-3"><b>Date:</b> <?php echo $_POST['fullypaid_date']; ?></span>
	   <span class=" col-sm-2" style="float: right;"><b>Officer Sign &amp; Stamp</b></span>
	   </div>

	   <div class="row">
	   <span class="col-sm-11">The form of request is not considered acceptable without the seal of the financial department.</span>	   
	   </div>
	   </div>
	   <br />
</div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
