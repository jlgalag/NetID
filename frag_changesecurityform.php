<div style="width:200px; display:none" id='changeScrtyForm'>
	<?php
      $dn = $entries[0]['dn'];
      $userpassword = $entries[0]['userpassword'][0];	
      $sendtomail = $entries[0]['mail'][0] ;
	 if($changeown){  // flag to determine if the user is changing his own password or not
					  // if he is changing his own password, a current password field is needed
	?>	     
		<form class="form-vertical" id="csForm" name="changeScrtyForm" method="POST" action="javascript:changeownsecurity('<?php echo $dn?>')">
			<div class="control-group" style="margin-bottom:0px">
			     <label class="control-label">Enter Password</label>
				 <div class="controls">
		        	 <input style="width:100%" type="password" id="pwd" name="pwd" required="required"/>
				</div>
			</div>
			<input type="hidden" id="sendtomail" name="sendtomail" value="<?php echo $sendtomail ?>"/>
			<input type="hidden" id="userpassword" name="userpassword" value="<?php echo $userpassword?>"/>
			<button class="btn btn-small btn-primary" type="submit" name="changeScrtyButton">Confirm <i class="icon-ok icon-white"></i> </button>
	    </form>
	    <div style="width:200px; display:none" id='changeSecurity'>
	    	<input type="text" placeholder="security answer"/>
	    </div>
	<?php
	} 
	else { 
	?>
		<form class="form-vertical" id="csForm" name="changeScrtyForm" method="POST" action="javascript:changeothersecurity('<?php echo $dn?>')">

			<input type="hidden" id="sendtomail" name="sendtomail" value="<?php echo $sendtomail ?>">
			<input type="hidden" id="userpassword" name="userpassword" value="<?php echo $userpassword?>">
			<input type="hidden" id="username" name="username" value="<?php echo $uid?>">
			<button class="btn btn-small btn-primary" type="submit" name="changeScrtyButton">Save <i class="icon-ok icon-white"></i> </button>
	    </form>
	<?php
	}
	?>
	
</div>

