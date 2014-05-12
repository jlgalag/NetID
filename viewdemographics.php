<?php
       //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//User with STUDENT or EMPLOYEE as role cannot view this page
	if(!($activerole=='ADMIN'))
	   redirect("home.php");		        
	    $conn = mysqli_connect('localhost','root','','netid');				
							
?>


<!DOCTYPE>
<html>
<head>
    <?php
     $pagetitle = "NetID :: Demographics";
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
					    <?php $active=10;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				     <h1 class="pull-left"> Demographics </h1>
					 <img src="tools/img/horredline.jpg"/>
                     
						
				
				<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tabs1-pane1" data-toggle="tab">Student</a></li>
									<li><a href="#tabs1-pane2" data-toggle="tab">Employee</a></li>
								</ul>
								<div class="tab-content">
								    <?php
                                        
										$cachefile = 'files/cache/demog.cache'; // e.g. cache/index.php.cache
										$cachetime = 120 * 60 * 12 ; // 24 hours
                                        // if cache file exists use the file  
										if(file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))){
										 
										  include $cachefile; 
										  echo "<!--Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
										}
										else{
											ob_start();
											        // tab for students
								            echo	'<div class="tab-pane active" id="tabs1-pane1">';
													    // Check connection
														if (!mysqli_connect_errno($conn)){
															$query="SELECT * FROM college ORDER BY gidnumber";
															$college=mysqli_query($conn, $query);
															
															echo "<table class='table' style='font-size:14px; width:600px'>";
															while($row = mysqli_fetch_array($college)){
															    $sum=0;
																echo '<tr><td colspan="3"><h4>'.$row['name'].'</h4></td></tr>';
															    $query = "SELECT * FROM degreeprograms WHERE gidnumber=".$row['gidnumber'];
																$degreeprograms = mysqli_query($conn, $query);
																while($row2 = mysqli_fetch_array($degreeprograms)){
																	echo '<tr>';
																	echo '<td>'.$row2['name'].'</td>';
																	echo '<td>'.$row2['title'].'</td>';
																	$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(course=".$row2['name'].")(gidNumber=".$row2['gidnumber']."))");
																	$count = ldap_count_entries($ldapconn, $sr);
																	echo '<td>'.$count.'</td>';
																	echo '</tr>';
																	$sum += $count;
																}
																echo '<tr><td colspan="2">Total</td>';
																echo '<td><h5>'.$sum.'</h5></td><tr/>';
																    
															}	
															echo '</table>';
												
											

											}

											else echo "Cannot connect to the database";
											echo'	</div>
											        <!-- tab for employees-->
													<div class="tab-pane" id="tabs1-pane2">';
														 if (!mysqli_connect_errno($conn)){
																	$query="SELECT * FROM offices ORDER BY gidnumber";
																	$offices=mysqli_query($conn, $query);
																	
																	echo "<table class='table' style='font-size:14px; width:600px'>";
																	while($row = mysqli_fetch_array($offices)){
																	    	echo '<tr>';
																			echo '<td>'.$row['name'].'</td>';
																			$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(gidnumber=".$row['gidnumber'].")(title=employee))");
																			echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
																			echo '</tr>';
																		}
																		
																	$query="SELECT * FROM college ORDER BY gidnumber";
																	$college=mysqli_query($conn, $query);
					                                                while($row = mysqli_fetch_array($college)){
																	    	echo '<tr>';
																			echo '<td>'.$row['name'].'</td>';
																			$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(gidnumber=".$row['gidnumber'].")(title=employee))");
																			echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
																			echo '</tr>';
																		}												
																		    
																	
																	echo '</table>';
																	
																	mysqli_close($conn);
																}	
																else echo "Cannot connect to the database";
											echo	'</div>';
													
													// Cache the contents to a file
													$cached = fopen($cachefile, 'w');
													fwrite($cached, ob_get_contents());
													fclose($cached);

										}
									?>
								</div><!-- /.tab-content --> 
							</div><!-- /.tabbable -->
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div> <!-- row -->
    </div>	<!-- container -->


<?php include 'frag_footer.php'?>
