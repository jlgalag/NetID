
    <h1 style="float:left">Alumni Profile</h1>
	<img src="tools/img/horredline.jpg"/>
    

	<table class="table table-stripped">
	   <?php
            $changeown = true; //flag to determine if user is changing his own password; use to determine which form to display
			echo "<tr>";
            echo "<td>Name</td>";
			echo "<td>".$entries[0]["cn"][0]."</td>";
			echo "</tr>";
		    
			echo "<tr>";
            echo "<td>Course Gradutated</td>";
			echo "<td>".$entries[0]["coursegraduated"][0]."</td>";
			echo "</tr>";
		    
			echo "<tr>";
            echo "<td>Year Gradutated</td>";
			echo "<td>".$entries[0]["yeargraduated"][0]."</td>";
			echo "</tr>";
			  
			echo "<tr>";
            echo "<td>College</td>";
			echo "<td>".$group."</td>";
			echo "</tr>";
			
			echo "<tr>";
            echo "<td>Email Address</td>";
			echo "<td><span  id='mail'>".$entries[0]["mail"][0]."</span>   <a id='displayEditMailForm', href='javascript:toggleEditMailForm();'>(Edit)</a>"; 
	              include 'frag_editmailform.php'; 
			echo "</td></tr>";
			
			echo "<tr><td></td>";
			echo "<td><a id='displayChangePwdForm', href='javascript:toggleChangePwdForm();'>Change Password</a>";
			echo '<div id="changePwdNotif">
                     
                  </div>';
			
			include 'frag_changepasswordform.php';
			echo "</td></tr>";
		?>	

		
	</table>
			

