<?php
session_start();
include 'config.php';

$errorMessage = "";
$successMessage = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT UserID, Password, UserRole FROM users WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $dbPassword = $row['Password'];
            $userRole = $row['UserRole'];
            $userId = $row['UserID'];

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            if ($password === $dbPassword) {
                $_SESSION['UserId'] = $userId;
                $_SESSION['UserRole'] = $userRole;
                

                if ($userRole === 'Admin') {
                    $successMessage = "Login successful! You can now log in.";
                    $_SESSION['UserId'] = $userId;
                    $_SESSION['UserRole'] = 'admin';
                    header("refresh:1.5;url=books.php");
                } else {
                    $successMessage = "Login successful! You can now log in.";
                    $_SESSION['UserId'] = $userId;
                    $_SESSION['UserRole'] = 'patron';
                    header("refresh:1.5;url=user-dashboard.php");
                }
            } else {
                $errorMessage = "Incorrect Password.";
            }
        } else {
            $errorMessage = "Incorrect Username.";
        }
    } else {
        die("Prepare error: " . mysqli_error($conn));
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
            <div>
                <?php if (!empty($errorMessage)) { ?>
                <div class="error">
                    <?php echo $errorMessage; ?>
                </div>
                <?php } elseif (!empty($successMessage)) { ?>
                <div class="success">
                    <?php echo $successMessage; ?>
                </div>
                <?php } ?>
            </div>

            <form class="login-form" action="login.php" method="POST">
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
