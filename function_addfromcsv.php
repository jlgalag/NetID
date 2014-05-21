<?php

	require 'tools/PHPMailer_5.2.4/class.phpmailer.php';
   	require 'ldap_config.php'; 
	require 'frag_sessions.php';
	 ini_set('max_execution_time', 300);
	
	$search = ldap_search($ldapconn, "ou=numberholder,".$ldapconfig['basedn'], "cn=uidlatestnumber");
    $entry = ldap_get_entries($ldapconn, $search);
    $uidnumholderdn = $entry[0]['dn'];	
    $uidnumholder = $entry[0]['serialnumber'][0]+1;

	 //User with STUDENT or EMPLOYEE as role cannot view this page
	if(!($activerole=='OUR' || $activerole=='ADMIN' || $activerole=='HRDO'))
	   redirect("home.php");


	   
	 $titletoadd = $_POST["title"];
 
        $from = "itcspmanegr@uplb.edu.ph";
		$mail = new PHPMailer;


		$mail->IsSMTP();  // telling the class to use SMTP
		$mail->Host     = "202.92.144.113"; // SMTP server
		$mail->SMTPAuth = true;
		$mail->SMTPDebug = 1;
		$mail->Port = 25;
		$mail->Username="itcspmanager";
		$mail->Password="1tc5pm4n4g3r";
        $mail->IsHTML(true); 
		

		$mail->From     = $from;
		$mail->FromName = "UPLB NETID";
		
    function sendmail($subject, $data, $to){
		global $mail;
		
		$mail->AddAddress($to);

		$mail->Subject  = $subject;
		$mail->Body     = $data;
		$mail->WordWrap = 100;

		if(!$mail->Send()) {
		  return false;
		} else {
		  return true;
		}
	}
	 
// generate random password when adding an entry (student/employee)
function generatepassword() {
	$length = 10;
	$validchars="abcdefghijklmnopqrstuvwxyz";
	$password = "";
	 for ($i = 0; $i < $length ; $i++) {
        $n = rand(0, strlen($validchars)-1);
        $password .= $validchars[$n];
    }
	return($password);


}	
	function addattributes($info2,$cn,$username){
		global $ldapconn, $ldapconfig;
		$search = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(|(cn=".$cn.")(uid=".$username."))");
	    $entry = ldap_get_entries($ldapconn, $search);
	    $uidnumholder2 = $entry[0]['uidnumber'][0]; 
	    $dry = ldap_mod_add($ldapconn, "uniqueIdentifierUPLB=".$uidnumholder2.",ou=people,dc=uplb,dc=edu,dc=ph", $info2);
	    return($dry);
	}


     function checkstudentnumber($studentnumber,$username,$cn){
	  global $ldapconn, $ldapconfig;
	  /*$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(studentNumber=".$studentnumber.")",  array("uid"));
	  if(ldap_count_entries($ldapconn, $sr) > 0){
	     $entry = ldap_get_entries($ldapconn, $sr);
		 return $entry[0]['uid'][0]; 
	  }
      else return NULL;	
      */
      	$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=student)(|(uid=".$username.")(studentnumber=".$studentnumber.")))", array("uid"));
	  	$count = ldap_count_entries($ldapconn, $sr);

		$sr2 = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=employee)(|(uid=".$username.")(cn=".$cn.")))", array("uid"));
	  	$count2 = ldap_count_entries($ldapconn, $sr2);

	  	$sr3 = ldap_search($ldapconn, "ou=disabledaccounts,".$ldapconfig['basedn'], "(&(title=student)(|(uid=".$username.")(cn=".$cn.")))", array("uid"));
	  	$count3 = ldap_count_entries($ldapconn, $sr3);

	  	if(ldap_count_entries($ldapconn, $sr) > 0){
	    	$entry = ldap_get_entries($ldapconn, $sr);
		 	return $entry[0]['uid'][0]; 
	  	}else   if(ldap_count_entries($ldapconn, $sr2) > 0){
		 	return "ADDattr"; 
	  	}else if(ldap_count_entries($ldapconn, $sr3) > 0){
		 	return "AcctDisabled"; 
	  	}else return "ADDentry";

	}
	
	function checkemployeenumber($employeenumber,$username,$cn){
	  global $ldapconn, $ldapconfig;
	  
	    $sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=employee)(|(uid=".$username.")(employeenumber=".$employeenumber.")))", array("uid"));
	  	$count = ldap_count_entries($ldapconn, $sr);

		$sr2 = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(title=student)(|(uid=".$username.")(cn=".$cn.")))", array("uid"));
	  	$count2 = ldap_count_entries($ldapconn, $sr2);

	  	$sr3 = ldap_search($ldapconn, "ou=disabledaccounts,".$ldapconfig['basedn'], "(&(title=employee)(|(uid=".$username.")(cn=".$cn.")))", array("uid"));
	  	$count3 = ldap_count_entries($ldapconn, $sr3);

	  	if(ldap_count_entries($ldapconn, $sr) > 0){
	    	$entry = ldap_get_entries($ldapconn, $sr);
		 	return $entry[0]['uid'][0]; 
	  	}else if(ldap_count_entries($ldapconn, $sr2) > 0){
		 	return "ADDattr"; 
	  	}else if(ldap_count_entries($ldapconn, $sr3) > 0){
		 	return "AcctDisabled"; 
	  	}else return "ADDentry";
   }
	

	function encrypt($string){

		$salt = sha1(rand());
		$salt = substr($salt, 0, 4);

		//return encrpted value
		return $encrypted = "{SSHA}".base64_encode( sha1( $string . $salt, true) . $salt );
	}

  function csvaddentry($info,$dn, $title, $uidnumholder, $uidnumholderdn){

        global $ldapconn, $titletoadd;
		$activerole = $_SESSION['activerole'];
	
	    $info["uidnumber"] =  $uidnumholder;
	    $info["objectclass"][0] = "UPLBEntity";

	    if($title == "student")
	    	$info["objectclass"][1] = "UPLBStudent";
	    else if($title == "employee")
	    	$info["objectclass"][1] = "UPLBEmployee";

	    $info['securityquestion'] = encrypt($info['securityquestion']);
	    $info['securityanswer'] = encrypt($info['securityanswer']);
		$info['userpassword'] = encrypt($info['userpassword']);

        if($info['title'] != $titletoadd ) return false;

		if($info['title'] == 'student'){
		  	$checkstudnum = checkstudentnumber($info['studentnumber'],$info['uid'],$info['cn']);
			//entry is already present with student attributes
			if($checkstudnum != "ADDattr" && $checkstudnum != "ADDentry" && $checkstudnum != "AcctDisabled")
			{
				echo "<tr><td>".$info['cn']."</td>";  
				echo "<td colspan='4'><a href='viewprofile.php?title=student&uid=".$checkstudnum."'></b>".$info['studentnumber']."</b></a> already exists.</td></tr>"; 
	            return false;													
			}
			//entry is already present but certain attributes should be added
			else if ($checkstudnum == "ADDattr") {
				$info2['studentnumber'] = $info['studentnumber'];
				$info2['objectclass'] = "UPLBStudent";
				$info2['studenttype'] = $info['studenttype'];
				$info2['college'] = $info['college'];
				$info2['course'] = $info['course'];
				$info2['activestudent'] = "TRUE";
				$info2['title'] = "student";
 				$addr = addattributes($info2,$info['cn'],$info['uid']);
			}
			//entry is disabled
			else if($checkstudnum == "AcctDisabled"){
				echo "<tr><td>".$info['cn']."</td>";  
				echo "<td colspan='4'><a href='viewprofile_dis.php?title=student&uid=".$info['uid']."'></b>".$info['studentnumber']."</b></a> is inactive.</td></tr>"; 
	            return false;	
			}
			//entry should be added
			else$add = ldap_add($ldapconn, $dn, $info);
		}else{
		  	$checkempnum = checkemployeenumber($info['employeenumber'],$info['uid'],$info['cn']);
		  	//entry is already present with employee attributes
		  	if($checkempnum!= "ADDattr" && $checkempnum != "ADDentry" && $checkempnum != "AcctDisabled")
		    {
			    echo "<tr><td><b>".$info['cn']."</b></td>";  
				echo "<td colspan='4'><a href='viewprofile.php?title=employee&uid=".$checkempnum."'></b>".$info['employeenumber']."</b></a> already exists.</td></tr>"; 
                return false;	
			}
			//entry is already present but certain attributes should be added
			else if ($checkempnum == "ADDattr") {
				$info2['employeenumber'] = $info['employeenumber'];
				$info2['employeetype'] = $info['employeetype'];
				$info2['objectclass'] = "UPLBEmployee";
				$info2['o'] = $info['o'];
				$info2['ou'] = $info['ou'];
				$info2['activeemployee'] = "TRUE";
				$info2['title'] = "employee";
 				$addr = addattributes($info2,$info['cn'],$info['uid']);
			}
			//entry is disabled
			else if($checkempnum == "AcctDisabled"){
				echo "<tr><td>".$info['cn']."</td>";  
				echo "<td colspan='4'><a href='viewprofile_dis.php?title=employee&uid=".$info['uid']."'></b>".$info['studentnumber']."</b></a> is inactive.</td></tr>"; 
	            return false;	
			}
			//add entry
			else $add = ldap_add($ldapconn, $dn, $info);
		}
        
       	//increase uidnumberholder
        if($add){
       		//modify uidLatestNumber
	        $dnuidnumberholder = 'cn=uidLatestNumber,ou=numberholder,dc=uplb,dc=edu,dc=ph';		
		    $newuidnumholder = array("serialnumber" => array($info['uidnumber']));
		    $moduid = ldap_modify($ldapconn, $dnuidnumberholder, $newuidnumholder);
        }

	    if(($add && $moduid) || $addr) {

		     //SEND EMAIL
			
			 echo "<tr>";
				echo 	"<td><a  style='color:#333333' href='viewprofile.php?title=".$title."&uid=".$info['uid']."'>";  
				echo           $info['cn']."</a></td>";														  // OUR, HRDO, and ADMIN users will be able to 
				if($titletoadd=='student') {
					echo 	"<td>".$info['studentnumber']."</td>";
					echo 	"<td>".$info['college']."</td>";
				}else{
					echo 	"<td>".$info['employeenumber']."</td>";	
					echo 	"<td>".$info['ou']."</td>";
				}
										    echo 	"<td>".$info['mail']."</td>";
				if($sent)                   echo "<td><i class='icon-ok'></td>";
				else                        echo "<td><i class='icon-remove'></td>";
			echo "</tr>";
			
			return true;
		}
	    else   {  echo "<tr><td>".$info['cn']."</td>";  
				  echo "<td colspan='4'><a href='viewprofile.php?title=".$title."&uid=".$info['uid']."'><b>". $info['uid']."</b></a> ".strtolower(ldap_error($ldapconn))."</td></tr>"; 
	             return false; }
	   
	 }	





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

	
	<?php  include 'frag_header.php'; 
			
	?>
	
	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php if($titletoadd=='student') $active=3;
						      else $active=5;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->
            
               <div class="span9">
			    <div class="hero-unit">
				     <h1> Add <?php echo $titletoadd?> </h1>
					 <img src="tools/img/horredline.jpg"/>
				
					
					<?php
					  if ($_FILES["file"]["error"] > 0)
					  {
					  echo "<h1>Error: " . $_FILES["file"]["error"] . "</h1><br>";
					  }
					  else if($_FILES["file"]["type"] != "application/vnd.ms-excel"){
					  echo "<h4>File type must be <b>'application/vnd.ms-excel'</b><br/></h4>";
					  }
					
					  else
					  {
					      $count=0;
						  move_uploaded_file ($_FILES['file']['tmp_name'], 
						       "files/csvuploads/{$_FILES['file'] ['name']}");
						 // echo "role: ".$role."<br/>";
						  echo "<br>Uploaded: " . $_FILES["file"]["name"] . "<br>";
						  //echo "Type: " . $_FILES["file"]["type"] . "<br>";
						  //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
						  //echo "Stored in: " . $_FILES["file"]["tmp_name"];
						 echo '<table class="table" id="tablelist" style="font-size:14px;">
										
											<tr>
								                 <th>Name</th>';
						if ($titletoadd=='student') echo
                        						'<th>Student Number</th>
												 <th>Course</th>';
						else echo				'<th>Employee Number</th>
												 <th>Office/Dept</th>';		 
						echo		            '<th>Mail</th>
						                        <th>Sent Mail</th>
								            </tr>';
						echo "<tr></tr>";		
						$row = 1;
						if (($handle = fopen('files/csvuploads/'.$_FILES["file"]["name"], "r")) !== FALSE) {
						    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						        $num = count($data);
						        //echo "<p> $num fields in line $row: <br /></p>\n";
						        $row++;
						        for ($c=0; $c < $num; $c++) {
						            //echo $data[$c] . "<br />\n";
						        }
						        	$info['uniqueIdentifierUPLB'] = $uidnumholder;
									$info['uid'] = $data[0];
									$info['cn'] = $data[1];
									$info['sn'] = $data[2];
									$info['givenname'] = $data[3];
									$info['mail'] = $data[4];
									$info['gidnumber'] = $data[7];
									$info['homedirectory'] = "/home/".$uid;
									$info['loginshell'] = "/bin/bash";
									$info['title'] = $data[10];
									$info['userpassword'] = generatepassword();
							        $info['securityquestion'] = generatepassword();
							        $info['securityanswer'] = generatepassword();
							       	$info['displayname'] = $data[1];
							       	$info['personaltitle'] = "Mr";
 									$dn = "uniqueIdentifierUPLB=".$uidnumholder.",ou=people,dc=uplb,dc=edu,dc=ph";
 									
 									//gets college or office from database depending in gidnumber
 									$conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
											$query="SELECT name FROM college offices where gidnumber=".$data[7];
											$college=mysqli_query($conn, $query);
											$row = mysqli_fetch_array($college);
											$CO = $row['name'];
										}
										else echo "<option>Cannot connect to the database</option>";
									mysqli_close($conn);
								//student attributes
							    if($titletoadd == 'student'){
									$info['studentnumber'] = $data[5];
									$info['studenttype'] = $data[6];
									$info['course'] = $data[8];									
									$info['college'] =  $CO;
									$info['activestudent'] = "TRUE";    
								}
								//employee attributes
								else{
									$info['employeenumber'] = $data[5];
									$info['employeetype'] = $data[6];
									$info['o'] = $CO;
									$info['ou'] = $data[8];
							        $info['activeemployee'] = "TRUE";
								}
							    $add = csvaddentry($info,$dn,$titletoadd, $uidnumholder, $uidnumholderdn);
								if($add){
									$count++;							
									$uidnumholder++;
								}
						}
					    fclose($handle);
						
						echo "</table>";
                      
						echo "<b>".$count."</b> records added.<br/>";
					
					}
					//header('location:function_addfromcsv.php');
					}
				?>
				
					     <!-- where the pagination will be showed-->
					<div id="pagination" class="pagination pagination-small">
                    </div>	
                    
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div>
    </div>	

  <!-- script for pagination-->
    <script type="text/javascript">
        $(document).ready(function () {
           $('#pagination').smartpaginator({ 
				totalrecords: <?php echo $row;?>, 
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
    </script>  
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

  

	 

