<?php
include 'connect.php';
$total_expenditure = 0;

// Check if the form is submitted
if(isset($_POST['submit'])){
    $date_range = $_POST['date_range'];
    $from_date = "";
    $to_date = "";
    $category = $_POST['category'];

    // Set date range based on user selection
    switch ($date_range) {
        case 'today':
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
            break;
        case 'last_month':
            $from_date = date('Y-m-01', strtotime('last month'));
            $to_date = date('Y-m-t', strtotime('last month'));
            break;
        case 'last_six_months':
            $from_date = date('Y-m-d', strtotime('-6 months'));
            $to_date = date('Y-m-d');
            break;
        case 'last_year':
            $from_date = date('Y-01-01', strtotime('last year'));
            $to_date = date('Y-12-31', strtotime('last year'));
            break;
        case 'custom':
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            break;
    }

    // Get user_id from cookie
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];

        // Query to fetch expenses within the given date range and category
        if($category == 'All') {
            $select_expenses = $conn->prepare("SELECT * FROM `expense` WHERE `user_id` = ? AND `date` BETWEEN ? AND ?");
            $select_expenses->bind_param("sss", $user_id, $from_date, $to_date);
        } else {
            $select_expenses = $conn->prepare("SELECT * FROM `expense` WHERE `user_id` = ? AND `category` = ? AND `date` BETWEEN ? AND ?");
            $select_expenses->bind_param("ssss", $user_id, $category, $from_date, $to_date);
        }
        
        $select_expenses->execute();
        $result_expenses = $select_expenses->get_result();

        // Check if there are any errors in the query execution
        if (!$result_expenses) {
            die("Error: " . $conn->error);
        }
    } else {
        $message = 'User ID not found. Please login again.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Expenses</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
   <link rel="stylesheet" href="style.css"/>
</head>
<body>

<?php include 'user_header.php'; ?>
<div class="container" style="overflow-y: auto; max-height: calc(100vh - 80px);"> <!-- Subtracting header height from viewport height -->
<section class="form-container" style="float: top; width: 80%; margin-bottom:-20px; margin-top:-80px"> <!-- Aligned to top-left and increased width -->
    <form action="" method="post">
    <h3 >View Expenses</h3> <!-- Changed to h2 and increased font weight -->
    <div class="flex">
        <div class="col">
        <p  style="display: inline;
    width: 50px; /* Adjust as needed */
    margin-right: 10px; font-size:20px; color:black;">Select Date Range:</p>
        <select name="date_range" id="date_range">
            <option value="today">Today</option>
            <option value="last_month">Last Month</option>
            <option value="last_six_months">Last 6 Months</option>
            <option value="last_year">Last Year</option>
           <option value="custom">Custom</option>
        </select>
</div>
<div class="col">
        <p  style="display: inline;
    width: 50px; /* Adjust as needed */
    margin-right: 10px; font-size:20px; color:black;">Select Category:</p>
       <select name="category" id="category">
        <option value="All">All</option>
                <option value="Food & Dining">Food & Dining</option>
                <option value="health care & medicine">health care & medicine</option>
                <option value="Transportation">Transportation</option>
                <option value="Housing rent">Housing rent</option>
                <option value="House Expense">House Expense</option>
                <option value="Electronics Bills">Electronics Bills</option>
                <option value="Shopping">Shopping</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Education">Education</option>
                <option value="Travel">Travel</option>
                <option value="Grocery">Grocery</option>
                <option value="Insurance">Insurance</option>
                <option value="Taxes">Taxes</option>

            </select>
</div>
        <div class="col" id="custom_date_range" >
    <label for="from_date" style="display:block;
    width: 50px; /* Adjust as needed */
    margin-right: 10px; font-size:20px;">From:</label>
    <input style="width: 150px;" type="date" id="from_date" name="from_date">
</div>
    <div class="col" id="custom_date_range" >
    <label for="to_date" style="display: block;
    width: 50px; /* Adjust as needed */
    margin-right: 10px; font-size:20px;">To:</label>
    <input style="width: 150px;"type="date" id="to_date" name="to_date">
</div>

</div>
        <input type="submit" name="submit" value="Submit" class="btn"> <!-- Increased width and styled button -->
    </form>
</section>


<section class="assignments" style="margin-top: -160px; overflow-y: auto; max-height:200px; width: 100%; display: block;">
    <?php if(isset($result_expenses) && $result_expenses->num_rows > 0): ?>
        <h3 style="font-size: 25px;"><b> Expense Track from <?php echo $from_date ?> To  <?php echo $to_date ?></b></h3>
        <table border="1" style="border-collapse: collapse; width: 100%;"> <!-- Increased font size -->
            <thead>
                <tr style="font-weight: bold;">
                   
                    <th style="border: 2px solid #333; font-size: 20px;">Category</th>
                    <th style="border: 2px solid #333; font-size: 20px;">Date</th>
                    <th style="border: 2px solid #333; font-size: 20px;">Expenditure</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result_expenses->fetch_assoc()): ?>
                    <?php $total_expenditure+=$row['expenditure'];?>
                    <tr>
                        
                        <td style="border: 2px solid #333; font-size: 18px; "><?php echo $row['category']; ?></td>
                        <td style="border: 2px solid #333; font-size: 18px; "><?php echo $row['date']; ?></td>
                        <td style="border: 2px solid #333; font-size: 18px; "><?php echo $row['expenditure']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <h3 style="font-size: 25px;">Total Expense : <?php echo $total_expenditure ?> </h3>
    <?php elseif(isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</section>
    </div>
<?php include 'footer.php'; ?> 

<script>
    document.getElementById('date_range').addEventListener('change', function() {
        var customRange = document.getElementById('custom_date_range');
        if (this.value === 'custom') {
            customRange.style.display = 'flex'; // Changed to flex
        } else {
            customRange.style.display = 'none';
        }
    });
</script>
<script src="script.js"></script>

</body>
</html>
