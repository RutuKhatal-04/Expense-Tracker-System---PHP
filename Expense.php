<?php

include 'connect.php';

// Check if the form is submitted
if(isset($_POST['submit'])){
   // Validate and sanitize input data
   $expense_category = filter_var($_POST['expense_category'], FILTER_SANITIZE_STRING);
   $expense_date = date('Y-m-d'); // Get current date
   $money_spent = filter_var($_POST['money_spent'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

   // Get user_id from cookie
   if (isset($_COOKIE['user_id'])) {
       $user_id = $_COOKIE['user_id'];

       // Insert data into the expense table
       $insert_expense = $conn->prepare("INSERT INTO `expense` (`user_id`, `category`, `date`, `expenditure`) VALUES (?, ?, ?, ?)");
       $insert_expense->bind_param("sssd", $user_id, $expense_category, $expense_date, $money_spent);
       if ($insert_expense->execute()) {
           $message = 'Expense recorded successfully!';
       } else {
           $message = 'Failed to record expense. Please try again.';
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
   <title>Expense Tracker</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>

<?php include 'user_header.php'; ?>

<section class="form-container">

   <form action="" method="post" enctype="multipart/form-data">
      <h3>Track Expenses</h3>
      <?php if(isset($message)): ?>
         <p><?php echo $message; ?></p>
      <?php endif; ?>
      <div class="flex">
         <div class="col">
            <p>Expense Category</p>
            <select name="expense_category" id="category">
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
         <div class="col">
            <p>Date</p>
            <input type="text" name="expense_date" value="<?= date('Y-m-d'); ?>" readonly class="box" required>
         </div>
         <div class="col">
            <p>Money Spent</p>
            <input type="number" name="money_spent" placeholder="Enter amount spent" class="box" required>
         </div>
      </div>
      <input type="submit" name="submit" value="Submit" class="btn">
   </form>

</section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>
   
</body>
</html>
