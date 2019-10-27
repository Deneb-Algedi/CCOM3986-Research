 
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



<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>


<link rel="stylesheet" href="path/to/css/cal-heatmap.css" />
<script type="text/javascript" src="path/to/cal-heatmap.min.js"></script>

<script type="text/javascript" src="//d3js.org/d3.v3.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/cal-heatmap/3.3.10/cal-heatmap.css" />

<title>Network Security Visualization</title>

</head>

<!-- The navigation menu -->

 <ul>
  <li><a href="home.php">Home</a></li>
  <li><a href="port-form.php">Ports Stacked Bars</a></li>
  <li><a href="cal-form.php">Ports Calendar View</a></li>
  <li style="float:right"><a class="active" href="logout.php">Log Out</a></li>
</ul> 

 <h2 id="h">Ports Calendar Heatmap Visual</h2>

<body>
	
<div id="cal-heatmap"></div>
<script type="text/javascript">

var url_string= window.location.href;
var url = new URL(url_string);
var date2str = url.searchParams.get("syear");
var startDate = new Date(date2str, 0, 1);

//var startDate = new Date(2017, 0, 1);
var startTimestamp = new Date(2017, 0, 1).getTime()/1000;


function GAconverter(data) {
		
	var i, total, results = {};
	for(i = 0, total = data.length; i < total; i++) {
		myDate = data[i]["date"];
		myDate = myDate.split("/");
		var newDate = myDate[1]+"/"+myDate[2]+"/"+myDate[0];
		results[new Date(newDate).getTime()/1000] = data[i]["value"];
		}
	//console.log(results);
	return results;
}


var cal = new CalHeatMap();
cal.init({
	//itemSelector: "#example-j",
	domain: "month",
	subDomain: "day",
	data: "total-sum.json",
	dataType: "json",
	cellSize: 20,
	domainMargin: 25,
	domainGutter: -25,
	start: startDate,
	afterLoadData: GAconverter,
	range: 12,
	itemName: "count",
	//legendHorizontalPosition: "#left",
	//legendVerticalPosition: "#top",
	//legendOrientation: "#horizontal",
	legendMargin: [-15, 0, 0, 0],
	legendCellSize: 20,
	legend: [10000, 20000, 30000, 40000, 50000, 60000, 70000, 80000, 90000],
	legendColors: {
		empty: "#ededed",
		min: "#40ffd8",
		max: "#f20013"
	}
});

</script>
</body>
</html>
