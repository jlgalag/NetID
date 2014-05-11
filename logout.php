<?php
// logout 
   session_start();
   session_destroy();
   ldap_close($ldapconn);
   header('Location:index.php');
?>