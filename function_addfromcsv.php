<?php

	require 'tools/PHPMailer_5.2.4/class.phpmailer.php';
   	require 'ldap_config.php'; 
	require 'frag_sessions.php';
	 ini_set('max_execution_time', 300);
	
	 //User with STUDENT or EMPLOYEE as role cannot view this page
	if(!($activerole=='OUR' || $activerole=='ADMIN' || $activerole=='HRDO'))
	   redirect("home.php");


	   
	 $titletoadd = $_POST["title"];
 
        $from = "itcspmanegr@uplb.edu.ph";
		$mail = new PHPMailer;


		$mail->IsSMTP();  // telling the class to use SMTP
		$mail->Host     = "202.92.144.18"; // SMTP server
		$mail->SMTPAuth - true;
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

     function checkstudentnumber($studentnumber){
	  global $ldapconn, $ldapconfig;
	  $sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(studentnumber=".$studentnumber.")",  array("uid"));
	  if(ldap_count_entries($ldapconn, $sr) > 0){
	     $entry = ldap_get_entries($ldapconn, $sr);
		 return $entry[0]['uid'][0]; 
	  }
      else return NULL;	 
	}
	
	function checkemployeenumber($employeenumber){
	  global $ldapconn, $ldapconfig;
	  $sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(employeenumber=".$employeenumber.")",  array("uid"));
	  if(ldap_count_entries($ldapconn, $sr) > 0){
	     $entry = ldap_get_entries($ldapconn, $sr);
		 return $entry[0]['uid'][0]; 
	  }
      else return NULL;	 
   }
	
  function csvaddentry($info,$dn, $title){
        global $ldapconn, $titletoadd;
		$activerole = $_SESSION['activerole'];
  
        
        
		$search = ldap_search($ldapconn, 'dc=intranet,dc=uplb,dc=edu,dc=ph', "(ou=uidnumholder)");
        $entry = ldap_get_entries($ldapconn, $search);
        $uidnumhorderdn = $entry[0]['dn'];	
        $uidnumholder = $entry[0]['telexnumber'][0];
	
	    $info["uidnumber"] = $uidnumholder;
	    $info["objectclass"][0] = "inetOrgPerson";
	    $info["objectclass"][1] = "posixAccount";
	    $info["objectclass"][2] = "top";
	    $info["objectclass"][3] = "shadowAccount";
        $userpassword =  $info['userpassword'];
	    $info['userpassword'] = "{MD5}".preg_replace('/=+$/','',base64_encode(pack('H*',md5($info['userpassword']))))."==";
        if($info['title'] != $titletoadd ) return false;
		
		if($info['title'] == 'student'){
		  $checkstudnum = checkstudentnumber($info['studentnumber']);
		  if($checkstudnum!= NULL)
		      {
			      echo "<tr><td>".$info['cn']."</td>";  
				  echo "<td colspan='4'><a href='viewprofile.php?title=".$title."&uid=".$checkstudnum."'></b>".$info['studentnumber']."</b></a> already exists.</td></tr>"; 
                  return false;													
			  }
		}	  
		else{
		  $checkempnum = checkemployeenumber($info['employeenumber']);
		  if($checkempnum!= NULL)
		      {
			    echo "<tr><td><b>".$info['cn']."</b></td>";  
				  echo "<td colspan='4'><a href='viewprofile.php?title=".$title."&uid=".$checkempnum."'></b>".$info['employeenumber']."</b></a> already exists.</td></tr>"; 
                  return false;	
			  }
		}
		
		$add = ldap_add($ldapconn, $dn, $info);
        
       	//increase uidnumberholder
        $dnuidnumberholder = 'ou=uidnumholder,ou=numberholder,dc=intranet,dc=uplb,dc=edu,dc=ph';		
	    $newuidnumholder = array("telexnumber" => array($uidnumholder+ 1));
        if($add)	   
			$moduid = ldap_modify($ldapconn, $dnuidnumberholder, $newuidnumholder);
    
	    if($add && $moduid) {
		     //SEND EMAIL
			if($info['mail'] != NULL){
						$subject = "UPLB LDAP Account created";
						$data = "<p>Hi ".$info['mail']."<br/><br/>".
						            "You're UPLB NetID account has been created.". "<br/>".
							        "Your username is <b>". $info['uid'] ."</b> and your password is <b>".$userpassword. "</b>"."<br/>". 
									"NetID account is used by various UPLB network services, like the UPLB Wifi, for user authentication."."<br/>".
									"Visit \<NetID web page\> and change your password right away.<br/><br/>".
									"Sincerely,"."<br/>".
									"UPLB NetID Account team". "<br/></p>";
						
						$sent = sendmail($subject ,$data, $info['mail']);	
			}
			
			 echo "<tr>";
				echo 	"<td><a  style='color:#333333' href='viewprofile.php?title=".$title."&uid=".$info['uid']."'>";  
				echo           $info['cn']."</a></td>";														  // OUR, HRDO, and ADMIN users will be able to 
				if($titletoadd=='student')  echo 	"<td>".$info['studentnumber']."</td>";
				else 				        echo 	"<td>".$info['employeenumber']."</td>";	
											echo 	"<td>".$info['ou']."</td>";
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
								if($titletoadd == 'student'){
									$info['uid'] = $data[0];
									$info['cn'] = $data[1];
									$info['sn'] = $data[2];
									$info['givenname'] = $data[3];
									$info['mail'] = $data[4];
									$info['studentnumber'] = $data[5];
									$info['studenttype'] = $data[6];
									$info['gidnumber'] = $data[7];
									$info['ou'] = $data[8];
									$info['homedirectory'] = $data[9];
									$info['title'] = $data[10];
									$info['shadowmax'] = $data[11];
									$info['shadowwarning'] = $data[12];
									$info['loginshell'] = $data[13];
							        $info['userpassword'] = generatepassword();
									$dn = "uid=".$info['uid'].",ou=student,ou=people,dc=intranet,dc=uplb,dc=edu,dc=ph";
								}
								else{
									$info['uid'] = $data[0];
									$info['cn'] = $data[1];
									$info['sn'] = $data[2];
									$info['givenname'] = $data[3];
									$info['mail'] = $data[4];
									$info['employeenumber'] = $data[5];
									$info['employeetype'] = $data[6];
									$info['gidnumber'] = $data[7];
									$info['ou'] = $data[8];
									$info['homedirectory'] = $data[9];
									$info['title'] = $data[10];
									$info['shadowmax'] = $data[11];
									$info['shadowwarning'] = $data[12];
									$info['loginshell'] = $data[13];
							        $info['userpassword'] = generatepassword();
									$dn = "uid=".$info['uid'].",ou=employee,ou=people,dc=intranet,dc=uplb,dc=edu,dc=ph";
								}
							    $add = csvaddentry($info,$dn,$titletoadd);
								if($add) $count++;							
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

  

	 

