<?php
include ('captcha2.php');
?>

<div style="width:200px; display:none" id='changeSQForm'>
	<?php
      $dn = $entries[0]['dn'];
      $userpassword = $entries[0]['password'][0];	
      $sendtomail = $entries[0]['mail'][0] ;
	 if($changeown){  
	?>	     
		<form class="form-vertical" id="cpForm" name="changePwdForm" method="POST" action="javascript:changeownsq('<?php echo $dn?>')">
			<div class="control-group" style="margin-bottom:0px">
			     <label class="control-label">Current Password</label>
				 <div class="controls">
		        	 <input style="width:100%" type="password" id="pwd" name="pwd" required="required"/>
				</div>
			</div>	

			<div class="control-group">
			  	<label class="control-label" for="securityQuestion">Security Question</label>
			  	<div class="controls">
			       <select name="securityQuestion" id="inputSecurityQuestion"  class="input-large" onchange="changeSecQuestion()">
			       <option value='' disabled selected style='display:none;'>Select Security Question</option>
			       <option value="own">Create a security question</option>
			       <option value="What was your childhood nickname?">What was your childhood nickname?</option>
			       <option value="What is the name of your favorite childhood friend? ">What is the name of your favorite childhood friend? </option>
			       <option value="What was the last name of your third grade teacher?">What was the last name of your third grade teacher?</option>
			       <option value="What street did you live on in third grade?">What street did you live on in third grade?</option>
			       <option value="What is your grandmother's first name?">What is your grandmother's first name?</option>
			       <option value="What is your mother's middle name?">What is your mother's middle name?</option>
			       <option value="What time of the day were you born?">What time of the day were you born?</option>
			       <option value="What was your dream job as a child?">What was your dream job as a child?</option>
			       <option value="What is your preferred musical genre?">What is your preferred musical genre?</option>
			       <option value="What year did you graduate from High School?">What year did you graduate from High School?</option> 
				  </select>
				   <input type="text" id="hiddenSecurityQuestion" name="secQuestion" value=""/>
				</div>
			 </div>

			  <div class="control-group">
			    <label class="control-label" for="inputSecurityAnswer">Security Answer</label>
			    <div class="controls">
			      <div class="input-append">
				  <input class="input-large" name="securityAnswer" type="text" id="inputSecurityAnswer" placeholder="Security Answer" required>
			      </div>
				  <br/>
				</div>
			  </div>

			 <input type="hidden" id="sendtomail" name="sendtomail" value="<?php echo $sendtomail ?>">
			<input type="hidden" id="userpassword" name="userpassword" value="<?php echo $userpassword?>">
			<button class="btn btn-small btn-primary" type="submit" name="changeSQButton">Save <i class="icon-ok icon-white"></i> </button>

</form>
	<?php
	} 
	else { 
	?>
		<form class="form-vertical" id="cpForm" name="changePwdForm" method="POST" action="javascript:changeothersq('<?php echo $dn?>')">	

			<div class="control-group">
			  	<label class="control-label" for="securityQuestion">Security Question</label>
			  	<div class="controls">
			       <select name="securityQuestion" id="inputSecurityQuestion"  class="input-large" onchange="changeSecQuestion()">
			       <option value='' disabled selected style='display:none;'>Select Security Question</option>
			       <option value="own">Create a security question</option>
			       <option value="What was your childhood nickname?">What was your childhood nickname?</option>
			       <option value="What is the name of your favorite childhood friend? ">What is the name of your favorite childhood friend? </option>
			       <option value="What was the last name of your third grade teacher?">What was the last name of your third grade teacher?</option>
			       <option value="What street did you live on in third grade?">What street did you live on in third grade?</option>
			       <option value="What is your grandmother's first name?">What is your grandmother's first name?</option>
			       <option value="What is your mother's middle name?">What is your mother's middle name?</option>
			       <option value="What time of the day were you born?">What time of the day were you born?</option>
			       <option value="What was your dream job as a child?">What was your dream job as a child?</option>
			       <option value="What is your preferred musical genre?">What is your preferred musical genre?</option>
			       <option value="What year did you graduate from High School?">What year did you graduate from High School?</option> 
				  </select>
				   <input type="text" id="hiddenSecurityQuestion" name="secQuestion" value=""/>
				</div>
			 </div>

			  <div class="control-group">
			    <label class="control-label" for="inputSecurityAnswer">Security Answer</label>
			    <div class="controls">
			      <div class="input-append">
				  <input class="input-large" name="securityAnswer" type="text" id="inputSecurityAnswer" placeholder="Security Answer" required>
			      </div>
				  <br/>
				</div>
			  </div>

			<button class="btn btn-small btn-primary" type="submit" name="changeSQButton">Save <i class="icon-ok icon-white"></i> </button>

</form>
	<?php
	}
	?>
	

	
</div>


<script>

$("#hiddenSecurityQuestion").hide();

</script>