
<?php
      //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
	
	//User with STUDENT or EMPLOYEE as role cannot view this page
	if(!($activerole=='ADMIN'))
	   redirect("home.php");		        
	
	$orderby=$_GET['orderby'];
	$count=0;
    //$conn = mysqli_connect('localhost','root','','netid');
							// Check connection
						if (!mysqli_connect_errno($conn)){
						    if($orderby == 'timestamp')
							    $orderby .= " DESC";
							$query="SELECT * FROM auditlog ORDER BY ".$orderby;
							$result=mysqli_query($conn, $query);
							$count = mysqli_num_rows($result);	
						}
						
							
?>


<!DOCTYPE>
<html>
<head>
    <?php
     $pagetitle = "NetID :: Audit Logs";
	 include 'header.php';
    ?>
   
	<!-- script for pagination-->
    <script type="text/javascript">
	    // script for the datepicker
        $(document).ready(function () {
		    $('#daterange').daterangepicker(
                     {
					    format: 'yyyy.MM.dd',
						minDate: '2013.01.01',
                        maxDate:  Date.today(),
						            showDropdowns: true
                     }
                  );
				  
            $('#auditdaterange').daterangepicker(
                     {
					    format: 'yyyy.MM.dd',
						minDate: '2013.01.01',
                        maxDate:  Date.today(),
						            showDropdowns: true
                     }
                  );
		   // script for paginator
           $('#pagination').smartpaginator({ 
				totalrecords: <?php echo $count;?>, 
				recordsperpage: 10, 
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
					    <?php $active=9;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				     <h1 class="pull-left"> Audit Logs </h1>
					 <img src="tools/img/horredline.jpg"/>
					<form action="javascript:auditdate()">
						 <div class="control-group">
		                     <label class="control-label" for="reservation">Date range:</label>
		                     <div class="controls">
		                       <span class="add-on"><i class="icon-calendar"></i></span><input type="text" id="auditdaterange" required/> 
							   <button type="submit" class="btn btn-primary">Go</button>
		                     </div>
						 </div> 
					</form> 
					<div id="logs">
						 <!-- where the pagination will be showed-->
						<div id="pagination" class="pagination pagination-small">
	                    </div>	
						<?php
						    if (!mysqli_connect_errno($conn)){
								
							
	                         echo '<ul class="inline pull-right" style="font-size:12px; margin-top:-30px">';
							     echo '<li>Order by:&nbsp</li>';
		                         echo '<li>';
								 echo '<a href="viewauditlogs.php?orderby=username">User ID</a> <span class="divider">/</span></li>';
								 echo '<li>';
								 echo '<a href="viewauditlogs.php?orderby=ipaddress">IP Address</a> <span class="divider">/</span></li>';
								 echo '<li>'; 
								 echo '<a href="viewauditlogs.php?orderby=timestamp">Timestamp</a> <span class="divider">/</span></li>';
								 echo '<li>'; 
								 echo '<a href="viewauditlogs.php?orderby=accesstype">Access Type</a> <span class="divider">/</span></li>';
								 echo '<li>'; 
								 echo '<a href="viewauditlogs.php?orderby=affecteduser">Affected User</a> </li>';
							 echo "</ul>";	
							 
								echo '<table class="table" id="tablelist" style="font-size:14px;">
								        <thead>
											   <tr>
												<th>User ID</th>
												<th>IP Address</th>
												<th>Timestamp</th>
												<th>Access Type</th>
												<th>Affected User</th>
												</tr>
										</thead>';
								echo   '<tbody id="tablebody">';		
										while($row = mysqli_fetch_array($result))
													  {
													  echo "<tr>";
													  echo "<td>" . $row['username'] . "</td>";
													  echo "<td>" . $row['ipaddress'] . "</td>";
													  echo "<td>" . $row['timestamp'] . "</td>";
													  echo "<td>" . $row['accesstype'] . "</td>";
													  echo "<td>" . $row['affecteduser'] . "</td>";
													  echo "</tr>";
	                                             											 
													}
						         echo   '</tbody>';       
						         echo '</table>';
								 mysqli_close($conn);
							}
							else 
						       echo "Failed to connect to MySQL: " . mysqli_connect_error();
							 
						  
						 ?>
					</div> 
				
              <div class="well">
			  <!-- Save Logs Form-->
			   <h4>Save Audit Logs to File</h4>
               <form class="form-vertical" method="POST" action="javascript: savelogstofile('0')">
                  <div class="control-group">
                    <label class="control-label" for="reservation">Date range:</label>
                    <div class="controls">
                     
                       <span class="add-on"><i class="icon-calendar"></i></span><input type="text" name="reservation" id="daterange" />
                    
					    <button class="btn btn-primary"  name="savetofilebutton">Save to File<i class="icon-file icon-white"></i> </button>
                    </div>
                  </div>
               </form>


			    <button class="btn btn-primary"  onclick="javascript: savelogstofile('1')" name="savetofilebutton">Save All to File<i class="icon-file icon-white"></i> </button>
             </div>  

           
					 
					 
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
