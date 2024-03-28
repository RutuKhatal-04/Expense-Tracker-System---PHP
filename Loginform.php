<?php
include 'connect.php';

if(isset($_COOKIE['user_id'])){
  $user_id=$_COOKIE['user_id'];

}
else{
  $user_id='';
}


if(isset($_POST['submit'])){
  $email=$_POST['email'];
  $pass=$_POST['pass'];
  $select_query = mysqli_query($conn,"SELECT id FROM `users` WHERE email = '$email' ");
  $res = mysqli_num_rows($select_query);
  if($res>0){
    $data=mysqli_fetch_array($select_query);
    $user_id = $data['id'];
    $user_name = $data['name'];

    setcookie('user_id',$user_id,time() + 60*60*20*30,'/');
    header('location:home.php?user_id='.$user_id);
    // <!-- exit(); -->
  }
  else{
    $msg="Invalid Email";
  }
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <!-- Font Awesome CDN link -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="Loginform.css" />
  </head>

  <body>
    <div class="wrapper">
      <div>
        <section class="form-container">
          <form
            action=""
            method="post"
            enctype="multipart/form-data"
            class="login"
          >
            <h3>Welcome back!</h3>
           

            <?php
                    // Debugging statement
                    
                    // error_log('10. User ID: ' . $_COOKIE['user_id']);
                    ?>
            <p>Your email <span>*</span></p>
            <input
              type="email"
              name="email"
              placeholder="Enter your email"
              maxlength="50"
              required
              class="box"
            />
            <p>Your password <span>*</span></p>
            <input
              type="password"
              name="pass"
              placeholder="Enter your password"
              maxlength="20"
              required
              class="box"
            />
            <p class="link">
              Don't have an account? <a href="Registerform.php">Register now</a>
            </p>
            
            <input type="submit" name="submit" value="Login now" class="btn" />
          </form>
        </section>
      </div>
    </div>

    <!-- Custom JS file link -->
    <script src="js/script.js"></script>
  </body>
</html>
