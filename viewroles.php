<?php
       //Connect and bind to ldap server
	include 'ldap_config.php'; 
	include 'frag_sessions.php';
   
    //User with STUDENT or EMPLOYEE as role cannot view this page
	if(!$activerole=='ADMIN')
	   redirect("home.php");
	
    // search for the current value of uidnumholder, this will be the the uidnumber of the entry to be added	
	$search = ldap_search($ldapconn, $ldapconfig['basedn'], "(ou=uidnumholder)");
    $entry = ldap_get_entries($ldapconn, $search);
    $uidnumhorderdn = $entry[0]['dn'];	
    $uidnumholder = $entry[0]['telexnumber'][0];
    

	
?> 

<!DOCTYPE html>
<html>
<head>
   <?php
     $pagetitle = "NetID :: Add Student";
	 include 'header.php';
   ?>

    <style>
     #addrole{
	  width: 45%;
	  float: right; 
	 }
	 .tab-content{
	   width: 50%; 
		float: left;
	 }
	</style>
	
	
</head>
<body>

	
	<?php  include 'frag_header.php';  ?>
	
	<div class="container-fluid">
	    <div class="row-fluid">
		    <div class="span3">
             	<div class="well sidebar-nav">
                     <ul class="nav nav-list">
					    <li class="nav-header">Menu</li>
					    <?php $active=8;
 						      include 'frag_menu.php'  ?>
					 </ul>
				</div> <!-- well  -->				
            </div> <!-- span3 -->

            <div class="span9">
			    <div class="hero-unit">
				     <h1> User Roles </h1>
					 <div id="confirmDiv" style="display: none;" class="">
                    </div>
	                 <img src="tools/img/horredline.jpg"/>
                    
				     <?php 
					  //$conn = mysqli_connect('localhost','root','','netid');

						// Check connection
					  if (mysqli_connect_errno($conn))
						  echo "Failed to connect to MySQL: " . mysqli_connect_error();
						 
					  else{
					 ?>
							<div class="tabbable">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tabs1-pane1" data-toggle="tab">OCS</a></li>
									<li><a href="#tabs1-pane2" data-toggle="tab">OUR</a></li>
									<li><a href="#tabs1-pane3" data-toggle="tab">HRDO</a></li>
									<li><a href="#tabs1-pane4" data-toggle="tab">ADMIN</a></li>
								</ul>
								<div class="tab-content">
								    <!-- Tab for OCS roles -->
									<div class="tab-pane active" id="tabs1-pane1">
									  <?php 
									    $query="SELECT uid FROM user_role WHERE role='OCS'";
										$result=mysqli_query($conn, $query);
						             
										echo "<table id='ocstable' class='table table-stripped tablerole'>
										<tr>
										<th>Uid</th>
										<th>Delete</th>
										</tr>";
                                       while($row = mysqli_fetch_array($result))
												  {
												  echo "<tr>";
												  echo "<td class='uidtd'><a style='color:#333333' href='viewprofile.php?title=employee&uid=".$row['uid'] . "'>".$row['uid']."</a></td><td>";
                                             ?>												 
												 <button onclick='javascript:deleterole("<?php echo $row['uid'];?>","ocs")' class='btn-link' data-toggle='tooltip' title='Delete'><i class='icon-remove'></i></button></td>
												  </tr>
												<?php  }
										echo "</table>";
										?>
									</div>
									<!-- Tab for OUR roles -->
									<div class="tab-pane" id="tabs1-pane2">
									  <?php 
									    $query="SELECT uid FROM user_role WHERE role='OUR'";
										$result=mysqli_query($conn, $query);
										
										echo "<table id='ourtable' class='table table-stripped tablerole'>
										<tr>
										<th>Uid</th>
										<th>Delete</th>
										</tr>";

										  while($row = mysqli_fetch_array($result))
												  {
												  echo "<tr>";
												  echo "<td class='uidtd'><a style='color:#333333' href='viewprofile.php?title=employee&uid=".$row['uid'] . "'>".$row['uid']."</a></td><td>";
                                             ?>												 
												 <button onclick='javascript:deleterole("<?php echo $row['uid'];?>","our")' class='btn-link' data-toggle='tooltip' title='Delete'><i class='icon-remove'></i></button></td>
												  </tr>
												<?php  }
										echo "</table>";
										?>
									</div>
									<!-- Tab for HRDO roles -->
									<div class="tab-pane" id="tabs1-pane3">
									  <?php 
									    $query="SELECT uid FROM user_role WHERE role='HRDO'";
										$result=mysqli_query($conn, $query);
										
										echo "<table id='hrdotable' class='table table-stripped tablerole'>
										<tr>
										<th>Uid</th>
										<th>Delete</th>
										</tr>";
						
										  while($row = mysqli_fetch_array($result))
												  {
												  echo "<tr>";
												  echo "<td class='uidtd'><a style='color:#333333' href='viewprofile.php?title=employee&uid=".$row['uid'] . "'>".$row['uid']."</a></td><td>";
                                             ?>												 
												 <button onclick='javascript:deleterole("<?php echo $row['uid'];?>","hrdo")' class='btn-link' data-toggle='tooltip' title='Delete'><i class='icon-remove'></i></button></td>
												  </tr>
												<?php  }
										echo "</table>";
										?>
										
									</div>
									<!-- Tab for HRDO roles -->
									<div class="tab-pane" id="tabs1-pane4">
									  <?php 
									    $query="SELECT uid FROM user_role WHERE role='ADMIN'";
										$result=mysqli_query($conn, $query);
						                
										echo "<table id='ADMINtable' class='table table-stripped tablerole'>
										<tr>
										<th>Uid</th>
										<th>Delete</th>
										</tr>";

										  while($row = mysqli_fetch_array($result))
												  {
												  echo "<tr>";
												  echo "<td class='uidtd'><a style='color:#333333' href='viewprofile.php?title=employee&uid=".$row['uid'] . "'>".$row['uid']."</a></td><td>";
                                             ?>												 
												 <button onclick='javascript:deleterole("<?php echo $row['uid'];?>","ADMIN")' class='btn-link' data-toggle='tooltip' title='Delete'><i class='icon-remove'></i></button></td>
												  </tr>
												<?php  }
										echo "</table>";
										?>								
									</div>
								</div><!-- /.tab-content -->
							<!-- Add role form-->
							<div id="addrole">
							    <form name="searchaddrole" id="searchaddrole" class="form-search" action="javascript:searchuid()"> 
								 <input type="text" name="uid" id="searchuid" placeholder="Username"/>
                                 <button class="btn btn-primary" type="submit"><i class="icon-search icon-white"></i>  Search</button>
								</form>
								<div id="addrolediv" style="display:none;  width:300px;">
								<form name="addrole" id="formaddrole" action="javascript:addrole()"> 
                                 <div class="searchresults">
					             </div>
							     
								 
								 
								 <button class='btn btn-primary'><i class='icon-plus'></i> Add</button>
								 
								 </form>
								 </div>
							</div>
							</div><!-- /.tabbable -->
						<?php } ?>	
							
                </div> <!-- hero -->				
            </div>	<!-- span9-->		
	    </div>
    </div>	

<?php include 'frag_footer.php'?>
  

	 