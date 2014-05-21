				
					<img src="tools/img/horredline.jpg"/>
					<table class="table table-stripped">
				     <?php
						echo "<tr>";
			            echo "<td>Name</td>";
						echo "<td>".$entries[0]["cn"][0]."</td>";
						echo "</tr>";
					    
						echo "<tr>";
			            echo "<td>Course Graduated</td>";
						echo "<td>".$entries[0]["coursegraduated"][0]."</td>";
						echo "</tr>";
					    
						echo "<tr>";
			            echo "<td>Year Graduated</td>";
						echo "<td>".$entries[0]["yeargraduated"][0]."</td>";
						echo "</tr>";
						  
						echo "<tr>";
			            echo "<td>College</td>";
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
                				  
	