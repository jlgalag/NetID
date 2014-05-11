<?php
       //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	
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
    <?php  include 'frag_header.php';  ?>
	
	<div class="container">
	   <div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tabs1-pane1" data-toggle="tab">Student</a></li>
				<li><a href="#tabs1-pane2" data-toggle="tab">Employee</a></li>
									
			</ul>
			
			<div class="tab-content">
			    <!-- tab for students-->
				<div class="tab-pane active" id="tabs1-pane1">
                    
					<div class="row-fluid">
					    <div class="well sidebar-nav span2">
							<ul class="nav nav-list">
								<li class="nav-header">College</li>
					    

						 
						  <?php 
						       $conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											
												while($row = mysqli_fetch_array($college)){
												    echo '<li class="dropdown-submenu">';
														echo    '<a tabindex="-1" >'.$row['name'].'</a>';
													    $query = "SELECT * FROM degreeprograms WHERE gidnumber=".$row['gidnumber'];
														$degreeprograms = mysqli_query($conn, $query);
															echo '<ul class="dropdown-menu">';
															while($row2 = mysqli_fetch_array($degreeprograms)){
																	echo '<li><a tabindex="-1" onclick="viewstudentdirectory(\''.$row2['name'].'\')">'.$row2['title'].'</a></li>';
					                                        } 
		                                                    echo '</ul>';													
												    echo '</li>';    
												}
                                             
											
                                            mysqli_close($conn); 											
										}
										else echo "<option>Cannot connect to the database</option>";
								
						  ?>
						   </ul>
						    
					    </div>
						<div class="span9">
			                <div class="hero-unit" id="studdirlist">
							    <script>viewstudentdirectory('BSA');</script>
							</div>
					    </div>
						
				   
                     </div> 
                </div>
				<!-- tab for employees-->
				<div class="tab-pane" id="tabs1-pane2">
				   <div class="row-fluid">
					    <div class="well sidebar-nav span2">
					      <?php 
						       $conn = mysqli_connect('localhost','root','','netid');
									    // Check connection
										if (!mysqli_connect_errno($conn)){
											echo "<ul class='unstyled'>";
											
											echo "<li class='nav-header'>Offices</li>";
											$query="SELECT * FROM offices ORDER BY gidnumber";
											$office=mysqli_query($conn, $query);
											while($row = mysqli_fetch_array($office)){
											    echo    '<li ><button onclick="viewemployeedirectory(\''.$row['gidnumber'].'\', \''.$row['name'].'\')" class="btn btn-link">'.$row['name'].'</button></li>';
											}
											
											echo "<li class='nav-header'>Colleges</li>";
											$query="SELECT * FROM college ORDER BY gidnumber";
											$college=mysqli_query($conn, $query);
											
											while($row = mysqli_fetch_array($college)){
											    echo    '<li ><button onclick="viewemployeedirectory(\''.$row['gidnumber'].'\', \''.$row['name'].'\')" class="btn btn-link">'.$row['name'].'</button></li>';
											}
											
                                            echo "</ul>";  
											
                                            mysqli_close($conn); 											
										}
										else echo "<option>Cannot connect to the database</option>";
								
						  ?>
					    </div>
						<div class="span9">
			                <div class="hero-unit" id="empdirlist">
								 <script>viewemployeedirectory('1000', 'OC');</script>
							</div>
					    </div>
						
				   
                     </div> 
				</div>
			</div><!-- /.tab-content -->
							
							
		</div><!-- /.tabbable -->
	   
    </div>	
		 
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