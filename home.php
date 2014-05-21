<?php
    //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	// search for the logged in user in the directory
	$sr=ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "uid=".$userUid);
	$entries = ldap_get_entries($ldapconn, $sr);
	
	//get college name base on gidnumber (ex. 1006 - CA)  
	if($_SESSION['gidnumber']==""){      
	    $csr = ldap_search($ldapconn, "ou=posixGroups,".$ldapconfig['basedn'], "(gidnumber=".$entries[0]["gidnumber"][0].")", array('cn'));			  
		$entry = ldap_first_entry($ldapconn, $csr);
		$ou = ldap_get_values($ldapconn, $entry,'cn');
		$group= $ou[0]; 
		$_SESSION['gidnumber'] = $entries[0]["gidnumber"][0];
		$_SESSION['group'] = $group;
	}
?>

<!DOCTYPE html>
<html>
<head>
   <?php
     $pagetitle = "NetID :: Home";
	 include 'header.php';
   ?>
   
</head>


<body>
    <?php  include 'frag_header.php';?>
	
	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php // navigation bar
						      $active=1;
 						      include 'frag_menu.php'  
					    ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">	
			<?php 
			   $userRole = $_SESSION['activerole'];
			   $title=$_SESSION['title'] ;
			   if($userRole=='student')
			     include 'frag_profilestudent.php';
			 	else if($userRole=='alumni')
			 	 include 'frag_profilealumni.php';
			   else
			     include 'frag_profileemployee.php';
			?>
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div>
    </div>	
		 
<?php include 'frag_footer.php'?>