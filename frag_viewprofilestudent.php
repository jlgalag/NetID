				
					<img src="tools/img/horredline.jpg"/>
					<table class="table table-stripped">
				     <?php
						echo "<tr>";
			            echo "<td>Name</td>";
						echo "<td>".$entries[0]["cn"][0]."</td>";
						echo "</tr>";
						
						echo "<tr>";
			            echo "<td>Student Number</td>";
						echo "<td>".$entries[0]["studentnumber"][0]."</td>";
						echo "</tr>";
					    
						echo "<tr>";
			            echo "<td>Student Type</td>";
						echo "<td>".$entries[0]["studenttype"][0]."</td>";
						echo "</tr>";
					    
						echo "<tr>";
			            echo "<td>Course</td>";
						echo "<td>".$entries[0]["course"][0]."</td>";
						echo "</tr>";
						  
						echo "<tr>";
			            echo "<td>College</td>";
						echo "<td>".$entries[0]["college"][0]."</td>";
						echo "</tr>";
						
						echo "<tr>";
			            echo "<td>Email Address</td>";
						echo "<td><span  id='mail'>".$entries[0]["mail"][0]; 
				        echo "</td></tr>";
						
						echo "<tr><td></td>";
						if($activerole=='ADMIN'){
						echo "<td><a id='displayChangePwdForm', href='javascript:toggleChangePwdForm();'>Change Password</a>";
						
						
						echo '<div id="changePwdNotif">
			                     
			                  </div>';
						$changeown=false;  //flag to determine if user is changing his own password; use to determine which form to display	  
						require 'frag_changepasswordform.php'; }
						echo "</td></tr>";
					
		            ?>	
                  </table>

	
				<!--Edit student Modal -->
				<!--  -This will be displayed when the edit link is clicked-->
				<!-- -When the form is submitted , call the editentry function in the functions.js-->
				<div id="editModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				    <h3 id="myModalLabel">Edit Student Information</h3>
				  </div>
				  
				  <div class="modal-body">
						<form class="form-horizontal" id="formaddstudent" name="formaddstudent">
                          <div class="control-group">
						    <label class="control-label" for="editUsername">Username</label>
						    <div class="controls">
						      <input type="text" class="input-xlarge" readonly name="uid" id="editUsername" placeholder="Username"  value='<?php echo $entries[0]['uid'][0]?>'>
						    </div>
						  </div>						 
						 <div class="control-group">
						    <label class="control-label" for="editGivenname">Given name</label>
						    <div class="controls">
						      <input class="input-xlarge" name="givenname" type="text" id="editGivenname" placeholder="GIVEN NAME MI"  value='<?php echo $entries[0]['givenname'][0]?>'>
						    </div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="editSurname">Surname</label>
						    <div class="controls">
						      <input  class="input-xlarge" name="sn" type="text" id="editSurname" placeholder="Surname"   value='<?php echo $entries[0]['sn'][0]?>'>
						    </div>
						  </div>
						  <div class="control-group">
						    <label class="control-label" for="editEmail">Email</label>
						    <div class="controls">
						      <input class="input-xlarge" name="mail" type="text" id="editEmail" placeholder="Email"  value='<?php echo $entries[0]['mail'][0]?>'>
						    </div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="editStudentnumber">Student Number</label>
						    <div class="controls">
						      <input class="input-xlarge" readonly name="studentnumber" type="text" id="editStudentnumber" placeholder="xxxxxxxxx" value='<?php echo$entries[0]['studentnumber'][0] ?>'>
						    </div>
						  </div>
						     <div class="control-group">
						    <label class="control-label" for="editStudenttype">Student Type</label>
						    <div class="controls">
						       <select name="studenttype" id="editStudenttype"  class="input-xlarge"  >
								       <option value='' disabled selected style='display:none;'>Student Type</option>
								       <option value="UG" <?php if($entries[0]['studenttype'][0] == "UG") echo ' selected';?>>UG</option>
								       <option value="GS" <?php if($entries[0]['studenttype'][0] == "GS") echo ' selected';?>>GS</option>
							   </select>
							</div>
							</div>
						  	 <div class="control-group">
						    <label class="control-label" for="editGidnumber">College</label>
						    <div class="controls">
							     <select name="gidnumber" id="editGidnumber" class="input-xlarge" onchange="onEditCollegeChange('<?php echo $entries[0]['ou'][0] ?>')"  >
									  <?php
									    // show the list of colleges
									      //$conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											while($row = mysqli_fetch_array($college)){
											    echo    '<option value="'.$row['gidnumber'].'"';
												if($entries[0]['gidnumber'][0] == $row['gidnumber']) echo ' selected';
												echo '>'.$row['name'].'</option>';
											}	
										}
										else echo "<option>Cannot connect to the database</option>";
										//mysqli_close($conn);
									 ?>
								</select></div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="editOu">Course</label>
						    <div class="controls" id="selectcourse">
						    
							  <?php
							      // show the list of degree programs based on the selected college
							      //$conn = mysqli_connect('localhost','root','','netid');
								  if (!mysqli_connect_errno($conn)){
									$query = "SELECT * FROM degreeprograms WHERE gidnumber=".$entries[0]['gidnumber'][0];
									$degreeprograms = mysqli_query($conn, $query);
									
									echo  '<select name="ou" id="editOu" class="input-xlarge"   >';		
									       while($row = mysqli_fetch_array($degreeprograms)){
												echo '<option value="'.$row['name'].'"';
												if($entries[0]['ou'][0] == $row['name']) echo ' selected';
												echo '>'.$row['title'].'</option>';
											}
										
									echo '</select>';
										
								}
								else echo "Cannot connect to the database"; 
								mysqli_close($conn);
							?>
						    </div>
						  </div>
						 
						   <!-- hiddden attributes-->
						  <input type="hidden" id="hiddenHomedirectory" name="homedirectory" value="/home/<!-- username will be appended -->"/> 
						   <input type="hidden" id="hiddenTitle" name="title" value="employee"/>
						   <input type="hidden" id="hiddenShadowmax" name="shadowmax" value="9999"/>
						   <input type="hidden" id="hiddenShadowwarning" name="" value="7"/>
						   <input type="hidden" id="hiddenLoginshell" name="loginshell" value="/bin/bash"/>	
						   <input type="hidden" id="hiddenObjectclass[0]" name="objectclass[0]" value="inetOrgPerson"/>	
						   <input type="hidden" id="hiddenObjectclass[1]" name="objectclass[1]" value="posixAccount"/>	
						   <input type="hidden" id="hiddenObjectclass[2]" name="objectclass[2]" value="top"/>	
						   <input type="hidden" id="hiddenObjectclass[3]" name="objectclass[3]" value="shadowAccount"/>	
                           <!-- objectclass -->						   
					    </form>
				  </div>
                  
				  <div class="modal-footer">
				    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
				    <button type="submit" onclick="javascript:editentry('student', '<?php echo $entries[0]['dn'];?>')" class="btn btn-primary">Save changes</button>
				  </div>
                </div><!-- end modal -->		   
                				  
	