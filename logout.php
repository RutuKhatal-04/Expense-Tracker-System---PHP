<?php

   include 'connect.php';

   setcookie('admin_email', '', time() - 1, '/');

   header('location:index.html');

?>