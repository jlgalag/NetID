<?php
    // create dropdown of degree programs based on the given gidnumber/college
	function showdegreeprograms($gidnumber){
		 $conn = mysqli_connect('localhost','root','','netid');
	    // Check connection
		if (!mysqli_connect_errno($conn)){
			echo '<ul class="dropdown-menu">';      
			
			$query = "SELECT * FROM degreeprograms WHERE gidnumber=".$gidnumber;
			$degreeprograms = mysqli_query($conn, $query);
					
			while($row2 = mysqli_fetch_array($degreeprograms)){
				echo '<li><a tabindex="-1" href="frag_liststudent.php?dp='.$row2['name'].'&orderby=sn">'.$row2['title'].'</a></li>';
			}
				
			echo '</ul>';
				
		}
		else echo "Cannot connect to the database"; 
     }
  
    //create dropdown of colleges and degree programs (CAS - BSCS, BSBIO..)
    function showcollegesanddegreeprograms(){
        $conn = mysqli_connect('localhost','root','','netid');
	    // Check connection
		if (!mysqli_connect_errno($conn)){
			$query="SELECT * FROM college ORDER BY gidnumber";
			$college=mysqli_query($conn, $query);
			
			
			echo '<ul class="dropdown-menu">';      
			
			while($row = mysqli_fetch_array($college)){
			    echo '<li class="dropdown-submenu">';
				echo    '<a tabindex="-1" href="#">'.$row['name'].'</a>';
			    showdegreeprograms($row['gidnumber']);
			    echo '</li>';    
			}	
			echo '</ul>';
			
			
		}
		else echo "Cannot connect to the database";

  }  
  // create dropdown of all offices (OC, OVCI..)
  function showoffices(){
     $conn = mysqli_connect('localhost','root','','netid');
	    // Check connection
		if (!mysqli_connect_errno($conn)){
			$query="SELECT * FROM offices ORDER BY gidnumber";
			$offices=mysqli_query($conn, $query);
			
			echo '<ul class="dropdown-menu">';      
			
			while($row = mysqli_fetch_array($offices)){
			     echo '<li><a tabindex="-1" href="frag_listemployee.php?gidnumber='.$row['gidnumber'].'&gidname='.$row['name'].'&orderby=sn">'.$row['name'].'</a></li>';
			}
				   
			echo '</ul>';
			
			
		}
		else echo "Cannot connect to the database";
  
  }   
    // create drop down of all colleges (CA, CAS... )  
    function showcolleges(){
	   $conn = mysqli_connect('localhost','root','','netid');
	    // Check connection
		if (!mysqli_connect_errno($conn)){
			$query="SELECT * FROM college ORDER BY gidnumber";
			$college=mysqli_query($conn, $query);
			
			echo '<ul class="dropdown-menu">';      
			
			while($row = mysqli_fetch_array($college)){
			     echo '<li><a tabindex="-1" href="frag_listemployee.php?gidnumber='.$row['gidnumber'].'&gidname='.$row['name'].'&orderby=sn">'.$row['name'].'</a></li>';
			}
			
			echo '</ul>';
			
			
		}
		else echo "Cannot connect to the database";
  
  }  
  
    
 
?>

<!--  ROLE = student / employee    -->
<?php 
if ($activerole=='student' || $activerole=='employee'){
?>
<li <?php if($active==1) echo 'class="active"'; ?>><a href="home.php">Profile</a></li>

<!--  ROLE = OCS    -->
<?php
}
else if($activerole=='OCS')
{
?>
<li <?php if($active==1) echo 'class="active"'; ?>><a href="home.php">Profile</a></li>
<li class="dropdown-submenu <?php if($active==2) echo 'class= "active"'; ?>">
	<a tabindex="-1" href="#">Courses</a>
    <?php  showdegreeprograms($_SESSION['gidnumber']); ?>
</li>
<li <?php if($active==6) echo 'class="active"'; ?> >
	<a href="searchstudent.php">Search Student</a>
</li>

<!--  ROLE = OUR  -->
<?php
}
else if($activerole=='OUR')
{
?>
<li <?php if($active==1) echo 'class="active"'; ?>><a href="home.php">Profile</a></li>
<li class="dropdown-submenu" <?php if($active==2) echo 'class="active"'; ?>>
	<a>Colleges</a>
	<?php  showcollegesanddegreeprograms(); ?>
</li>
<li <?php if($active==3) echo 'class="active"'; ?> >
	<a href="addstudent.php">Add Student</a>
</li>
<li <?php if($active==6) echo 'class="active"'; ?> >
	<a href="searchstudent.php">Search Student</a>
</li>


<!--  ROLE = HRDO  -->
<?php
}
else if($activerole=='HRDO')
{

?>
<li <?php if($active==1) echo 'class="active"'; ?>><a href="home.php">Profile</a></li>
<li class="dropdown-submenu <?php if($active==4) echo 'class="active"'; ?>">
	<a>Offices</a>
	<?php  showoffices(); ?> 
</li>
<li class="dropdown-submenu <?php if($active==4) echo 'class="active"'; ?>">
	<a>Colleges</a>
	<?php  showcolleges(); ?> 
</li>
<li <?php if($active==5) echo 'class="active"'; ?> >
	<a href="addemployee.php">Add Employee</a>
</li>
<li <?php if($active==7) echo 'class="active"'; ?> >
	<a href="searchemployee.php">Search Employee</a>
</li>

<!--  ROLE = ADMIN   -->
<?php
}
else if($activerole=='ADMIN')
{

?>
<li <?php if($active==1) echo 'class="active"'; ?>><a href="home.php">Profile</a></li>
<li class="nav-header">Student</li>
<li  class="dropdown-submenu" <?php if($active==2) echo 'class="active"'; ?>>
   <a>Colleges</a>
	<?php  showcollegesanddegreeprograms(); ?>
</li>
<li <?php if($active==3) echo 'class="active"'; ?> >
	<a href="addstudent.php">Add Student</a>
</li>	
<li <?php if($active==6) echo 'class="active"'; ?> >
    <a href="searchstudent.php">Search Student</a>
</li>	
<li class="nav-header">Employee</li>
<li class="dropdown-submenu <?php if($active==4) echo 'class="active"'; ?>">
	<a>Offices</a>
	<?php  showoffices(); ?> 
</li>
<li class="dropdown-submenu <?php if($active==4) echo 'class="active"'; ?>">
	<a>Colleges</a>
	<?php  showcolleges(); ?> 
</li>
<li <?php if($active==5) echo 'class="active"'; ?> >
	<a href="addemployee.php">Add Employee</a>
</li>
<li <?php if($active==7) echo 'class="active"'; ?> >
<a href="searchemployee.php">Search Employee</a>
</li>
<li class="nav-header">View</li>
<li <?php if($active==8) echo 'class="active"'; ?> >
	<a href="viewroles.php">User Roles</a>
</li>
<li <?php if($active==9) echo 'class="active"'; ?> >
	<a href="viewauditlogs.php?orderby=timestamp">Audit Logs</a>
</li>
<li <?php if($active==10) echo 'class="active"'; ?> >
	<a href="viewdemographics.php">Demographics Table</a>
</li>
<li <?php if($active==11) echo 'class="active"'; ?> >
	<a href="viewdemographicsgraph.php">Demographics Graph</a>
</li>
<li class="divider"></li>
<li <?php if($active==12) echo 'class="active"'; ?> >
	<a href="addgroup.php">Add Degree Program</a>
</li>

<?php
}
?>