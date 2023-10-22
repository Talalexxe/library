<?php
session_start();
include 'config.php';

if (isset($_POST['id'])) {
    $book_id = $_POST['id'];
    $user_id = $_SESSION['UserId'];

    
    // Check book quantity
    $check_quantity_query = "SELECT Quantity FROM books WHERE BookID = ?";
    $stmt = mysqli_prepare($conn, $check_quantity_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $book_id);
        mysqli_stmt_execute($stmt);
        $quantity_result = mysqli_stmt_get_result($stmt);

        if ($quantity_result && $quantity_row = mysqli_fetch_assoc($quantity_result)) {
            $book_quantity = $quantity_row['Quantity'];

            if ($book_quantity > 0) {
                // Proceed to borrow the book
                $borrow_date = date('Y-m-d');
                $return_date = date('Y-m-d', strtotime('+2 weeks'));

                // Insert into borrowed_books
                $sql_loan = "INSERT INTO borrowed_books (BookID, UserID, DateBorrowed) VALUES (?, ?, CURRENT_DATE)";
                $stmt = mysqli_prepare($conn, $sql_loan);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'ii', $book_id, $user_id);
                    $query_loan = mysqli_stmt_execute($stmt);

                    if ($query_loan) {
                        // Update the book quantity to reduce by 1
                        $sql_update_quantity = "UPDATE books SET Quantity = Quantity - 1 WHERE BookID = ?";
                        $stmt = mysqli_prepare($conn, $sql_update_quantity);
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, 'i', $book_id);
                            $query_update_quantity = mysqli_stmt_execute($stmt);

                            if ($query_update_quantity) {
                                header("Location: borrow.php");
                                exit();
                            } else {
                                echo "Error updating book quantity: " . mysqli_error($conn);
                            }
                        } else {
                            echo "Error preparing update statement: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Error borrowing the book: " . mysqli_error($conn);
                    }
                } else {
                    echo "Error preparing insert statement: " . mysqli_error($conn);
                }
            } else {
                echo "The book is out of stock and cannot be borrowed.";
            }
        } else {
            echo "Error checking book quantity: " . mysqli_error($conn);
        }
    } else {
        echo "Error preparing select statement: " . mysqli_error($conn);
    }
}
?>
