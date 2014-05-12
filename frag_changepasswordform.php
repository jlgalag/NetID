<div style="width:200px; display:none" id='changePwdForm'>
	<?php
      $dn = $entries[0]['dn'];
      $userpassword = $entries[0]['userpassword'][0];	
      $sendtomail = $entries[0]['mail'][0] ;
	 if($changeown){  // flag to determine if the user is changing his own password or not
					  // if he is changing his own password, a current password field is needed
	?>	     
		<form class="form-vertical" id="cpForm" name="changePwdForm" method="POST" action="javascript:changeownpassword('<?php echo $dn?>')">
			<div class="control-group" style="margin-bottom:0px">
			     <label class="control-label">Current Password</label>
				 <div class="controls">
		        	 <input style="width:100%" type="password" id="pwd" name="pwd" required="required"/>
				</div>
			</div>
			<div class="control-group" style="margin-bottom:0px">	
			    <label class="control-label">New Password</label>
			    <div class="controls">
			         <input style="width:100%" type="password" id="newPwd" name="newPwd" required="required"/>
			    </div>
			</div>	
	        <div class="control-group" style="margin-bottom:0px">	
			    <label class="control-label">Confirm Password</label>
	  		    <div class="controls">
					<input style="width:100%" type="password" id="conPwd" name="conPwd" required="required"/>
			    </div>
			</div>	
			<input type="hidden" id="sendtomail" name="sendtomail" value="<?php echo $sendtomail ?>">
			<input type="hidden" id="userpassword" name="userpassword" value="<?php echo $userpassword?>">
			<button class="btn btn-small btn-primary" type="submit" name="changePwdButton">Save <i class="icon-ok icon-white"></i> </button>
	    </form>
	<?php
	} 
	else { 
	?>
		<form class="form-vertical" id="cpForm" name="changePwdForm" method="POST" action="javascript:changeotherpassword('<?php echo $dn?>')">
			<div class="control-group" style="margin-bottom:0px">	
			    <label class="control-label">New Password</label>
			    <div class="controls">
			         <input style="width:100%" type="password" id="newPwd" name="newPwd" required="required"/>
			    </div>
			</div>	
	        <div class="control-group" style="margin-bottom:0px">	
			    <label class="control-label">Confirm Password</label>
	  		    <div class="controls">
					<input style="width:100%" type="password" id="conPwd" name="conPwd" required="required"/>
			    </div>
			</div>	
			<input type="hidden" id="sendtomail" name="sendtomail" value="<?php echo $sendtomail ?>">
			<input type="hidden" id="userpassword" name="userpassword" value="<?php echo $userpassword?>">
			<input type="hidden" id="username" name="username" value="<?php echo $uid?>">
			<button class="btn btn-small btn-primary" type="submit" name="changePwdButton">Save <i class="icon-ok icon-white"></i> </button>
	    </form>
	<?php
	}
	?>
	
</div>

