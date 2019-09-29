<?php
session_start(); //Start the session
if(!isset($_SESSION['username'])){ //If session not registered
header("location:signin.php"); // Redirect to login.php page
}
else {
	include(getcwd().'/db_connect.php'); 
	
	$ref_result= get_program();	
	while($fetchresult = mysql_fetch_array($ref_result))
	$programmaster[] = $fetchresult;
	
	$ref_result= get_referencevalue("COMPLETE_STATUS");	
	while($fetchresult = mysql_fetch_array($ref_result))
	$complete_status[] = $fetchresult;
		
	header( 'Content-Type: text/html; charset=utf-8' );
}

   function get_program(){    	
		$query = "SELECT a.uid,a.programname FROM programmaster as a where a.statusflag='A' order by a.uid";
		$result=mysql_query($query); 
		if(!$result):
		       die("FAIL: TO SELECT QUERY BECAUSE: " . mysql_error()); 
 	    else:
				$rowsreturn = mysql_num_rows($result);
		endif;				
		return $result;		
	}
	
	
	 function get_referencevalue($domain_name){    	
		$query = "SELECT a.displayorder uid,a.description FROM referencevalue as a, referencedomain as b WHERE a.referencedomainuid = b.uid and b.domaincode= '".$domain_name."' AND b.statusflag='A' order by a.displayorder";
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
	
		<div class="row">
	<img src="assets/img/bnr.png" class="img-responsive">
   </div>
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
            	
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="#">Home</a></li>
	     <li class="active"><a href="advisor_search.php">Search</a></li>
         
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
   
   <div class="form-group row">
    <label for="accno" class="col-sm-2 control-label">Academic Number</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="accno" name="accno" placeholder="Give Academic Number" value="">
    </div>
    
     <label for="studname" class="col-sm-1 control-label">Name</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="studname" name="studname" placeholder="Give Student Name" value="">
    </div>
    <input type="hidden" name="staffuid" value="<?php echo $_SESSION['work_section']; ?>"/>
  </div>
  <div class="form-group">
    <label for="program" class="col-sm-2 control-label">Program</label>
    <div class="col-sm-3">
      <select class="form-control" name="program" id="program">				                
       		<option value="">Select Your Program</option>
       		<?php if(!empty($programmaster)){
	  	foreach($programmaster as $key => $value){
		   if($value["uid"] == $_COOKIE['program']){
		   	echo '<option value="'.$value["uid"].'" selected>'.$value["programname"].'</option>';}
		   	else {
			echo '<option value="'.$value["uid"].'">'.$value["programname"].'</option>';}
		   }		
		}	  
     ?> 	    
       </select>
    </div>
  	   
    <label for="study_year" class="col-sm-1 control-label">Year</label>
    <div class="col-sm-4">
      <select class="form-control" name="study_year" id="study_year">				                
       		<option value="">Select Year</option>
       </select>
    </div>
   
	  </div>
	  
	  
	    
	 <div class="form-group">
     <label for="contactno" class="col-sm-2 control-label">Semester</label>
    <div class="col-sm-3">
      <select class="form-control" name="semester" id="semester">				                
       		<option value="">Select Semester</option>
       </select>
    </div>
    
      <label for="gender" class="col-sm-1 control-label">Gender</label>
    <div class="col-sm-4">
      <select class="form-control" name="gender" id="gender">				                
       		<option value="">Select Gender</option>
       		<option value="M" <?php if(isset($_COOKIE['gender'])){if($_COOKIE['gender'] == 'M') echo 'selected'; } ?>>Male</option>
       		<option value="F" <?php if(isset($_COOKIE['gender'])){if($_COOKIE['gender'] == 'F') echo 'selected'; } ?>>Female</option>
       </select>
    </div>
     <div class="col-sm-1">
			    	<button type="submit" class="btn btn-primary" id="btnsearch" >
			 		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp; Search
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
    <script type="text/javascript" src="dist/js/moment.min.js"></script>       
    
    <script src="dist/js/daterangepicker.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    
   <script type="text/javascript">
   		
   		 function baseUrl() {
   var href = window.location.href.split('/');
   return href[0]+'//'+href[2]+'/';
}


	function loadsearch(){
		var data = { "action": "search" };
		var dataString = $("form").serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/ajax_studentsearch.php",
			data: dataString,
			cache: false,
			success: function(html)
			{	
				$("#result").html(html).show();				 
			}
		});	
	}

   	$( document ).ready( function () {
   		
   	var year_value =0;
	var sem_value =0;
   		
   		
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


	$("#searchform").submit(function(){
		var data = { "action": "advisor_search" };
		var dataString = $("form").serialize() + "&" + $.param(data);
		$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/ajax_studentsearch.php",
			data: dataString,
			cache: false,
			success: function(html)
			{	
				$("#result").html(html).show();				 
			}
		});	
	return false;    
	});


/*loadsearch();*/

});
   </script>
  </body>
</html>
