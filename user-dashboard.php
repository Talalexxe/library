<?php
session_start();
include 'config.php';

$currentUserID = $_SESSION['currentUserID'];

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

try {
    // SQL query to count the number of books not returned for the current user
    $sql = "SELECT COUNT(*) AS notReturnedBooks FROM borrowed_books WHERE UserID = $currentUserID AND LoanStatus = 'Not Returned'";

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

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System | User Dashboard</title>
        <!-- BOOTSTRAP CORE STYLE  -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>
<div class="content-wrapper">
        <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <h4 class="header-line">User DASHBOARD</h4>
                </div>
            </div>
            <div class="row">
                <a href="books.php">
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-5x"></i>
                            <h3><?php echo htmlentities($totalBooks); ?></h3>
                            Books Listed
                        </div>
                    </div>
                </a>
                <div class="col-md-4 col-sm-4 col-xs-6">
                    <div class="alert alert-warning back-widget-set text-center">
                        <i class="fa fa-recycle fa-5x"></i>
                        <h3><?php echo htmlentities($returnedbooks); ?></h3>
                        Books Not Returned Yet
                    </div>
                </div>
                <a href="issued-books.php">
                    <div class="col-md-4 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class="fa fa-book fa-5x"></i>
                            <h3>&nbsp;</h3>
                            Issued Books
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
