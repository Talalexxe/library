<?php
    session_start();
    include 'config.php';

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $fname = $_POST["first_name"];
        $lname = $_POST["last_name"];
        $uname = $_POST["username"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $password = $_POST["password"];


        $sql = "INSERT INTO users(FirstName,LastName,Username,Email,PhoneNumber,Password) VALUES ('$fname','$lname','$uname','$email','$phone','$password')";

        $query = mysqli_query($conn,$sql);
        
        if($query){
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Error registering user: " . mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System | Registration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-image: url('image/reg-bg.jpg'); background-size: cover;">
    <div class="reg-container">
        <div class="registration-box">
            
        <h2 class="registration-header">Registration</h2>
                   
            <form class="registration-form" action="registration.php" method="post">
                <input type="text" name="first_name" id="first_name" placeholder="First Name" required><br><br>
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" required><br><br>
                <input type="text" name="username" id="username"placeholder="Username" required><br><br>
                <input type="email" name="email" id="email" required placeholder="john@example.com"><br><br>
                <input type="text" name="phone" id="phone" required placeholder="(876)123-4567"><br><br>
                <input type="password" name="password" id="password" placeholder="Password" required><br>
                <input type="submit" value="Register Account" class="pull-center" ><br><br>
                <p>Already have an account? <a href="login.php">Login</a></p>
            </form>
        </div>
        
        
    </div>   
</body>
</html>