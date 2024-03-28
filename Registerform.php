<?php

include 'connect.php';
$user_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id']:'';


if(isset($_POST['submit'])){
$id = unique_id();
$name = $_POST['name'];
$name=filter_var($name,FILTER_SANITIZE_STRING);
$email = $_POST['email'];
$email = filter_var($email,FILTER_SANITIZE_STRING);
 $pass = $_POST['pass']; // Don't sanitize before hashing
    $cpass = $_POST['cpass']; // Don't sanitize before hashing

    // Hash the password
    $hashed_password = sha1($pass);


$image = $_FILES['image']['name'];
$image = filter_var($image,FILTER_SANITIZE_STRING);
$ext = pathinfo($image , PATHINFO_EXTENSION);
$rename = unique_id().'.'.$ext;
$image_size = $_FILES['image']['size'];
$image_tmp_name=$_FILES['image']['tmp_name'];
$image_folder='uploaded files/'.$rename;

$select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
$select_user->bind_param("s",$email);
$select_user->execute();
$select_user->store_result();

if($select_user->num_rows > 0){
  $message[]='Email already taken!';
}
else{
  if($pass!=$cpass){
    $message[]='Password and confirm password should be same';
  }
  else{
    $insert_user = $conn->prepare("INSERT INTO `users`(id,name,email,password,image)VALUES(?,?,?,?,?)");
    $insert_user->bind_param("sssss",$id,$name,$email,$hashed_password,$rename);
    $insert_user->execute();
    move_uploaded_file($image_tmp_name, $image_folder);
    setcookie('user_id',$id,time()+60*60*20*30,'/');
    // <!-- header('location:Loginform.html?user_id') -->
  }
  
}

}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home</title>

    <!-- Font Awesome CDN link -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="Registerform.css" />
  </head>

  <body>
    <div class="wrapper">
      <div>
        <section class="form-container">
          <form
            class="register"
            action=""
            method="post"
            enctype="multipart/form-data"
          >
            <h3>Create Account</h3>
            <div class="flex">
              <div class="col">
                <p>Your Name <span>*</span></p>
                <input
                  type="text"
                  name="name"
                  placeholder="Enter your name"
                  maxlength="50"
                  required
                  class="box"
                />
                <p>Your Email <span>*</span></p>
                <input
                  type="email"
                  name="email"
                  placeholder="Enter your email"
                  maxlength="50"
                  required
                  class="box"
                />
              </div>
              <div class="col">
                <p>Your Password <span>*</span></p>
                <input
                  type="password"
                  name="pass"
                  placeholder="Enter your password"
                  maxlength="20"
                  required
                  class="box"
                />
                <p>Confirm Password <span>*</span></p>
                <input
                  type="password"
                  name="cpass"
                  placeholder="Confirm your password"
                  maxlength="20"
                  required
                  class="box"
                />
              </div>
            </div>
            <p>Select Picture <span>*</span></p>
            <input
              type="file"
              name="image"
              accept="image/*"
              required
              class="box"
            />
            <p class="link">
              Already have an account? <a href="Loginform.php">Login now</a>
            </p>
            <input
              type="submit"
              name="submit"
              value="Register now"
              class="btn"
            />
          </form>
        </section>
      </div>
    </div>

    <!-- Custom JS file link -->
    <!-- <script src="js/script.js"></script> -->
  </body>
</html>
