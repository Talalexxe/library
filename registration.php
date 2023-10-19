<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>

    <h2>User Registration</h2>
    <form action="registration.php" method="post">
        <label for="first_name">First Name</label><br>
        <input type="text" name="first_name" id="first_name" required><br><br>
        <label for="last_name">Last Name</label><br>
        <input type="text" name="last_name" id="last_name" required><br><br>
        <label for="username">Username</label><br>
        <input type="text" name="username" id="username" required><br><br>
        <label for="email">Email Address</label><br>
        <input type="email" name="email" id="email" required placeholder="john@example.com"><br><br>
        <label for="phone">Phone Number</label><br>
        <input type="text" name="phone" id="phone" required placeholder="(876)123-4567"><br><br>
        <label for="password">Password</label><br>
        <input type="password" name="password" id="password" required><br><br>
        <input type="submit" value="Register Account">
    </form>

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
                header("Location: main.php");
            }else{
                echo "Registration Failed";
            }
        }
    ?>
    
</body>
</html>