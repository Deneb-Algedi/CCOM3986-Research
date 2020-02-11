 
<!DOCTYPE html>
<html>
<?php 
      session_start();

      if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == false) {
        header("Location: index.php");
      }
  
      ?>

<head>
<link href="nav.css" rel="stylesheet" type="text/css">


<link rel="stylesheet" href="path/to/css/cal-heatmap.css" />
<script type="text/javascript" src="path/to/cal-heatmap.min.js"></script>

<script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css" />


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>


<title>Network Security Visualization</title>

</head>

<!-- The navigation menu -->

 <ul>
  <li><a href="home.php">Home</a></li>
  <li><a href="port-form.php">Stacked Bars Graph</a></li>
  <li><a href="cal-form.php">Calendar Heatmap</a></li>
  <li><a href="file-upload.php">Flow File Upload</a></li>
  <li style="float:right"><a class="active" href="logout.php">Log Out</a></li>
</ul> 

 <h2 id="h">Ports Calendar Heatmap Visual</h2>

<body>

<p> Calendar Heatmap of hourly incoming connections for the selected date. Click on a date for further details.</p>
	

<div>
<b><p id="hour"></p></b>
<p id="ports"></p>
</div>

<div id="cal-heatmap" >
<script type="text/javascript">

//receive clicked date
var url_string= window.location.href;
var url = new URL(url_string);
var date2str = url.searchParams.get("date");

var startDate = new Date(date2str);


//load json to variable
var DATA;


//function to convert data to calheat format 
function GAconverter(data) { 

	DATA = data;

	// first date of data !!!!
	var date1 = new Date("2018/01/01");

	//calculate object index from date1
	var difference = Math.abs(startDate - date1);
	var index = Math.ceil(difference / (1000 * 60 * 60 * 24)); 

	var i, results = {}

	//selected date 
	mydate = Object.keys(data[index])[0];

	//iterate through hours of selected date
	for (i=0; i<= 23; i++) {

		date = startDate.setHours(i);
		
		results[new Date(date).getTime()/1000] = data[index][mydate][i]['htotal'];

	}
	
	
	return results;

}

// hour onclick function for displaying top ports  
function Event(date, nb) {


	var mydate = new Date(date.getFullYear(), date.getMonth(), date.getDate());

	// first date of data !!!!
	var date1 = new Date("2018/01/01");

	//calculate object index from date1
	var difference = Math.abs(mydate - date1);
	var index = Math.ceil(difference / (1000 * 60 * 60 * 24)); 

	hour = date.getHours();
	date =  Object.keys(DATA[index])[0];

	//object of ports and counts for the selected hour
	var ports = DATA[index][date][hour];

	//remove htotal to not effect max
	delete ports['htotal'];

	//sort ports 
	var keys = Object.keys(ports);
	keys.sort(function(a,b){
	    return ports[b] - ports[a];
	  })
	
	//array of top ports
	var top5ports = keys.slice(0,5);

	$("#hour").html("Top Ports for hour: " + hour)

	$("#ports").html(top5ports[0] + ":" + ports[top5ports[0]] + ", " + top5ports[1] + ":" + ports[top5ports[1]] + ", " + top5ports[2] + ":" + ports[top5ports[2]] + ", " + top5ports[3] + ":" + ports[top5ports[3]] + ", " + top5ports[4] + ":" + ports[top5ports[4]] + ".")
	

}


var cal = new CalHeatMap();
cal.init({
	//itemSelector: "#example-j",
	domain: "day",
	subDomain: "hour",
	data: "ports-hourlyDaily.json",
	dataType: "json",
	cellSize: 50, subDomainTextFormat: "%-H",
	domainMargin: 25,
	domainGutter: -25,
	start: startDate,
	afterLoadData: GAconverter,
	range: 1,
	itemName: "count",
	legendMargin: [-30, 0, 0, 20],
	legendCellSize: 20,
	legend: [10000, 20000, 30000, 40000, 50000, 60000, 70000, 80000, 90000],
	legendColors: {
		empty: "#ededed",
		min: "#40ffd8",
		max: "#f20013"
	},
	onClick: Event


});

</script>
</div>

</body>
</html>
