
<!-- Edit Email Address Form
   When the form is submitted, the system calls the function editmail() in the function.js and pass the dn of the user
-->

<?php
include ('captcha.php');
?>


<div id='editMailForm' style='display: none'>
	<form class="form-vertical" id="emForm" name="editMailForm" action="javascript:editmail('<?php echo $entries[0]['dn']?>');" method="POST" onsubmit="return validatejs();">   
        <div class="control-group">	
	        <label class="control-label">Enter new email address</label>
		    <div class="controls">
	            <input type="text" aria-invalid="false" id="newMail" name="newMail"> 
	        </div>
		</div>

	<img src='image.php'/><br/>
	Enter code above: <input type="text" id="lolcode" name="lolcode"><span name="prompt"></span><br/>

		<button class="btn btn-small btn-primary" type="submit" name="editMailButton">Save <i class="icon-ok icon-white"></i> </button>
    </form>  
</div>

<script type="text/javascript" language="javascript">

var vercodejs = "<?php echo $_SESSION['vercode']; ?>";


function validatejs(){

	if(vercodejs == document.getElementById("lolcode").value){
        document.getElementsByName('prompt')[0].innerHTML='';
        return true;
	}
	else{
        document.getElementsByName('prompt')[0].innerHTML='Text does not match.';
        return false;
	}
}

</script>
