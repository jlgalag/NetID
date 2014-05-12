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
	$result = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'],  "uid=".$userUid);
	$entries = ldap_get_entries($ldapconn, $result);
	$entry_count = $entries['count'];
	$uidNum = $entries[0]['uidnumber'][0];

	
    if($entry_count > 0){ 
	       /*Bind to the ldap active directory*/
	   $bind2=ldap_bind($ldapconn, "uniqueIdentifierUPLB=".$uidNum.",ou=people,dc=uplb,dc=edu,dc=ph", $userPassword);
	   if (!$bind2) {   
	   		echo "incorrect password";
	      	redirect('index.php');
	   }else{
	   		session_start();
	   		$_SESSION['userUid'] = "";
			$_SESSION['title'] = "";
			$_SESSION['role'] = "";
			$_SESSION['date'] = "";
			
			$title = array();
			$role_count=0;
			// set title  (student / employee)
			foreach ($entries[0]['objectclass'] as $value) {
				if($value == "UPLBEmployee"){
					$title[$role_count] = "employee";
					$role_count++;
				}
				elseif ($value == "UPLBStudent"){
					$title[$role_count] = "student";
					$role_count++;
				}
			}

			//get role from ldap
			$userRole = array();
			$i=0;
			// dump roles from directory too array
			for(; $i<$role_count; $i++)
				$userRole[$i] = $title[$i];
			//get role from database
			$query="SELECT role FROM user_role WHERE uid='" .$userUid. "'";
			$result=mysqli_query($conn,$query);
			$count = mysqli_num_rows($result);	
			if($count>0)
			// dump roles from database to array
			for(;$i<$count+$role_count+1;$i++){
				$row = mysqli_fetch_array($result);
				$userRole[$i] = $row['role'];
			}
			mysqli_close($conn);
		    
			//set session data
			$_SESSION['userUid'] = $userUid;
			$_SESSION['title'] = 	$title;		  
			$_SESSION['role'] = 	$userRole;
			$_SESSION['activerole'] = 	$userRole[0];
			$today = date("Y-m-d H:i:s");     
			$_SESSION['date'] = 	$today;   
			$_SESSION['group'] = "";	  
			$_SESSION['gidnumber'] = "";	  

	   		redirect('home.php');

	   }
	}   
	else {  
	   echo "Unable to Connect..</br>";
    } 

    // convert password to md5
/*
	$ldap = ldap_bind($ldapconn);

	 $dn = "uniqueIdentifierUPLB=2725,ou=people,dc=uplb,dc=edu,dc=ph";
        $attr = "password";
        $value = "secretPassword";

        // compare value
        $r=ldap_compare($ldapconn, $dn, $attr, $value);

        if ($r === -1) {
            echo "Error: " . ldap_error($ldapconn);
        } elseif ($r === true) {
            echo "Password correct.";
        } elseif ($r === false) {
            echo "Wrong guess! Password incorrect.";
        }
*/
	//$sshaPassword = "{SSHA}".base64_encode(sha1($userPassword.$salt). $salt);
	// check for user with password exists
	//$result = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'],  "uid=$userUid");
	//$entries = ldap_get_entries($ldapconn, $result);
	//for ($i=0; $i<$entries["count"]; $i++) {
	//	echo "dn is: " . $entries[$i]["dn"] . "<br />";
	//	echo "first cn entry is: " . $entries[$i]["o"][3] . "<br />";
	//}
	//$r = ldap_compare($ldapconn, "uniqueIdentifierUPLB=".$entries[0]["uniqueIdentifierUPLB"].",ou=people,".$ldapconfig['basedn'], attribute, value)
	// count of roles of user in the directort (student and employee)
	//$rolecount = $entries['count'];

	//if($rolecount > 0){
	//	session_start();   
	//	echo "haha";
	//	redirect('home.php');

	//}
	//else 
	//	echo "<h4>Incorrect Password.</h4>";	
?>