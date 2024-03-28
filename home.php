<?php

include 'connect.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Now, you can use $user_id_from_url in your page as needed
    // For example, you can display it:
    echo "User ID from URL: " . $user_id;
} else {
    // Redirect or handle the case when user_id is not present in the URL
    header('location: Loginform.php');
    exit;
}
    // Continue with the rest of your code
echo "Welcome, User ID: $user_id ";
$data = array();

// Fetching total expenditure for each category
$sql = "SELECT category, SUM(expenditure) AS total_expenditure FROM `expense` GROUP BY category";
$result = mysqli_query($conn, $sql);

// Adding data to the array
while($row = mysqli_fetch_assoc($result)) {
    $data[] = array($row['category'], (float)$row['total_expenditure']); // Casting expenditure to float
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
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

<?php include 'user_header.php'; ?>


<?php echo"user_id:".$user_id;?>
<div id="piechart" style="width: 100%; height: 500px;"></div>
<?php include 'footer.php'; ?>
<!-- footer section ends -->

<!-- custom js file link  -->
<script src="script.js"></script>
</body>
</html>