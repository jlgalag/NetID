 <!-- Log in Authentication -->
<?php
	function redirect($filename) {
	   if (!headers_sent())
		   header('Location: '.$filename);
	   else {
		   echo '<script type="text/javascript">';
		   echo 'window.location.href="'.$filename.'";';
		   echo '</script>';
		   echo '<noscript>';
		   echo '<meta http-equiv="refresh" content="0;url='.$filename.'" />';
		   echo '</noscript>';
	   }
	}

	$result = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "uid=".$userUid);
	$entries = ldap_get_entries($ldapconn, $result);
	$rolecount = $entries['count'];
	$uidNum = $entries[0]['uidnumber'][0];

	if($ldapconn){

		$bind2 = ldap_bind($ldapconn, "uniqueIdentifierUPLB=".$uidNum.",ou=people,dc=uplb,dc=edu,dc=ph",$userPassword);
		
		if(!$bind2){
			echo "<h4>Incorrect Password.</h4>";
			//redirect('index.php');	
			
		}
		else {
			session_start();   
			
			$_SESSION['userUid'] = "";
			$_SESSION['title'] = "";
			$_SESSION['role'] = "";
			$_SESSION['date'] = "";
		
			// set title  (student / employee)
			$temp=0;
			foreach ($entries[0]['objectclass'] as $value){
				$title[$temp] = strtolower(substr($value,4));
				$temp++;
			}
			//get role from ldap
			$userRole = array();
			$i=0;
			//get role from database
			$query="SELECT role FROM user_role WHERE uid='" .$userUid. "'";
			$result=mysqli_query($conn,$query);
			$count = mysqli_num_rows($result);	
			if($count>0)
			// dump roles from database to array
			for($i=0;$i<$count;$i++){
				$row = mysqli_fetch_array($result);
				$userRole[$i] = $row['role'];
			}
		    // dump roles from directory too array
		    $j=2;
			for($i=$i; $i<$count+$temp; $i++){
				$userRole[$i] = $title[$j];
				$j++;
			}
			mysqli_close($conn);
			//set session data
			$_SESSION['userUid'] = $userUid;	
			$_SESSION['title'] = $title[$j-3];		  
			$_SESSION['role'] = 	$userRole;
			$_SESSION['activerole'] = 	$userRole[0];
			$today = date("Y-m-d H:i:s");     
			$_SESSION['date'] = 	$today;   
			$_SESSION['group'] = "";	  
			$_SESSION['gidnumber'] = "";	  

			
			redirect('home.php');
		}
		
	}
	else{
		echo "Error:". ldap_error($ldapconn);
	}


	
?>