<?php
session_start(); //Start the session
if(!isset($_SESSION['username'])) //If session not registered
header("location:signin.php"); // Redirect to login.php page
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="keywords" content="student-registration,ibn sina, registration, ibn sina college, arun ibn sina, arun">
    <meta name="description" content="IBN Sina Student Registration 2018">
    
    <meta name="author" content="Arun, Software Head">
    <meta name="application-name" content="ibn sina registration">    <link rel="icon" href="favicon.ico">

    <title>Student Iqama/ID</title>
    
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
          <li role="presentation"><a href="http://www.ibnsina.edu.sa/">Home</a></li>
            <li><a href="student_search.php">Search</a></li>
	    <li class="active"><a href="updatestudent.php">Student Details</a></li>
            <li><a href="signout.php">Sign Out</a></li>
					
          </ul>
        </nav>
        <h3 class="text-danger"><b>STUDENT IQAMA/ID DETAILS</b></h3>
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
			    <div class="col-sm-2">
			    	<button type="button" class="btn btn-primary search_acc" id="search_acc" >
			 		<span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp; Search
					</button>
				</div>
			    
			</div>
			
			
			<hr />
  
   <div class="form-group">
			    <label for="accno" class="col-sm-3 control-label">National ID/ Iqama Number</label>
			    <div class="col-sm-7">
			      <input type="text" class="form-control" class="idno" id="idno" name="idno" placeholder="Give National ID/ Iqama Number">
			    </div>
			</div>
			
  <div class="form-group">
    <label for="studname" class="col-sm-3 control-label">Name</label>
    <div class="col-sm-7">
      <input type="text" class="form-control" id="studname" name="studname" placeholder="" disabled>
    </div>
  </div>
  
    <div class="form-group">
    <label for="gender" class="col-sm-3 control-label">Gender</label>
    <div class="col-sm-3">
      <select class="form-control" name="gender" id="gender" disabled>				                
       		<option value="">Select Gender</option>
       		<option value="M">Male</option>
       		<option value="F">Female</option>
       </select>
    </div>
     <label for="nationality" class="col-sm-1 control-label">Nationality</label>
    <div class="col-sm-3">
      <select class="form-control" name="nationality" id="nationality">				                
       		<option value="">Select Nationality</option>
       		<option value="16">Saudi</option>
       		<option value="17">Non-Saudi</option>
       </select>
    </div>
    
  </div>


    <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
     <button type="submit" id="regstudent"  class="btn btn-primary ">
  <span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp; Register
</button>
    </div>
  </div>


</form>
  		</div>
</div>
	



     

      <footer class="footer">
        <p>&copy; 2015-2018 Arun, Software Head.</p>
      </footer>

    </div> <!-- /container -->

<script src="dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="dist/js/bootstrap-select.min.js"></script>
     <script src="dist/js/bootbox.min.js"></script>
     <script type="text/javascript">
     
    var year_value =0;
    var sem_value =0;
    
    
    var stud_yeardb =0;
    var stud_typedb =0;
    
    var edit_studenttype =0;
    var edit_schedule =0;

    var response;
    
    
         function validation(){
   	
   				var msg='';
   				if ($("#idno").val().length == 0){
						 	msg='Iqama no is required';
							return msg;
						}
   						
   						if ($("#idno").val().length != 10){
						 	msg='Invalid iqama no';
							return msg;
						}
					
				
					
					return msg;
				}
    
    
    
    
        function baseUrl() {
   var href = window.location.href.split('/');
   return href[0]+'//'+href[2]+'/';
}
    $.validator.setDefaults( {
			submitHandler: function () {
					
					var tt ='';
   					tt = validation();
					
					if(tt == ''){
						
						updateUser();
						
					}else
					{
						bootbox.alert(tt, function(){  });
						
					}
					
					return false;
				}				
		});

  
	
		
		function updateUser(){
		
		var data = {
	      "action": "update_studentdetails","accuid": $("#accuid").val()};
	    data = $("form").serialize() + "&" + $.param(data);
	    $.ajax({
	      type: "POST",
	      dataType: "json",
	      url: baseUrl() +"studentreg/save.php", //Relative or absolute path to response.php file
	      data: data,
	      success: function(data) {
	        bootbox.alert("Information updated successfully", function(){  });
	        //window.location.href = "student_search.php";
	      }
	    });	    
		}
		
		$( document ).ready( function () {
			
				/*$("#registerform").submit(function(){
				 )};*/
			
			
$("#search_acc").click(function() 
{ 
	 year_value =0;
     sem_value =0;
     stud_yeardb =0;
    stud_typedb =0;
	
	var search_keyword_value = $("#accno").val();
	
	var dataString = { 'action':'search','accno' : search_keyword_value };
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
				/*alert(data);*/	
				var json = jQuery.parseJSON(data);
				$("#registerform")[0].reset(); 
				$("#accno").val(search_keyword_value);
				
			
				if (json.name != "") {	
					
					$('#studname').val(json.name);				
					$("#accuid").val(json.uid);						 																	$("#accuid").val(json.uid);		
					$("#gender").val(json.gender);
					$("#nationality").val(json.nationalityuid);
					if(json.completestatus > 1 ) {
						bootbox.alert('Sorry Cannot Update, Registration Process already started for Academic no: '+$('#accno').val(), function(){ location.reload(); }); 
					}else{
					$('#idno').val(json.idno);		
					}
								
					$("#regstudent").prop('disabled', false);					
				}
				else{
				$("#regstudent").prop('disabled', true);				
				}
				 
			}
		});
	}
	return false;    
});
	$( "#registerform" ).validate( {
				rules: {		
				
					accno: "required",
					idno: {
						required: true,
						number: true,
						minlength: 10
						
					},
					nationality: "required"
				},
				messages: {
				
					accno: "Please enter a valid academic number",	
					idno: {
						required: "Please enter a valid ID",
						number: "Only numbers allowed",
						minlength: "Your ID must consist of 10 digits"
						
					},
					nationality: "Salect Nationality"
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					if ( element.prop( "type" ) === "checkbox" ) {
						error.insertAfter( element.parent( "label" ) );
					} else {
						error.insertAfter( element );
					}
				},
				highlight: function ( element, errorClass, validClass ) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
				},
				unhighlight: function (element, errorClass, validClass) {
					$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
				}
			} );
//DISABLE RIGHT CLCIK
		});

     </script>
   
    
  </body>
</html>
