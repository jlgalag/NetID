<?php
    require 'tools/PHPMailer_5.2.4/class.phpmailer.php';
   	require 'ldap_config.php'; 
	require 'frag_sessions.php';
    //$conn = mysqli_connect('localhost','root','','netid');

	// Check connection
	if (mysqli_connect_errno($conn))
		 echo "Failed to connect to MySQL: " . mysqli_connect_error();
		 
    function sendmail($subject, $data, $to){
		$from = "ace.almazar@gmail.com";
		$mail = new PHPMailer;


		$mail->IsSMTP();  // telling the class to use SMTP
		$mail->Host     = "smtp.gmail.com"; // SMTP server
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 1;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465	;
		$mail->Username="ace.almazar@gmail.com";
		$mail->Password="";

		$mail->IsHTML(true); 

		$mail->SetFrom     = $from;
		$mail->FromName = "UPLB NETID";
		$mail->AddAddress($to);

		$mail->Subject  = $subject;
		$mail->Body     = $data;
		$mail->WordWrap = 100;

		if(!$mail->Send()) {
		  echo 'Message was not sent.';
		  echo 'Mailer error: ' . $mail->ErrorInfo;
		} else {
		  echo 'Message has been sent to '. $to;
		}
	}
	
	// Delete an entry in the directory
    function delete($dn,$uid,$status,$title){
	   global $ldapconn,$conn,$userUid;

	   //determines what account type should be disabled or enabled
	   if($title == 'student'){
	   	if($status == 'activate') $info['activestudent'] = "TRUE";
	   	else $info['activestudent'] = "FALSE";
	   }
	   else if ($title == 'employee'){
	   	if($status == 'activate') $info['activeemployee'] = "TRUE";
	   	else $info['activeemployee'] = "FALSE";
	   }

	   	//makes changes in the server
	   	$res = ldap_modify($ldapconn, $dn, $info);
	   	
	   	if(strpos($dn, 'disabled')) $ndn = "ou=disabledAccounts,dc=uplb,dc=edu,dc=ph";
	   	else $ndn = "ou=people,dc=uplb,dc=edu,dc=ph";

	   	//searches for the entry's attributes
	 	$sr=ldap_search($ldapconn, $ndn, "(&(uid=".$uid.")(title=".$title."))");
		$entries = ldap_get_entries($ldapconn, $sr);

		//variable rename is set to 1 initially, if the entry has both disabled employee and student accounts. it will be disabled
		$rename = 1;

	   	if ($entries[0]['activeemployee'][0] == 'FALSE'){
	   		$rename = 0;
	   		if($entries[0]['activestudent'][0] == 'FALSE'){
	   			$rename = 0;
	   		}else if($entries[0]['activestudent'][0] == 'TRUE') $rename = 1;
	   	}else if ($entries[0]['activestudent'][0] == 'FALSE'){
	   		$rename = 0;
	   		if($entries[0]['activeemployee'][0] == 'FALSE'){
	   			$rename = 0;
	   		}else if($entries[0]['activeemployee'][0] == 'TRUE') $rename = 1;
	   	}else $rename = 1;
		
		//moves the dn of entry to disabledAccounts if rename is set to 0
		if($rename == 0){	
			$newRdn = 'uniqueIdentifierUPLB='.$entries[0]['uidnumber'][0];
			$newParent = 'ou=disabledAccounts,dc=uplb,dc=edu,dc=ph';
			$ren = ldap_rename($ldapconn, $dn, $newRdn, $newParent, true);
		}
		//however if rename is 1, entry is activated
		else if($rename == 1){
			$newRdn = 'uniqueIdentifierUPLB='.$entries[0]['uidnumber'][0];
			$newParent = 'ou=people,dc=uplb,dc=edu,dc=ph';
			$ren = ldap_rename($ldapconn, 'uniqueIdentifierUPLB='.$entries[0]['uidnumber'][0].',ou=disabledAccounts,dc=uplb,dc=edu,dc=ph', $newRdn, $newParent, true);
		}


	   if($res){
	        //insert to audit logs
	        date_default_timezone_set('Asia/Manila');
			$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','delete','".$_SERVER["REMOTE_ADDR"]."','".$uid."')";
	        $insert =mysqli_query($conn, $query);
			   
			echo "Entry successfully ".$status."d."; }
	   else      echo ldap_error($ldapconn );

       mysqli_close($conn);		
       return;
	}
	
	 // Edit email address
	 function editmail($dn, $newmail){
	   global $ldapconn,$conn,$userUid;
	    //insert to audit logs
	    date_default_timezone_set('Asia/Manila');
		$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','change email','".$_SERVER["REMOTE_ADDR"]."','".$userUid."')";
	    $insert =mysqli_query($conn, $query);
 	    $editmail = array("mail" => array($newmail));
        $res = ldap_modify($ldapconn, $dn, $editmail);
      
	   if($res) {
	      echo $newmail; }
	   else      echo ldap_error($ldapconn );
	   
	   mysqli_close($conn);
	}
	
	/**
	*	Function to be used when user changes his own password
	*
	*	@param String - $dn 	distinguished name of an LDAP entity
	* 		   Array - $pwd 	current password , new password, confirmation password
	*		   String - $mail 	email address of the user
	*
	*/
	 function changeownpassword($dn, $pwd, $mail){
		global $ldapconn,$conn,$userUid;
	 
			$res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph","uid=".$userUid);
			$entries = ldap_get_entries($ldapconn, $res);
			$rolecount = $entries['count'];
			$uidNum = $entries[0]['uidnumber'][0];

			$bind3 = ldap_bind($ldapconn, "uniqueIdentifierUPLB=".$uidNum.",ou=people,dc=uplb,dc=edu,dc=ph",$pwd['inputPwd']);

			if(!$bind3){ 
				echo "<p class='changepasswordnotif'>Incorrect Password</p>";
			}
			else{
		        if($pwd['newPwd'] == $pwd['conPwd']){
		            $changePwd= array("userpassword" => array($pwd['md5NewPwd']));
		            $res= ldap_modify($ldapconn, $dn, $changePwd);
		            if($res){
					    //insert into audit logs
						date_default_timezone_set('Asia/Manila');
						$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','change password','".$_SERVER["REMOTE_ADDR"]."','".$userUid."')";
					    $insert =mysqli_query($conn, $query);
				 	    
						echo "<p class='changepasswordnotif'>Password successfully change</p>";
						if($mail != NULL){
						//SEND EMAIL
						    $subject = "NetID Account password change";
							$data = "<p>Hi ".$mail."<br/>".
							        "The password of your NetID account was recently changed. <br/>". 
									"If you made this change, you don't need to do anything more."."<br/>".
									"If you didn't authorized this change, your account might have been hijacked.<br/> 
									You are advised to go to UPLB NetID page if you wish to change your password.". "<br/><br/>".
									"Sincerely,"."<br/>".

									"UPLB NetID Account team". "<br/>";
							sendmail($subject, $data,  $mail);
						}
						else echo "Please provide an email address.<br/>";
					}
				}
				else echo "<p class='changepasswordnotif'>New Password and Confirm Password inputs are not the same.</p>";	
		  }
		  
		  mysqli_close($conn);
    }
    
	/**
	*	Function to be used when user changes other account's password
	*
	*	@param String - $dn 	distinguished name of an LDAP entity
	* 		   Array - $pwd 	current password , new password, confirmation password
	*		   String - $mail 	email address of the affected user
	*
	*/
	 function changeotherpassword($dn, $pwd, $uid, $mail){
		global $ldapconn,$conn,$userUid;
	  
	 	  
		    if($pwd['newPwd'] == $pwd['conPwd']){
		            $changePwd= array("userpassword" => array($pwd['md5NewPwd']));
		            $res= ldap_modify($ldapconn, $dn, $changePwd);

		            if($res){
					    //insert into audit logs
						date_default_timezone_set('Asia/Manila');
						$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','change password','".$_SERVER["REMOTE_ADDR"]."','".$uid."')";
					    $insert =mysqli_query($conn, $query);
				 	    
						echo "<p class='changepasswordnotif'>Password successfully change</p>";
						if($mail != NULL){

							//SEND EMAIL
							$subject = "NetId Account password change";
							$data = "<p>Hi ".$mail."<br/>".
							        "The password of your NetID account was recently changed.<br/>". 
									"If you made this change, you don't need to do anything more."."<br/>".
									"If you didn't authorized this change, your account might have been hijacked. 
									You are advised to go to UPLB NetID page if you wish to change your password.". "<br/><br/>".
									"Sincerely,"."<br/>".
									"UPLB NetID Account team". "<br/>";
							sendmail($subject, $data,  $mail);
						}
						else echo "Please provide an email address.<br/>";
					
					}
			}		
			else echo "<p class='changepasswordnotif'>New Password and Confirm Password inputs are not the same.</p>";	
		  
		  mysqli_close($conn);
    }
	
    //show degreee programs on select for add
    function selectdegreeprograms($gidnumber){
	     global $conn;
		 // Check connection
		if (!mysqli_connect_errno($conn)){
			$query = "SELECT * FROM degreeprograms WHERE gidnumber=".$gidnumber;
			$degreeprograms = mysqli_query($conn, $query);
			
			echo  '<select name="ou" id="inputOu" class="input-large">';		
			        echo "<option value='' disabled selected style='display:none;'>Select Course</option>";

					while($row = mysqli_fetch_array($degreeprograms)){
						echo '<option value="'.$row['name'].'">'.$row['title'].'</option>';
					}		

			echo '</select>';		
		}
		else echo "Cannot connect to the database";

        mysqli_close($conn);		
    }	 
   
     //show degreee programs on select for edit
    function editselectdegreeprograms($gidnumber, $ou){
	     global $conn;
		 // Check connection
		if (!mysqli_connect_errno($conn)){
			$query = "SELECT * FROM degreeprograms WHERE gidnumber=".$gidnumber;
			$degreeprograms = mysqli_query($conn, $query);
			
			echo  '<select name="ou" id="editOu" class="input-large"  required >';
			       while($row = mysqli_fetch_array($degreeprograms)){
						echo '<option value="'.$row['name'].'"';
						if($ou == $row['name']) echo ' selected';
						echo '>'.$row['title'].'</option>';
					}
				
			echo '</select>';
				
		}
		else echo "Cannot connect to the database"; 
		mysqli_close($conn);
    }

    /**
    *	Checks the student account already exist
    *
    *	@param String - $username 				Username 
    * 		   String - $studentnumber 			Student Number	
    * 		   String - $cn 					Complete name of UPLBEntity
	*											format (Surname, First Name, Middle Initial.)
	*
    */
	function checkstudentnumber($username,$studentnumber,$cn){
	  global $ldapconn, $ldapconfig;

	  $sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=student)(|(uid=".$username.")(studentnumber=".$studentnumber.")))");
	  $count = ldap_count_entries($ldapconn, $sr).'</td>';

	  $sr2 = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=employee)(|(uid=".$username.")(cn=".$cn.")))");
	  $count2 = ldap_count_entries($ldapconn, $sr2).'</td>';


	  if($count > 0) echo "Username or Student Number already exists.";
	  else if ($count2 > 0) echo "ADD";
	  else echo "OK";
	}
	
	/**
    *	Checks the employee account already exist
    *
    *	@param String - $username 				Username 
    * 		   String - $employeenumber 		Employee Number	
    * 		   String - $cn 					Complete name of the entity
    */
	function checkemployeenumber($username, $employeenumber,$cn){
	  global $ldapconn, $ldapconfig;
	  
 
	  $sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=employee)(|(uid=".$username.")(employeenumber=".$employeenumber.")))");
	  $count = ldap_count_entries($ldapconn, $sr).'</td>';

	  $sr2 = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=student)(|(uid=".$username.")(cn=".$cn.")))");
	  $count2 = ldap_count_entries($ldapconn, $sr2).'</td>';

	  if($count > 0)  echo "Username or Employee Number already exists.";
	  else if ($count2 > 0)  echo "ADD";
	  else echo "OK";
	}
	
	/**
	*	Encrypts a string into salted sha1 (SSHA)
	*
	* 	@param String - $string 	 String to be encrypted (password)
	*
	*/
	function encrypt($string){

		$salt = sha1(rand());
		$salt = substr($salt, 0, 4);

		//return encrpted value
		return $encrypted = "{SSHA}".base64_encode( sha1( $string . $salt, true) . $salt );
	}

	function addentry($info, $dn){
        global $ldapconn, $ldapconfig, $userUid, $conn;
        $userpassword = $info['userpassword'];
	    $info["objectclass"][0] = "UPLBEntity";
	    
	    //if add as student
	    if(isset($info['studentnumber'])){
	    	 $info["objectclass"][1] = "UPLBStudent";
	    }
	    //if add as employee
	    elseif (isset($info['employeenumber'])) {
	    	$info["objectclass"][1] = "UPLBEmployee";
	    }

	    $info['securityquestion'] = $info['securityquestion'];
	    $info['securityanswer'] = encrypt($info['securityanswer']);
		$info['userpassword'] = encrypt($info['userpassword']);

        $add = ldap_add($ldapconn, $dn, $info);
        
		if($add){
       		//modify uidLatestNumber
	        $dnuidnumberholder = 'cn=uidLatestNumber,ou=numberholder,dc=uplb,dc=edu,dc=ph';		
		    $newuidnumholder = array("serialnumber" => array($info['uidnumber']));
		    $moduid = ldap_modify($ldapconn, $dnuidnumberholder, $newuidnumholder);
        }
        else 	echo ldap_error($ldapconn );

	    if($add && $moduid) {
		       // add to audit log
			   date_default_timezone_set('Asia/Manila');
			   $query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','insert','".$_SERVER["REMOTE_ADDR"]."','".$info['uid']."')";
	           $insert =mysqli_query($conn, $query);
			 
			echo "Successfully added  <b>". $info['uid']. "</b>  to the directory. <br/>"; 
            //SEND EMAIL
			if($info['mail'] != NULL){
						$subject = "UPLB NetID Account created";
						$data = "<p>Hi ".$info['mail']."<br/><br/>".
						            "You're UPLB NetID account has been created.". "<br/>".
							        "Your username is <b>". $info['uid'] ."</b> and your password is <b>".$userpassword. "</b>"."<br/>". 
									"NetID account is used by various UPLB network services, like the UPLB Wifi, for user authentication."."<br/>".
									"Visit <\NetID web page\> and change your password right away.<br/><br/>".
									"Sincerely,"."<br/>".
									"UPLB NetID Account team". "<br/></p>";
						
						sendmail($subject ,$data, $info['mail']);	
			}
			else echo "Please provide an email address.<br/>";
	    }
		else  echo ldap_error($ldapconn); 
	    
		mysqli_close($conn);
	 }	 

	/**
	*	Add an account in the server
	*
	* 	@param Array - $info 	 attributes to be added
	* 		   String - $dn 	 distinguished name of an LDAP entity
	*
	*/
     function f($info, $dn){
        global $ldapconn, $ldapconfig, $userUid, $conn;
        $userpassword = $info['userpassword'];
	    $info["objectclass"][0] = "UPLBEntity";
	    
	    //if add as student
	    if(isset($info['studentnumber'])){
	    	 $info["objectclass"][1] = "UPLBStudent";
	    }
	    //if add as employee
	    elseif (isset($info['employeenumber'])) {
	    	$info["objectclass"][1] = "UPLBEmployee";
	    }

	    $info['securityquestion'] = $info['securityquestion'];
	    $info['securityanswer'] = encrypt($info['securityanswer']);
		$info['userpassword'] = encrypt($info['userpassword']);

        $add = ldap_add($ldapconn, $dn, $info);
        
		if($add){
       		//modify uidLatestNumber
	        $dnuidnumberholder = 'cn=uidLatestNumber,ou=numberholder,dc=uplb,dc=edu,dc=ph';		
		    $newuidnumholder = array("serialnumber" => array($info['uidnumber']));
		    $moduid = ldap_modify($ldapconn, $dnuidnumberholder, $newuidnumholder);
        }
        else 	echo ldap_error($ldapconn );

	    if($add && $moduid) {
		       // add to audit log
			   date_default_timezone_set('Asia/Manila');
			   $query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','insert','".$_SERVER["REMOTE_ADDR"]."','".$info['uid']."')";
	           $insert =mysqli_query($conn, $query);
			 
			echo "Successfully added  <b>". $info['uid']. "</b>  to the directory. <br/>"; 
            //SEND EMAIL
			if($info['mail'] != NULL){
						$subject = "UPLB NetID Account created";
						$data = "<p>Hi ".$info['mail']."<br/><br/>".
						            "You're UPLB NetID account has been created.". "<br/>".
							        "Your username is <b>". $info['uid'] ."</b> and your password is <b>".$userpassword. "</b>"."<br/>". 
									"NetID account is used by various UPLB network services, like the UPLB Wifi, for user authentication."."<br/>".
									"Visit <\NetID web page\> and change your password right away.<br/><br/>".
									"Sincerely,"."<br/>".
									"UPLB NetID Account team". "<br/></p>";
						
						sendmail($subject ,$data, $info['mail']);	
			}
			else echo "Please provide an email address.<br/>";
	    }
		else  echo ldap_error($ldapconn); 
	    
		mysqli_close($conn);
	 }	 
   
   	 /**
   	 *	Adds attributes on an existing account
   	 *	Records the changes made to auditlog
   	 *
   	 * 	@param Array - $info 		attributes to be added
   	 *		   String - $dn 		distinguished name of an LDAP entity
   	 */
     function addattr($info, $dn, $uid){
     	global $ldapconn, $ldapconfig, $userUid, $conn;
        
     	$add = ldap_mod_add($ldapconn, $dn, $info);

		if(!$add) echo ldap_error($ldapconn);
       	else {
       		date_default_timezone_set('Asia/Manila');
			$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','insert','".$_SERVER["REMOTE_ADDR"]."','".$uid."')";
	        $insert =mysqli_query($conn, $query);
       		echo "Attribute Successfully Added";
       	}
     }

     /**
   	 *	Remove student attributes on existing account
   	 *	Records the changes made to auditlog
   	 *
   	 * 	@param String - $uid 		user name of the affected user
   	 *		   String - $dn 		distinguished name of an LDAP entity
   	 */
     function removestudentattr($dn, $uid){
     	global $ldapconn, $ldapconfig, $userUid, $conn;
        

     	$ress = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph", "uid=".$uid);
        $entry = ldap_get_entries($ldapconn, $ress);

		$studInfo["objectclass"] = 'UPLBStudent';
		$studInfo["studentnumber"] =  $entry[0]['studentnumber'][0];
		$studInfo["studenttype"] =  $entry[0]['studenttype'][0];
		$studInfo["course"] =  $entry[0]['course'][0];
		$studInfo["college"] =  $entry[0]['college'][0];
		$studInfo["activestudent"] =  $entry[0]['activestudent'][0];
		$studInfo["title"] =  'student';
			     

     	$del = ldap_mod_del($ldapconn, $dn, $studInfo);

		if(!$del) echo ldap_error($ldapconn);
       	else {
       		date_default_timezone_set('Asia/Manila');
			$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','deleted','".$_SERVER["REMOTE_ADDR"]."','".$uid."')";
	        $insert =mysqli_query($conn, $query);
       	}
     }


		 // Edit email address
	 function editentry($info, $dn){
	   global $ldapconn, $conn, $userUid;
	   //$editmail = array("mail" => array($newmail));
       $res = ldap_modify($ldapconn, $dn, $info);
      
	   if($res) {
		// add to audit log
		date_default_timezone_set('Asia/Manila');
		$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','edit profile','".$_SERVER["REMOTE_ADDR"]."','".$info['uid']."')";
	    $insert =mysqli_query($conn, $query);
		
		echo "Edit Successful";
	   }
	   else      echo ldap_error($ldapconn );
	   
	   mysqli_close($conn);
	}
   

     function searchstudent($filter, $role){
	   global $ldapconn;
	   $rowNum = 0;

 	   //$editmail = array("mail" => array($newmail));
       $res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph", $filter);
       $entries = ldap_get_entries($ldapconn, $res);
	   if($res){  
	    //echo "<h4>".$filter."</h4>";
		if(!($entries["count"] > 0))
			echo "No Results Found";
		  else{
		  echo  "<script type='text/javascript'>
		        $(document).ready(function () {
		           $('#pagination').smartpaginator({ 
						totalrecords: ".$entries['count'].", 
						recordsperpage: 20, 
						datacontainer: 'tablelist', 
						dataelement: 'tr', 
						initval: 0, 
						next: 'Next', 
						prev: 'Prev', 
						first: 'First', 
						last: 'Last' ,
			            controlsalways: true,
						onchange: function (newPage) {
			                $('#r').html('Page # ' + newPage);
			                 }
		            });

		        });
		    </script>";
	   echo  '<div id="pagination" class="pagination pagination-small">
                    </div>	';
		   echo '<table class="table" id="tablelist" style="font-size:14px;">
										
											<tr>
								                 <th>Name</th>
								                 <th>Student Number</th>
												 <th>Type</th>
								                 <th>Mail</th>';
								                if($role==('ADMIN' || 'OUR')){
								               		echo  '<th>Status</th>
								                 <th>Undergrad</th>
								            </tr>';
								        }else echo '</tr>';
									    
									for($i=0; $i<count($entries)-1; $i++){
									   echo "<tr id='".$i."'>";
											echo 	"<td id='identifier' value='".$entries[$i]['uniqueidentifieruplb'][0]."'><a  style='color:#333333' href='viewprofile.php?title=student&uid=".$entries[$i]['uid'][0]."'";  
											         if (!($_SESSION['activerole'] =='ADMIN' || $_SESSION['activerole']=='HRDO' || $_SESSION['activerole']=='OUR')) echo "onclick='return false;'";        
													echo ">".$entries[$i]['cn'][0]."</a></td>";										  
											echo 	"<td id='uid' value='".$entries[$i]['uid'][0]."'>".$entries[$i]['studentnumber'][0]."</td>";
											echo 	"<td id='course' value='".$entries[$i]['course'][0]."'>".$entries[$i]['studenttype'][0]."</td>";
											//check if student has mail 
										    if(isset($entries[$i]['mail'])) echo 	"<td>".$entries[$i]['mail'][0]."</td>";
										    else echo "<td></td>";
										   	if($role==('ADMIN' || 'OUR')){
										    echo "<td id='enable_account' value='".$entries[$i]['activestudent'][0]."'><button class='btn btn-primary' id='enableButton' onclick='enableStudentAccount(".$i.")'>";
										    		if($entries[$i]['activestudent'][0]=='TRUE') echo "Deactivate";
										    		else echo "Activate";
										    		echo" </button></td>";
									    	echo "<td id='alumniAccount'><button class='btn btn-primary' onclick='addAlumniAttributes(".$i.")'><i class='icon-remove'></i></button></td>";
									    	}
									    echo "</tr>";
									   //$rowNum++;
					                }
				echo '</table>';
		  }
	   }
	   else  echo ldap_error($ldapconn);
	  
	} 
	
	
	 function searchemployee($filter, $role){
	   global $ldapconn;

 	   //$editmail = array("mail" => array($newmail));
       $res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph", $filter);
       $entries = ldap_get_entries($ldapconn, $res);
	   if($res){  
	     // echo "<h4>".$filter."</h4>";
		  if(!($entries["count"] > 0))
			echo "No Results Found";
		  else{
		    echo  "<script type='text/javascript'>
			        $(document).ready(function () {
			           $('#pagination').smartpaginator({ 
							totalrecords: ".$entries['count'].", 
							recordsperpage: 20, 
							datacontainer: 'tablelist', 
							dataelement: 'tr', 
							initval: 0, 
							next: 'Next', 
							prev: 'Prev', 
							first: 'First', 
							last: 'Last' ,
				            controlsalways: true,
							onchange: function (newPage) {
				                $('#r').html('Page # ' + newPage);
				                 }
			            });
			        });
			      </script>";

	   		echo  '<div id="pagination" class="pagination pagination-small">
             	  </div>	';
					
		   echo '<table class="table" id="tablelist" style="font-size:14px;">
										
											<tr>
								                 <th>Name</th>
								                 <th>Employee Number</th>
												 <th>Type</th>
								                 <th>Mail</th>';
								            if($role==('ADMIN' || 'HRDO')){
								               echo  '<th>Status</th>
								            </tr>';
								        	}else echo '</tr>';
									    
									for($i=0; $i<count($entries)-1; $i++){
									   echo "<tr id='".$i."'>";
											echo 	"<td id='identifier' value='".$entries[$i]['uniqueidentifieruplb'][0]."'><a  style='color:#333333' href='viewprofile.php?title=employee&uid=".$entries[$i]['uid'][0]."'>";  
											         if (!($_SESSION['activerole'] =='ADMIN' || $_SESSION['activerole']=='HRDO' || $_SESSION['activerole']=='OUR')) echo "onclick='return false;'";        
													echo $entries[$i]['cn'][0]."</a></td>";														  
											echo 	"<td id='uid' value='".$entries[$i]['uid'][0]."'>".$entries[$i]['employeenumber'][0]."</td>";
											echo 	"<td id='course' value='".$entries[$i]['ou'][0]."'>".$entries[$i]['employeetype'][0]."</td>";
											//check if student has mail 
										    if(isset($entries[$i]['mail'])) echo 	"<td>".$entries[$i]['mail'][0]."</td>";
										    else echo "<td></td>";
											if($role==('ADMIN' || 'HRDO')){
											echo "<td id='enable_account' value='".$entries[$i]['activeemployee'][0]."'><button class='btn btn-primary' id='enableButton' onclick='enableEmployeeAccount(".$i.")'>";
										    		if($entries[$i]['activeemployee'][0]=='TRUE') echo "Deactivate";
										    		else echo "Activate";
										    		echo" </button></td>";
										   	}
											// /else echo "<td>Inactive</td>";
									    echo "</tr>";
									    //$rowNum++;
					                }

				echo '</table>';
		  }
	   }
	   else  echo ldap_error($ldapconn );
	
	}

	
	 function searchuid($uid){
	   global $ldapconn;
	  
 	   //$editmail = array("mail" => array($newmail));
       $res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph", "(uid=*".$uid."*)");
       $entries = ldap_get_entries($ldapconn, $res);
	   
	    if(!($entries["count"] > 0))
			echo "No Results Found";
		else{
		    echo '<select name="adduid" id="addinputuid" class="input-xlarge" required >';
		    for($i=0; $i<count($entries)-1; $i++){
                echo "<option value=".$entries[$i]['uid'][0].">".$entries[$i]['title'][0]." - ".$entries[$i]['cn'][0].", &nbsp;".$entries[$i]['ou'][0]."</option>"; 
            }			
			echo "</select>";
			
			echo '<select name="addrole" id="addinputrole" class="input-xlarge">
					 <option value="OCS">OCS</option>
					 <option value="OUR">OUR</option>
					 <option value="HRDO">HRDO</option>
					 <option value="ADMIN">ADMIN</option>
				 </select>';
		}
	   
	 }  
	 
	 function addrole($uid, $addrole){
		   global $conn, $userUid;
		   
		   $query = "SELECT * FROM user_role WHERE uid='".$uid."' AND role='".$addrole."'";
		   $check = mysqli_query($conn, $query);

		   if(mysqli_num_rows($check) > 0 )
				echo "User <b>".$uid."</b> already has <b>".$addrole."</b> role.";	

		   else{ 
			   $query= "INSERT INTO user_role VALUES ('".$uid."','".$addrole."')";
			   $add =mysqli_query($conn, $query);

			   if($add){
				    // add to audit log
					date_default_timezone_set('Asia/Manila');
				    $query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','add role','".$_SERVER["REMOTE_ADDR"]."','".$uid."')";
				    $insert =mysqli_query($conn, $query);
					echo "Role successfully added.";
			   }

		       else echo mysqli_errno($conn) ." : ". mysqli_error($conn);	
		   }  
		  
	}

    function deleterole($uid, $delrole){
	   global $conn, $userUid;
	   $query="DELETE FROM user_role WHERE role='".$delrole."' and uid='".$uid."'";
	   $del =mysqli_query($conn, $query);
	   
	   			               
	   if($del) { echo "Role successfully deleted.";
		   // add to audit log
			date_default_timezone_set('Asia/Manila');
		    $query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','delete role','".$_SERVER["REMOTE_ADDR"]."','".$uid."')";
		    $insert =mysqli_query($conn, $query);
	   }
	   else echo mysqli_errno($conn) ." : ". mysqli_error($conn);	;
	   
	   mysqli_close($conn);
	}

    function adddegreeprogram($info){
       global $userUid, $conn;
	   
	   date_default_timezone_set('Asia/Manila');
		$query = "INSERT INTO degreeprograms (gidnumber, name, title) VALUES ('".$info['gidnumber']."','".$info['name']."','".$info['title']."')";
		$insert =mysqli_query($conn, $query);
		
		if($insert){
			$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','add degree program','".$_SERVER["REMOTE_ADDR"]."','".$info['name']."')";
		    $insert =mysqli_query($conn, $query);
	 	    
			echo "Successfully added  degree program <b>". $info['name']. "</b>  to the database. <br/>"; 
		}
		else echo mysqli_errno($conn) ." : ". mysqli_error($conn);	
		
		mysqli_close($conn);
	}
		
	function deletedegreeprogram($info){
       global $userUid, $conn;
	   
	   date_default_timezone_set('Asia/Manila');
		$query = "DELETE FROM degreeprograms WHERE gidnumber=".$info['gidnumber']." AND name='".$info['name']."'";
		$delete =mysqli_query($conn, $query);
		
		if($delete){
			$query="INSERT INTO auditlog (username,timestamp, accesstype, ipaddress, affecteduser) VALUES ('".$userUid."','".date('Y-m-d H:i:s')."','delete degree program','".$_SERVER["REMOTE_ADDR"]."','".$info['name']."')";
		    $insert =mysqli_query($conn, $query);
	 	    
			echo "Successfully deleted  degree program <b>". $info['name']. "</b>  from the database. <br/>"; 
		}
		else echo mysqli_errno($conn) ." : ". mysqli_error($conn);	
		
		mysqli_close($conn);
	}
	
	function viewauditlogs($dates){
	     global $conn;
		        if (!mysqli_connect_errno($conn)){
							if($dates[0] == $dates[1]){
							    $query = "SELECT * FROM auditlog WHERE timestamp >= '".$dates[0]." 00:00:00'"; 
			                }
							else $query = "SELECT * FROM auditlog WHERE timestamp >= '".$dates[0]."' and timestamp <= '". $dates[1]." 23:59:59'"; 
							$result=mysqli_query($conn, $query);
							$count = mysqli_num_rows($result);	
							
					echo  "<script type='text/javascript'>
					        $(document).ready(function () {
					           $('#pagination').smartpaginator({ 
									totalrecords: ".$count.", 
									recordsperpage: 20, 
									datacontainer: 'tablelist', 
									dataelement: 'tr', 
									initval: 0, 
									next: 'Next', 
									prev: 'Prev', 
									first: 'First', 
									last: 'Last' ,
						            controlsalways: true,
									onchange: function (newPage) {
						                $('#r').html('Page # ' + newPage);
						                 }
					            });

					        });
					    	</script>";

				    echo  '<div id="pagination" class="pagination pagination-small">
			              </div>';
					             
							echo '<table class="table" id="tablelist" style="font-size:14px;">
						        <thead>
								   <tr>
									<th>User ID</th>
									<th>IP Address</th>
									<th>Timestamp</th>
									<th>Access Type</th>
									<th>Affected User</th>
									</tr>
								</thead>';
							echo   '<tbody id="tablebody">';	

								while($row = mysqli_fetch_array($result))
								{
								  echo "<tr>";
								  echo "<td>" . $row['username'] . "</td>";
								  echo "<td>" . $row['ipaddress'] . "</td>";
								  echo "<td>" . $row['timestamp'] . "</td>";
								  echo "<td>" . $row['accesstype'] . "</td>";
								  echo "<td>" . $row['affecteduser'] . "</td>";
								  echo "</tr>";									 
								}

					         echo   '</tbody>';       
					         echo '</table>';
							 mysqli_close($conn);
				}
				else  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	function savelogstofile($dates){
       global $userUid, $conn;
	   
	   date_default_timezone_set('Asia/Manila');
	   $csv_output="";
	   $query="SHOW COLUMNS FROM auditlog";
	   $result = mysqli_query($conn, $query);
	 	$i = 0;
		 if (mysqli_num_rows($result) > 0) {
			 while ($row = mysqli_fetch_assoc($result)) {  
				 $csv_output .= $row['Field'].", ";
				 $i++;
			 }
		 }
		 $csv_output .= "\r\n";  	
         if(gettype($dates)=='array'){ 
 		   if($dates[0] == $dates[1]){
			$query = "SELECT * FROM auditlog WHERE timestamp >= '".$dates[0]." 00:00:00'"; 
			}
			else $query = "SELECT * FROM auditlog WHERE timestamp >= '".$dates[0]."' and timestamp <= '". $dates[1]." 23:59:59'"; 
		 }
		 
		 else $query = "SELECT * FROM auditlog";
        $values = mysqli_query($conn, $query);
		 while ($rowr = mysqli_fetch_row($values)) {
			 for ($j=0;$j<$i;$j++) { 
			 $csv_output .= $rowr[$j].", ";
		 }
		 $csv_output .= "\r\n"; 
		 }
        mysqli_close($conn);
        $filename = "NetIDLogs_".date("d-m-Y_H-i",time()).".csv";
		$fp = fopen("files/logs/".$filename, 'w');
        fwrite($fp, $csv_output);
		fclose($fp); 
		echo "Successfully saved audit logs to file ". $filename. " <br/>"; 
			 
	}

	/**
	*	Searches the uniqueIdentifierUPLB of an entity given his cn
	*
	*	@param String - $cn 	complete name of UPLBEntity
	*							format (Surname, First Name, Middle Initial.)
	*		   
	*/
	function searchUniqueUPLBIdentifier($cn){
		global $ldapconn, $ldapconfig;

		$ress = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph", "cn=".$cn);
        $ents = ldap_get_entries($ldapconn, $ress);
		$identifier =  $ents[0]['uniqueidentifieruplb'][0];
		echo $identifier;

	}
	
	/**
	*	Function to be used when user changes his own security question
	*
	*	@param String - $dn 	distinguished name of an LDAP entity
	* 		   Array - $pwd 	current password 
	*		   String - $mail 	email address of the user
	*		   Array - $sec 	security question, security answer
	*
	*/
	 function changeownsq($dn, $pwd, $mail, $sec){
		global $ldapconn,$conn,$userUid;
	 
			$res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph","uid=".$userUid);
			$entries = ldap_get_entries($ldapconn, $res);
			$rolecount = $entries['count'];
			$uidNum = $entries[0]['uidnumber'][0];

			$bind3 = ldap_bind($ldapconn, "uniqueIdentifierUPLB=".$uidNum.",ou=people,dc=uplb,dc=edu,dc=ph",$pwd['inputPwd']);

			if(!$bind3){ 
				echo "<p class='changesqnotif'>Incorrect Password</p>";
			}
			else{
		            
		            $securityquestion= array("securityquestion" => array($sec['securityquestion']));
		            $res= ldap_modify($ldapconn, $dn, $securityquestion);
		            if($res) echo "Successfully modified security question<br/>";
		            else echo ldap_error($ldapconn);

		            ldap_unbind($bind3);
		            $bind3 = ldap_bind($ldapconn,"cn=admin,dc=uplb,dc=edu,dc=ph","testtesttest");

		            $securityanswer= array("securityanswer" => array($sec['securityanswer']));
		            $res= ldap_modify($ldapconn, $dn, $securityanswer);
		            if($res) echo "Successfully modified security answer";
		            else echo ldap_error($ldapconn);
		             ldap_unbind($bind3);
		    }
		  
		  mysqli_close($conn);
    }

    /**
	*	Function to be used when user changes others security question
	*
	*	@param String - $dn 	distinguished name of an LDAP entity
	* 		   Array - $pwd 	current password 
	*		   String - $mail 	email address of the user
	*		   Array - $sec 	security question, security answer
	*
	*/
	 function changeothersq($dn, $sec){
		global $ldapconn,$conn,$userUid;
	 
			$res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph","uid=".$userUid);
			$entries = ldap_get_entries($ldapconn, $res);
			$rolecount = $entries['count'];
			$uidNum = $entries[0]['uidnumber'][0];
		            
            $securityquestion= array("securityquestion" => array($sec['securityquestion']));
            $res= ldap_modify($ldapconn, $dn, $securityquestion);
            if($res) echo "Successfully modified security question<br/>";
            else echo ldap_error($ldapconn);

            ldap_unbind($bind3);
            $bind3 = ldap_bind($ldapconn,"cn=admin,dc=uplb,dc=edu,dc=ph","testtesttest");

            $securityanswer= array("securityanswer" => array($sec['securityanswer']));
            $res= ldap_modify($ldapconn, $dn, $securityanswer);
            if($res) echo "Successfully modified security answer";
            else echo ldap_error($ldapconn);
             ldap_unbind($bind3);
		  
		  mysqli_close($conn);
    }


    // function is the variable passed by ajax, it will then be the basis of which function to execute
    $function = $_POST['func']; 
	
    switch($function)
	{
	    case 'searchUniqueUPLBIdentifier':
	    			$cn = $_POST['cn'];
	    			searchUniqueUPLBIdentifier($cn);
	    			break;

	    case 'delete' :
	                $dn = $_POST['dn'];
	                $uid = $_POST['uid'];
	                $status = $_POST['status'];
	                $title = $_POST['title'];
		            delete($dn,$uid,$status,$title);
	                break;
					
		case 'editmail':
					$dn = $_POST['dn'];
					$newmail = $_POST['newmail'];
					editmail($dn,$newmail);
					break;
					
		case 'changeownpassword':
					$dn = $_POST['dn'];
					$mail = $_POST['mail'];
					$pwd['userPwd'] = $_POST['userpassword'];
					$pwd['inputPwd'] = $_POST['pwd'];
					$pwd['newPwd'] = $_POST['newPwd'];
					$pwd['conPwd'] = $_POST['conPwd'];
					//encrypt new password
                    $pwd['md5NewPwd'] = encrypt($_POST['newPwd']);
                    changeownpassword($dn, $pwd, $mail);
					break;
        
		case 'changeotherpassword':
					$dn = $_POST['dn'];
					$mail = $_POST['mail'];
					$uid = $_POST['uid']; //uid of user to change password
					$pwd['userPwd'] = $_POST['userpassword'];
					$pwd['newPwd'] = $_POST['newPwd'];
					$pwd['conPwd'] = $_POST['conPwd'];
					//encrypt new password
                    $pwd['md5NewPwd'] = encrypt($_POST['newPwd']);
                    changeotherpassword($dn, $pwd, $uid, $mail);
					break;
					
        case 'selectdegreeprograms':
                    $gidnumber = $_POST['gidnumber'];
					selectdegreeprograms($gidnumber);
					break;		
		
		case 'editselectdegreeprograms':
                    $gidnumber = $_POST['gidnumber'];
					$ou = $_POST['ou'];
					editselectdegreeprograms($gidnumber, $ou);
					break;	
      
        case 'checkstudentnumber':
                    $studentnumber = $_POST['studentnumber'];
                    $uid = $_POST['uid'];
                    $cn = $_POST['cn'];
                    checkstudentnumber($uid,$studentnumber,$cn);
                    break;					
		
		 case 'checkemployeenumber':
                    $employeenumber = $_POST['employeenumber'];
                    $uid = $_POST['uid'];
                    $cn = $_POST['cn'];
                    checkemployeenumber($uid, $employeenumber,$cn);
                    break;	
					
		case 'addentry':
					$info = $_POST['info'];
					$dn = $_POST['dn'];
					addentry($info, $dn);
					break;

		case 'addattr':
					$info = $_POST['info1'];
					$dn = $_POST['dn'];
					$uid = $_POST['uid'];
					addattr($info,$dn,$uid);
					break;

		case 'editentry':
					$info = $_POST['info'];
					$dn = $_POST['dn'];
					editentry($info, $dn);
					break;

        case 'search':
                    $filter = $_POST['filter'];
                    $title = $_POST['title'];
                    $role = $_POST['activerole'];
                    if($title=='student') searchstudent($filter, $role);					
                    else if ($title=='employee') searchemployee($filter, $role);					
					break;
		
		case 'deleterole':
		            $uid =  $_POST['uid'];
		            $delrole =  $_POST['role'];
		            deleterole($uid,$delrole);
					break;
					
		case 'searchuid':
		            $uid =  $_POST['uid'];
		            searchuid($uid);
	                break;
					
        case 'addrole':
		            $uid =  $_POST['uid'];
		            $addrole =  $_POST['role'];
		            addrole($uid, $addrole);
                    break;
	    
		case 'changeactiverole':
		            $activerole =  $_POST['activerole'];
					if($activerole=='student') 
						$_SESSION['title'] = 'student';
		            $_SESSION['activerole'] = $activerole;
					break;
		case 'adddegreeprogram':
		            $info['name'] =  $_POST['name'];
		            $info['title'] =  $_POST['title'];
		            $info['gidnumber'] =  $_POST['gidnumber'];
					adddegreeprogram($info);
					break;	
	    
		case 'deletedegreeprogram':
		            $info['name'] =  $_POST['name'];
		            $info['gidnumber'] =  $_POST['gidnumber'];
					deletedegreeprogram($info);
					break;
        
		case 'viewauditlogs':
		            $dates = $_POST['dates'];
                    viewauditlogs($dates);
                    break;	
		
		case 'savelogstofile':
		            $dates = $_POST['dates'];
                    savelogstofile($dates);
                    break;	

        case 'addalumniattributes':
		            $dn = $_POST['dn'];
		            $info = $_POST['info'];
		            $uid = $_POST['uid'];
                    addattr($info, $dn, $uid);
                    removestudentattr($dn, $uid);
                    break;	
        case 'changeownsq':
        			$dn = $_POST['dn'];
					$mail = $_POST['mail'];
					$pwd['userPwd'] = $_POST['userpassword'];
					$pwd['inputPwd'] = $_POST['pwd'];
					$sec['securityquestion'] = $_POST['securityquestion'];
					$sec['securityanswer'] = encrypt($_POST['securityanswer']);
                    changeownsq($dn, $pwd, $mail, $sec);
					break;

		case 'changeothersq':
        			$dn = $_POST['dn'];
					$sec['securityquestion'] = $_POST['securityquestion'];
					$sec['securityanswer'] = encrypt($_POST['securityanswer']);
                    changeothersq($dn, $sec);
					break;                    			    
	}
	
?>