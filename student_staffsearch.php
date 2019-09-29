
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

    <title>Search Advisor</title>

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

<div class="header clearfix">
		<div class="row">
	<img src="assets/img/bnr.png" class="img-responsive">
   </div>
        <nav>
          <ul class="nav nav-pills pull-right">
          <li role="presentation" class="active"><a href="http://www.ibnsina.edu.sa/">Home</a></li>
           
					
          </ul>
        </nav>
        <h3 class="text-danger"><b>STUDENT ADVISOR SEARCH..</b></h3>
      </div>
	 
      <!-- Main component for a primary marketing message or call to action -->
       <div class="panel panel-primary">
  		<div class="panel-heading">
    		<h3 class="panel-title">Search Filter</h3>
  		</div>
  		<div class="panel-body">  
    <form id="searchform" method="post" class="form-horizontal" action="" accept-charset="utf-8">
   
   <div class="form-group row ">
    <label for="accno" class="col-sm-3 control-label">Enter your Academic Number</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" id="accno" name="accno" placeholder="Give Academic Number" value="">
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



   	$( document ).ready( function () {
			

	$("#searchform").submit(function(){
		var data = { "action": "assigned_staffsearch" };
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




});
   </script>
  </body>
</html>
