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
	  $rowscount =0;
	  $rows = $return = $subjects = $addrepeatsubj = $arresult =  array(); 
	  $query ="SELECT b.engname,b.academicno,a.add_subjects,a.repeat_subjects,a.programmasteruid,a.semesteruid,a.studenttype_uid,getProgramName(a.programmasteruid) AS programname,getYearnameByUid(a.coursemasteruid) AS courseyear,getSemesterNamebyUid(a.semesteruid)semestername,a.email,a.contactno,a.otherno,getReferenceValueByUID(a.studenttype_uid)studenttype,getReferenceValueByUID(a.scheduleuid)scheduletype,a.academic_officer,a.academic_date,case a.isno_objection when 'Y' then 'No Objection to register the student above mentioned name in the study year 2017' when 'N' then 'Student above mentioned name is not allowed to register in the study year 2017' else 'Student above mentioned name is not allowed to register in the study year 2017' end as isnoobjection,a.scholartypeuid,a.semester_fees,a.register_officer,a.register_date,a.concessionfees,b.prevpendingfees,a.feespaid,getReferenceValueByUID(a.isfullypaid)fullypaid,a.fullypaid_date,a.finance_officer,a.isapproved,a.dean_name FROM registration as a, academicdetails as b where a.academicdetailsuid = b.uid and a.statusflag='A' and b.statusflag='A' and a.uid=".$_GET['reguid'];
	  
	 // $query = "CALL pGetRegistrationDetails(".$_GET['reguid'].")";
	  $result=mysql_query($query); 	
	
	  if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
	  $row = mysql_fetch_assoc($result);
	  mysql_free_result($result);

	 
	  	$_POST['engname'] = your_filter($row['engname']);
	  	$_POST['academicno'] = your_filter($row['academicno']);
	  	$_POST['programmasteruid'] = your_filter($row['programmasteruid']);
	  	$_POST['semesteruid'] = your_filter($row['semesteruid']);
	  	$_POST['add_subjects']= your_filter($row['add_subjects']);
	  	$_POST['repeat_subjects']= your_filter($row['repeat_subjects']);
	  	$_POST['programname'] = your_filter($row['programname']);
	  	$_POST['courseyear'] = your_filter($row['courseyear']);
	  	$_POST['semestername'] = your_filter($row['semestername']);
	  	$_POST['email'] = your_filter($row['email']);
	  	$_POST['contactno'] = your_filter($row['contactno']);
	  	$_POST['otherno'] = your_filter($row['otherno']);
	  	$_POST['studenttype_uid'] = your_filter($row['studenttype_uid']);
	  	$_POST['studenttype'] = your_filter($row['studenttype']);
	  	$_POST['scheduletype'] = your_filter($row['scheduletype']);
	  	$_POST['academic_officer'] = your_filter($row['academic_officer']);
	  	$_POST['academic_date'] = your_filter($row['academic_date']);
	  	$_POST['isnoobjection'] = your_filter($row['isnoobjection']);
	  	$_POST['isscholar_avail'] = your_filter($row['scholartypeuid']);
	  	$_POST['semester_fees'] = your_filter($row['semester_fees']);
	  	$_POST['register_officer'] = your_filter($row['register_officer']);
	  	$_POST['register_date'] = your_filter($row['register_date']);
	  	$_POST['concessionfees'] = your_filter($row['concessionfees']);
	  	$_POST['prevpendingfees'] = your_filter($row['prevpendingfees']);
	  	$_POST['feespaid'] = your_filter($row['feespaid']);
	  	$_POST['fullypaid'] = your_filter($row['fullypaid']);
	  	$_POST['fullypaid_date'] = your_filter($row['fullypaid_date']);
	  	$_POST['finance_officer'] = your_filter($row['finance_officer']);
	  	$_POST['isapproved'] = your_filter($row['isapproved']);
	  	$_POST['dean_name'] = your_filter($row['dean_name']);
	  	
	  	
	  if($_POST['studenttype_uid'] == 5 || $_POST['studenttype_uid'] == 6){
	  	if($_POST['isscholar_avail'] == 17){
		$query = "select * from (select uid from subjectmaster where programmasteruid='".$_POST['programmasteruid']."' and semestermasteruid ='".$_POST['semesteruid']."' and Statusflag = 'A' union all select uid from subjectmaster where UID in (".$_POST['add_subjects'].") and Statusflag = 'A')as t group by uid having count(uid)>1";	
		$result1=mysql_query($query); 
			 if(!$result1) { die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); } else{ 
			 if(mysql_num_rows($result1)== 0){
					$query = "select subjectcode,subjectname,credithour from subjectmaster where programmasteruid='".$_POST['programmasteruid']."' and semestermasteruid ='".$_POST['semesteruid']."' and Statusflag = 'A' order by displayorder";	
			
					  $result1=mysql_query($query); 
				  if(!$result1) { die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); } else{ $rowscount = $rowscount + mysql_num_rows($result1);}
				  
				  while($fetchresult1 = mysql_fetch_array($result1))
					$subjects[] = $fetchresult1;	
				}
		 	}
		
	}else{
		$query = "select subjectcode,subjectname,credithour from subjectmaster where programmasteruid='".$_POST['programmasteruid']."' and semestermasteruid ='".$_POST['semesteruid']."' and Statusflag = 'A' order by displayorder";	
			
					  $result1=mysql_query($query); 
				  if(!$result1) { die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); } else{ $rowscount = $rowscount + mysql_num_rows($result1);}
				  
				  while($fetchresult1 = mysql_fetch_array($result1))
					$subjects[] = $fetchresult1;
	}
	
		
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
    <link rel="icon" href="../../favicon.ico">

    <title>Table of Registered Subjects</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
        <!--<link href="dist/custom_css/print.css" media="print" type="text/css" rel="stylesheet">-->

	<style>
	
	body {margin:0; padding:0; line-height: 1.4em; word-spacing:1px; letter-spacing:0.2px; font: 15px Arial, Helvetica,"Lucida Grande", serif; color: #000;}


	

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
.page-header{
	border-bottom: 1px solid black;
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
  <body onload="window.print()">
    <div class="container">
 <div class="header clearfix">
		<div class="row">
	<img src="assets/img/bnr.png" class="img-responsive">
   </div></div>
      <div  class="page-header text-center">
        <h3>Table of Registered Subjects</h3>
      </div>
	   
	   
	   <form class="form-horizontal">
	   	<div class="form-group">
	   	<label for="academicno" class="col-sm-3 control-label">Academic Number</label>
	   	<span class="col-sm-6"> : <?php echo $_POST['academicno']; ?></span>
	   	</div>
	   	
	   	<div class="form-group">
	   	<label for="academicno" class="col-sm-3 control-label" style="margin-right: 35px;" >Student Name</label>
	   	<span class="col-sm-6"> : <?php echo $_POST['engname']; ?></span>
	   	</div>
	   	
	   	<div class="form-group">
	   	<label for="academicno" class="col-sm-3 control-label" style="margin-right: 30px;">Program Name</label>
	   	<span class="col-sm-6"> : <?php echo $_POST['programname']; ?></span>
	   	</div>
	   	
	   	<div class="form-group">
	   	<label for="academicno" class="col-sm-3 control-label" style="margin-right: 4em;">Study Year</label>
	   	<span class="col-sm-6"> : <?php echo $_POST['courseyear']; ?></span>
	   	</div>
	   	
	   	<div class="form-group">
	   	<span class="col-sm-6">Subjects registered for <?php echo $_POST['semestername']; ?> 2017 - 2018</span>
	   	</div>
	   	
	   	<div class="form-group">
	   	<?php 
	  
	   		
	   	  $tablerows ='';
		  $rows_number =0;
		  $totalcredit =0;
		  echo '<div class="table-responsive"><table class="table table-bordered" style=""><thead><tr><th>Student Type</th><th>Subj.Code</th><th>Subject Name</th><th>Credit Hrs</th></tr></thead><tbody>';
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
	   	
	   
	    <div class="form-group">
	    <img alt="stamp" src="assets/img/signfooter.png" />
	    </div>
	   
	   	
	   </form>
	  
	  <!-- <div class="row">
	    <div class="col-sm-4">
	    <span><h4>Registrar</h4></span>
	    <img alt="signature" src="assets/img/signature.png"/> 
	    <span><h4>Mr.Mishaal Alharbi</h4></span></div>
	   <div class="col-sm-4">
	    <img alt="stamp" src="assets/img/mishal.png" class="pull-right" /></div>
	   </div>-->
	   
	   <div class="row">
	    
	   
	   </div>
	   
	   
	 
</div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
