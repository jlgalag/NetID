
<!-- Edit Email Address Form
   When the form is submitted, the system calls the function editmail() in the function.js and pass the dn of the user
-->
<div id='editMailForm' style='display: none'>
	<form class="form-vertical" id="emForm" name="editMailForm" method="POST" action="javascript:editmail('<?php echo $entries[0]['dn']?>');">   
        <div class="control-group">	
	        <label class="control-label">Enter new email address</label>
		    <div class="controls">
	            <input type="text" aria-invalid="false" id="newMail" name="newMail"> 
	        </div>
		</div>	 
		<button class="btn btn-small btn-primary" type="submit" name="editMailButton">Save <i class="icon-ok icon-white"></i> </button>
    </form>  
</div>