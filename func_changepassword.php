<?php
  
  include 'ldap_config.php'; 
  include 'frag_sessions.php'; 
   
  $pwd = $_POST['pwd'];
  $newPwd = $_POST['newPwd'];
  $conPwd = $_POST['conPwd'];
   
  $md5Pwd = "{MD5}".preg_replace('/=+$/','',base64_encode(pack('H*',md5($pwd))))."=="; 
  $md5NewPwd = "{MD5}".preg_replace('/=+$/','',base64_encode(pack('H*',md5($newPwd))))."=="; 
  
  
  $sr = ldap_search($ldapconn, "ou=".$title.",ou=people,".$ldapconfig['basedn'],  "(uid=" . $userUid . ")", array('userpassword'));
  $entries = ldap_get_entries($ldapconn, $sr);
  
  if($entries[0]['userpassword'][0] == $md5Pwd){
	if($newPwd == $conPwd){
   	  $dn = $entries[0]['dn'];
	  $changePwd = array("userpassword" => array($md5NewPwd));
	  $result = ldap_modify($ldapconn, $dn, $changePwd);
	  
	  //SEND EMAIL
	echo "<p class='changepasswordnotif'>Password successfully change. <br/> A mail is sent to your email address.</p>";
	}
    else echo "<p class='changepasswordnotif'>New Password and Confirm Password inputs are not the same.</p>";	
  }
  else  echo "<p class='changepasswordnotif'>Incorrect Password</p>"; 
     
 
 ?>