
    var year_value =0;
    var sem_value =0;
    
    
    var stud_yeardb =0;
    var stud_typedb =0;
    
    var edit_studenttype =0;
    var edit_schedule =0;

    var response;
    
    
         function validation(){
   	
   				var msg='';
   				 if($("#study_year option:selected").text() == "6th Year" && $("#student_type").val() == 5 && $("#program").val() == 1 ){
						
						if ($("#schedule").val() != 9){
						 	msg='Select Special Schedule';
							return msg;
						}
						
						if ($("#add_subjects").val() == "" || $("#add_subjects").val() == null){
						msg='Add subjects';
						return msg;
						}
					}
					
					if ($("#student_type").val() == 7 && $("#schedule").val() != 9){
						msg='Repeater student should seelct special schedule(attached) for add/repeat subjects';
						return msg;
					}
					
					if ($("#student_type").val() == 6 && $("#schedule").val() != 8){
						msg='Only Normal Schedule is allowed for Regular + 1 Test';
						return msg;
					}
					
					if ($("#student_type").val() == 6 && $("#schedule").val() == 8){
						
						if($('#program').val() != 1 ){
							msg='Regular + 1 Test is available only for MBBS';
							return msg;
						}
						
						if($("#study_year option:selected").text() != "3rd Year" && $("#study_year option:selected").text() != "4th Year"){
							msg='Select MBBS 3rd or 4th year for Regular + 1 Test';
							return msg;
						}
							
						var str1 =   $("#add_subjects").val();	
						var repeat=0;					
						
						if(str1 == null || str1 == ''){
							msg='Add 1 subject for Regular + 1 Test';
							return msg;
						    
						}
						
						$.each( str1, function( key, value ) {
							repeat= repeat +1;
						});
						if(repeat == 0) {
							msg='Select 1 Add Subject';
							return msg;
						}else if(repeat > 1)  {
							msg='Should not add more than one subject to Normal + 1 Test';
							
							return msg;
						}
						
					}
					if ($("#schedule").val() == 9 && ($("#add_subjects").val() == "" || $("#add_subjects").val() == null) && ($("#repeat_subjects").val() == "" || $("#repeat_subjects").val() == null)){
						msg = 'Add/ Repeat Subjects should not be empty for special schedule';
						
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
				var button_text = $.trim($('#regstudent').text());
					
					var tt ='';
   					tt = validation();
					
					if(tt == ''){
						if (button_text == "Update") {
						updateUser();
						}else if (button_text== "Register") {
						addUser();					
						}
					}else
					{
						bootbox.alert(tt, function(){  });
						
					}
					
					return false;
				}				
		});

  
		function addUser(){
			var str =   $("#add_subjects").val();			
		 var add =repeat= '';
		if(str != null){
		$.each( str, function( key, value ) {
			var strlength = value.substr(value.indexOf("-")).length;
		  add = add + value.substr(0,value.length - strlength)+',';
		});
		add= add.substr(0,add.length-1); 
		}
		
		
		var str1 =   $("#repeat_subjects").val();
		if(str1 != null){
		$.each( str1, function( key, value ) {
			var strlength = value.substr(value.indexOf("-")).length;
		  repeat = repeat + value.substr(0,value.length - strlength)+',';
		});
		repeat= repeat.substr(0,repeat.length-1); }
		
		var data = {
	      "action": "save","accuid": $("#accuid").val(),"studname": $("#studname").val(),"year": $("#year").val(),"program": $("#program").val(),"add_subjects":add,"add_credits":$("#add_credits").val(),"repeat_subjects":repeat,"repeat_credits":$("#repeat_credits").val(),"fees_amnt":$("#fees_amnt").val(),"student_type1":$("#student_type").val(),"schedule1":$("#schedule").val(),"study_year1":$("#study_year").val() };
	    data = $("form").serialize() + "&" + $.param(data);
	    $.ajax({
	      type: "POST",
	      dataType: "json",
	      url: baseUrl() +"studentreg/save.php", //Relative or absolute path to response.php file
	      data: data,
	      success: function(data) {
	        $("#registerform")[0].reset();    
	        $("#regcontainer").hide();
	        $("#savecontainer h1").html('<b>Thank You...</b>')
	        $("#savecontainer").show();
	      }
	    });	    
		}
		
		function updateUser(){
		var str =   $("#add_subjects").val();			
		 var add =repeat= '';
		if(str != null){
		$.each( str, function( key, value ) {
			var strlength = value.substr(value.indexOf("-")).length;
		  add = add + value.substr(0,value.length - strlength)+',';
		});
		add= add.substr(0,add.length-1); 
		}
		
		
		var str1 =   $("#repeat_subjects").val();
		if(str1 != null){
		$.each( str1, function( key, value ) {
			var strlength = value.substr(value.indexOf("-")).length;
		  repeat = repeat + value.substr(0,value.length - strlength)+',';
		});
		repeat= repeat.substr(0,repeat.length-1); }
		
		var data = {
	      "action": "update","accuid": $("#accuid").val(),"course": $("#program").val(),"year": $("#year").val(),"email": $("#email").val(),"contactno": $("#contactno").val(),"otherno": $("#otherno").val(),"add_subjects":add,"add_credits":$("#add_credits").val(),"repeat_subjects":repeat,"repeat_credits":$("#repeat_credits").val(),"fees_amnt":$("#fees_amnt").val(),"student_type1":$("#student_type").val(),"schedule1":$("#schedule").val(),"study_year1":$("#study_year").val()
	    };
	    data = $("form").serialize() + "&" + $.param(data);
	    $.ajax({
	      type: "POST",
	      dataType: "json",
	      url: baseUrl() +"studentreg/save.php", //Relative or absolute path to response.php file
	      data: data,
	      success: function(data) {
	        $("#registerform")[0].reset();    
	        $("#regcontainer").hide();
	        $("#savecontainer h1").html('<b>Information Updated Successfully...</b>')
	        $("#savecontainer").show();
	      }
	    });	    
		}
		
		$( document ).ready( function () {
			
				/*$("#registerform").submit(function(){
				 )};*/
			
			$("#savecontainer").hide();
$("#search_acc").click(function() 
{ 
	 year_value =0;
     sem_value =0;
     stud_yeardb =0;
    stud_typedb =0;
	
	var search_keyword_value = $("#accno").val();
	
	var dataString = { 'action':'search','accno' : search_keyword_value, 'studentstatus': '21' };
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
				/*$("#year").val('2019');	*/
			
				if (json.name != "") {	
					
					$('#studname').val(json.name);				
					$("#accuid").val(json.uid);						 																	$("#accuid").val(json.uid);		
					$("#gender").val(json.gender);
					if (json.stud_yeardb != 0){ year_value= json.stud_yeardb; $("#study_year").prop('disabled',true); }else{ year_value= json.study_year; $("#study_year").prop('disabled',false);} 					 													
					stud_typedb = json.stud_typedb;
					
					if (stud_typedb != 0){
						$("#student_type").val(stud_typedb);
						$("#schedule").val(8);
						$("#student_type").attr('disabled',true);
						$("#schedule").attr('disabled',true);
						
					}else{
						$("#student_type").val('');
						$("#schedule").val('');
						$("#student_type").attr('disabled',false);
						$("#schedule").attr('disabled',false);
					}
										
					if (json.action == "update"){	
					if(json.completestatus > 0 ) {
						bootbox.alert('Sorry, Registration Process already started for Academic no: '+$('#accno').val(), function(){ location.reload(); }); 
					}else{
					year_value= json.study_year;
					sem_value= json.semester;
					edit_studenttype = json.student_type;
					edit_schedule= json.schedule;
					$("#program").val(json.course).change();
					$('#idno').val(json.idno);		
					//$("#study_year").val(json.study_year).change();
					$("#student_type").val(json.student_type).change();
					$("#schedule").val(json.schedule).change();
						
					$('#email').val(json.email);															 					
					$('#contactno').val(json.contactno);				
					$("#otherno").val(json.otherno);
					$("#add_credits").val(json.add_credits);
					$("#repeat_credits").val(json.repeat_credits);
					$("#fees_amnt").val(json.semester_fees);		
					$("#regstudent").html("<span class='glyphicon glyphicon-user'></span>&nbsp; Update"); 
					}
					}
					else if(json.action == "new"){
					
					$("#program").val(json.course).change();
					 if (json.stud_yeardb != 0) $("#study_year").val(json.stud_yeardb).change();
					
					
					edit_studenttype = json.student_type;
					edit_schedule= json.schedule;
					$("#program").val(json.course).change();
					$('#idno').val(json.idno);		
					//$("#study_year").val(json.study_year).change();
					$("#student_type").val(json.student_type).change();
					//$("#schedule").val(json.schedule).change();
						
					$('#email').val(json.email);															 	
					$('#contactno').val(json.contactno);				
					$("#otherno").val(json.otherno);
					
					$("#regstudent").html("<span class='glyphicon glyphicon-user'></span>&nbsp; Register"); 
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
    
    
    function change_fees(){
			var dataString = {"action": "get_fees","programuid": $("#program").val(),"accno": $("#accno").val(),"semester_name": $("#study_year option:selected").text()};
			var total_fees = 0;	
		    dataString = $.param(dataString);
			$.ajax({
			type: "POST",
			datatype: "json",
			url: baseUrl() +"studentreg/save.php",
			data:  dataString,
			cache: false,
			success: function(data)
			{	
				var json = jQuery.parseJSON(data);	
							
				total_fees = parseFloat(json.semester_fees);
				if ($("#student_type option:selected").text() == "Repeater Student"){
				total_fees = 0;	
				}if ($("#student_type option:selected").text() == "Regular + 1 Test"){
				total_fees = total_fees + 1000;	
				}
				var add_subject=0;
				if( $("#study_year option:selected").text() == "6th Year"  && $("#program").val() == 1){
					if($("#add_subjects").val() != "" && $("#add_subjects").val() != null){
						var str1 =   $("#add_subjects").val();	
						var repeat=0;					
						$.each( str1, function( key, value ) {
							repeat= repeat +1;
						});
					add_subject = 20000 * repeat;
					}else{
						add_subjects=0;
					}
				   total_fees=0; 
				}else{
				add_subject = $("#add_credits").val() * json.add_subjectfees;				
				}
				
				var repeat_subject = $("#repeat_credits").val() * json.repeat_subjectfees;	
				if ($("#schedule option:selected").text() == "Normal Schedule"){
				repeat_subject = add_subject = 0;	
				}
				total_fees = 	total_fees + repeat_subject + add_subject
				$("#fees_amnt").val(total_fees);	
			}
		  });
		 }
		 
    
    
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
      
        $("#howtoreg").click(function(){
    	bootbox.dialog({
  message: "<a href='reg_en.pdf' target='_blank'><strong>How to Register - English</strong></a><br /><a href='reg_ar.pdf' target='_blank'><strong>How to Register - Arabic</strong></a>",
  title: "Select English/Arabic",
  buttons: {
    main: {
      label: "Ok",
      className: "btn-primary"
    }
  }
});
    	
    }); 
      
 $("#study_year").on("change",function () {
	/* if (year_value >= 1){
				  var dataString = {"action": "get_semestername","programuid": $("#program").val(),"courseuid":year_value,"semester_value": sem_value};
			}else{
				var dataString = {"action": "get_semestername","programuid": $("#program").val(),"courseuid":$("#study_year").val(),"semester_value": sem_value};
			}*/
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

				/*$('#schedule').html('<option value="">Select Schedule</option><option value="8">Normal Schedule</option><option value="9">Special Schedule(attached)</option>');
				$("#schedule").attr('disabled', false);
				$('#student_type').html('<option value="">Select Student Type</option><option value="5">Regular Student</option><option value="6">Regular + 1 Test</option><option value="7">Repeater Student</option>');
				
				
				$('#add_subjects').selectpicker('deselectAll');
				$("#add_subjects").attr('disabled', true).selectpicker('refresh');
				$("#add_credits").val('');
				$('#repeat_subjects').selectpicker('deselectAll');
				$("#repeat_subjects").attr('disabled', true).selectpicker('refresh');
				$("#repeat_credits").val('');
				if (studentype>0) $("#student_type").val(studentype);
				if (scheduleuid>0) $("#schedule").val(scheduleuid);*/
				change_fees();
			}
		
		  });
		  		   
        
      });
    
     $("#semester").change(function () {
     	change_fees();
     	});
   
    
    $("#student_type").change(function () {   
    	if ($("#student_type option:selected").text() == 'Regular + 1 Test'){
			$("#schedule").val(8);
			$("#schedule").attr('disabled', true);
    		$("#schedule").change();
		}else if ($("#student_type option:selected").text() == 'Repeater Student'){
			$("#schedule").val(9);
    		$("#schedule").attr('disabled', true);
    		$("#schedule").change();
		}else if ($("#student_type option:selected").text() == 'Regular Student'){
			if ($("#student_type option:selected").text() == '6th Year' && $("#program").val() == 1){
				$("#schedule").val(9);
    			$("#schedule").attr('disabled', true);
    			$("#schedule").change();
			}else{
				$("#schedule").val('');
				$("#schedule").attr('disabled', false);
    			$("#schedule").change();
			}
		}else{
				$("#schedule").val('');
				$("#schedule").attr('disabled', false);
    			$("#schedule").change();
			}  

    	change_fees();
    	});
	/*$("#registerform").submit(function(){
    
  });*/
			 $("#schedule").change(function () {
			 	
			 	if ($("#schedule").val() != ""){
			    if ($('option:selected',this).text() == 'Normal Schedule' && $("#student_type option:selected").text() == 'Regular Student'){
			 	$('#add_subjects').selectpicker('deselectAll');
				$("#add_subjects").attr('disabled', true).selectpicker('refresh');
				$("#add_credits").val('');
				$('#repeat_subjects').selectpicker('deselectAll');
				$("#repeat_subjects").attr('disabled', true).selectpicker('refresh');
				$("#repeat_credits").val('');
				change_fees();
			}
		 	else{
				//$("#repeat_subjects").attr('disabled', false).selectpicker('refresh');
				var dataString = {"action": "get_addsubjects","reguid": $("#accuid").val(),"programuid": $("#program").val(),"courseuid":year_value,"semester_value": sem_value,"schedule": $("#schedule").val()};
				
					dataString = $.param(dataString);
					$.ajax({
					type: "POST",
					datatype: "json",
					url: baseUrl() +"studentreg/save.php",
					data:  dataString,
					cache: false,
					success: function(data)
					{	
					
					var json = jQuery.parseJSON(data);
					$('#add_subjects').html(json.add_subj);
    				$("#add_subjects").attr('disabled', false).selectpicker('refresh');
					 var str =   $('#add_subjects').val();
							var totalcredits=0;
							if(str != null){
							$.each( str, function( key, value ) {
							  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
							  totalcredits = totalcredits + credithour;		  
							});
							$("#add_credits").val(totalcredits);
							}else{
							$("#add_credits").val('');
							}
					
					if ($("#student_type option:selected").text() == 'Regular + 1 Test'){
					  $('#repeat_subjects').selectpicker('deselectAll');	
					  $("#repeat_subjects").attr('disabled', true).selectpicker('refresh');
					  $("#repeat_credits").val('');
					}else{
							$('#repeat_subjects').html(json.repeat_subj);
    						$("#repeat_subjects").attr('disabled', false).selectpicker('refresh');
    						 str =   $('#repeat_subjects').val();
							var totalcredits=0;
							if(str != null){
							$.each( str, function( key, value ) {
							  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
							  totalcredits = totalcredits + credithour;		  
							});
							$("#repeat_credits").val(totalcredits);
							}else{
							$("#repeat_credits").val('');
							}
					}
    			
							
							change_fees();
					}
				  	});
				 }
			  }
					
					
				
			 });
			
			$('#add_subjects').selectpicker({
      liveSearch: true
    });
    
    	$('#repeat_subjects').selectpicker({
      liveSearch: true
    });
    
    
    $('#add_subjects').on('hidden.bs.select', function (e) {
		var str =   $(this).val();
		var totalcredits=0;
		if(str != null){
		$.each( str, function( key, value ) {
		  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
		  totalcredits = totalcredits + credithour;		  
		});
		$("#add_credits").val(totalcredits);
		}else{
		$("#add_credits").val('');
		}
		change_fees();
		
	});
	
	$('#repeat_subjects').on('hidden.bs.select', function (e) {
		var str =   $(this).val();
		var totalcredits=0;
		if(str != null){
		$.each( str, function( key, value ) {
		  var credithour = parseInt(value.substr(value.indexOf("-") + 1),10);
		  totalcredits = totalcredits + credithour;		  
		});
		$("#repeat_credits").val(totalcredits);
		}else{ $("#repeat_credits").val('');}
		
		change_fees();
		
	});


			$( "#registerform" ).validate( {
				rules: {		
				
					accno: "required",
					idno: {
						required: true,
						number: true,
						minlength: 10
						
					},
					studname: {
						required: true,
						minlength: 2
					},
					program: "required",
					study_year: "required",
					semester: "required",
					add_subjects: {
					required: true,
					valueNotEquals: true	
					},
					email: {
						required: true,
						email: true
					},
					contactno: {
						required: true,
						number: true,
						minlength: 5
					},
					student_type: "required",
					schedule: "required",
					agree: "required"
				},
				messages: {
				
					accno: "Please enter a valid academic number",	
					idno: {
						required: "Please enter a valid ID",
						number: "Only numbers allowed",
						minlength: "Your ID must consist of 10 digits"
					},				
					studname: {
						required: "Please enter a name",
						minlength: "Your name must consist of at least 2 characters"
					},
					program: "Select a Program",					
					student_type: "Select Student type",
					schedule: "Select Schedule",
					study_year: "Select Year",
					semester: "Select Semester",
					email: "Please enter a valid email address",
					contactno: {
						required: "Please provide your contact no",
						number: "Only numbers allowed",
						minlength: "Your contact no must be at least 5 characters long"
					},
					agree: "Please accept our policy"
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
