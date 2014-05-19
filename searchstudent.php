
<?php

	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//User with STUDENT or EMPLOYEE as role cannot view this page
	if(!($activerole=='OUR' || $activerole=='OCS' || $activerole=='ADMIN'))
	   redirect("home.php");
	   

	$sr=ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(uid=".$userUid.")(title=student))");	
	$entries = ldap_get_entries($ldapconn, $sr);
	
	if($_SESSION['gidnumber']==""){//get college name base on gidnumber       
	    $csr = ldap_search($ldapconn, "ou=posixGroups,".$ldapconfig['basedn'], "(gidnumber=".$entries[0]["gidnumber"][0].")", array('cn'));			  

		$entry = ldap_first_entry($ldapconn, $csr);
		$ou = ldap_get_values($ldapconn, $entry,'cn');
		$group= $ou[0]; 
		$_SESSION['gidnumber'] = $entries[0]["gidnumber"][0];
		$_SESSION['group'] = $group;
	}
?>

<!DOCTYPE html>
<html>
<head>
   <?php
     $pagetitle = "NetID :: Search Student";
	 include 'header.php';
   ?>
	
<style>
   .tdlabel{
     width: 120px;
	 font-weight: bold;
	 font-size: 13px;
	 text-align: right;
	 line-height:100%;
   }
   .searchtable td select {
      font-size: 12px;
	}
   
   .searchtable td {
      padding: 5px 10px;
   }

</style>
   
</head>
<body>
    <?php  include 'frag_header.php';  ?>
	
	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php $active=6;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				      <h1 class="pull-left"> Search Student </h1>
				     <img src="tools/img/horredline.jpg"/>
				    
		
			
			       
			            <!-- Search form-->
					    <form class="form-horizontal" id="formsearch" name="formsearch" method="POST" action="javascript:searchstudent(0)">
							<table class="searchtable"> 
						     <tr>
							   <td class='tdlabel'> Username </td>
								<td>
								       <select style="font-size:12px;" name="optionstudentuid" id="optionstudentuid" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="studentuid" type="text" id="searchstudentuid" placeholder="Username" value="">
							    </td>
								
							  <td class='tdlabel' colspan="2"> Search operator </td>
								<td>
								     <label class="radio inline">
								     <input type="radio" name="optionstudentoperator" id="optionstudentoperatorand" value="and"> And
								     
									 </label>&nbsp;&nbsp;&nbsp;
									 <label class="radio inline">
									 <input type="radio" name="optionstudentoperator" id="optionstudentoperatoror" value="or" checked> Or
									 </label>
		 						</td>
							
							 </tr> 
							 <tr> 
							  <td  class='tdlabel'> Student Number </td>
								<td>
								       <select style="font-size:12px;" name="optionstudentnumber" id="optionstudentnumber" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="studentnumber" type="text" id="searchstudentnumber" placeholder="xxxx-xxxxx" value="">
							    </td>
							   
                              <td  class='tdlabel'> Student Type </td>
								<td>
								       <select style="font-size:12px;" name="optionstudenttype" id="optionstudenttype" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<select name="studenttype" id="searchstudenttype"  class="input-medium"  >
								       <option value='' disabled selected style='display:none;'>Student Type</option>
								       <option value="UG">UG</option>
								       <option value="GS">GS</option>
									</select>
							    </td>
							  
                         
							</tr> 
                            <tr> 							
  							  <td class='tdlabel'> Given Name </td>
								<td>
								       <select name="optionstudentgivenname" id="optionstudentgivenname" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="studentgivenname" type="text" id="searchstudentgivenname" placeholder="Given name MI" value="">
							    </td>
								
							
 							 <td  class='tdlabel'> Last Name </td>
								<td>
								       <select style="font-size:12px;" name="optionstudentsn" id="optionstudentsn" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="studentsn" type="text" id="searchstudentsn" placeholder="Last Name">
							    </td>
							 </tr>
                             <tr> 							
  							   <td  class='tdlabel'> College </td>
								<td colspan="2">
								 <select name="studentgidnumber" id="searchstudentgidnumber" class="input-medium" >
									 <?php
									      // show list of colleges
									      $conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											echo "<option value='' disabled selected style='display:none;'>Select College</option>";
											 
											while($row = mysqli_fetch_array($college)){
											    echo    '<option value="'.$row['gidnumber'].'">'.$row['name'].'</option>';
											}	
										}
										else echo "<option>Cannot connect to the database</option>";
										mysqli_close($conn);
									 ?>
							    </select>
		 						</td>
								
							    <td class='tdlabel'> Degree Program </td>
								<td>
								       <select name="optionstudentou" id="optionstudentou" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
                                <td>
									<input class="input-medium" name="studentou" type="text" id="searchstudentou" placeholder="Course" value="">
							    </td>
							   </tr>
						</table>
						<button class="btn btn-primary pull-right" style="margin-right:10px;" type="submit" name="searchstudentbutton"><i class="icon-search icon-white"></i>  Search</button>
															
					</form>	
			    
			<!-- Search results will be displayed here-->		
			 <div class="searchresults">
			     <script> searchstudent(1); </script>
					</div>
			
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div>
    </div>	
	 <!-- footer -->
     <!-- pagination does not work if include('frag_footer') is used -->	 
     <footer class='footer'>
		<p style="font-size:8px; text-align:center; ">

				University of the Philippines Los Baños<br/>
				College Los Baños, Laguna 4031 Philippines</br/>
				Copyright © 2012 University of the Philippines Los Baños. All Rights Reserved.

			</p>
		   </div>	
    </footer>

</body>
</html>


<script>

function enableStudentAccount(data){
	var stat = $("#"+data).children("#enable_account").children("#enableButton").text();
	var status = $("#"+data).children("#enable_account").attr('value');
	var title = 'student';
	var uid = $("#"+data).children("#uid").attr('value');
	var identifier = $("#"+data).children("#identifier").attr('value');
	var dn = "uniqueIdentifierUPLB="+identifier+",ou=people,dc=uplb,dc=edu,dc=ph";

	if(stat=='Deactivate') status = 'TRUE';
		else status = 'FALSE';


		alert(status);
	$.ajax({
		type: "POST",
		url: 'functions.php',
	    data: 
		{   
		    dn : dn,
		    uid : uid,
		    status : status,
		    title : title,
		 	func: 'delete'
		},
	    success: function(data1){
	    	bootbox.alert('Success!');
	    	if(status=='TRUE') $("#"+data).children("#enable_account").children("#enableButton").text('Activate');
	    	else $("#"+data).children("#enable_account").children("#enableButton").text('Deactivate');
	    }
	});

}

function addAlumniAttributes(data){
	var coursegraduated = $("#"+data).children("#course").attr('value');
	var uid = $("#"+data).children("#uid").attr('value');
	var yeargraduated = <?php echo date("Y"); ?>;
	var identifier = $("#"+data).children("#identifier").attr('value');
	var dn = "uniqueIdentifierUPLB="+identifier+",ou=people,dc=uplb,dc=edu,dc=ph";

	var info = {
		"objectclass" : 'UPLBAlumni',
		"coursegraduated" : coursegraduated,
		"yeargraduated" : yeargraduated,
		"title" : 'alumni'
	};

	alert(data);

	$.ajax({
		 type: "POST",
			url: 'functions.php',
		    data: 
			{   
			    dn : dn,
			    info : info,
			    uid : uid,
			 	func: 'addalumniattributes'
			},
		    success: function(data1){
		    	bootbox.alert('Successfully added!');
		    }
	});
}

</script>