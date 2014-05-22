<!--Connect and bind to ldap server-->
<?php
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//User with STUDENT, OCS, OUR or EMPLOYEE as role cannot view this page
	if(!($activerole=='HRDO' || $activerole=='ADMIN'))
	   redirect("home.php");
	
	$sr=ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(uid=".$userUid.")(title=".$title."))");
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
     $pagetitle = "NetID :: Search Employee";
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
					    <?php $active=7;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				      <h1 class="pull-left"> Search Employee </h1>
				     <img src="tools/img/horredline.jpg"/>
				    
		
			
			       
			
							 <form class="form-horizontal" id="formsearchemploee" name="formsearchemployee" method="POST" action="javascript:searchemployee(0)">
						<table class="searchtable"> 
						     <tr>
							   <td class='tdlabel'> Username </td>
								<td>
								       <select style="font-size:12px;" name="optionemployeeuid" id="optionemployeeuid" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="employeeuid" type="text" id="searchemployeeuid" placeholder="Username" value="">
							    </td>
								
							  <td class='tdlabel' colspan="2"> Search operator </td>
								<td>
								     <label class="radio inline">
								     <input type="radio" name="optionemployeeoperator" id="optionemployeeoperatorand" value="and"> And
								     
									 </label>&nbsp;&nbsp;&nbsp;
									 <label class="radio inline">
									 <input type="radio" name="optionemployeeoperator" id="optionemployeeoperatoror" value="or" checked> Or
									 </label>
		 						</td>
							
							 </tr> 
							 <tr> 
							  <td  class='tdlabel'> Employee Number </td>
								<td>
								       <select style="font-size:12px;" name="optionemployeenumber" id="optionemployeenumber" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="employeenumber" type="text" id="searchemployeenumber" placeholder="xxxx-xxxxx" value="">
							    </td>
							   
                              <td  class='tdlabel'> Employee Type </td>
								<td>
								       <select style="font-size:12px;" name="optionemployeetype" id="optionemployeetype" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
							       <select name="employeetype"  id="searchemployeetype"  class="input-medium"  >
								       <option value='' disabled selected style='display:none;'>Employee Type</option>
								       <option value="FAC">FAC</option>
								       <option value="NGW">NGW</option>
								       <option value="ADM">ADM</option>
								       <option value="R E P S">REPS</option>
							       </select>
							    </td>
							  
                         
							</tr> 
                            <tr> 							
  							  <td class='tdlabel'> Given Name </td>
								<td>
								       <select name="optionemployeegivenname" id="optionemployeegivenname" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="employeegivenname" type="text" id="searchemployeegivenname" placeholder="Given name MI" value="">
							    </td>
								
							
 							 <td  class='tdlabel'> Last Name </td>
								<td>
								       <select style="font-size:12px;" name="optionemployeesn" id="optionemployeesn" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
								<td>
									<input class="input-medium" name="employeesn" type="text" id="searchemployeesn" placeholder="Last Name">
							    </td>
							 </tr>
                             <tr> 							
  							   <td  class='tdlabel'> Office / College</td>
								<td colspan="2">
								 <select name="employeegidnumber" id="searchemployeegidnumber" class="input-medium" >
									<?php
									      //$conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
										    $query="SELECT * FROM offices ORDER BY gidnumber";
											$offices=mysqli_query($conn, $query);
											
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											echo "<option value='' disabled selected style='display:none;'>Select College</option>";
											 
											while($row = mysqli_fetch_array($offices)){
											    echo    '<option value="'.$row['gidnumber'].'">'.$row['name'].'</option>';
											}	
											
											while($row = mysqli_fetch_array($college)){
											    echo    '<option value="'.$row['gidnumber'].'">'.$row['name'].'</option>';
											}
										}
										else echo "<option>Cannot connect to the database</option>";
									 ?>
							    </select>
		 						</td>
								
								<td class='tdlabel'> Office </td>
								<td>
								       <select name="optionemployeeou" id="optionemployeeou" class="input-small">
											 <option value="is">is</option>
										     <option value="contains" selected>contains</option>
										     <option value="begins">begins with</option>
										     <option value="ends">ends with</option>
										     <option value="isnot">is not</option>
										</select>
		 						</td>
                                <td>
									<input class="input-medium" name="employeeou" type="text" id="searchemployeeou" placeholder="Office/Dept" value="">
							    </td>
							   
							   </tr>
						</table>
						<input type="hidden" id="hiddenactiverole" name="activerole" value="<?php echo $activerole?>"/>
						<button class="btn btn-primary pull-right" style="margin-right:10px;" type="submit" name="searchemployeebutton"><i class="icon-search icon-white"></i>  Search</button>
															
					</form>	
			        
					
					<div class="searchresults">
					   <script> searchemployee(1); </script>
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

/**
*	Activate/Deactive Employee Account
*
* 	@param data 			row number in search results
*
*/
function enableEmployeeAccount(data){

	/**
	* 	Variables used:
	*
	* 	stat 			string		button label (Deactivate,Activate)
	*	status 			boolean 	active status of employee
	* 	title			string		type (employee)
	* 	uid 			string 		user name of employee
	* 	identifier  	int 	 	uniqueIdentifierUPLB of employee
	* 	dn 				string 		distinguished name of employee entity
	*
	*/

	var stat = $("#"+data).children("#enable_account").children("#enableButton").text();
	var status = $("#"+data).children("#enable_account").attr('value');
	var title = 'employee';
	var uid = $("#"+data).children("#uid").attr('value');
	var identifier = $("#"+data).children("#identifier").attr('value');
	var dn = "uniqueIdentifierUPLB="+identifier+",ou=people,dc=uplb,dc=edu,dc=ph";
	
	if(stat=='Deactivate') status = 'TRUE';
	else status = 'FALSE';

	//call delete function located in functions.php
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
		    	//change button text
		    	if(status=='TRUE') $("#"+data).children("#enable_account").children("#enableButton").text('Activate');
    			else $("#"+data).children("#enable_account").children("#enableButton").text('Deactivate');
		    }
	});
}

</script>