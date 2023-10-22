<?php
session_start();
include 'config.php';

$user_role = $_SESSION['UserRole'];
$user_id = $_SESSION['UserId'];

$errorMessage = "";
$successMessage = "";

$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

 if (isset($_POST['edit_user'])) {
    $editedId = mysqli_real_escape_string($conn, $_POST['id']);
    $editedFirstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $editedLastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $editedUsername = mysqli_real_escape_string($conn, $_POST['username']);
    $editedEmail = mysqli_real_escape_string($conn, $_POST['email']);
    $editedPhoneNumber = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $editedUserRole = mysqli_real_escape_string($conn, $_POST['user_role']);
    $editedTotalFines = (float)$_POST['total_fines'];

    // Perform user update logic here

} else if (isset($_POST['delete_user'])) {
    $userId = mysqli_real_escape_string($conn, $_POST['id']);
    $delete_query = "DELETE FROM users WHERE UserID = $userId";

    // Perform user deletion logic here
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T.T.K.A Library Management System | Manage Books</title>
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Z9R6ww6I5B+jWVRJZn8a+4J4y8JXt8RfF5P8z8l5Cw1pY/PmICmXrJ7gZYqD4+pi" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/solid.js" integrity="sha384-/BxOvRagtVDn9dJ+JGCtcofNXgQO/CCCVKdMfL115s3gOgQxWaX/tSq5V8dRgsbc" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/fontawesome.js" integrity="sha384-dPBGbj4Uoy1OOpM4+aRGfAOc0W37JkROT+3uynUgTHZCHZNMHfGXsmmvYTffZjYO" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            $(".edit-btn").click(function () {
                var userId = $(this).data('id');
                $("#edit-container").fadeIn();
                $.ajax({
                    url: "get_user.php", 
                    method: "POST",
                    data: { id: userId },
                    dataType: "json",
                    success: function (data) {
                        $("#id").val(data.UserID);
                        $("#first_name").val(data.FirstName);
                        $("#last_name").val(data.LastName);
                        $("#username").val(data.Username);
                        $("#email").val(data.Email);
                        $("#phone_number").val(data.PhoneNumber);
                        $("#user_role").val(data.UserRole);
                    }
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

                $(".edit-btn").click(function () {
                    var bookId = $(this).data('id');
                    openEditBookPopup(bookId);
                });

                $("#close-edit-container").click(function () {
                    closeEditBookPopup();
                });

                function closeEditBookPopup() {
                    $("#edit-container").fadeOut();
                }
            });
        });
    </script>
</head>
<body style="background-image: url('image/books-bg.jpg'); background-size: cover;" >
<?php include('header.php');?>
    <div class="content-wrapper">
         <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <?php if($user_role === "admin" ){?>
                        <h4 style="color: white;" class="header-line">Manage Users</h4>
                    <?php } ?>
                    <?php if($user_role === "patron" ){?>
                        <h4 style="color: white;" class="header-line">View Users</h4>
                    <?php } ?>
                    
                </div>
                    <!-- Error Message Container -->
                <div>
                    <?php if (!empty($errorMessage)) { ?>
                    <div class="error">
                        <?php echo $errorMessage; ?>
                    </div>

                    <?php } elseif (!empty($successMessage)) { ?>
                    <div class="success">
                        <?php echo $successMessage; ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Users Listing
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <?php if ($user_role === "admin") { ?>
                                                <th>User Role</th>
                                                <th>Total Fines</th>
                                            <?php } ?>
                                            <th>Actions</th>
                                        </tr>
                                        <?php foreach ($users as $user) { ?>
                                            <tr class="user-rows">
                                                <td hidden><?php echo $user['UserID']; ?></td>
                                                <td style="display: table-cell;vertical-align: middle" class="user-first-name center-text"><?php echo $user['FirstName']; ?></td>
                                                <td style="display: table-cell;vertical-align: middle" class="user-last-name center-text"><?php echo $user['LastName']; ?></td>
                                                <td style="display: table-cell;vertical-align: middle" class="user-username center-text"><?php echo $user['Username']; ?></td>
                                                <td style="display: table-cell;vertical-align: middle" class="user-email center-text"><?php echo $user['Email']; ?></td>
                                                <td style="display: table-cell;vertical-align: middle" class="user-phone center-text"><?php echo $user['PhoneNumber']; ?></td>
                                                <td style="display: table-cell;vertical-align: middle" class="user-role center-text"><?php echo $user['UserRole']; ?></td>
                                                <td style="display: table-cell;vertical-align: middle" class="user-fines center-text"><?php echo $user['TotalFines']; ?></td>
                                                <td>
                                                    <button class="edit-btn" data-id="<?php echo $user['UserID']; ?>">Edit</button>
                                                    <button class="delete-btn" data-id="<?php echo $user['UserID']; ?>">Delete</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Container -->
                <div id="edit-container">
                    <button id="close-edit-container">Close</button><br><br>
                    <form id="edit-form" action="users.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id">
                        <label>First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>
                        <label>Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>
                        <label>Username:</label>
                        <input type="text" id="username" name="username" required>
                        <label>Email:</label>
                        <input type="text" id="email" name="email" required>
                        <label>Phone Number:</label>
                        <input type="text" id="phone_number" name="phone_number" required>
                        <label>User Role:</label>
                        <input type="text" id="user_role" name="user_role" required>
                        <input type="submit" name="edit_user" value="Save">
                    </form>
                </div>
            </div>
         </div>
    </div>

    <?php include('footer.php');?>
    <script>




        // Add Button Clicked
        $("#add-book-btn").click(function () {
            $("#add-container").fadeIn();
        });

        $("#close-add-container").click(function () {
            $("#add-container").fadeOut();
        });

        // Delete Button Clicked
        $(".delete-btn").click(function () {
            var bookId = $(this).data('id');
            var confirmDelete = confirm('Are you sure you want to delete this book?');

            if (confirmDelete) {
                $.ajax({
                    url: "delete_book.php",
                    method: "POST",
                    data: {id: bookId},
                    success: function (response) {
                        alert(response);
                        location.href = 'books.php';
                    }
                });
            }
        });

        $(".borrow-btn").click(function () {
            var bookId = $(this).data('id');

            var confirmReturn = confirm("Are you sure you want to return this book?");
            
            if (confirmReturn){
            $.ajax({
                type: "POST",
                url: "borrow-book.php", // Create a PHP script to handle book borrowing
                data: { id: bookId },
                success: function (response) {
                    alert(response); 
                    location.reload();
                },
                error: function (xhr, status, error) {
                    alert("Book Borrowed Successfully" );
                    location.reload();
                }
            });
            }
        });


        // Close Edit Container
        $("#close-edit-container").click(function () {
            $("#edit-container").fadeOut();
        });
    </script>

    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/dataTables/jquery.dataTables.js"></script>
    <script src="js/dataTables/dataTables.bootstrap.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>
<?php ?>
