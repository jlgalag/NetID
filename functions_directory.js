function viewstudentdirectory(name){

    $.ajax({
			type: "POST",
			url: 'functions_directory.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			    name: name,
				func: 'viewstudentlist' },
				success: function(data){
				   $('#studdirlist').html(data);
				}
		}); 

}

function viewemployeedirectory(gidnumber, name){

    $.ajax({
			type: "POST",
			url: 'functions_directory.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			    gidnumber: gidnumber,
				name: name,
				func: 'viewemployeelist' },
				success: function(data){
				   $('#empdirlist').html(data);
				}
		}); 

}