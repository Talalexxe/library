<?php
    session_start();
    include 'config.php';

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $uname = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE Username = '$uname'";
        $query = mysqli_query($conn,$sql);

        if(mysqli_num_rows($query)==1){
            $user = mysqli_fetch_assoc($query);

            if($user['Password']!=$password){
                echo "Password incorrect. Try again";
            }else{
                $_SESSION['UserID'] = $user['UserID'] && $_SESSION['currentUserID'] = $currentUserID;;
                header("Location:user-dashboard.php");
            }
        }else{
            echo "Incorrect username. Please try again";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System | Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background-image: url('image/log-bg.jpg'); background-size: cover;" >
    <div class="log-container">
        <div class="login-box">
            <h2 class="login-header">User Login</h2>
            <form class="login-form" action="login.php" method="post">
                <label for="username">Username</label><br>
                <input type="text" name="username" id="username" required><br><br>
                <label for="password">Password</label><br>
                <input type="password" name="password" id="password" required><br><br>
                <input type="submit" value="Login"> <br><br>
                <p>Don't have an account? <a href="registration.php">Register</a></p>
            </form>
        </div>
    </div> 
</body>
</html>