<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MARY BROWN LIBRARY</title>
</head>
<body>

    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username</label><br>
        <input type="text" name="username" id="username" required><br><br>
        <label for="password">Password</label><br>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" value="Login">
    </form><br><br>

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
                    $_SESSION['UserID'] = $user['UserID'];
                    header("Location:main.php");
                }
            }else{
                echo "Incorrect username. Please try again";
            }
        }

    ?>
    <br><br>

<a href="registration.php">Click here to register a new account</a>
    
</body>
</html>