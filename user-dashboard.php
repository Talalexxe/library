<?php
session_start();
include 'config.php';


$user_role = $_SESSION['UserRole'];

$user_id = $_SESSION['UserId']; // Get the user_id from the session

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

try {
    // SQL query to count the number of books not returned for the current user
    $sql = "SELECT COUNT(*) AS notReturnedBooks FROM borrowed_books WHERE UserID = $user_id AND CURRENT_DATE > DateDue";


    // Execute the query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the result into an associative array
        $row = mysqli_fetch_assoc($result);

        // Store the count of not returned books in a PHP variable
        $notReturnedBooks = $row['notReturnedBooks'];
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

try {
    // SQL query to count the number of books
    $sql = "SELECT COUNT(*) AS totalBooks FROM books";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the result into an associative array
        $row = mysqli_fetch_assoc($result);

        // Store the totalBooks count in variable
        $totalBooks = $row['totalBooks'];
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}



try {
    // SQL query to sum the FineAmount for the current user
    $sql = "SELECT Fines AS totalFines FROM fines WHERE UserID = $user_id";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the result into an associative array
        $row = mysqli_fetch_assoc($result);

        // Store the total fines in a PHP variable
        $totalFines = $row['totalFines'];

        // Check if totalFines is null and set it to 0 if needed
        if ($totalFines === null) {
            $totalFines = 0;
        }
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}



mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System | User Dashboard</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/solid.js" integrity="sha384-/BxOvRagtVDn9dJ+JGCtcofNXgQO/CCCVKdMfL115s3gOgQxWaX/tSq5V8dRgsbc" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/fontawesome.js" integrity="sha384-dPBGbj4Uoy1OOpM4+aRGfAOc0W37JkROT+3uynUgTHZCHZNMHfGXsmmvYTffZjYO" crossorigin="anonymous"></script>
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/bootstrap.css" rel="stylesheet" />

</head>
<body>
    <?php include('header.php'); ?>
    <div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User DASHBOARD</h4>
                </div>
            </div>
            <div class="row" style="margin-top: 100px;">
                <a href="books.php">
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-5x"></i>
                            <h3><?php echo htmlentities($totalBooks); ?></h3>
                            Books Listed
                        </div>
                    </div>
                </a>
                <div class="col-md-3 col-sm-4 col-xs-6">
                    <div class="alert alert-warning back-widget-set text-center">
                        <i class="fa fa-recycle fa-5x"></i>
                        <h3><?php echo htmlentities($notReturnedBooks); ?></h3>
                        Books Not Returned Yet
                    </div>     
                </div>
                <a href="return.php">
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-5x"></i>
                            <h3><?php echo htmlentities($issuedBooks); ?></h3>
                            Received Books
                        </div>
                    </div>
                </a>
                <a href="issued-books.php">
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class='fas fa-dollar-sign fa-5x'></i>
                            <h3>$<?php echo number_format($totalFines, 2); ?></h3> 
                            Payment Due
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <?php include('footer.php'); ?>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>
