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
	  $query ="SELECT a.invoiceno,b.engname,b.academicno,b.idno,a.programmasteruid,getArabprogramname(a.programmasteruid)programname,a.semester_fees,a.vatamt,a.fullypaid_date,a.semester_fees+a.vatamt total FROM registration as a, academicdetails as b where a.academicdetailsuid = b.uid and a.statusflag='A' and b.statusflag='A' and a.uid=".$_GET['reguid'];
	  
	  $result=mysql_query($query); 	
	
	  if(!$result) die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
	  $row = mysql_fetch_assoc($result);
	  mysql_free_result($result);

	 	$_POST['invoiceno'] = your_filter($row['invoiceno']);
	  	$_POST['engname'] = your_filter($row['engname']);
	  	$_POST['academicno'] = your_filter($row['academicno']);
	  	$_POST['idno'] = your_filter($row['idno']);
	  	$_POST['programname'] = your_filter($row['programname']);
	  	$_POST['semester_fees'] = your_filter($row['semester_fees']);
	  	$_POST['vatamt']= your_filter($row['vatamt']);
	  	$_POST['fullypaid_date']= your_filter($row['fullypaid_date']);
	  	$_POST['total']= your_filter($row['total']);
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

    <title>Student Invoice</title>

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
       <h3>Invoice - <?php echo $_POST['invoiceno']; ?></h3>
      </div>
	  <p><span><b>Invoice Date:</b><?php echo $_POST['fullypaid_date']; ?></span> 


	  <span style="padding-left: 21%;"><b>الرقم الضريبي :</b> 300251366400003</span></p>
	  <br />
	   
	   <br /><br />
	   <form class="form-horizontal">
	   	<div class="form-group">
	   	<label for="academicno" class="col-sm-3 control-label">الرقم الاكاديمي /Academic Number</label>
	   	<span class="col-sm-6" style="padding-left: 10%;"> : <?php echo $_POST['academicno']; ?></span>
	   	</div>
	   	
	   		<div class="form-group">
	   	<label for="idno" class="col-sm-3 control-label">رقم الاقامه / الهوية الوؕنية /Iqama/ID Number </label>
	   	<span class="col-sm-6"> : <?php echo $_POST['idno']; ?></span>
	   	</div>
	   	
	   	
	   	
	   
	   	
	   	
	   	<div class="form-group">
	   	<label for="academicno" class="col-sm-3 control-label" style="margin-right: 35px;" >اسم الؕالب/Student Name</label>
	   	<span class="col-sm-6" style="padding-left: 14%;"> : <?php echo $_POST['engname']; ?></span>
	   	</div>
	   	
	   	<div class="form-group">
	   	<label for="academicno" class="col-sm-3 control-label" style="margin-right: 30px;">البرنامج/Program Name</label>
	   	<span class="col-sm-6" style="padding-left: 15%;"> : <?php echo $_POST['programname']; ?></span>
	   	</div>
	   	
	   	
	   	<div class="form-group">
	   	<?php 
	  
	   		
	   	  $tablerows ='';
		 
		  echo '<div class="table-responsive"><table class="table table-bordered" style=""><thead><tr><th>Slno</th><th>الوص� /Description</th><th>Fees</th><th>VAT</th><th>Net Total</th></tr></thead><tbody>';
		  
		$tablerows = $tablerows . '<tr><td>1</td><td>Semester Fees<br\>رسوم الفصل الدراسي</td><td>'.$_POST['semester_fees'].'</td><td>'.$_POST['vatamt'].'</td><td>'.$_POST['total'].'</td></tr>';					
		$tablerows = $tablerows . '<td colspan="4">Total</td><td>'.$_POST['total'].'</td></tr>';		
		$tablerows =  $tablerows .'</tbody></table></div>';
		echo $tablerows;
	   	?>
	   		
	   	</div>
	   
	   <br /><br />
	   <p><b>ؕبعت بواسؕة/Printed By</b><br /><?php echo $_SESSION['username']; ?></p>
	   	
	   </form>
	
	   
	   
	 
</div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
