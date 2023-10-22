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

try {
    $sql = "SELECT b.Title, b.Author, b.Genre, bb.* FROM borrowed_books bb 
        INNER JOIN books b ON bb.BookID = b.BookID
        WHERE bb.UserID = $user_id";


    $result = mysqli_query($conn, $sql);

    if ($result) {
        $borrowedBooks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $borrowedBooks[] = $row;
        }
    } else {
        echo "Query failed: " . mysqli_error($conn);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
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
</head>
<body style="background-image: url('image/return-bg.jpg'); background-size: cover;" >
    <?php include('header.php');?>
    <div class="content-wrapper">
         <div class="container">
            <div class="row">
                <div class="col-md-12">          
                    <h4 style="color: white;" class="header-line">Return Log</h4>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Borrow Listing
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <?php foreach ($borrowedBooks as $book) { ?>
                                        <tr>
                                            <th>Title</th>
                                            <th>Author</th>
                                            <th>Genre</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr class="book-rows">
                                            <td style="display: table-cell;vertical-align: middle" ><?php echo $book['Title']; ?></td>
                                            <td style="display: table-cell;vertical-align: middle" ><?php echo $book['Author']; ?></td>
                                            <td style="display: table-cell;vertical-align: middle" ><?php echo $book['Genre']; ?></td>
                                            <td>
                                                <button style="margin-left: 35%;" class="return-btn" data-id="<?php echo $book['BookID']; ?>">Return</button>
                                            </td>                                              
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </div>


    <?php include('footer.php');?>
    <script>
        $(".return-btn").click(function () {
            var bookId = $(this).data('id');
            var confirmReturn = confirm("Are you sure you want to return this book?");
            
            if (confirmReturn) {
                $.ajax({
                    type: "POST",
                    url: "Return-book.php", 
                    data: { id: bookId },
                    success: function (response) {
                        alert(response);    
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        alert("Error Returning book: " + error);
                    }
                });
            }
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
