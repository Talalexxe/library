<?php
session_start();
include 'config.php';

if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Query to fetch user data based on the user ID
    $sql = "SELECT * FROM users WHERE UserID = $userId";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Return user data as JSON
        header('Content-Type: application/json');
        echo json_encode($user);
    } else {
        // Return an error message or an empty JSON object if the user is not found
        echo json_encode(array('error' => 'User not found'));
    }
} else {
    // Return an error message if the ID is not provided
    echo json_encode(array('error' => 'User ID not provided'));
}

// Close the database connection
$conn->close();
?>
