<?php
       //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//User with STUDENT or EMPLOYEE as role cannot view this page
	if($activerole=='student' || $activerole=='employee')
	   redirect("home.php");
	
	 $degprog=$_GET['dp'];
	 $orderby=$_GET['orderby'];
	 // search for the students
	 $result  = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(course=".$degprog.")(title=student))");
							 ldap_sort($ldapconn, $result, $orderby);
					         $entries = ldap_get_entries($ldapconn, $result);
	 $count = $entries["count"];		        
					
?>


<!DOCTYPE>
<html>
<head>
    <?php
     $pagetitle = "NetID :: Student List";
	 include 'header.php';
    ?>
	
	<!-- script for pagination-->
    <script type="text/javascript">
        $(document).ready(function () {
           $('#pagination').smartpaginator({ 
				totalrecords: <?php echo $count;?>, 
				recordsperpage: 20, 
				datacontainer: 'tablelist', 
				dataelement: 'tr', 
				initval: 0, 
				next: 'Next', 
				prev: 'Prev', 
				first: 'First', 
				last: 'Last' ,
	            controlsalways: true,
				onchange: function (newPage) {
	                $('#r').html('Page # ' + newPage);
	                 }
            });

        });
    </script>
    
</head>

<body>
   	<?php  include 'frag_header.php';  ?>
 

	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php $active=2;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				     <h1> <?php echo $degprog?> </h1>
	                 <img src="tools/img/horredline.jpg" />
                    <!-- where the pagination will be showed-->
					<div id="pagination" class="pagination pagination-small">
                    </div>	

					 <ul class="inline pull-right" style="font-size:12px; margin-top:-30px">
					<?php 
						 echo '<li>Order by:&nbsp</li>';
                         if ($orderby=="sn") echo '<li class="active">';						   
						 else                echo '<li>';
						 echo '<a href="frag_liststudent.php?dp='.$degprog.'&orderby=sn">Name</a> <span class="divider">/</span></li>';
						 if ($orderby=="studentnumber") echo '<li class="active">';						   
						 else                           echo '<li>';
						 echo '<a href="frag_liststudent.php?dp='.$degprog.'&orderby=studentnumber">Student number</a> <span class="divider">/</span></li>';
						 if ($orderby=="mail") echo '<li class="active">';						   
						 else                  echo '<li>'; 
						 echo '<a href="frag_liststudent.php?dp='.$degprog.'&orderby=mail">Mail</a> </li>';
					  ?>
					</ul>
					
				    
					<?php
			      		    echo '<table class="table" id="tablelist" style="font-size:14px;">
										
											<tr>
								                 <th>Name</th>
								                 <th>Student Number</th>
												 <th>Type</th>
								                 <th>Mail</th>
								            </tr>';
									    
									for($i=0; $i<count($entries)-1; $i++){
									   echo "<tr>";
											echo 	"<td><a  style='color:#333333' href='viewprofile.php?title=student&uid=".$entries[$i]['uid'][0]."'";  
											         if (!($activerole =='ADMIN' || $activerole=='HRDO' || $activerole=='OUR')) echo "onclick='return false;'";                 // restrict unauthorized users to view profile of other users
											         echo ">".$entries[$i]['cn'][0]."</a></td>";														 // OUR, HRDO, and ADMIN users will be able to  view the profile of the students by clicking the name
											echo 	"<td>".$entries[$i]['studentnumber'][0]."</td>";
											echo 	"<td>".$entries[$i]['studenttype'][0]."</td>";
											//check if student has mail 
										    if(isset($entries[$i]['mail'])) echo 	"<td>".$entries[$i]['mail'][0]."</td>";
									    echo "</tr>";
					                }
					         echo '</table>';
				    ?>
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div> <!-- row -->
    </div>	<!-- container -->
      
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