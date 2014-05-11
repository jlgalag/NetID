
    <h1>Employee Profile</h1>
	<img src="tools/img/horredline.jpg"/>
    
	<table class="table table-stripped">
	   <?php
             $changeown = true; //flag to determine if user is changing his own password; use to determine which form to display
		    

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
			echo "<td>".$entries[0]["o"][0]."</td>";
			echo "</tr>";
			
			echo "<tr>";
            echo "<td>Email Address</td>";
			echo "<td><span  id='mail'>".$entries[0]["mail"][0]."</span>   <a id='displayEditMailForm', href='javascript:toggleEditMailForm();'>(Edit)</a>"; 
	              include 'frag_editmailform.php'; 
			echo "</td></tr>";
			
			echo "<tr><td></td>";
			echo "<td><a id='displayChangePwdForm', href='javascript:toggleChangePwdForm();'>Change Password</a>";
			include 'frag_changepasswordform.php';
			echo "</td></tr>";
		?>			
	   		
			
	
	</table>
    

