<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="keywords" content="student-registration,ibn sina, registration, ibn sina college, arun ibn sina, arun">
    <meta name="description" content="IBN Sina Student Registration 2017">
    
    <meta name="author" content="Arun, Software Head">
    <meta name="application-name" content="ibn sina registration">    <link rel="icon" href="favicon.ico">

    <title>Student Registration</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    
    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dist/css/bootstrap-select.min.css">
    
    <!-- jQuery Plugins -->
	<script type="text/javascript" src="dist/js/jquery-1.11.1.js"></script>
	<script type="text/javascript" src="dist/js/jquery.validate.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dist/custom_css/jumbotron-narrow.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    
     <style>
        body {
            font-family: 'Lato';
        }
        .container{
			max-width: 65%;
		}
    </style>
  </head>

  <body>
      <div class="container">
       <div class="header clearfix">
		<div class="row">
	<img src="assets/img/bnr.png" class="img-responsive">
   </div>
        <nav>
          <ul class="nav nav-pills pull-right">
          <li role="presentation" class="active"><a href="http://www.ibnsina.edu.sa/">Home</a></li>
           
					
          </ul>
        </nav>
        <h3 class="text-danger"><b>STUDENT REGISTRATION</b></h3>
      </div>	<div class="alert alert-danger" role="alert">
	<strong>Need Help: </strong>
  <a href='reg_en.pdf' target='_blank' class="alert-link" >&nbsp;&nbsp; Click here How to Register...</a><br />
<a href='reg_ar.pdf' target='_blank' class="alert-link" >طريقة التسجيل</a>
</div>	
     <div class="panel panel-primary" id="regcontainer">
     	
  		<div class="panel-heading">
    		<h3 class="panel-title">Ensure Correct Details before save</h3>
  		</div>
  		<div class="panel-body">  			 
    		<form id="registerform" method="post" class="form-horizontal" action="" accept-charset="utf-8">
			  <div class="form-group">
			    <label for="accno" class="col-sm-3 control-label">Academic Number</label>
			   
			    <div class="col-sm-5">
			      <input type="text" class="form-control" class="accno" id="accno" name="accno" placeholder="Give Valid Academic Number">
			      <input type="hidden" class="accuid" id="accuid" name="accuid">
			    </div>
			    
			</div>
			
			 <div class="form-group">
			    <label for="accno" class="col-sm-3 control-label">National ID/ Iqama Number</label>
			    <div class="col-sm-5">
			      <input type="text" class="form-control" class="idno" id="idno" name="idno" placeholder="Give National ID/ Iqama Number">
			    </div>
			    <div class="col-sm-2">
			    	<button type="button" class="btn btn-primary search_acc" id="search_acc" >
			 		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp; Search
					</button>
				</div>
			</div>
			<hr />
  
  <div class="form-group">
    <label for="studname" class="col-sm-3 control-label">Name</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="studname" name="studname" placeholder="" disabled>
    </div>
  </div>
  
    <div class="form-group">
    <label for="gender" class="col-sm-3 control-label">Gender</label>
    <div class="col-sm-7">
      <select class="form-control" name="gender" id="gender" disabled>				                
       		<option value="">Select Gender</option>
       		<option value="M">Male</option>
       		<option value="F">Female</option>
       </select>
    </div>
  </div>


    <div class="form-group">
    <label for="year" class="col-sm-3 control-label">Register Year &amp; Program</label>
    
   <div class="col-sm-2">
      <input type="text" class="form-control" id="year" name="year" value="2017" disabled>
    </div>
    
    <div class="col-sm-5">
       <select class="form-control" name="program" id="program" disabled >				                
       		<option value="">Select Your Program</option>
       		<option value="1">M.B.B.S</option>
       		<option value="2">B.D.S</option>
       		<option value="3">Pharmacy</option>
       		<option value="4">Nursing</option>
       </select>
    </div>
  	</div>
  	

  	
  	<div class="form-group">
    <label for="year" class="col-sm-3 control-label">Study Year &amp; Semester</label>
    
   <div class="col-sm-3">
       <select class="form-control" name="study_year" id="study_year">				                
       		<option value="">Select Year</option>
       </select>
    </div>
    
    <div class="col-sm-4">
       <select class="form-control" name="semester" id="semester">				                
       		<option value="">Select Semester</option>
       </select>
    </div>
  	</div>
  	
  	
  	  	<div class="form-group">
    <label for="student_type" class="col-sm-3 control-label">Student Type</label>
    <div class="col-sm-7">
      <select class="form-control" name="student_type" id="student_type">				                
       		<option value="">Select Student Type</option>
       		<option value="5">Regular Student</option>
       		<option value="6">Regular + 1 Test</option>
       		<option value="7">Repeater Student</option>
       </select>
    </div>
  </div>
  
  <div class="form-group">
    <label for="schedule" class="col-sm-3 control-label">Schedule</label>
    <div class="col-sm-7">
      <select class="form-control" name="schedule" id="schedule">				                
       		<option value="">Select Schedule</option>
       		<option value="8">Normal Schedule</option>
       		<option value="9">Special Schedule(attached)</option>
       </select>
    </div>
  </div>
  
  
  		<div class="form-group">
    <label for="add_subject" class="col-sm-3 control-label">Add Subjects &amp; Credits</label>
    
   <div class="col-sm-5">
<select class="show-tick form-control" name="add_subjects[]" id="add_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled></select>
    </div>
    
    <div class="col-sm-2">
        <input type="text" class="form-control" id="add_credits" name="add_credits" disabled>
    </div>
  	</div>
  	
  	<div class="form-group">
    <label for="repeat_subject" class="col-sm-3 control-label">Repeat Subjects &amp; Credits</label>
    
   <div class="col-sm-5">
      <select class="show-tick form-control" name="repeat_subjects[]" id="repeat_subjects" data-size="10" data-live-search-placeholder="Search" title="Select Subject" multiple disabled></select>
    </div>
    
    <div class="col-sm-2">
        <input type="text" class="form-control" id="repeat_credits" name="repeat_credits" disabled>     
    </div>
       <input type="hidden" id="fees_amnt" name="fees_amnt">
  	</div>
  
     <div class="form-group">
    <label for="email" class="col-sm-3 control-label">Email</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="email" name="email" placeholder="Give Valid Email">
    </div>
  </div>
  
  
  <div class="form-group">
    <label for="contactno" class="col-sm-3 control-label">Contact No</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="contactno" name="contactno" placeholder="Give your Contact No">
    </div>
  </div>
  

  
  <div class="form-group">
    <label for="otherno" class="col-sm-3 control-label">Other No</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="otherno" name="otherno" placeholder="Give any other No">
    </div>
  </div>
    <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
     <button type="submit" id="regstudent"  class="btn btn-primary ">
  <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp; Register
</button>
    </div>
  </div>
  <div class="form-group">

 <div class="alert alert-danger" role="alert">

  <span  class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>  
  <span class="sr-only">Error:</span>
   <input  type="checkbox" id="agree" name="agree" value="agree" /><b>&nbsp;&nbsp; I Agree, the below terms and conditions</b><p class="text-justify"><br /> 
   I'm the student,my data shown above requesting the registration in the first semester for the study year 2017/2018.<br />
I acknowledge the validity of my above mentioned information and the college has the right to cancel my request if it is proved otherwise and I promise to pay the fees within 3 days and to complete the registration. The college has the right to stop me from studying in case of non-payment of fees</p>
</div>
</div>
 

</form>
  		</div>
</div>
	
      <div class="text-left" id="savecontainer">
        <h1>Thank You,</h1>
        <h2>Your registration will be processed.<br /> You will be informed about the status of your registration by the given contact number through <b>SMS</b></h2>        
        <p><a class="btn btn-lg btn-primary" href="index.php" role="button">New Registeration</a></p>
      </div>



     

      <footer class="footer">
        <p>&copy; 2017 Arun, Inc.</p>
      </footer>

    </div> <!-- /container -->

<script src="dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="dist/js/bootstrap-select.min.js"></script>
     <script src="dist/js/bootbox.min.js"></script>
     <script src="dist/js/custom.js"></script>
   
    
  </body>
</html>
