<?php
session_start();
include 'config.php';


$user_role = $_SESSION['UserRole'];

$user_id = $_SESSION['UserId'];


$errorMessage = "";
$successMessage = "";

$query = "SELECT * FROM books";
$result = mysqli_query($conn, $query);

$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}

if (isset($_POST['add_new_book'])) {
    $newTitle = mysqli_real_escape_string($conn, $_POST['new_title']);
    $newCover = mysqli_real_escape_string($conn, $_POST['new_cover']);
    $newISBN = mysqli_real_escape_string($conn, $_POST['new_isbn']);
    $newAuthor = mysqli_real_escape_string($conn, $_POST['new_author']);
    $newGenre = mysqli_real_escape_string($conn, $_POST['new_genre']);
    $newPublisher = mysqli_real_escape_string($conn, $_POST['new_publisher']);
    $newQuantity = (int)$_POST['new_quantity'];

    // Create an INSERT query to add the new book to the 'books' table
    $insert_query = "INSERT INTO books (Cover, Title, ISBN, Author, Genre, Publisher, Quantity) 
                    VALUES ('$newCover', '$newTitle', '$newISBN', '$newAuthor', '$newGenre', '$newPublisher', $newQuantity)";

    if (mysqli_query($conn, $insert_query)) {
        $successMessage = "New Book Added Successfully!";
    } else {
        $errorMessage = "Error Adding New Book: " . mysqli_error($conn);
    }
} else if (isset($_POST['edit_book'])) {
    $editedId = mysqli_real_escape_string($conn, $_POST['id']);
    $editedTitle = mysqli_real_escape_string($conn, $_POST['title']);
    $editedISBN = mysqli_real_escape_string($conn, $_POST['isbn']);
    $editedAuthor = mysqli_real_escape_string($conn, $_POST['author']);
    $editedGenre = mysqli_real_escape_string($conn, $_POST['genre']);
    $editedPublisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $editedQuantity = (int)$_POST['quantity'];

    
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        // Get the temporary file path
        $tmpImagePath = $_FILES['cover']['tmp_name'];
    
        // Define the path where you want to save the uploaded image
        $uploadPath = 'resources/' . $_FILES['cover']['name'];
    
        // Move the uploaded file to the desired location
        if (move_uploaded_file($tmpImagePath, $uploadPath)) {
            $imagePath = $uploadPath;
        } else {
            $errorMessage = "Error uploading the image.";
        }
    } else {
        // No new image uploaded, use the existing path
        $imagePath = $_POST['current_image_path'];
    }
    
    // Create an UPDATE query to edit the book in the 'books' table
    $update_query = "UPDATE books 
                    SET image = '$imagePath', Title = '$editedTitle', ISBN = '$editedISBN', 
                    Author = '$editedAuthor', Genre = '$editedGenre', Publisher = '$editedPublisher', Quantity = $editedQuantity 
                    WHERE BookID = $editedId";

    if (mysqli_query($conn, $update_query)) {
        $successMessage = "Book Updated Successfully!";
        header("refresh:1.5;url=books.php");
    } else {
        $errorMessage = "Error Updating Book: " . mysqli_error($conn);
    }
} else if (isset($_POST['delete_book'])) {
    $bookId = mysqli_real_escape_string($conn, $_POST['id']);
    $delete_query = "DELETE FROM books WHERE BookID = $bookId";
    if (mysqli_query($conn, $delete_query)) {
        $successMessage = "Book Deleted Successfully!";
    } else {
        $errorMessage = "Error Deleting Book: " . mysqli_error($conn);
    }
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

            function openEditBookPopup(bookId) {
                // Retrieve book data based on bookId and populate the fields
                $.ajax({
                    url: "get_book_data.php", // Replace with the actual PHP script to fetch book data
                    method: "POST",
                    data: { id: bookId },
                    success: function (response) {
                        var bookData = JSON.parse(response);
                        $("#id").val(bookData.BookID);
                        $("#title").val(bookData.Title);
                        $("#cover").val(bookData.Cover);
                        $("#isbn").val(bookData.ISBN);
                        $("#author").val(bookData.Author);
                        $("#genre").val(bookData.Genre);
                        $("#publisher").val(bookData.Publisher);
                        $("#quantity").val(bookData.Quantity);
                        $("#edit-container").fadeIn();
                    },
                    error: function (xhr, status, error) {
                        alert("Error fetching book data: " + error);
                    }
                });
            }

            function closeEditBookPopup() {
                $("#edit-container").fadeOut();
            }
        });
    </script>
</head>
<body>
<?php include('header.php');?>
    <div class="content-wrapper">
         <div class="container">
            <div class="row pad-botm">
                <div class="col-md-12">
                    <?php if($user_role === "admin" ){?>
                        <h4 class="header-line">Manage Books</h4>
                    <?php } ?>
                    <?php if($user_role === "patron" ){?>
                        <h4 class="header-line">View Books</h4>
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
                                Books Listing
                            </div>
                            <?php if($user_role === "admin" ){?>
                                <button id="add-book-btn" class="pull-right" >Add New Book</button>
                            <?php } ?>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <tr>
                                            <th>Cover</th>
                                            <th>Title</th>
                                            <th>ISBN</th>
                                            <th>Author</th>
                                            <th>Genre</th>
                                            <th>Publisher</th>
                                            <?php if($user_role === "admin" ){?>
                                                <th>Quantity</th>
                                            <?php } ?>
                                            <th>Actions</th>
                                        </tr>
                                        <?php foreach ($books as $book) {
                                            if ($book['Quantity'] > 0) { 
                                            ?>
                                            <tr class="book-rows">
                                                <td class="center-image">
                                                    <img class="book-cover" src="<?php echo $book['image']; ?>" alt="Book Cover">
                                                </td>
                                                <td style="padding-top: 2.85%;" class="book-title center-text"><?php echo $book['Title']; ?></td>
                                                <td style="padding-top: 2.85%;" class="book-ISBN center-text"><?php echo ($book['ISBN']); ?></td>
                                                <td style="padding-top: 2.85%;" class="book-author center-text"><?php echo $book['Author']; ?></td>
                                                <td style="padding-top: 2.85%;" class="book-genre center-text"><?php echo $book['Genre']; ?></td>
                                                <td style="padding-top: 2.85%;" class="book-publisher center-text"><?php echo $book['Publisher']; ?></td>
                                                <?php if($user_role === "admin" ){?>
                                                    <td style="padding-top: 2.85%;" class="book-quantity center-text"><?php echo $book['Quantity']; ?></td>
                                                <?php } ?>
                                                <?php if ($user_role === "admin") { ?>
                                                    <td>
                                                        <button style="margin-top: 11%;" class="edit-btn" data-id="<?php echo $book['BookID']; ?>">Edit</button>
                                                        <button class="delete-btn" data-id="<?php echo $book['BookID']; ?>">Delete</button>
                                                    </td>
                                                <?php } ?>
                                                <?php if ($user_role === "patron") { ?>
                                                    <td>
                                                        <button class="borrow-btn" data-id="<?php echo $book['BookID']; ?>">Borrow</button>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } }?>
                                    </table>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
    <!-- Add Container -->
    <div id="add-container">
        <button id="close-add-container">Close</button>
        <br><br>
        <div id="add-content">
            <form id="add-form" action="books.php" method="post" enctype="multipart/form-data">
                <input type="text" name="new_title" placeholder="Title" required>
                <input type="text" name="new_cover" placeholder="Cover Image URL" required>
                <input type="text" name="new_isbn" placeholder="ISBN" required>
                <input type="text" name="new_author" placeholder="Author" required>
                <input type="text" name="new_genre" placeholder="Genre" required>
                <input type="text" name="new_publisher" placeholder="Publisher" required>
                <input type="number" name="new_quantity" placeholder="Quantity" required>
                <input class="add_submit" type="submit" name="add_new_book" value="Add New Book">
            </form>
        </div>
    </div>

    <!-- Edit Container -->
    <div id="edit-container">
        <button id="close-edit-container">Close</button><br><br>
        <form id="edit-form" action="books.php" method="post" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id">
            <label>Title:</label>
            <input type="text" id="title" name="title" required>
            <input type="hidden" id="current_image_path" name="current_image_path" readonly >
            <label>Cover Image URL:</label>
            <input type="file" id="cover" name="cover"accept="image/*" >
            <label>ISBN:</label>
            <input type="text" id="isbn" name="isbn" required>
            <label>Author:</label>
            <input type="text" id="author" name="author" required>
            <label>Genre:</label>
            <input type="text" id="genre" name="genre" required>
            <label>Publisher:</label>
            <input type="text" id="publisher" name="publisher" required>
            <label>Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <input type="submit" name="edit_book" value="Save">
        </form>
    </div>

    <?php include('footer.php');?>
    <script>
        $(".edit-btn").click(function () {
            var bookId = $(this).data('id');
            $("#edit-container").fadeIn();
            $.ajax({
                url: "get_book.php",
                method: "POST",
                data: { id: bookId },
                dataType: "json",
                success: function (data) {
                    $("#id").val(data.id);
                    $("#title").val(data.title);
                    $("#isbn").val(data.isbn);
                    $("#author").val(data.author);
                    $("#genre").val(data.genre);
                    $("#publisher").val(data.publisher);
                    $("#quantity").val(data.quantity);

                    $("#current_image_path").val(data.image);
                    $("#current_image").html(data.image ? "Current Cover: <br>" + data.image : "");
                }
            });
        });

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
                    },
                    error: function (xhr, status, error) {
                        alert("Error deleting book: " + error);
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
