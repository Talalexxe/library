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
    $sql = "SELECT TotalFines AS totalFine FROM users WHERE UserID = $user_id";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the result into an associative array
        $row = mysqli_fetch_assoc($result);

        // Store the total fines in a PHP variable
        $totalFine = $row['totalFine'];

        // Check if totalFines is null and set it to 0 if needed
        if ($totalFine === null) {
            $totalFine = 0;
        }
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$payment_result = ""; // Initialize a variable to store the payment result

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pay_submit"])) {
    $payment_amount = floatval($_POST["amount"]);
    $card_number = $_POST["card_number"];
    $expiration = $_POST["expiration"];
    $cvv = $_POST["cvv"];


    $new_total_fines = $totalFine - $payment_amount;

    
    $sql_update_total_fines = "UPDATE users SET TotalFines = $new_total_fines WHERE UserID = $user_id";
    $query_update_total_fines = mysqli_query($conn, $sql_update_total_fines);

    if ($query_update_total_fines) {
        // Insert a payment record in a 'payments' table (create this table if not already created)
        $sql_insert_payment = "INSERT INTO payments (UserID, Amount, PaymentDate) VALUES ($user_id, $payment_amount, NOW())";
        $query_insert_payment = mysqli_query($conn, $sql_insert_payment);

        if ($query_insert_payment) {            
            header("refresh:0.1;url=user-dashboard.php");
        } else {
            $payment_result = "Error inserting payment record: " . mysqli_error($conn);
        }
    } else {
        $payment_result = "Error updating total fines: " . mysqli_error($conn);
    }
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <script>
        $(document).ready(function () {
            $("#payment-btn").click(function () {
                openAddBookPopup();
            });

            $("#close-add-container").click(function () {
                closeAddBookPopup();
            });

            function openAddBookPopup() {
                $(".overlay").fadeIn();
                $("#add-container").fadeIn();
            }

            function closeAddBookPopup() {
                $(".overlay").fadeOut();
                $("#add-container").fadeOut();
            }
        });
    </script>
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
                        Books To Be Returned
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
                <a href="#" id="payment-btn"> <!-- Add an ID to the link -->
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="alert alert-success back-widget-set text-center">
                            <i class='fas fa-dollar-sign fa-5x'></i>
                            <h3>$<?php echo number_format($totalFine, 2); ?></h3> 
                            Payment Due
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div id="add-container">
        <button id="close-add-container">Close</button>
        <br>
        <div id="add-content">
            <h2>Pay Fine</h2>
            <form style="width: 400px;" action="user-dashboard.php" method="post">
                <label for="fine">Total Fine:</label><br>
                <input style="width: 90%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: none;"
                    type="text" name="fine" value="$<?php echo number_format($totalFine, 2); ?>" readonly><br>

                <label for="amount">Payment Amount:</label><br>
                <input style="width: 90%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: none;"
                    type="text" name="amount" required><br>

                <label for="card_number">Card Number:</label><br>
                <input style="width: 90%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: none;"
                    type="text" name="card_number" required><br>

                <label for="expiration">Expiration Date (MM/YYYY):</label><br>
                <input style="width: 90%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: none;"
                    type="text" name="expiration" required><br>

                <label for="cvv">CVV:</label><br>
                <input style="width: 90%; padding: 8px; margin-bottom: 15px; border-radius: 4px; border: none;"
                    type="text" name="cvv" required><br>

                <input style="margin-left: 40%; display: block; padding: 7px; background-color: #F2CB07; border: none; border-radius: 4px; cursor: pointer;"
                    type="submit" name="pay_submit" value="Pay">
            </form>
        </div>
    </div>
    <script>


        $("#payment-btn").click(function () {
            $("#add-container").fadeIn();
        });

        $("#close-add-container").click(function () {
            $("#add-container").fadeOut();
        });

    </script>

    <?php include('footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>
