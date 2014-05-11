
<!--Connect and bind to ldap server-->
<?php
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//Only Users with ADMIN as role can view this page
	if(!($activerole=='ADMIN'))
	   redirect("home.php");		        
	
	
?>


<!DOCTYPE>
<html>
<head>
    <?php
     $pagetitle = "NetID :: Demographics Graph";
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
					    <?php $active=11;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				     <h1 class="pull-left"> Demographics Graph </h1>
					 <img src="tools/img/horredline.jpg"/>
                     
					 
					 <ul class="nav nav-tabs">
									<li class="active"><a href="#tabs1-pane1" data-toggle="tab">Student</a></li>
									<li><a href="#tabs1-pane2" data-toggle="tab">Employee</a></li>
								</ul>
								<div class="tab-content">
								    <!-- Tab for students -->
									<div class="tab-pane active" id="tabs1-pane1">
										 <ul class="nav nav-pills" >
										    <li>View:</li>
											<li><a onclick="generateAllStudentsTable()">All</a></li>
					                        <li class="dropdown">
												<a class="dropdown-toggle" data-toggle="dropdown" href="#">Per College</a>
												    <ul class="dropdown-menu">
												       <?php
													   // show dropdown of all colleges
													   $conn = mysqli_connect('localhost','root','','netid');
														    // Check connection
															if (!mysqli_connect_errno($conn)){
																$query="SELECT * FROM college ORDER BY gidnumber";
																$college=mysqli_query($conn, $query);
																while($row = mysqli_fetch_array($college)){
																     echo '<li><a tabindex="-1" onclick="generateCollegeTable('.$row['gidnumber'].',\''.$row['name'].'\')">'.$row['name'].'</a></li>';
																}
															}
															else echo "Cannot connect to the database";
													   ?>
												    </ul>
										    </li>
											
										 </ul>
										 

                                         <div id="container" style="min-width: 400px; height:400px; margin: 0 auto"></div>
                                         <br/>
										 <div id="container2" style="min-width: 400px;  margin: 0 auto"></div>
										  
		
										 <div id="table"></div>
										 
									</div>
									<!-- Tab for employees -->
									<div class="tab-pane" id="tabs1-pane2">
									    <ul class="nav nav-pills" >
										    <li>View:</li>
											<li><a onclick="generateAllEmployeesTable()">All</a></li>
											<li><a onclick="generateCollegeEmployeesTable()">College</a></li>
											<li><a onclick="generateOfficeEmployeesTable()">Office</a></li>
					                    </ul>
										
										 <div id="container3" style="min-width: 400px; height:400px; margin: 0 auto"></div>
                                         <br/>
										 <div id="container4" style="min-width: 400px;  margin: 0 auto"></div>
                                         <div id="table2"></div>
										
									</div>
						            
								</div><!-- /.tab-content -->
							</div><!-- /.tabbable -->
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div> <!-- row -->
    </div>	<!-- container -->
    <script src="tools/highcharts/js/highcharts.js"></script>
	<script src="tools/highcharts/js/modules/exporting.js"></script>
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
</body>
</html>
