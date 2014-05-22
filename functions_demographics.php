<?php
   require 'ldap_config.php'; 
   require 'frag_sessions.php';
   

   function createcollegeTable($gidnumber, $name){
      global $ldapconn, $ldapconfig, $conn;
	    $cachefile = 'files/cache/demog'.$name.'stud.cache'; // e.g. cache/index.php.cache
		$cachetime = 120 * 60 * 12 ; // 24 hours

		if(file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))){
		 
		  $table = file_get_contents($cachefile);
		  echo $table;
		  echo "<!--Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
		}
		else{
			ob_start();	 
			  ////$conn = mysqli_connect('localhost','root','','netid');			
			   // Check connection
				if (!mysqli_connect_errno($conn)){
					 echo "<table id='datatable' style='display:none'>";
					  echo "<thead>";
					       echo "<th></th>";
					       echo "<th>".$name."</th>";
					  echo "</thead>";
			          echo "<tbody>";
					$query = "SELECT * FROM degreeprograms WHERE gidnumber=".$gidnumber;
					$degreeprograms = mysqli_query($conn, $query);
					while($row2 = mysqli_fetch_array($degreeprograms)){
						echo '<tr>';
						echo '<th>'.$row2['name'].'</th>';
						$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(course=".$row2['name'].")(title=student))");
						echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
						echo '</tr>';
					}
					  echo "</tbody>";
					echo "</table>";
				}
				else echo "Cannot connect to the database";  
				
			// Cache the contents to a file
			$cached = fopen($cachefile, 'w');
			fwrite($cached, ob_get_contents());
			fclose($cached);	
		}	
   }
   
   
   function createallstudentstable(){
      global $ldapconn, $ldapconfig, $conn;

	    $cachefile = 'files/cache/demogallstud.cache'; // e.g. cache/index.php.cache
		$cachetime = 120 * 60 * 12 ; // 24 hours

		if(file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))){
		 
		  $table = file_get_contents($cachefile);
		  echo $table;
		  echo "<!--Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
		}
		else{
			ob_start();	  	  
			  //$conn = mysqli_connect('localhost','root','','netid');			
			   // Check connection
				if (!mysqli_connect_errno($conn)){
					 echo "<table id='datatable' style='display:none'>";
					  echo "<thead>";
					       echo "<th></th>";
					       echo "<th>College</th>";
					  echo "</thead>";
			          echo "<tbody>";
					$query = "SELECT * FROM college ORDER BY gidnumber";
					$college = mysqli_query($conn, $query);
					while($row2 = mysqli_fetch_array($college)){
						echo '<tr>';
						echo '<th>'.$row2['name'].'</th>';
						$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(college=".$row2['name'].")(title=student))");
						echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
						echo '</tr>';
					}
					  echo "</tbody>";
					echo "</table>";
				}
				else echo "Cannot connect to the database";  
				
			// Cache the contents to a file
				$cached = fopen($cachefile, 'w');
				fwrite($cached, ob_get_contents());
				fclose($cached);
        }				
   }
   
   function createallemployeestable(){
      global $ldapconn, $ldapconfig, $conn;
	  
	    $cachefile = 'files/cache/demogallemp.cache'; // e.g. cache/index.php.cache
		$cachetime = 120 * 60 * 12 ; // 24 hours

		if(file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))){
		 
		  $table = file_get_contents($cachefile);
		  echo $table;
		  echo "<!--Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
		}
		else{
			ob_start();	 
		    //$conn = mysqli_connect('localhost','root','','netid');			
		   // Check connection
			if (!mysqli_connect_errno($conn)){
				 echo "<table id='datatable2' style='display:none'>";
				  echo "<thead>";
					   echo "<th></th>";
					   echo "<th>Colleges and Offices</th>";
				  echo "</thead>";
				  echo "<tbody>";
				$query = "SELECT * FROM college ORDER BY gidnumber";
				$college = mysqli_query($conn, $query);
				while($row2 = mysqli_fetch_array($college)){
					echo '<tr>';
					echo '<th>'.$row2['name'].'</th>';
					$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(o=".$row2['name'].")(title=employee))");
					echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
					echo '</tr>';
				}
				$query = "SELECT * FROM offices ORDER BY gidnumber";
				$office = mysqli_query($conn, $query);
				while($row2 = mysqli_fetch_array($office)){
					echo '<tr>';
					echo '<th>'.$row2['name'].'</th>';
					$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(o=".$row2['name'].")(title=employee))");
					echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
					echo '</tr>';
				}
				  echo "</tbody>";
				echo "</table>";
			
			
			}
			
			else echo "Cannot connect to the database";  
			
			// Cache the contents to a file
			$cached = fopen($cachefile, 'w');
			fwrite($cached, ob_get_contents());
			fclose($cached);
		}	
   }
   
    function createcolleegemployeestable(){
      global $ldapconn, $ldapconfig, $conn;
	  
	    $cachefile = 'files/cache/demogcolemp.cache'; // e.g. cache/index.php.cache
		$cachetime = 120 * 60 * 12 ; // 24 hours

		if(file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))){
		 
		  $table = file_get_contents($cachefile);
		  echo $table;
		  echo "<!--Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
		}
		else{
			ob_start();	 
			  //$conn = mysqli_connect('localhost','root','','netid');			
			   // Check connection
				if (!mysqli_connect_errno($conn)){
					 echo "<table id='datatable2' style='display:none'>";
					  echo "<thead>";
					       echo "<th></th>";
					       echo "<th>Colleges</th>";
					  echo "</thead>";
			          echo "<tbody>";
					$query = "SELECT * FROM college ORDER BY gidnumber";
					$college = mysqli_query($conn, $query);
					while($row2 = mysqli_fetch_array($college)){
						echo '<tr>';
						echo '<th>'.$row2['name'].'</th>';
						$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(o=".$row2['name'].")(title=employee))");
						echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
						echo '</tr>';
					}
					
					  echo "</tbody>";
					echo "</table>";
				
				
				}
				
				else echo "Cannot connect to the database";  
			
			    // Cache the contents to a file
				$cached = fopen($cachefile, 'w');
				fwrite($cached, ob_get_contents());
				fclose($cached);
		}	
   }
   
   function createofficeeemployeestable(){
     global $ldapconn, $ldapconfig, $conn;
	    $cachefile = 'files/cache/demogoffemp.cache'; // e.g. cache/index.php.cache
		$cachetime = 120 * 60 * 12 ; // 24 hours

		if(file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))){
		 
		  $table = file_get_contents($cachefile);
		  echo $table;
		  echo "<!--Cached ".date('jS F Y H:i', filemtime($cachefile))."-->";
		}
		else{
			ob_start();
			  //$conn = mysqli_connect('localhost','root','','netid');			
			   // Check connection
				if (!mysqli_connect_errno($conn)){
					 echo "<table id='datatable2' style='display:none'>";
					  echo "<thead>";
					       echo "<th></th>";
					       echo "<th>Offices</th>";
					  echo "</thead>";
			          echo "<tbody>";
					
					$query = "SELECT * FROM offices ORDER BY gidnumber";
					$office = mysqli_query($conn, $query);
					while($row2 = mysqli_fetch_array($office)){
						echo '<tr>';
						echo '<th>'.$row2['name'].'</th>';
						$sr = ldap_search($ldapconn, "ou=people,".$ldapconfig['basedn'], "(&(o=".$row2['name'].")(title=employee))");
						echo '<td>'.ldap_count_entries($ldapconn, $sr).'</td>';
						echo '</tr>';
					}
					  echo "</tbody>";
					echo "</table>";
				
				
				}
				
				else echo "Cannot connect to the database";  
		   
			// Cache the contents to a file
				$cached = fopen($cachefile, 'w');
				fwrite($cached, ob_get_contents());
				fclose($cached);
		}	
   }
   
    // function is the variable passed by ajax, it will then be the basis of which function to execute
    $function = $_POST['func']; 
   
   switch($function)
   {
	 case 'createcollegetable' :
	                $gidnumber = $_POST['gidnumber'];
					$name = $_POST['name'];
	                createcollegetable($gidnumber,$name);
	                break;
   
     case 'createallstudentstable' :
	                createallstudentstable();
	                break; 
	
	 case 'createallemployeestable' :
	                createallemployeestable();
	                break;
	
	case 'createcolleegemployeestable' :
	                createcolleegemployeestable();
	                break;
	
	case 'createofficeeemployeestable' :
	                createofficeeemployeestable();
	                break;
   }
?>