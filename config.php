<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

// Establish a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection was successful
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_SESSION['UserId'])) {
    $user_id = $_SESSION['UserId'];

    $issuedBooks = 0; // Initialize issuedBooks as 0 by default

    // Check if the user is logged in
    if (isset($_SESSION['UserId'])) {
        // Get the user's ID from the session

        // SQL query to count issued books for the user
        $issuedBooksQuery = "SELECT COUNT(*) AS issuedBooksCount FROM borrowed_books WHERE UserID = $user_id";

        // Execute the query
        $issuedBooksResult = mysqli_query($conn, $issuedBooksQuery);

        if ($issuedBooksResult) {
            // Fetch the result into an associative array
            $issuedBooksRow = mysqli_fetch_assoc($issuedBooksResult);

            // Store the count of issued books in the variable
            $issuedBooks = $issuedBooksRow['issuedBooksCount'];

        } else {
            echo "Query failed: " . mysqli_error($conn);
        }
    }
    $user_id = $_SESSION['UserId'];

    // Calculate the total overdue fine as before
    $sql_overdue = "SELECT BookID, DateDue FROM borrowed_books WHERE UserID = $user_id AND DateDue < CURRENT_DATE";
    $query_overdue = mysqli_query($conn, $sql_overdue);
    $daily_rate = 150;
    $current_date = strtotime(date('Y-m-d'));
    $total_fine = 0;

    $overdue_books = array();

    while ($row = mysqli_fetch_assoc($query_overdue)) {
        $overdue_books[] = $row;
    }

    foreach ($overdue_books as $book) {
        $book_id = $book['BookID'];
        $due_date = strtotime($book['DateDue']);

        $diff = $current_date - $due_date;
        $days = floor($diff / (60 * 60 * 24));

        $fine_amt = $daily_rate * $days;

        $total_fine += $fine_amt;
    }

    // Check the payment table for any payments made by the user
    $sql_payment = "SELECT SUM(amount) as total_payment FROM payments WHERE UserID = $user_id";
    $query_payment = mysqli_query($conn, $sql_payment);
    $payment_row = mysqli_fetch_assoc($query_payment);
    $total_payment = $payment_row['total_payment'];

    // Subtract the total_payment from the total_fine
    $total_fine -= $total_payment;

    // Now, proceed with the update of the user's total fine
    $sql_update_fine = "UPDATE users SET TotalFines = $total_fine WHERE UserID = $user_id";
    $query_update_fine = mysqli_query($conn, $sql_update_fine);

    if (!$query_update_fine) {
        echo "Error updating total fine: " . mysqli_error($conn);
    }
} else {

}
?>
