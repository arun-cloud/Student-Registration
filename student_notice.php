<?php
session_start(); //Start the session
if(!isset($_SESSION['username'])){ //If session not registered
header("location:signin.php"); // Redirect to login.php page
}
else {
	include(getcwd().'/db_connect.php'); 
	
	$ref_result= get_referencevalue("NOTICEMSG");	
	while($fetchresult = mysql_fetch_array($ref_result))
	$studentnotice[] = $fetchresult;
		
	header( 'Content-Type: text/html; charset=utf-8' );
}

   	 function get_referencevalue($domain_name){    	
		$query = "SELECT a.uid,a.valuecode,a.description FROM referencevalue as a, referencedomain as b WHERE a.referencedomainuid = b.uid and b.domaincode= '".$domain_name."' AND b.statusflag='A' order by a.displayorder";
		$result=mysql_query($query); 
		if(!$result):
		       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
 	    else:
				$rowsreturn = mysql_num_rows($result);
		endif;				
		return $result;		
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
	     <li ><a href="student_search.php">Search</a></li>
             <li><a href="reports.php">Reports</a></li>
	     <li><a target="_blank" href="register.php">Student Registration</a></li>
	     <li class="dropdown">
	        <a class="dropdown-toggle" data-toggle="dropdown" href="#">Student Details
	        <span class="caret"></span></a>
	        <ul class="dropdown-menu">
	          <li class="active"><a href="student_notice.php">Notices</a></li>
		     <li><a href="studentupdate.php">Change Iqama/ID</a></li>
		     <li><a href="statuschange.php">Change Status</a></li>
	        </ul>
     	 </li>
	     <li><a href="search_archieve.php">Archive</a></li>
	

              <!--  <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="reports.php?reportname=student_not_register">Student Not Registered</a></li>
                  <li><a href="#">Another action</a></li>
                  <li><a href="#">Something else here</a></li>
                  <li role="separator" class="divider"></li>
                  <li class="dropdown-header">Nav header</li>
                  <li><a href="#">Separated link</a></li>
                  <li><a href="#">One more separated link</a></li>
                </ul>
              </li>-->
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
    <form id="searchform" method="post" class="form-horizontal" action="" accept-charset="utf-8">
   
   <div class="form-group">
   
    <label for="accno" class="col-sm-2 control-label col-sm-offset-2">Academic Number</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="accno" name="accno" placeholder="Give Academic Number" value="">
    </div>
    
     
    
    
    <div class="col-sm-1">
			    	<button type="button" class="btn btn-primary" id="btnsearch" >
			 		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp; Search
					</button>
				</div>
  </div>
  
  <hr />
  <div class="form-group">
  
  
    <label for="program" class="col-sm-2 control-label">Program</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="program" name="program" readonly value="">
    </div>
    
    
    <label for="studname" class="col-sm-1 control-label">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="studname" name="studname"  value="" readonly>
    </div>
    
    
     
   
	  </div>
	  
	   <div class="form-group">
    <label for="studyyear" class="col-sm-2 control-label">Study Year</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="studyyear" name="studyyear"  value="" readonly>
    </div>
    
     <label for="semester" class="col-sm-1 control-label">Semester</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="semester" name="semester"  value="" readonly>
    </div>
  </div>
	    
	    
	    <div class="form-group">
	    
	    <label for="contactno" class="col-sm-2 control-label">Contact</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="contactno" name="contactno" placeholder="" value="" readonly>
    </div>
    
    
    <label for="notice" class="col-sm-1 control-label">Notice</label>
    <div class="col-sm-4">
      <select class="form-control" name="notice" id="notice">				                
       		<option value="">Select Student Notice</option>
       		<?php if(!empty($studentnotice)){
	  	foreach($studentnotice as $key => $value)
		 echo '<option value="'.$value["uid"].'">'.$value["valuecode"].'</option>';}
     ?> 	    
       </select>
    </div>
    
         <div class="col-sm-1">
			    	<button type="button" class="btn btn-primary" id="btnsendmsg" >
			 		<span class="glyphicon glyphicon-send" aria-hidden="true"></span>&nbsp; Send Msg
					</button>
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
    <script src="dist/js/bootbox.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    
   <script type="text/javascript">
   		
   		 function baseUrl() {
   var href = window.location.href.split('/');
   return href[0]+'//'+href[2]+'/';
}


	

   	$( document ).ready( function () {
		
	$('#btnsearch').click(function(){
		var search_keyword_value = $("#accno").val();
		var dataString = { 'action':'search_student','accno' : search_keyword_value };
		if(search_keyword_value!='')
		{
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data: dataString,
			cache: false,
			success: function(data)
			{	
			var json = jQuery.parseJSON(data);
			 $('#program').val(json.program);		
			 $('#studname').val(json.studname);		
			 $('#studyyear').val(json.courseyear);		
			 $('#semester').val(json.semestername);		
			 $('#contactno').val(json.contactno);		
			}
			});
		}
	});
	
	$('#btnsendmsg').click(function(){
	
var dataString = { 'action':'student_notice','mobileno' : $("#contactno").val(),'RefValueUID':$('#notice').val() };
			 	$.ajax({
				type: "POST",
				datatype: "json",
				url: baseUrl() +"studentreg/save.php",
				data: dataString,
				cache: false,
				success: function(data)
				{	
				bootbox.alert("SMS Sent successfully..", function() { 
				});
				
				}
			  });

});

});
   </script>
  </body>
</html>
