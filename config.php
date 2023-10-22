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

$sql_overdue = "SELECT * FROM borrowed_books WHERE UserId AND DateDue < CURRENT_DATE";
$query_overdue = mysqli_query($conn,$sql_overdue);
$daily_rate = 150;
$current_date = strtotime(date('Y-m-d'));

while($row = mysqli_fetch_assoc($query_overdue)){
    $due_date = strtotime ($row['DateDue']);

    $diff = $current_date - $due_date;
    $days = floor($diff / (60 * 60 * 24));

    $fine_amt = $daily_rate * $days;

    $sql_fine = "UPDATE fines SET Fines = $fine_amt WHERE UserID = $user_id" ;
    $query_fine = mysqli_query($conn,$sql_fine);

}
?>
