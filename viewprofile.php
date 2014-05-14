<?php
    //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//User with STUDENT , EMPLOYEE or OCS as role cannot view this page
	if($activerole=='student' || $activerole=='employee' || $activerole=="OCS")
	   redirect("home.php");
	   
	$uid=$_GET['uid'];
	$title=$_GET['title'];		
    
	// search for the student/employee given the uid and title
	$sr=ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(uid=".$uid.")(title=".$title."))");
	$entries = ldap_get_entries($ldapconn, $sr);
	$dn = $entries[0]['dn'];  // to be used for deletion or removing the user from the directory
	if (($entries[0]['activeemployee'][0] == 'TRUE' && $title == 'employee') || ($entries[0]['activestudent'][0] == 'TRUE' && $title == 'student')) {
		$status = "disable";
	}else $status = "activate";

	//search for the corresponding group of the gid number ex. 1000 - OC
	$csr = ldap_search($ldapconn, "ou=posixGroups,".$ldapconfig['basedn'], "(gidnumber=".$entries[0]["gidnumber"][0].")", array('cn'));			  
	$entry = ldap_first_entry($ldapconn, $csr);
	$ou = ldap_get_values($ldapconn, $entry,'cn');
	$group= $ou[0];  
?>

<!DOCTYPE html>
<html>
<head>
   <?php
     $pagetitle = "NetID :: View Profile";
	 include 'header.php';
   ?>
   
<script type="text/javascript">
    // script for showing confirmation dialog box if the user wants to disable/activate a user account
	$(function () {
	    //show the confirm dialog box
		$("#confirmButton").click(function () {
           	bootbox.confirm("Are you sure you want to <?php echo $status." "."<strong>".$uid."</strong>";?>'s<?php echo " "."<strong>".$title."</strong>";?> account?",
		    function(result) {
			   	var dn = "<?php echo $dn?>";
			   	var uid = "<?php echo $uid?>";
			   	var title = "<?php echo $title?>";
				var status = "<?php echo $status?>";
			   	if(result){    			   // the actual deletion of an entry
			        $.ajax({
					    type: "POST",
						url: 'functions.php',   // calls delete() function from functions.php
					    //encodeURIComponent is to keep the '+' from changing to ' '.
						data: 
						{   dn: dn,
						    uid: uid,
						    status: status,
						    title: title,
						    func: 'delete' },
					    success: function(data){
							bootbox.alert(data, function(){history.back()});
							
						}
				    }); 
				
				}
			  });
        }); 

    });
</script>
</head>

<body>
	<?php  include 'frag_header.php';  ?>
	
	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php $active=0;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				     <h1 class="pull-left"> <?php echo $uid?> </h1>
					 <!-- Edit and Delete linkk on the profile-->
					 <div class="pull-left" style="margin-left:300px;">
					     <button class="btn btn-link btn-large" id="editButton" data-toggle="modal" data-target="#editModal"><i class="icon-edit"></i>Edit</button>
						<?php
							if(($title=='student' && $entries[0]['activestudent'][0]=='TRUE') || ($title=='employee' && $entries[0]['activeemployee'][0]=='TRUE'))
						 		echo "<button class='btn btn-link btn-large' id='confirmButton'><i class='icon-remove'></i>Disable</button>";
						 else 
						 		echo "<button class='btn btn-link btn-large' id='confirmButton'><i class='icon-ok'></i>Activate</button>";
				     	?>
				     </div>
				   <?php
						   if($title=='student')
						     include('frag_viewprofilestudent.php');
						   else if ($title=='employee')
						     include('frag_viewprofileemployee.php');	 
			       ?>

                
				
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div>
    </div>	

<?php include 'frag_footer.php'?>
  

	 