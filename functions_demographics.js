
function createpiechart(tablename, renderto, title, axis) 
            {
                /**
                * Visualize an HTML table using Highcharts. The top (horizontal) header
                * is used for series names, and the left (vertical) header is used
                * for category names. This function is based on jQuery.
                * @param {Object} table The reference to the HTML table to visualize
                * @param {Object} options Highcharts options
                */
               /**
 * Visualize an HTML table using Highcharts. The top (horizontal) header
 * is used for series names, and the left (vertical) header is used
 * for category names. This function is based on jQuery.
 * @param {Object} table The reference to the HTML table to visualize
 * @param {Object} options Highcharts options
 */
Highcharts.visualize = function (table, options) {
    // the categories
	var sliceNames = [];
	$('tbody th', table).each(function (i) 
	{
    	sliceNames.push(this.innerHTML);
	});

    // the data series
    options.series = [];
    $('tr', table).each(function (i) {
        var tr = this;
        $('th, td', tr).each(function (j) {
            if (j > 0) { // skip first column
                if (i == 0) { // get the name and init the series
                    options.series[j - 1] = {
                        name: this.innerHTML,
                        data: []
                    };
                } else { // add values
                    options.series[j - 1].data.push({name: sliceNames[i - 1], y: parseFloat(this.innerHTML)});
                }
            }
        });
    });

    var chart = new Highcharts.Chart(options);
}

var table = document.getElementById(tablename),
    options = {
        chart: {
            renderTo: renderto,
            type: 'pie'
        },
        title: {
            text: title
        },
        xAxis: {},
        yAxis: {
            title: {
                text: axis
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' + this.y + ' ' + this.point.name;
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    formatter: function () {
                        return '<b>' + this.point.name + '</b>: ' + this.percentage.toFixed(2) + ' %';
                    }
                }
            }
        }
    };

Highcharts.visualize(table, options);
}

function createbargraph(tablename, container, title, axis){
		  Highcharts.visualize = function(table, options) {
				            // the categories
				           options.xAxis.categories = [];
				            $('tbody th', table).each( function(i) {
				                options.xAxis.categories.push(this.innerHTML);
				           });
				    
				            // the data series
				            options.series = [];
				            $('tr', table).each( function(i) {
				                var tr = this;
				                $('th, td', tr).each( function(j) {
				                    if (j > 0) { // skip first column
				                        if (i == 0) { // get the name and init the series
				                            options.series[j - 1] = {
				                                name: this.innerHTML,
				                                data: []
				                            };
				                        } else { // add values
				                            options.series[j - 1].data.push(parseFloat(this.innerHTML));
				                        }
				                    }
				                });
				            });
				    
				            $(container).highcharts(options);
				        }
				    
				        var table = document.getElementById(tablename),
				        options = {
				            chart: {
				                type: 'column'
				            },
				            title: {
				                text: title
				            },
				            xAxis: {
								title: {
				                    text: 'Colleges and Offices'
				                }
				            },
				            yAxis: {
				                title: {
				                    text: axis
				                }
				            },
				            tooltip: {
				                formatter: function() {
				                    return '<b>'+ this.series.name +'</b><br/>'+
				                        this.y +' '+ this.x;
				                }
				            }
				        };
				    
				        Highcharts.visualize(table, options);
}






function generateCollegeTable(gidnumber, name){
    bootbox.dialog("Please wait...");
    $.ajax({
			type: "POST",
			url: 'functions_demographics.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			    gidnumber: gidnumber,
				name: name,
				func: 'createcollegetable' },
				success: function(data){
				  $("#table").html(data);
				  bootbox.hideAll(); 
				  createbargraph('datatable', '#container', 'Number of Students in ' + name, 'Students');		
				  createpiechart('datatable', 'container2', 'Distribution of Students in ' + name, 'Students');
				}
		}); 
}



function generateAllStudentsTable(){
    bootbox.dialog("Please wait...");
    $.ajax({
			type: "POST",
			url: 'functions_demographics.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			   
				func: 'createallstudentstable' },
				success: function(data){
				  $("#table").html(data);
				  bootbox.hideAll(); 
				  createbargraph('datatable', '#container', 'Number of All Students', 'Students');
				  createpiechart('datatable', 'container2', 'Distribution of Students All Students', 'Students');
				}
		}); 
}


function generateAllEmployeesTable(){
    bootbox.dialog("Please wait...");
    $.ajax({
			type: "POST",
			url: 'functions_demographics.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			   
				func: 'createallemployeestable' },
				success: function(data){
				  $("#table2").html(data);
				        bootbox.hideAll(); 
						createbargraph('datatable2', '#container3', 'Number of Employees in UPLB ', 'Employees');
						
						createpiechart('datatable2', 'container4', 'Distribution of All Employees', 'Employees');
				  
				}
		}); 
}


function generateCollegeEmployeesTable(){
    bootbox.dialog("Please wait...");
    $.ajax({
			type: "POST",
			url: 'functions_demographics.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			   
				func: 'createcolleegemployeestable' },
				success: function(data){
				  $("#table2").html(data);
				        bootbox.hideAll(); 
						createbargraph('datatable2', '#container3', 'Number of Employees by Colleges ', 'Employees');
						
						createpiechart('datatable2', 'container4', 'Distribution of Employees by Colleges', 'Employees');
				  
				}
		}); 
}

function generateOfficeEmployeesTable(){
    bootbox.dialog("Please wait...");
    $.ajax({
			type: "POST",
			url: 'functions_demographics.php',
			 //encodeURIComponent is to keep the '+' from changing to ' '.
			data: 
			{   
			   
				func: 'createofficeeemployeestable' },
				success: function(data){
				  $("#table2").html(data);
				        bootbox.hideAll(); 
						createbargraph('datatable2', '#container3', 'Number of Employees by Offices', 'Employees');
						
						createpiechart('datatable2', 'container4', 'Distribution of Employees by Offices', 'Employees');
				  
				}
		}); 
}