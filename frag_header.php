 

 <?php
   // if the user changed his role (ex. from employee to OCS)
   function changerole($newrole){
       $_SESSION['activerole'] = $newrole; 
	   echo $newrole;
   }
 ?>
 
<div class="navbar navbar-static-top">
   <div class="navbar-inner">
        <div class="nav-collapse collapse">
           <img class="" src="tools/img/logoheaderblk.png"/> 
		   <span style="position:absolute; bottom:0; right:0;">
			
			  You are logged in as <?php echo $userUid?>     
			 <ul class="dropdown-menu pull-right">
			    <li class="nav-header">Role</li>
				<?php
				   // show list of roles from the session data
				   for($i = 0; $i<count($role); $i++){
				?>
				    <li><a onclick="javascript:changeactiverole('<?php echo $role[$i] ?>');"><?php echo $role[$i]?></a></li>
				   
			    <?php } ?>
				<li class="divider"></li>
				<li><a href="directory.php">Directory</a></li>
				
				<li class="divider"></li>
				<li><a href="logout.php">Logout</a></li>
				
			</ul>	
		
           <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            (<?php echo $activerole?>)
           </a>
   

</span>

		</div><!--/.nav-collapse -->
    </div>
</div>

