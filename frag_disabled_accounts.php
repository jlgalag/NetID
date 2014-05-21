<?php
       //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//User with STUDENT or EMPLOYEE as role cannot view this page
	if($activerole=='student' || $activerole=='employee' || $activerole == 'HRDO' || $active == 'OUR' || $active == 'OCS')
	   redirect("home.php");
	   
	 // search for the disabled accounts
	 $result  = ldap_search($ldapconn, "ou=disabledaccounts,".$ldapconfig['basedn'], "uniqueIdentifierUPLB=*");
			ldap_sort($ldapconn, $result, $orderby);
			$entries = ldap_get_entries($ldapconn, $result);
	 $count = $entries["count"];					        
					
?>


<!DOCTYPE>
<html>
<head>
    <?php
     $pagetitle = "NetID :: Employee List";
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
					    <?php $active=4;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				     <h1> <?php echo $gidname?> </h1>
	                 <img src="tools/img/horredline.jpg"/>
                      <!-- where the pagination will be showed-->
					<div id="pagination" class="pagination pagination-small">
                    </div>	

					 <ul class="inline pull-right" style="font-size:12px; margin-top:-30px">
					
					    <?php 
						 echo '<li>Order by:&nbsp</li>';
                         if ($orderby=="sn") echo '<li class="active">';						   
						 else                echo '<li>';
						 echo '<a href="frag_listemployee.php?gidnumber='.$gidnumber.'&gidname='.$gidname.'&orderby=sn">Name</a> <span class="divider">/</span></li>';
						 if ($orderby=="studentnumber") echo '<li class="active">';						   
						 else                           echo '<li>';
						 echo '<a href="frag_listemployee.php?gidnumber='.$gidnumber.'&gidname='.$gidname.'&orderby=employeenumber">Emp Number</a> <span class="divider">/</span></li>';
						 if ($orderby=="ou") echo '<li class="active">';						   
						 else                  echo '<li>'; 
						 echo '<a href="frag_listemployee.php?gidnumber='.$gidnumber.'&gidname='.$gidname.'&orderby=employeetype">Type</a> <span class="divider">/</span></li>';
						 if ($orderby=="ou") echo '<li class="active">';						   
						 else                  echo '<li>'; 
						 echo '<a href="frag_listemployee.php?gidnumber='.$gidnumber.'&gidname='.$gidname.'&orderby=ou">Unit</a> <span class="divider">/</span></li>';
						 if ($orderby=="mail") echo '<li class="active">';						   
						 else                  echo '<li>'; 
						 echo '<a href="frag_listemployee.php?gidnumber='.$gidnumber.'&gidname='.$gidname.'&orderby=mail">Mail</a> </li>';
					   ?>
					</ul>
					<?php
					         echo '<table class="table" id="tablelist" style="font-size:14px;">
										   <tr>
								                 <th>Name</th>
								                 <th>Employee Number</th>
												 <th>Type</th>
								                 <th>Department</th>
								                 <th>Mail</th>
											</tr>';
									    
									for($i=0; $i<count($entries)-1; $i++){
									   echo "<tr>";
											echo 	"<td><a style='color:#333333' href='viewprofile.php?title=employee&uid=".$entries[$i]['uid'][0]."'";
											  if (!($activerole =='ADMIN' || $activerole=='HRDO' || $activerole=='OUR')) echo "onclick='return false;'";     // restrict unauthorized users to view profile of other users
											  echo ">".$entries[$i]['cn'][0]."</a></td>";	   // OUR, HRDO, and ADMIN users will be able to  view the profile of the students by clicking the name
											echo    "<td>".$entries[$i]['employeenumber'][0]."</td>";
											echo 	"<td>".$entries[$i]['employeetype'][0]."</td>";
											echo 	"<td>".$entries[$i]['ou'][0]."</td>";
										    //check if employee has mail 
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
