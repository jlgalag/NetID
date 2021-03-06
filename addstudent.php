<?php
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
   
    //User with STUDENT or EMPLOYEE as role cannot view this page
	if(!($activerole=='OUR' || $activerole=='ADMIN'))
	   redirect("home.php");
	
    // search for the current value of uidnumholder, this will be the the uidnumber of the entry to be added	
	$search = ldap_search($ldapconn, "ou=numberholder,".$ldapconfig['basedn'], "cn=uidlatestnumber");
    $entry = ldap_get_entries($ldapconn, $search);
    $uidnumholderdn = $entry[0]['dn'];	
    $uidnumholder = $entry[0]['serialnumber'][0] +1;
	
?> 

<!DOCTYPE html>
<html>
<head>
   <?php
     $pagetitle = "NetID :: Add Student";
	 include 'header.php';
   ?>
</head>
<body>

	
	<?php  include 'frag_header.php';  ?>
	
	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php $active=3;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->
            
               <div class="span9">
			    <div class="hero-unit">
				     <h1> Add Student </h1>
					 <img src="tools/img/horredline.jpg"/>
					  <div class="fileupload fileupload-new" data-provides="fileupload">
					      <h5>Add from CSV File</h5>
						  <form action="function_addfromcsv.php" method="post" enctype="multipart/form-data">
						  <div class="input-append">
						    <div class="uneditable-input span3"><i class="icon-file fileupload-exists">
								</i> <span class="fileupload-preview"></span>
							</div>
							<span class="btn btn-file">
								<span class="fileupload-new">Select file</span>
								<span class="fileupload-exists">Change</span>
								<input type="file" name="file" id="file" />
								<input type="hidden" name="title" value="student" />
							</span>
							<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
							<input class="btn btn-primary fileupload-exists" type="submit" name="submit" value="Add">
							  </div>
						  </form>
					</div> 
					<hr>
					<div id="addform">
					<h5>Add from Input</h5>
					<form class="form-horizontal" id="formaddstudent" name="formaddstudent" method="POST" action="javascript:addstudent()">
						  <div class="control-group">
						    <label class="control-label" for="inputUsername">Username</label>
						    <div class="controls">
						      <input class="input-large" name="uid" type="text" id="inputUsername" placeholder="Username">
							  <span id="uiderror"></span>
							</div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="inputFirstname">First Name</label>
						    <div class="controls">
						      <input class="input-large" name="firstname" type="text" id="inputFirstname" placeholder="First Name">
							  
						    </div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="inputMiddleInitial">Middle Initial</label>
						    <div class="controls">
						      <input class="input-large" name="givenname" type="text" id="inputMiddleInitial" placeholder="MI">
						    </div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="inputSurname">Surname</label>
						    <div class="controls">
						      <input  class="input-large" name="sn" type="text" id="inputSurname" placeholder="Surname">
						    </div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="inputEmail">Email</label>
						    <div class="controls">
						      <input class="input-large" name="mail" type="text" id="inputEmail" placeholder="Email">
						    </div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="inputStudentnumber">Student Number</label>
						    <div class="controls">
						      <input class="input-large" name="studentnumber" type="text" id="inputStudentnumber" placeholder="xxxx-xxxxx">
						    </div>
						  </div>
						     <div class="control-group">
						    <label class="control-label" for="inputStudenttype">Student Type</label>
						    <div class="controls">
						     <select name="studenttype" id="inputStudenttype"  class="input-large"  >
								       <option value='' disabled selected style='display:none;'>Select Student Type</option>
								       <option value="UG">UG</option>
								       <option value="GS">GS</option>
							   </select>

						    </div>
							</div>
						  	 <div class="control-group">
						    <label class="control-label" for="inputGidnumber">College</label>
						    <div class="controls">
							     <select name="gidnumber" id="inputGidnumber" class="input-large" onchange="onCollegeChange()"  >
									
									 <?php
									      //$conn = mysqli_connect('localhost','root','','netid');
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
								</select></div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="inputOu">Course</label>
						    <div class="controls" id="selectcourse">
						    </div>
						  </div>

						  <div class="control-group">
						  	  <label class="control-label" for="securityQuestion">Security Question</label>
						  	<div class="controls">
						     <select name="securityQuestion" id="inputSecurityQuestion"  class="input-large" onchange="changeSecQuestion()">
						       <option value='' disabled selected style='display:none;'>Select Security Question</option>
						       <option value="own">Create a security question</option>
						       <option value="What was your childhood nickname?">What was your childhood nickname?</option>
						       <option value="What is the name of your favorite childhood friend? ">What is the name of your favorite childhood friend? </option>
						       <option value="What was the last name of your third grade teacher?">What was the last name of your third grade teacher?</option>
						       <option value="What street did you live on in third grade?">What street did you live on in third grade?</option>
						       <option value="What is your grandmother's first name?">What is your grandmother's first name?</option>
						       <option value="What is your mother's middle name?">What is your mother's middle name?</option>
						       <option value="What time of the day were you born?">What time of the day were you born?</option>
						       <option value="What was your dream job as a child?">What was your dream job as a child?</option>
						       <option value="What is your preferred musical genre?">What is your preferred musical genre?</option>
						       <option value="What year did you graduate from High School?">What year did you graduate from High School?</option> 
							  </select>
							   <input type="text" id="hiddenSecurityQuestion" name="secQuestion" value=""/>
							</div>
							<br/>
						   </div>

						  <div class="control-group">
						    <label class="control-label" for="inputSecurityAnswer">Security Answer</label>
						    <div class="controls">
						      <div class="input-append">
							  <input class="input-large uneditable-input" name="securityAnswer" type="text" id="inputSecurityAnswer" placeholder="Security Answer">
						      </div>
							  <br/>
							</div>
						  </div>
						  <div class="control-group">
						    <label class="control-label" for="inputUserPassword">Password</label>
						    <div class="controls">
						      <div class="input-append">
							  <input class="input-large uneditable-input" name="userpassword" type="text" id="inputUserpassword" placeholder="Password" >
						      <button class="btn" type="button" id="getpasswordbtn" onclick="javascript:generatepassword()">Get Password</button>
							  </div>
							  <br/>
							</div>
						  </div>

						  <div class="control-group">
						    <label class="control-label" for="inputActive">Active</label>
						    <div class="controls">
						     <select name="inputActive" id="inputActive"  class="input-large">
								       <option value="TRUE">Yes</option>
								       <option value="FALSE">No</option>
							 </select>
						    </div>
						  </div>
						  

						  <div class="control-group">
						   <div class="controls">					
							  <button class="btn btn-primary" type="submit" name="addbutton">Save <i class="icon-ok icon-white"></i> </button> 
							</div>
						  </div>

						  <!-- ********************************* -->
						   <!-- hiddden attributes-->
						   <input type="hidden" id="hiddenUidnumber" name="uidnumber" value="<?php echo $uidnumholder?>"/>
						   <input type="hidden" id="hiddenHomedirectory" name="homedirectory" value="/home/"/>  <!-- username will be appended -->
						   <input type="hidden" id="hiddenTitle" name="title" value="student"/>
						   <input type="hidden" id="hiddenShadowmax" name="shadowmax" value="9999"/>
						   <input type="hidden" id="hiddenShadowwarning" name="" value="7"/>
						   <input type="hidden" id="hiddenLoginshell" name="loginshell" value="/bin/bash"/>	
						   <input type="hidden" id="hiddenObjectclass[0]" name="objectclass[0]" value="UPLBEntity"/>	
						   <input type="hidden" id="hiddenObjectclass[1]" name="objectclass[1]" value="UPLBStudent"/>	
							
                           <!-- objectclass -->		



					</form>
		            </div>
                    
					
      
					
				
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div>
    </div>	


<script>

$("#hiddenSecurityQuestion").hide();

</script>

<?php include 'frag_footer.php'?>
  

	 