<?php
    
   // ini_set('display_errors','Off'); 
    //Connect and bind to ldap server
	$ldapconfig['host'] = '10.0.100.201';
	$ldapconfig['port'] = 389;
	$ldapconfig['basedn'] = 'dc=uplb,dc=edu,dc=ph';
	$username = 'admin';
	$password = 'testtesttest';
	//$dn= "cn=".$username $ldapconfig['basedn'];
	$dn = "cn=admin,dc=uplb,dc=edu,dc=ph";

	/*Connect to ldap server 10.0.100.58*/
	$ldapconn=ldap_connect($ldapconfig['host'], $ldapconfig['port']);     
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);

	if($ldapconn){ 
	       /*Bind to the ldap active directory*/
	   $bind=ldap_bind($ldapconn, $dn, $password);
	   if (!$bind) {   
	      echo("Unable to bind to server.</br>");
	   }
	}   
	else {  
	   echo "Unable to Connect..</br>";
    } 
	
?>