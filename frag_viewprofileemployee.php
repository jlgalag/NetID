					
					<img src="tools/img/horredline.jpg"/>
					<table class="table table-stripped">
					<?php
						echo "<tr>";
			            echo "<td>Name</td>";
					    echo "<td>".$entries[0]["cn"][0]."</td>";
					    echo "</tr>";
					    echo "<tr>";
			            echo "<td>Employee Number</td>";
						echo "<td>".$entries[0]["employeenumber"][0]."</td>";
						echo "</tr>";
					    echo "<tr>";
			            echo "<td>Employee Type</td>";
						echo "<td>".$entries[0]["employeetype"][0]."</td>";
						echo "</tr>";
					    echo "<tr>";
			            echo "<td>Department</td>";
						echo "<td>".$entries[0]["ou"][0]."</td>";
						echo "</tr>";
						 
						 
						echo "<tr>";
			            echo "<td>College/Office</td>";
						echo "<td>".$group."</td>";
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
		
	
				<!--Edit employee Modal -->
				<!--  -This will be displayed when the edit link is clicked-->
				<!-- -When the form is submitted , call the editentry function in the functions.js-->
				<div id="editModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				    <h3 id="myModalLabel">Edit Employee Information</h3>
				  </div>
				  
				  <div class="modal-body">
						<form class="form-horizontal" id="formeditemployee" name="formeditemployee">
						  <input class="input-xlarge" name="uid" type="hidden" id="editUsername" placeholder="Username"  value='<?php echo $entries[0]['uid'][0]?>'>
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
						    <label class="control-label" for="editEmployeenumber">Employee Number</label>
						    <div class="controls">
						      <input class="input-xlarge" readonly name="employeenumbernumber" type="text" id="editEmployeenumber" placeholder="xxxxxxxxx" value='<?php echo$entries[0]['employeenumber'][0] ?>'>
						    </div>
						  </div>
						     <div class="control-group">
						    <label class="control-label" for="editEmployeetype">Employee Type</label>
						    <div class="controls">
	                           <select name="employeetype"  id="editEmployeetype"  class="input-xlarge"  >
							       <option value='' disabled selected style='display:none;'>Select Employee Type</option>
							       <option value="FAC" <?php if($entries[0]['employeetype'][0] == "FAC") echo ' selected';?>>FAC</option>
							       <option value="NGW" <?php if($entries[0]['employeetype'][0] == "NGW") echo ' selected';?>>NGW</option>
							       <option value="ADM" <?php if($entries[0]['employeetype'][0] == "ADM") echo ' selected';?>>ADM</option>
							       <option value="R E P S"<?php if($entries[0]['employeetype'][0] == "R E P S") echo ' selected';?>>REPS</option>
							  </select>					     
  							 </div>
							</div>
						  	 <div class="control-group">
						    <label class="control-label" for="editGidnumber">College/Office</label>
						    <div class="controls">
							     <select name="gidnumber" id="editGidnumber" class="input-xlarge"  >
									 <?php
									    // show the list of colleges and offices in a select field
									      //$conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
										    $query="SELECT * FROM offices ORDER BY gidnumber";
											$offices=mysqli_query($conn, $query);
											
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											while($row = mysqli_fetch_array($offices)){
											     echo    '<option value="'.$row['gidnumber'].'"';
												if($entries[0]['gidnumber'][0] == $row['gidnumber']) echo ' selected';
												echo '>'.$row['name'].'</option>';
											}	
											
											while($row = mysqli_fetch_array($college)){
											    echo    '<option value="'.$row['gidnumber'].'"';
												if($entries[0]['gidnumber'][0] == $row['gidnumber']) echo ' selected';
												echo '>'.$row['name'].'</option>';
											}
										}
										else echo "<option>Cannot connect to the database</option>";
									 ?>
								  </select></div>
						  </div>
						   <div class="control-group">
						    <label class="control-label" for="editOu">Office/Department</label>
						    <div class="controls">
						      <input class="input-xlarge" name="ou" type="text" id="editOu" placeholder="Office/College"  value='<?php echo $entries[0]['ou'][0]?>'>
						    </div>
						  </div>
						 
						   <!-- hiddden attributes-->
						   <input type="hidden" id="hiddenHomedirectory" name="homedirectory" value="/home/"/> <!-- username will be appended here-->
						   <input type="hidden" id="hiddenTitle" name="title" value="employee"/>
						   <input type="hidden" id="hiddenShadowmax" name="shadowmax" value="9999"/>
						   <input type="hidden" id="hiddenShadowwarning" name="" value="7"/>
						   <input type="hidden" id="hiddenLoginshell" name="loginshell" value="/bin/bash"/>	
						   <input type="hidden" id="hiddenObjectclass[0]" name="objectclass[0]" value="inetOrgPerson"/>	
						   <input type="hidden" id="hiddenObjectclass[1]" name="objectclass[1]" value="posixAccount"/>	
						   <input type="hidden" id="hiddenObjectclass[2]" name="objectclass[2]" value="top"/>	
						   <input type="hidden" id="hiddenObjectclass[3]" name="objectclass[3]" value="shadowAccount"/>	
                          						   
					    </form>
				  </div>
                  
				  <div class="modal-footer">
				    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
				    <button type="submit" onclick="javascript:editentry('employee', '<?php echo $entries[0]['dn'];?>')" class="btn btn-primary">Save changes</button>
				  </div>
                </div><!-- end modal -->		   
                