<?php
include 'connect.php';

// Initialize an empty array to store category and total expenditure
$data = array();

// Fetching total expenditure for each category
$sql = "SELECT category, SUM(expenditure) AS total_expenditure FROM `expense` GROUP BY category";
$result = mysqli_query($conn, $sql);

// Adding data to the array
while($row = mysqli_fetch_assoc($result)) {
    $data[] = array($row['category'], (float)$row['total_expenditure']); // Casting expenditure to float
}

// Close the database connection
mysqli_close($conn);
?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        // Convert PHP array to JavaScript array
        var data = <?php echo json_encode($data); ?>;

        // Create the data table
        var chartData = google.visualization.arrayToDataTable([
          ['Category', 'Expenditure'],
          <?php foreach($data as $row): ?>
            ['<?php echo $row[0]; ?>', <?php echo $row[1]; ?>],
          <?php endforeach; ?>
        ]);

        // Set chart options
        var options = {
          title: 'Expenditure by Category'
        };

        // Instantiate and draw the chart
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(chartData, options);
      }
    </script>
  </head>
  <body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
  </body>
</html>
