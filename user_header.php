<?php
include 'connect.php';
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id']:'';

echo"user_id : ".$user_id;
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
      <div class="message">
         <span>' . $msg . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
    }
}
?>

<header class="header" style="background-color:white;">

    <section class="flex">

        <a href="home.php" class="logo" style="color:black;">Expense Tracker System</a>

        

        <div class="icons">
            <!-- <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div> -->
            <div id="user-btn" class="fas fa-user"></div>
            <div id="toggle-btn" class="fas fa-sun"></div>
        </div>

        <div class="profile" style="border:1px solid black;">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->bind_param("s", $user_id);
            $select_profile->execute();
            $result_profile = $select_profile->get_result();

            if ($result_profile->num_rows > 0) {
                $fetch_profile = $result_profile->fetch_assoc();
                ?>
                <img src="uploaded files/<?= $fetch_profile['image']; ?>" alt="">
                <h3><?= $fetch_profile['name']; ?></h3>
                <span>user</span>
                
                <div class="flex-btn">
                    <!-- <a href="login.php" class="option-btn">login</a> -->
                    <!-- <a href="register.php" class="option-btn">register</a> -->
                </div>
                <a href="logout.php" onclick="return confirm('logout from this website?');"
                   class="delete-btn">logout</a>
            <?php } else { ?>
                <h3>please login or register</h3>
                <div class="flex-btn">
                    <a href="login.php" class="option-btn">login</a>
                    <a href="register.php" class="option-btn">register</a>
                 </div>
            <?php } ?>
        </div>

    </section>

</header>

<!-- header section ends -->

<!-- side bar section starts  -->

<div class="side-bar" style="background-color:black;">

    <div class="close-side-bar">
        <i class="fas fa-times"></i>
    </div>

    <div class="profile">
        <?php
        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_profile->bind_param("s", $user_id);
        $select_profile->execute();
        $result_profile = $select_profile->get_result();

        if ($result_profile->num_rows > 0) {
            $fetch_profile = $result_profile->fetch_assoc();
            ?>
            <img src="uploaded files/<?= $fetch_profile['image']; ?>" alt="">
            <h3 style="font-size:30px; color:white;"><?= $fetch_profile['name']; ?></h3>
            <span style="font-size:20px; color:red;"><b>User<b></span>
            
        <?php } else { ?>
            <!-- <h3>please login or register</h3>
            <div class="flex-btn" style="padding-top: .5rem;">
                <a href="login.php" class="option-btn">login</a>
                <a href="register.php" class="option-btn">register</a>
            </div> -->
        <?php } ?>
    </div>

    <nav class="navbar">
        <a href="home.php?user_id=<?=$user_id?>"><i class="fas fa-home"></i><span style="font-size:25px; color:red;">Home</span></a>
        <a href="Expense.php?user_id=<?=$user_id?>"><i class="fas fa-money-bill-alt"></i><span style="font-size:25px; color:red;">Expense</span></a>
        <a href="Report.php?user_id=<?=$user_id?>"><i class="fas fa-chart-pie"></i><span style="font-size:25px; color:red; ">Report</span></a>
        
    </nav>

</div> 

<!-- side bar section ends -->
