<?php
session_start();
include 'config.php';

$user_id = $_SESSION['UserId'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['id'];

    // Check if the book exists and is borrowed by the user
    $borrowedBookQuery = "SELECT bb.*, b.Quantity, bb.DateDue FROM borrowed_books bb 
        INNER JOIN books b ON bb.BookID = b.BookID 
        WHERE bb.BookID = $bookId AND bb.UserID = $user_id";
    $borrowedBookResult = mysqli_query($conn, $borrowedBookQuery);

    if ($borrowedBookResult) {
        // Check if there are rows in the result set
        if (mysqli_num_rows($borrowedBookResult) !== 0) {
            $borrowedBook = mysqli_fetch_assoc($borrowedBookResult);
            
            // Update the books table to increase the quantity
            $updateBooksQuery = "UPDATE books SET Quantity = Quantity + 1 WHERE BookID = $bookId";
            mysqli_query($conn, $updateBooksQuery);

            // Insert a record into the returned_books table
            $returnDate = date('Y-m-d'); // Get the current date
            $insertReturnedBookQuery = "INSERT INTO returned_books (LoanID, BookID, UserID, ReturnDate) 
                VALUES ({$borrowedBook['LoanID']}, $bookId, $user_id, '$returnDate')";
            mysqli_query($conn, $insertReturnedBookQuery);


            // Delete the entry from the borrowed_books table
            $deleteBorrowedBookQuery = "DELETE FROM borrowed_books WHERE LoanID = {$borrowedBook['LoanID']}";
            mysqli_query($conn, $deleteBorrowedBookQuery);

            echo "Book returned successfully!";
        } else {
            echo "Unable to return the book. Please check the book's status.";
        }
    } else {
        // Handle the SQL query error
        echo "Error in the SQL query: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request method.";
}

mysqli_close($conn);
?>

