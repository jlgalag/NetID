<?php
//Connect and bind to ldap server
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
session_start();

$userUid = $_SESSION['userUid'];
$title = $_SESSION['title']; 	
$role = $_SESSION['role'];
$activerole = $_SESSION['activerole'];
$group = $_SESSION['group'];
$gidnumber = $_SESSION['gidnumber'];
$date = $_SESSION['date'];

// if not logged in
if($userUid=="") {
	redirect('index.php');
}
// don't show php error
ini_set('display_errors','Off');    
?>
