<?php
       //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
   	//global $conn;
    //Only User with ADMIN as role cannot view this page
	if(!($activerole=='ADMIN'))
	   redirect("home.php");
	
	
?> 

<!DOCTYPE html>
<html>
<head>
   <?php
      $pagetitle = "NetID :: Add Degree Program";
	 include 'header.php';
   ?>

</head>
<body>

	
	<?php  include 'frag_header.php';  ?>
	
	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php $active=12;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->
            
               <div class="span9">
			    <div class="hero-unit">
				     <h1>  Add Degree Program </h1>
					 <img src="tools/img/horredline.jpg"/>
					 
					
					<div id="addform">
						
						<hr>
						<h4>Degree Program</h4>
						<form class="form-inline" id="formadddegreeprogram" name="formadddegreeprogram" method="POST" action="javascript:adddegreeprogram()">
							  
							       <select name="gidnumber" id="gidnumber" class="input-large">
									
									 <?php
									      //$conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											echo "<option value='' disabled selected style='display:none;'>Select College</option>";
											 
											while($row = mysqli_fetch_array($college)){
											    echo    '<option value="'.$row['gidnumber'].'">'.$row['name'].'</option>';
											}	
										}
										else echo "<option>Cannot connect to the database</option>";
										//mysqli_close($conn);
									 ?>
						</select>
							  
							      <input class="input-large" name="name" type="text" id="inputdegreeprogramname" placeholder="Abbreviation of Degree Program">
							  
							      <input class="input-large" name="title" type="text" id="inputdegreeprogramtitle" placeholder="Full Name of Degree Program">
							    
							 
								  <button class="btn btn-primary" type="submit" name="addbutton">Save <i class="icon-ok icon-white"></i> </button>
								  
							
						</form>
						
						<h4>Delete</h4>
						<form class="form-inline" id="formdeldegreeprogram" name="formadeldegreeprogram" method="POST" action="javascript:deletedegreeprogram()">
						 <select name="gidnumber" id="inputGidnumber" class="input-large pull-left" onchange="onCollegeChange()" >
									
									 <?php
									    //$conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											echo "<option value='' disabled selected style='display:none;'>Select College</option>";
											 
											while($row = mysqli_fetch_array($college)){
											    echo    '<option value="'.$row['gidnumber'].'">'.$row['name'].'</option>';
											}	
										}
										else echo "<option>Cannot connect to the database</option>";
										mysqli_close($conn);
									 ?>
						</select>
						<div id="selectcourse" class="pull-left">
						</div>
						
						<button class="btn btn-primary" type="submit" name="deletebutton">Delete <i class="icon-trash icon-white"></i> </button>
						
						
					</div>
                    
					

           
					
				
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div>
    </div>	


<?php include 'frag_footer.php'?>
  

	 