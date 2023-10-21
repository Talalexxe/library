<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $bookId = mysqli_real_escape_string($conn, $_POST['id']);

    // Prepare and execute the DELETE query
    $deleteQuery = "DELETE FROM books WHERE BookID = $bookId";
    if (mysqli_query($conn, $deleteQuery)) {
        echo "Book Deleted Successfully!";
    } else {
        echo "Error Deleting Book: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request or missing book ID.";
}
?>
