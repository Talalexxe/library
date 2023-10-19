<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Books</title>
</head>
<body>

    <h2>Add new book</h2>
    <form action="add.php" method="post">
        <label for="isbn">ISBN</label><br>
        <input type="text" name="isbn" id="isbn" required><br><br>
        <label for="title">Book Title</label><br>
        <input type="text" name="title" id="title" required><br><br>
        <label for="genre">Genre</label><br>
        <input type="text" name="genre" id="genre" required><br><br>
        <label for="author">Author</label><br>
        <input type="text" name="author" id="author" required><br><br>
        <label for="publisher">Publisher</label><br>
        <input type="text" name="publisher" id="publisher" required><br><br>
        <input type="submit" value="Add">
    </form><br><br>

    <?php
        include 'config.php';

        $isbn = $_POST["isbn"];
        $title = $_POST["title"];
        $genre = $_POST["genre"];
        $author = $_POST["author"];
        $publisher = $_POST["publisher"];

        $sql = "INSERT INTO books (Status, ISBN, Title, Genre, Author, Publisher)
        VALUES ('Available', '$isbn', '$title', '$genre', '$author', '$publisher')";

        $query=mysqli_query($conn,$sql);

        if($query){
            echo "Book successfully added";
        }else{
            echo "ERROR while adding book, please try again";
        }

    ?>
    <br>

<a href="dashboard.php">Dashboard</a>
    
</body>
</html>