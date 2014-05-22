<!DOCTYPE html>
<?php

//connect to lda server and mysql
include 'ldap_config.php'; 
//$conn = mysqli_connect('localhost','root','','netid');

// Check connection
if (mysqli_connect_errno($conn))
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

session_start();
// if user alreadylogged in, redirect to home page
if(isset($_SESSION['userUid']))
 { 
     header("Location:home.php");
 }



?>

<html>
<head>
 <?php 
	   $pagetitle = "NetID :: Log in";    // title
	   include 'header.php';
 ?>
<!-- Style for login page-->	
<link href="tools/style/login.css" rel="stylesheet" type="text/css" />
	<style type="text/css">

		 body
		{
		 padding-right: 40px;
		 padding-left: 40px;
		}	 

		.navbar
		{
		 margin-bottom: 20px;
		 margin-top: 100px;
		}

		#login
		{
		margin: auto;
		width: 1200px;
		}

		#loginbox
		{
		 float: right;
		}
		
		.form-signin 
		{
		width: 300px;
		height: 250px;
		padding: 19px 29px 29px;
		margin: 30px 100px 30px 10px;
		background-color: #fff;
		border: 1px solid #e5e5e5;
		-webkit-border-radius: 5px;
		   -moz-border-radius: 5px;
				border-radius: 5px;
		-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
		   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
				box-shadow: 0 1px 2px rgba(0,0,0,.05);
		}

		.form-signin .form-signin-heading,
		.form-signin .checkbox
		{
		margin-bottom: 10px;
		}

		.form-signin input[type="text"],
		.form-signin input[type="password"],
		.form-signin select
		{
		font-size: 16px;
		height: auto;
		margin-bottom: 15px;
		padding: 7px 9px;
		} 

	</style>

</head>

<body>

 <div class="navbar navbar-static-top">
   <div class="navbar-inner">
        <div class="nav-collapse collapse">
            <div id="login">
			   <img class="loginlogo" src="tools/img/loginheader.png"/> 
		
				<div id="loginbox">
				    <!--  login form -->
				    <form class="form-signin" name="form_login" method="post" action="index.php">
					    <h3 class="form-signin-heading">Please sign in</h3>
  
                     <span style="color:#ff0000">
					 <?php 
					     if(isset($_POST['loginbutton']))
							{
							  $userUid = $_POST['userUid'];
							  $userPassword = $_POST['userPassword'];

							  session_destroy();
							  include 'login.php';   // log in process
						    } 
				
                    ?> 
                    </span>
 					    <input name="userUid" type="text" class="input-block-level" required="required" placeholder="User ID">
				        <input name="userPassword" type="password" class="input-block-level" required="required" placeholder="Password">
				        <?php
                    	if(isset($_GET['auth']) && $_GET['auth'] == 'false') {
						  echo("<h5>Incorrect Username or Password</h5>"); 
						}
						?>
				        <input class="btn btn-large btn-primary" name="loginbutton" type="submit" value="Sign in"/>
					</form>	
				</div>
			</div>	
		</div><!--/.nav-collapse -->
		
    </div>
</div> <!-- navbar -->

</body>

</html>
