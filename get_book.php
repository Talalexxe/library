<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $bookId = $_POST['id'];

    $query = "SELECT * FROM books WHERE BookID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Fetch the book data
        $bookData = array(
            'id' => $row['BookID'],
            'image' => $row['image'],
            'title' => $row['Title'],
            'isbn' => $row['ISBN'],
            'author' => $row['Author'],
            'genre' => $row['Genre'],
            'publisher' => $row['Publisher'],
            'quantity' => $row['Quantity']
        );

        // Return the book data as JSON
        echo json_encode($bookData);
    } else {
        echo json_encode(array('error' => 'Book not found'));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
