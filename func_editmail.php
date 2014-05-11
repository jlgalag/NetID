<?php

    include 'ldap_config.php'; 
 	include 'frag_sessions.php';
  $newMail = $_POST['newMail'];
  
  $sr = ldap_search($ldapconn, "ou=".$title.",ou=people,".$ldapconfig['basedn'],  "(uid=" . $userUid . ")", array('mail'));
  $entries = ldap_get_entries($ldapconn, $sr);
  $dn = $entries[0]['dn'];
  $editMail = array("mail" => array($newMail));
  $result = ldap_modify($ldapconn, $dn, $editMail);
  echo $newMail;
    
 ?>