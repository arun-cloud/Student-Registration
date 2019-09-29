<?php
session_start(); //Start the session
if(!isset($_SESSION['username'])){ //If session not registered
header("location:signin.php"); // Redirect to login.php page
}
else //Continue to current page
{
	
header( 'Content-Type: text/html; charset=utf-8' );
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

    <title>Search Student</title>

    <!-- Bootstrap core CSS -->
     <link href="dist/css/bootstrap.min.css" rel="stylesheet">

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
              <li><a href="index.php">Home</a></li>
              <li><a href="student_search.php">Search</a></li>
              <li class="active"><a href="reports.php">Reports</a></li>
		<li><a target="_blank" href="register.php">Student Registration</a></li>
	    <li class="dropdown">
	        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Student Details
	        <span class="caret"></span></a>
	        <ul class="dropdown-menu">
	          <li><a href="student_notice.php">Notices</a></li>
		     <li><a href="studentupdate.php">Change Iqama/ID</a></li>
		     <li><a href="statuschange.php">Change Status</a></li>
	        </ul>
     	 </li>
	     <li><a href="search_archieve.php">Archive</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
            <?php if(isset($_SESSION['username'])){ ?>
			<li class="active"><a href="#">Welcome Mr.<?php if(isset($_SESSION['username'])) echo htmlspecialchars(ucfirst($_SESSION['username'])); ?> <span class="sr-only">(current)</span></a></li>              	
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
    <form id="searchform" method="post" class="form-horizontal" action="" accept-charset="utf-8">
   
     <div class="form-group">

    <label for="program" class="col-sm-4 control-label">Report Name</label>
    <div class="col-sm-4">
      <select class="form-control" name="report" id="report">				                
       		<option value="">Select Report Name</option>
       		<option value="report_not_register">Student Not Registered Report</option>
       		<option value="report_registered">Students Registered Report</option>
       		<option value="report_register_process">Student Registration Process Report</option>
       		<option value="report_register_status">Registration Approval/Rejected Report</option>
       		<option value="report_student_subjects">Studentwise Subject Report</option>
       		<option value="report_attendance">Student Attendance Report</option>
       </select>
    </div>
    
       <div class="col-sm-1">
			    	<button type="submit" class="btn btn-primary" id="btnsearch" >
			 		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp; Search
					</button>
				</div>
				
				  <div class="col-sm-1">
    
			    	<button class="btn btn-primary" id="printreport" >
			 		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp; Export
					</button>
				</div>
				
				 <div class="col-sm-1">
    
			    	<button type="button" class="btn btn-primary" id="exportexcel" >
			 		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp; Export Excel
					</button>
				</div>
   
	  </div>
   <hr />
   <div class="form-group row">
    <label for="accno" class="col-sm-2 control-label">Academic Number</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="accno" name="accno" placeholder="Give Academic Number" value="">
    </div>
    
     <label for="studname" class="col-sm-1 control-label">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="studname" name="studname" placeholder="Give Student Name" value="">
    </div>
  </div>
  <div class="form-group">
    <label for="program" class="col-sm-2 control-label">Program</label>
    <div class="col-sm-3">
      <select class="form-control" name="program" id="program">				                
       		<option value="">Select Your Program</option>
       		<option value="1">M.B.B.S</option>
       		<option value="2">B.D.S</option>
       		<option value="3">Pharmacy</option>
       		<option value="4">Nursing</option>
       </select>
    </div>
    
     <label for="contactno" class="col-sm-1 control-label">Contact</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="contactno" name="contactno" placeholder="" value="">
    </div>
   
	  </div>
	  
	  
	   <div class="form-group">
    <label for="study_year" class="col-sm-2 control-label">Year</label>
    <div class="col-sm-3">
      <select class="form-control" name="study_year" id="study_year">				                
       		<option value="">Select Year</option>
       </select>
    </div>
    
     <label for="contactno" class="col-sm-1 control-label">Semester</label>
    <div class="col-sm-4">
      <select class="form-control" name="semester" id="semester">				                
       		<option value="">Select Semester</option>
       </select>
    </div>
	  </div>
	  
	  
	    <div class="form-group">

    
    <label for="idno" class="col-sm-2 control-label">ID Number</label>
    <div class="col-sm-3">
     <input type="text" class="form-control" name="idno" placeholder="Give valid 10 digit ID" value="" >
    </div>

  <label for="contactno" class="col-sm-1 control-label">Subject</label>
    <div class="col-sm-4">
      <select class="form-control" name="subject" id="subject">				                
       		<option value="">Select Subject</option>
       </select>
    </div>
	  </div>
	  
	  
	
	    <div class="form-group">
    <label for="fromdate" class="col-sm-2 control-label">From Date</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="fromdate" placeholder="Select Register From Date" value="" >
    </div>
    
     <label for="todate" class="col-sm-1 control-label">To Date</label>
    <div class="col-sm-4">
     <input type="text" class="form-control" name="todate" placeholder="Select Register To Date" value="" >
    </div>
    
	  </div>

	  
	 <div class="form-group">
    <label for="completestatus" class="col-sm-2 control-label">Complete Status</label>
    <div class="col-sm-3">
      <select class="form-control" name="completestatus" id="completestatus">				                
       		<option value="">Select Complete Status</option>
       		<option value="0">Not Started</option>
       		<option value="1">Academic Affairs</option>
       		<option value="2">Registration</option>
       		<option value="3">Finance Department</option>
       </select>
    </div>
    
      <label for="completestatus" class="col-sm-1 control-label">Register</label>
    <div class="col-sm-4">
      <select class="form-control" name="register" id="register">				                
       		<option value="">Select Register Status</option>
       		<option value="Y">Approved</option>
       		<option value="N">Not-Approved</option>
       </select>
    </div>

	  </div>
	  
	   <div class="form-group">
  
    
      <label for="gender" class="col-sm-2 control-label">Gender</label>
    <div class="col-sm-3">
      <select class="form-control" name="gender" id="gender">				                
       		<option value="">Select Gender</option>
       		<option value="M">Male</option>
       		<option value="F">Female</option>
       </select>
    </div>
    
       <label for="studentstatusuid" class="col-sm-1 control-label">Student Status</label>
    <div class="col-sm-4">
      <select class="form-control" name="studentstatusuid" id="studentstatusuid">				                
       		<option value="0">Select Student Status</option>
       		<option value="21">Active</option>
       		<option value="22">File Closed</option>
       		<option value="23">Excluded</option>
       		<option value="24">Postponed</option>
       		<option value="25">Withdrawn</option>
       		<option value="26">Graduate</option>
       		<option value="27">Internship</option>
       		<option value="28">No Show</option>
       		<option value="29">No Subjects</option> 
       		<option value="30">Dismissed</option> 
       		
       </select>
    </div>

   
	  </div>
	 
	    </form>
	</div>
	</div>
	
		<div id="result"></div>       
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <script type="text/javascript" src="dist/js/jquery-1.11.1.js"></script>    
    <script src="dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="dist/js/moment.min.js"></script>       
    <script type="text/javascript" src="dist/js/jquery.validate.js"></script>       
    
    <script src="dist/js/daterangepicker.js"></script>
    <script src="dist/js/jquery.table2excel.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    
   <script type="text/javascript">
   		
   		 function baseUrl() {
   var href = window.location.href.split('/');
   return href[0]+'//'+href[2]+'/';
}
	
   	$( document ).ready( function () {
			
				/*$("#registerform").submit(function(){
				 )};*/
			
	//$("#searchcontainer").hide();
	
	var year_value =0;
	var sem_value =0;
	
	$('input[name="fromdate"]').daterangepicker({	
		locale: {
		format: 'DD/MM/YYYY' },	
        singleDatePicker: true,
        showDropdowns: true,
         "drops": "down"
    });
    $('input[name="todate"]').daterangepicker({
    	locale: {
		format: 'DD/MM/YYYY' },	
        singleDatePicker: true,
        showDropdowns: true,
         "drops": "down"
    });
	
	
	    $("#program").change(function () {

			var dataString = {"action": "get_studyyear","programuid": $("#program").val(),"year_value": year_value};
		    dataString = $.param(dataString);
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data:  dataString,
			cache: false,
			success: function(data)
			{	
				$('#study_year').html(data);
				$('#study_year').change();
			}
		
		  });
		  		   
        return false;
      });
	
	
	$("#study_year").on("change",function () {
 			var dataString = {"action": "get_semestername","programuid": $("#program").val(),"courseuid":$("#study_year").val(),"semester_value": sem_value};
			
			dataString = $.param(dataString);
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data:  dataString,
			cache: false,
			success: 	function(data)
			{	
				$('#semester').html(data);
				$('#semester').change();
			}
		
		  });
		  		   
        
      });
	
	
	
	$("#semester").on("change",function () {
 			var dataString = {"action": "get_subjects","programuid": $("#program").val(),"semester":$("#semester").val()};
			
			dataString = $.param(dataString);
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data:  dataString,
			cache: false,
			success: 	function(data)
			{	
				$('#subject').html(data);
			}
		
		  });
		  		   
        
      });
	

	
	$("#exportexcel").click(function (e) {
    $("#searchresult").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "SomeFile" //do not include extension
  }); 
});
	
	$("#searchform").submit(function(){
		if ($("#report").val() != ""){
			
		
	var dataString = {"action": $("#report").val()};
	 dataString = $("form").serialize()+ "&" + $.param(dataString);	
		$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/report_search.php",
			data: dataString,
			cache: false,
			success: function(html)
			{	
				$("#result").html(html).show();		
				
			}
		});	
		}
	return false;    
	});

/*$('#printreport').click(function(){
//     var divContents = $('#printcontent').html();
   window.open(document.location.origin+"/studentreg/printpage.php?reportname=<?php echo $_GET['reportname']; ?>",null,"status=yes,toolbar=no,menubar=no,location=no");
   
});*/


		$('#printreport').click(function(){
			
			
     var divContents = $('#result').html();
     var printWindow = window.open('','',',width=800');
      printWindow.document.write('<html>');
      printWindow.document.write('<head>');
      
       printWindow.document.write('<link href="dist/custom_css/print.css" media="print" type="text/css" rel="stylesheet">');
        printWindow.document.write('<style>');

      printWindow.document.write('.table-bordered{border: 1px solid #000000;}');
      printWindow.document.write('.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th,.table-bordered>tfoot>tr>td,.table-bordered>tfoot>tr>th,.table-bordered>thead>tr>td,.table-bordered>thead>tr>th{border: 1px solid #000000;}');
      printWindow.document.write('.table>tbody>tr>td,.table>tbody>tr>th,.table>tfoot>tr>td,.table>tfoot>tr>th,.table>thead>tr>td,.table>thead>tr>th{padding:5px;line-height:1.42857143;vertical-align:top;border-top: 1px solid #ddd;}');
      printWindow.document.write('</style>');
       printWindow.document.write('</head>');
      printWindow.document.write('<body >');
       
      printWindow.document.write('<div class="container">');
      if ($("#report").val() == 'report_not_register' ){printWindow.document.write('<center><big><b>Student Not Registered Report</b></big></center>');}     else if ($("#report").val() == 'report_registered' ){printWindow.document.write('<center><big><b>Student Registered Report</b></big></center>');}
      else if ($("#report").val() == 'report_register_process' ){printWindow.document.write('<center><big><b>Student Registration Process</b></big></center>');}
      else if ($("#report").val() == 'report_register_status' ){printWindow.document.write('<center><big><b>Student Registration Status Report</b></big></center>');}
      else if ($("#report").val() == 'report_student_subjects' ){printWindow.document.write('<center><big><b>'+$("#subject option:selected").text()+'- Subject Report</b></big></center>');}
      else if ($("#report").val() == 'report_attendance' ){printWindow.document.write('<center><big><b>Subject: '+$("#subject option:selected").text()+'</b></big></center>');}
      printWindow.document.write('<br />');
      printWindow.document.write(divContents);
       printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');
        printWindow.print();
        printWindow.close();
});



});
   </script>
  </body>
</html>
