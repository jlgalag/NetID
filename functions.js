
//This function shows or hides the form for editting the email address.
function toggleEditMailForm() {
		var emDiv = document.getElementById("editMailForm");
		var cpDiv = document.getElementById("changePwdForm");
		var textBox = document.getElementById("newMail");
		if(emDiv.style.display == "block") {
	    		emDiv.style.display = "none";
			    document.getElementById ("emForm").reset();
		}
		else {
			emDiv.style.display = "block";
            cpDiv.style.display = "none";
			
		}
		
}

//This function is invoke when the sedit email form is submitted
function editmail(dn)
{       
        var submit=1;
		 $('.error').remove();
         var newmail = document.getElementById('newMail').value.trim();
		 
		var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		if(newmail.match(emailExp) == null)
		  {
			  $("#newMail").after("<br/><span class='error'>Not a valid email address</span>");
		  }
		else{  
			$.ajax({
			    async: false,
			    type: "POST",
			    url: 'functions.php',
			    data: "func=editmail&dn=" + dn + "&newmail=" + encodeURIComponent($('#newMail').val()),
			    success: function(data){
			     $('#mail').html(data);
				 bootbox.alert("Email successfully change to " + data +".", 
						 function() {
						    toggleEditMailForm();
							location.reload(true); }
						);
			 },
			error: function(){
		     bootbox.alert('Email was not change');
			 toggleEditMailForm();
			 }  
		   });
	   }
}

//This function shows or hides the form for changing user password.
function toggleChangePwdForm() {
		var cpDiv = document.getElementById("changePwdForm");
		var emDiv = document.getElementById("editMailForm");
		if(cpDiv.style.display == "block") {
	    		cpDiv.style.display = "none";
			    document.getElementById ("cpForm").reset();
	  	}
		else {
			cpDiv.style.display = "block";
		    if(emDiv != undefined)
				emDiv.style.display = "none";
		}
}		

//This function is called when the change password form is submitted ; user change his own password
function changeownpassword(dn)
{
  var mail = document.getElementById('sendtomail').value.trim();
  var userpassword = document.getElementById('userpassword').value.trim();
  $.ajax({
	    type: "POST",
		async: false,
	    url: 'functions.php',
	    //encodeURIComponent is to keep the '+' from changing to ' '.
		data: {
		  func: 'changeownpassword',
		  dn: dn,
		  mail: mail,
		  userpassword : userpassword,
		  pwd : document.getElementById('pwd').value.trim(),
		  newPwd : document.getElementById('newPwd').value.trim(),
		  conPwd : document.getElementById('conPwd').value.trim()
		  
		},
		success: function(data){
			toggleChangePwdForm();
			bootbox.alert(data, 
						 function() {
							location.reload(true); }
						);
		}
	});
}

//This function is called when the change password form is submitted ; user change other user's password
function changeotherpassword(dn)
{
  var mail = document.getElementById('sendtomail').value.trim();
  var userpassword = document.getElementById('userpassword').value.trim();
  var uid = document.getElementById('username').value.trim();
  
  $.ajax({
	    type: "POST",
		async: false,
	    url: 'functions.php',
	    //encodeURIComponent is to keep the '+' from changing to ' '.
		data: {
		  func: 'changeotherpassword',
		  dn: dn,
		  uid: uid,
		  mail: mail,
		  userpassword : userpassword,
		  newPwd : document.getElementById('newPwd').value.trim(),
		  conPwd : document.getElementById('conPwd').value.trim()
		  
		},
		success: function(data){
			toggleChangePwdForm();
			bootbox.alert(data, 
						 function() {
							location.reload(true); }
						);
		}
	});
}
// generate random password when adding an entry (student/employee)
function generatepassword() {
	var length = 10;
	var validchars="abcdefghijklmnopqrstuvwxyz";
	var count;
	   document.getElementById('inputUserpassword').value = "";
	  for (count = 1; count < length; count++)
	    document.getElementById('inputUserpassword').value += validchars.charAt(Math.floor(Math.random()
	      * validchars.length));


}

// show degree programs (add)
function onCollegeChange(){

	var selected = $("#inputGidnumber option:selected");	
    if(selected.val() != 0){
		 $.ajax({
					    type: "POST",
						url: 'functions.php',
					    data: 
						{   
						    gidnumber: selected.val(),
						 	func: 'selectdegreeprograms' },
					    success: function(data){
							$("#selectcourse").html(data);  
								 }
						
				    }); 
	}
	
}

// show degree programs (edit)
function onEditCollegeChange(ou){

	var selected = $("#editGidnumber option:selected");	
    if(selected.val() != 0){
		 $.ajax({
					    type: "POST",
						url: 'functions.php',
					    data: 
						{   
						    ou: ou,
						    gidnumber: selected.val(),
						 	func: 'editselectdegreeprograms' },
					        success: function(data){
							$("#selectcourse").html(data);  
								 }
						
				    }); 
	}
	
}

// show errors if inputs are invalid (add)
function validateadd(type){
   $('.error').remove();
   
  
  var submit = 1;
  var info;
  if(type=='student'){
      var ou = document.getElementById('inputOu');
	  if(!ou){ 
		 $("#inputGidnumber").after("<span class='error'>Required</span>");
		return false;
	  }
	  info= {
		       "uid" :            document.getElementById('inputUsername').value.trim(),
		       "cn" :              document.getElementById('inputFirstname').value.trim(),
			   "sn" :              document.getElementById('inputSurname').value.trim(),
		       "mi" :             document.getElementById('inputMiddleInitial').value.trim(),
		       "mail" :           document.getElementById('inputEmail').value.trim(),
			   "studentnumber" :   document.getElementById('inputStudentnumber').value.trim(),
		       "studenttype" :     document.getElementById('inputStudenttype').value.trim(),
		       "gidnumber" :       document.getElementById('inputGidnumber').value.trim(),
		       "ou" :              document.getElementById('inputOu').value.trim(),
		       "userpassword" :    document.getElementById('inputUserpassword').value.trim(),    // to be converted to md5password in the php file
	  };
	  
	   if(info['studentnumber']==""){
       $("#inputStudentnumber").after("<span class='error'>Required</span>");
       submit = 0;
	  }

	  var exp = /^[1-2][0-9][0-9][0-9]\-[0-9][0-9][0-9][0-9][0-9]$/;
	  if(info['studentnumber'].match(exp) == null){
	      $("#inputStudentnumber").after("<span class='error'>Not a valid student-number</span>");
	       submit = 0;
	  }
	  
	  if(info['studenttype']==""){
	       $("#inputStudenttype").after("<span class='error'>Required</span>");
	       submit = 0;
	  }
   }
  else{
     info= {
		       "uid" :            document.getElementById('inputUsername').value.trim(),
		       "cn" :              document.getElementById('inputFirstname').value.trim(),
			   "sn" :              document.getElementById('inputSurname').value.trim(),
		       "mi" :             document.getElementById('inputMiddleInitial').value.trim(),
		       "mail" :           document.getElementById('inputEmail').value.trim(),
			   "employeenumber" :   document.getElementById('inputEmployeenumber').value.trim(),
		       "employeetype" :     document.getElementById('inputEmployeetype').value.trim(),
		       "gidnumber" :       document.getElementById('inputGidnumber').value.trim(),
		       "ou" :              document.getElementById('inputOu').value.trim(),
		       "userpassword" :    document.getElementById('inputUserpassword').value.trim(),    // to be converted to md5password in the php file
	  };
	  
	   if(info['employeenumber']==""){
       $("#inputEmployeenumber").after("<span class='error'>Required</span>");
       submit = 0;
	  }
      var exp = /^[0-9]{2,}[0-9]$/;
	  if(info['employeenumber'].match(exp) == null){
	      $("#inputEmployeenumber").after("<span class='error'>Not a valid employee number</span>");
	       submit = 0;
	  }
	  
	  if(info['employeetype']==""){
	       $("#inputEmployeetype").after("<span class='error'>Required</span>");
	       submit = 0;
	  }	  
	  
	  if(info['gidnumber']==""){
	       $("#inputGidnumber").after("<span class='error'>Required</span>");
	       submit = 0;
	  }	  
   }  
 
  
  if(info['uid']==""){
       $("#inputUsername").after("<span class='error'>Required</span>");
	   submit=0;
  }
  if(info['cn']==""){
       $("#inputFirstname").after("<span class='error'>Required</span>");
       submit = 0;	   
  }
  if(info['cn'].length < 2){
       $("#inputFirstname").after("<span class='error'>Name too short</span>");
       submit = 0;
  }
   if(info['sn']==""){
       $("#inputSurname").after("<span class='error'>Required</span>");
       submit = 0;
  }
  if(info['sn'].length < 2){
       $("#inputSurname").after("<span class='error'>Surname too short.</span>");
       submit = 0;
  }
  if(info['mi']==""){
       $("#inputMiddleInitial").after("<span class='error'>Required</span>");
       submit = 0;
  }
  if(info['mi'].length > 3){
       $("#inputMiddleInitial").after("<span class='error'>Initial only</span>");
       submit = 0;
  }
  if(info['mail']==""){
       $("#inputEmail").after("<span class='error'>Required</span>");
       submit = 0;
  }
  var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
  if(info['mail'].match(emailExp) == null)
  {
	  $("#inputEmail").after("<span class='error'>Not a valid email address</span>");
	  submit = 0;
  }
  
  if(info['ou']==""){
       $("#inputOu").after("<span class='error'>Required</span>");
       submit = 0;
  }
  
  if(info['userpassword']==""){
       $("#getpasswordbtn").after("<span class='error'>Required</span>");
       submit = 0;
  }
  
  if(submit==0) return false;
  else return true;
}

// add student
function addstudent(){

    if(validateadd('student')){
	     var msg="", msg1="", confirmmsg="";
		 var info;
		 var firstname = document.getElementById('inputFirstname').value.trim(); 	 
	     var middleinitial = document.getElementById('inputMiddleInitial').value.trim(); 	 
	     var surname = document.getElementById('inputSurname').value.trim();
	     var cn = surname + ", " + firstname + " " + middleinitial;
	     var givenname =  	 firstname + " " + middleinitial;
	     
	     
	     
	     info= {
		       "uid" :            document.getElementById('inputUsername').value.trim(),
		       "cn" :              cn,
			   "sn" :              surname,
		       "givenname" :       givenname ,
		       "mail" :           document.getElementById('inputEmail').value.trim(),
			   "studentnumber" :   document.getElementById('inputStudentnumber').value.trim(),
		       "studenttype" :     document.getElementById('inputStudenttype').value.trim(),
		       "gidnumber" :       document.getElementById('inputGidnumber').value.trim(),
		       "ou" :              document.getElementById('inputOu').value.trim(),
		       "userpassword" :    document.getElementById('inputUserpassword').value.trim(),    // to be converted to md5password in the php file
		       "uidnumber" :       document.getElementById('hiddenUidnumber').value.trim(),
		       "homedirectory" :   document.getElementById('hiddenHomedirectory').value.trim() + document.getElementById('inputUsername').value.trim(),
		       "title" :           document.getElementById('hiddenTitle').value.trim(),
		       "shadowmax" :       document.getElementById('hiddenShadowmax').value.trim(),
		       "shadowwarning" :   document.getElementById('hiddenShadowwarning').value.trim(),
		       "loginshell" :  	   document.getElementById('hiddenLoginshell').value.trim()
			  	};
	        
			
			//show confirmation dialog box for adding of students
			msg = "<table class='table table-condensed'>"
			for (var key in info) {   
			   msg1 = "<tr><td><b>" + key + "</b> </td><td>" + info[key] + "</td></tr>";
			   msg += msg1;
			   }
			
			msg += "</table>";
			
			
			$.post("functions.php", { func: "checkstudentnumber", studentnumber : info['studentnumber'] },
  function(data){
    if(data.trim()=="OK") {
       confirmmsg = "<h4 style='text-align:center;'>Corfirm Addition of Student</h4>" + msg;
							           bootbox.confirm(confirmmsg,
									     function(result) {
										   var dn = "<?php echo $dn?>";
										   if(result){    			   // the actual deletion of an entry
										         $.ajax({
												    type: "POST",
													url: 'functions.php',
												    //encodeURIComponent is to keep the '+' from changing to ' '.
													data: 
													{   info: info,
													    dn: "uid=" + info['uid'] + ",ou=people,dc=uplb,dc=edu,dc=ph",
													 	func: 'addentry' },
												    success: function(data){
														bootbox.alert(
														     data, 
														     function(){window.location = "viewprofile.php?uid=" + info['uid']+ "&title=student";
															 });
													}
											    }); 
											
												}
											})
	}
    else bootbox.alert(data.trim());	
  }, "html");      
	}			
}

//add employee
function addemployee(){
     if(validateadd('employee')){
		 var msg="", msg1="", confirmmsg="";
		 var info;
	     var firstname = document.getElementById('inputFirstname').value.trim(); 	 
	     var middleinitial = document.getElementById('inputMiddleInitial').value.trim(); 	 
	     var surname = document.getElementById('inputSurname').value.trim();
	     var cn = surname + ", " + firstname + " " + middleinitial;
	     var givenname =  	 firstname + " " + middleinitial;
	     info= {
		       "uid" :            document.getElementById('inputUsername').value.trim(),
		       "cn" :              cn,
			   "sn" :              surname,
		       "givenname" :       givenname ,
		       "mail" :           document.getElementById('inputEmail').value.trim(),
			   "employeenumber" :   document.getElementById('inputEmployeenumber').value.trim(),
		       "employeetype" :     document.getElementById('inputEmployeetype').value.trim(),
		       "gidnumber" :       document.getElementById('inputGidnumber').value.trim(),
		       "ou" :              document.getElementById('inputOu').value.trim(),
		       "userpassword" :    document.getElementById('inputUserpassword').value.trim(),    // to be converted to md5password in the php file
		       "uidnumber" :       document.getElementById('hiddenUidnumber').value.trim(),
		       "homedirectory" :   document.getElementById('hiddenHomedirectory').value.trim() + document.getElementById('inputUsername').value.trim(),
		       "title" :           document.getElementById('hiddenTitle').value.trim(),
		       "shadowmax" :       document.getElementById('hiddenShadowmax').value.trim(),
		       "shadowwarning" :   document.getElementById('hiddenShadowwarning').value.trim(),
		       "loginshell" :  	 document.getElementById('hiddenLoginshell').value.trim()
			  	};
	        
			
			//show confirmation dialog box for adding of students
			msg = "<table class='table table-condensed'>"
			for (var key in info) {   
			   msg1 = "<tr><td><b>" + key + "</b> </td><td>" + info[key] + "</td></tr>";
			   msg += msg1;
			   }
			
			msg += "</table>";
	     
		   $.post("functions.php", { func: "checkemployeenumber", employeenumber : info['employeenumber'] },
  function(data){
    if(data.trim()=="OK") {
     confirmmsg = "<h4 style='text-align:center;'>Corfirm Addition of Employee</h4>" + msg;
							           bootbox.confirm(confirmmsg,
									     function(result) {
										   var dn = "<?php echo $dn?>";
										   if(result){    			   // the actual deletion of an entry
										         $.ajax({
												    type: "POST",
													url: 'functions.php',
												    //encodeURIComponent is to keep the '+' from changing to ' '.
													data: 
													{   info: info,
													    dn: "uid=" + info['uid'] + ",ou=people,dc=uplb,dc=edu,dc=ph",
													 	func: 'addentry' },
												    success: function(data){
														bootbox.alert(
														     data, 
														     function(){window.location = "viewprofile.php?uid=" + info['uid']+ "&title=employee";
															 });
													}
											    }); 
											
												}
											});		
	}
    else bootbox.alert(data.trim());	
  }, "html");      
	}		  	    		   
}


function editentry(title, dn)
{     
       $('.error').remove();
       var submit = 1;   
        	 
       
	   var givenname = document.getElementById('editGivenname').value.trim(); 	 
	   var surname = document.getElementById('editSurname').value.trim();
	   var cn = surname + ", " + givenname;
	   var info;
	   
	   info= {
	       "uid" :            document.getElementById('editUsername').value.trim(),
		   "cn" :             cn,
		   "sn" :             surname,
	       "givenname" :      givenname,
	       "mail" :           document.getElementById('editEmail').value.trim(),
		   "gidnumber" :       document.getElementById('editGidnumber').value.trim(),
	       "ou" :              document.getElementById('editOu').value.trim(),
	        };
		// validate inputs	
       if(info['givenname']==""){
	       $("#editGivenname").after("<br><span class='error'>Required</span>");
		   submit=0;
       }
	   else if(info['givenname'].length < 2){
	       $("#editGivenname").after("<br><span class='error'>Given name too short</span>");
	       submit = 0;
       }
	   
	   if(info['sn']==""){
	       $("#editSurname").after("<br><span class='error'>Required</span>");
		   submit=0;
       }
	   else if(info['sn'].length < 2){
	       $("#editSurname").after("<br><span class='error'>Surname too short</span>");
	       submit = 0;
       }
	   
	   if(info['mail']==""){
	       $("#editEmail").after("<br><span class='error'>Required</span>");
	       submit = 0;
	   }
	   else{
		   var emailExp = /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/;
		   if(info['mail'].match(emailExp) == null)
		   {
			  $("#editEmail").after("<br><span class='error'>Not a valid email address</span>");
			  submit = 0;
		   }
	   }
	   
	   if(info['ou']==""){
	       $("#editOu").after("<br><span class='error'>Required</span>");
	       submit = 0;
	   }
	   else if(info['ou'].length < 2){
	       $("#editOu").after("<br><span class='error'>Input too short</span>");
	       submit = 0;
       }
	   
	    
		if(submit==1){
	            $.ajax({
					    type: "POST",
						url: 'functions.php',
					    data: 
						{   info: info,
						    dn: dn,
						 	func: 'editentry' },
					    success: function(data){
							bootbox.alert(
							     data, 
							     function(){window.location = "viewprofile.php?uid=" + info['uid'] + "&title=" + title;
								 });
						}
				    });
	    }			
}


function searchstudent(back){
    var info, option, count=0;
	info={
        "uid" : 		document.getElementById('searchstudentuid').value.trim(),
		"givenname" :	document.getElementById('searchstudentgivenname').value.trim(),
		"sn" : 			document.getElementById('searchstudentsn').value.trim(),
		"studentnumber" : document.getElementById('searchstudentnumber').value.trim(),
		"studenttype":  document.getElementById('searchstudenttype').value.trim(),
		"ou":			document.getElementById('searchstudentou').value.trim(),
		"gidnumber":	document.getElementById('searchstudentgidnumber').value.trim() 
	};
	
	option={
        "uid" : 		document.getElementById('optionstudentuid').value.trim(),
		"givenname" :	document.getElementById('optionstudentgivenname').value.trim(),
		"sn" : 			document.getElementById('optionstudentsn').value.trim(),
		"studentnumber" : document.getElementById('optionstudentnumber').value.trim(),
		"studenttype":  document.getElementById('optionstudenttype').value.trim(),
		"ou":			document.getElementById('optionstudentou').value.trim()
	};
	
	
	var operator =   document.getElementsByName('optionstudentoperator')[0];
	var filter="";
    
	// get search filters and covert to valid ldap search query
	if(info['uid'] != ""){
	    count++;
		if(option['uid'] == 'is')
			filter += "(uid=" + info['uid'] + ")";
		else if(option['uid'] == 'contains')
		    filter += "(uid=*" + info['uid'] + "*)";
		else if(option['uid'] == 'begins')
		    filter += "(uid=" + info['uid'] + "*)";
		else if(option['uid'] == 'ends')
			filter += "(uid=*" + info['uid'] + ")";
		else if(option['uid'] == 'isnot')
			filter += "(!(uid=" + info['uid'] + "))";
	}
	if(info['givenname'] != ""){
	    count++;
		if(option['givenname'] == 'is')
			filter += "(givenname=" + info['givenname'] + ")";
		else if(option['givenname'] == 'contains')
		    filter += "(givenname=*" + info['givenname'] + "*)";
		else if(option['givenname'] == 'begins')
		    filter += "(givenname=" + info['givenname'] + "*)";
		else if(option['givenname'] == 'ends')
			filter += "(givenname=*" + info['givenname'] + ")";
		else if(option['givenname'] == 'isnot')
			filter += "(!(givenname=" + info['givenname'] + "))";
	}
	if(info['sn'] != ""){
	    count++;
		if(option['sn'] == 'is')
			filter += "(sn=" + info['sn'] + ")";
		else if(option['sn'] == 'contains')
		    filter += "(sn=*" + info['sn'] + "*)";
		else if(option['sn'] == 'begins')
		    filter += "(sn=" + info['sn'] + "*)";
		else if(option['sn'] == 'ends')
			filter += "(sn=*" + info['sn'] + ")";
		else if(option['sn'] == 'isnot')
			filter += "(!(sn=" + info['sn'] + "))";
	}
	if(info['studentnumber'] != ""){
	    count++;
	    if(option['studentnumber'] == 'is')
			filter += "(studentnumber=" + info['studentnumber'] + ")";
		else if(option['studentnumber'] == 'contains')
		    filter += "(studentnumber=*" + info['studentnumber'] + "*)";
		else if(option['studentnumber'] == 'begins')
		    filter += "(studentnumber=" + info['studentnumber'] + "*)";
		else if(option['studentnumber'] == 'ends')
			filter += "(studentnumber=*" + info['studentnumber'] + ")";
		else if(option['studentnumber'] == 'isnot')
			filter += "(!(studentnumber=" + info['studentnumber'] + "))"; 
    }
	if(info['studenttype'] != ""){
	    count++;
	    if(option['studenttype'] == 'is')
			filter += "(studenttype=" + info['studenttype'] + ")";
		else if(option['studenttype'] == 'contains')
		    filter += "(studenttype=*" + info['studenttype'] + "*)";
		else if(option['studenttype'] == 'begins')
		    filter += "(studenttype=" + info['studenttype'] + "*)";
		else if(option['studenttype'] == 'ends')
			filter += "(studenttype=*" + info['studenttype'] + ")";
		else if(option['studenttype'] == 'isnot')
		filter += "(!(studenttype=" + info['studenttype'] + "))"; 		
    }

	if(info['gidnumber'] != ""){
	    count++;
	      filter += "(gidnumber=" + info['gidnumber'] + ")";
		
	}
	
	if(info['ou'] != ""){
	    count++;
	  
		 if(option['ou'] == 'is')
			filter += "(course=" + info['ou'] + ")";
		else if(option['ou'] == 'contains')
		    filter += "(course=*" + info['ou'] + "*)";
		else if(option['ou'] == 'begins')
		    filter += "(course=" + info['ou'] + "*)";
		else if(option['ou'] == 'ends')
			filter += "(course=*" + info['ou'] + ")";
		else if(option['ou'] == 'isnot')
			filter += "((!course=" + info['ou'] + "))"; 		
	}

	    if(count > 1){
		   if(operator.checked) filter = "(&(title=student)(&" + filter + "))";
	       else filter = "(&(title=student)(|" + filter + "))";
	    }else filter = "(&(title=student)"+filter+")";
        if(count > 0){
				$.ajax({
					    type: "POST",
						url: 'functions.php',
					    //encodeURIComponent is to keep the '+' from changing to ' '.
						data: 
						{   filter: filter,
						    title: 'student',
						    func: 'search' },
					        success: function(data){
							
								    //window.location = "viewprofile.php?uid=" + info['uid']+ "&title=" + title;
									$('.searchresults').html(data);
									//alert(data);
								 }
					}); 	
	   }
       else if(back==0) bootbox.alert("Input Search Filters.");	   
}
	
function searchemployee(back){
    var info, option, count=0;
	info={
        "uid" : 		document.getElementById('searchemployeeuid').value.trim(),
		"givenname" :	document.getElementById('searchemployeegivenname').value.trim(),
		"sn" : 			document.getElementById('searchemployeesn').value.trim(),
		"employeenumber" : document.getElementById('searchemployeenumber').value.trim(),
		"employeetype":  document.getElementById('searchemployeetype').value.trim(),
		"ou":			document.getElementById('searchemployeeou').value.trim(),
		"gidnumber":	document.getElementById("searchemployeegidnumber").value
	};
	
	option={
        "uid" : 		document.getElementById('optionemployeeuid').value.trim(),
		"givenname" :	document.getElementById('optionemployeegivenname').value.trim(),
		"sn" : 			document.getElementById('optionemployeesn').value.trim(),
		"employeenumber" : document.getElementById('optionemployeenumber').value.trim(),
		"employeetype":  document.getElementById('optionemployeetype').value.trim(),
		"ou":			document.getElementById('optionemployeeou').value.trim()
	};
	
	
	var operator =   document.getElementsByName('optionemployeeoperator')[0];
	var filter="";
    
	
	if(info['uid'] != ""){
	    count++;
		if(option['uid'] == 'is')
			filter += "(uid=" + info['uid'] + ")";
		else if(option['uid'] == 'contains')
		    filter += "(uid=*" + info['uid'] + "*)";
		else if(option['uid'] == 'begins')
		    filter += "(uid=" + info['uid'] + "*)";
		else if(option['uid'] == 'ends')
			filter += "(uid=*" + info['uid'] + ")";
		else if(option['uid'] == 'isnot')
			filter += "(!(uid=" + info['uid'] + "))";
	}
	if(info['givenname'] != ""){
	    count++;
		if(option['givenname'] == 'is')
			filter += "(givenname=" + info['givenname'] + ")";
		else if(option['givenname'] == 'contains')
		    filter += "(givenname=*" + info['givenname'] + "*)";
		else if(option['givenname'] == 'begins')
		    filter += "(givenname=" + info['givenname'] + "*)";
		else if(option['givenname'] == 'ends')
			filter += "(givenname=*" + info['givenname'] + ")";
		else if(option['givenname'] == 'isnot')
			filter += "(!(givenname=" + info['givenname'] + "))";
	}
	if(info['sn'] != ""){
	    count++;
		if(option['sn'] == 'is')
			filter += "(sn=" + info['sn'] + ")";
		else if(option['sn'] == 'contains')
		    filter += "(sn=*" + info['sn'] + "*)";
		else if(option['sn'] == 'begins')
		    filter += "(sn=" + info['sn'] + "*)";
		else if(option['sn'] == 'ends')
			filter += "(sn=*" + info['sn'] + ")";
		else if(option['sn'] == 'isnot')
			filter += "(!(sn=" + info['sn'] + "))";
	}
	if(info['employeenumber'] != ""){
	    count++;
	    if(option['employeenumber'] == 'is')
			filter += "(employeenumber=" + info['employeenumber'] + ")";
		else if(option['employeenumber'] == 'contains')
		    filter += "(employeenumber=*" + info['employeenumber'] + "*)";
		else if(option['employeenumber'] == 'begins')
		    filter += "(employeenumber=" + info['employeenumber'] + "*)";
		else if(option['employeenumber'] == 'ends')
			filter += "(employeenumber=*" + info['employeenumber'] + ")";
		else if(option['employeenumber'] == 'isnot')
			filter += "(!(employeenumber=" + info['employeenumber'] + "))"; 
    }
	if(info['employeetype'] != ""){
	    count++;
	    if(option['employeetype'] == 'is')
			filter += "(employeetype=" + info['employeetype'] + ")";
		else if(option['employeetype'] == 'contains')
		    filter += "(employeetype=*" + info['employeetype'] + "*)";
		else if(option['employeetype'] == 'begins')
		    filter += "(employeetype=" + info['employeetype'] + "*)";
		else if(option['employeetype'] == 'ends')
			filter += "(employeetype=*" + info['employeetype'] + ")";
		else if(option['employeetype'] == 'isnot')
		filter += "(!(employeetype=" + info['employeetype'] + "))"; 		
    }
	if(info['gidnumber'] != ""){
	    count++;
	      filter += "(gidnumber=" + info['gidnumber'] + ")";
		
	}
	if(info['ou'] != ""){
	    count++;
	   if(option['ou'] == 'is')
			filter += "(ou=" + info['ou'] + ")";
		else if(option['ou'] == 'contains')
		    filter += "(ou=*" + info['ou'] + "*)";
		else if(option['ou'] == 'begins')
		    filter += "(ou=" + info['ou'] + "*)";
		else if(option['ou'] == 'ends')
			filter += "(ou=*" + info['ou'] + ")";
		else if(option['ou'] == 'isnot')
			filter += "(!(ou=" + info['ou'] + "))"; 		
	}
	
		
	//alert(filter);
	    if(count > 1){
		   if(operator.checked) filter = "(&(title=employee)(&" + filter + "))";
	       else filter = "(&(title=employee)(|" + filter + "))";
	    }else filter = "(&(title=employee)"+filter+")";
        if(count > 0){
				$.ajax({
					    type: "POST",
						url: 'functions.php',
					    //encodeURIComponent is to keep the '+' from changing to ' '.
						data: 
						{   filter: filter,
						    title: 'employee',
						    func: 'search' },
					        success: function(data){
							
								    //window.location = "viewprofile.php?uid=" + info['uid']+ "&title=" + title;
									$('.searchresults').html(data);
									//alert(data);
								 }
					}); 	
	   }
       else if(back==0) bootbox.alert("Input Search Filters.");	   
}



function deleterole(uid, role){


 confirmmsg = "Are you sure you want to remove role from <b>"+ uid +"</b>";
     bootbox.confirm(confirmmsg,
		     function(result) {
			    if(result){  
					$.ajax({
					    type: "POST",
						url: 'functions.php',
					    //encodeURIComponent is to keep the '+' from changing to ' '.
						data: 
						{   uid: uid,
						    role: role.toUpperCase(),
						    func: 'deleterole' },
					        success: function(data){
							
								    //window.location = "viewprofile.php?uid=" + info['uid']+ "&title=" + title;
									//$('.searchresults').html(data);
										bootbox.alert(
							     data, 
							     function(){location.reload(true);
								 });
									
								 }
					}); 
				}	 
             });
}
	

function searchuid(){
  var uid = document.getElementById('searchuid').value.trim();
 
  	$.ajax({
					    type: "POST",
						url: 'functions.php',
					    //encodeURIComponent is to keep the '+' from changing to ' '.
						data: 
						{   uid: uid,
						    func: 'searchuid' },
					        success: function(data){
							
								    //window.location = "viewprofile.php?uid=" + info['uid']+ "&title=" + title;
									$('.searchresults').html(data);
									document.getElementById("addrolediv").style.display = "block";
									//bootbox.alert(data);
									//location.reload(true);
								 }
					});
  
}

function addrole(){
 var uid = document.getElementById("addinputuid").value;
 var role = document.getElementById("addinputrole").value;
 confirmmsg = "Are you sure you want to add role <b> "+ role +"</b> to <b> "+ uid +"</b>?";
     bootbox.confirm(confirmmsg,
		     function(result) {
			    if(result){  
					$.ajax({
					    type: "POST",
						url: 'functions.php',
					    //encodeURIComponent is to keep the '+' from changing to ' '.
						data: 
						{   uid: uid,
						    role: role,
						    func: 'addrole' },
					        success: function(data){
							
								    //window.location = "viewprofile.php?uid=" + info['uid']+ "&title=" + title;
									//$('.searchresults').html(data);
									bootbox.alert(data, 
									        function() {
												location.reload(true); }
												 );
									
								 }
					}); 
				}	 
             });
}

function changeactiverole(activerole){
		$.ajax({
			type: "POST",
			url: 'functions.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			    activerole: activerole,
				func: 'changeactiverole' },
				success: function(data){
				   window.location = "home.php";
				}
		}); 
			
}


	



function adddegreeprogram(){
  var gidnumber = document.getElementById("gidnumber").value;
  var name = document.getElementById("inputdegreeprogramname").value;
  var title = document.getElementById("inputdegreeprogramtitle").value;
  $.ajax({
			type: "POST",
			url: 'functions.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			    gidnumber : gidnumber,
				name: name,
				title: title,
				func: 'adddegreeprogram' },
				success: function(data){
				  bootbox.alert(data, 
						 function() {
							location.reload(true); }
						);
				}
		}); 
}	

function deletedegreeprogram(){
  var gidnumber = document.getElementById("inputGidnumber").value;
  var name = document.getElementById("inputOu").value;
  confirmmsg = "Are you sure you want to delete <b>" + name + " </b> from the database?";
  bootbox.confirm(confirmmsg,
		     function(result) {
			   if(result){    			   // the actual deletion of an entry
			         $.ajax({
					    type: "POST",
						url: 'functions.php',
					    //encodeURIComponent is to keep the '+' from changing to ' '.
						data: 
						{   gidnumber: gidnumber,
						    name: name, 
						    func: 'deletedegreeprogram' },
					        success: function(data){
								bootbox.alert(
								     data, 
								     function(){location.reload(true);
									 });
						}
				    }); 
				
					}
				});
}

function savelogstofile(range){
	var daterange = document.getElementById("daterange").value;
	var dates ="";
	
	if(range == 0){
	dates = daterange.split('-');
	dates[0] = dates[0].replace(/\./g,'-').trim();
	dates[1] = dates[1].replace(/\./g,'-').trim();
	}
	$.ajax({
		type: "POST",
		url: 'functions.php',
		data: 
			{   
				dates: dates,
				func: 'savelogstofile' },
				success: function(data){
				bootbox.alert(
				data, 
				  function(){location.reload(true);
					});
				}
	});
	
}


function auditdate(){
  var daterange = document.getElementById("auditdaterange").value;
	var dates ="";
	
	dates = daterange.split('-');
	dates[0] = dates[0].replace(/\./g,'-').trim();
	dates[1] = dates[1].replace(/\./g,'-').trim();
  $.ajax({
		type: "POST",
		url: 'functions.php',
		data: 
			{   
				dates: dates,
				func: 'viewauditlogs' },
				success: function(data){
				   $('#logs').html(data);
				}
	});
}