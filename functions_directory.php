<?php
   require 'ldap_config.php'; 
   require 'frag_sessions.php';
   
   
   function viewstudentlist($name){
       global $ldapconn;
	   
	   $res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph", "(ou=".$name.")");
        ldap_sort($ldapconn, $res, "cn");
	   $entries = ldap_get_entries($ldapconn, $res);
	   echo "<h4>".$name."</h4>";
	   echo "<img src='img/horredline.jpg'/><br/>";
		
		if($res){  
	       
		  if(!($entries["count"] > 0))
			echo "No Results Found";
		  else{
		    echo  "<script type='text/javascript'>
			        $(document).ready(function () {
			           $('#pagination').smartpaginator({ 
							totalrecords: ".$entries['count'].", 
							recordsperpage: 20, 
							datacontainer: 'studtablelist', 
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
			    </script>";
			echo  '<div id="pagination" class="pagination pagination-small">
				   </div>	';
					
		    echo '<table class="table" id="studtablelist" style="font-size:14px;">
					<tr>
						<th>Name</th>
					</tr>';
									    
					for($i=0; $i<count($entries)-1; $i++){
						echo "<tr>";
								echo "<td>".$entries[$i]['cn'][0]."</td>";														  
					    echo "</tr>";
					}
			echo '</table>';
		  }
	   }
        else      echo ldap_error($ldapconn );
   }
   
    function viewemployeelist($gidnumber, $name){
       global $ldapconn;
	   
	   $res = ldap_search($ldapconn, "ou=people,dc=uplb,dc=edu,dc=ph", "(gidnumber=".$gidnumber.")");
        ldap_sort($ldapconn, $res, "cn");
	   $entries = ldap_get_entries($ldapconn, $res);
	   echo "<h4>".$name."</h4>";
	   echo "<img src='tools/img/horredline.jpg'/><br/>";
	   if($res){  
	       
		  if(!($entries["count"] > 0))
			echo "No Results Found";
		  else{
		    echo  "<script type='text/javascript'>
			        $(document).ready(function () {
			           $('#emppagination').smartpaginator({ 
							totalrecords: ".$entries['count'].", 
							recordsperpage: 20, 
							datacontainer: 'emptablelist', 
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
			    </script>";
			echo  '<div id="emppagination" class="pagination pagination-small">
				   </div>	';
					
		    echo '<table class="table" id="emptablelist" style="font-size:14px;">
					<tr>
						<th>Name</th>
					</tr>';
									    
					for($i=0; $i<count($entries)-1; $i++){
						echo "<tr>";
								echo "<td>".$entries[$i]['cn'][0]."</td>";														  
					    echo "</tr>";
					}
			echo '</table>';
		  }
	   }
        else      echo ldap_error($ldapconn );
   }
   
    // function is the variable passed by aajax, it will then be the basis of which function to execute
    $function = $_POST['func']; 
   
   switch($function)
   {
	 case 'viewstudentlist' :
	                $name = $_POST['name'];
	                viewstudentlist($name);
	                break;
     
     case 'viewemployeelist' :
	                $gidnumber = $_POST['gidnumber'];
					$name = $_POST['name'];
	                viewemployeelist($gidnumber, $name);
	                break;  	 
   }
?>