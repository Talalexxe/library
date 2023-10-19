<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Books</title>
</head>
<body>

    <h2>Return Books</h2>

    <?php
        session_start();
        include 'config.php';

        $user_id = $_SESSION['UserID'];

        $sql = "SELECT * FROM borrowed_books INNER JOIN books on borrowed_books.BookID = books.BookID WHERE UserID = $user_id";
        $query = mysqli_query($conn,$sql);

        if(mysqli_num_rows($query) >0){
            

    ?>
    
            <ul>
                <?php
                while($book=mysqli_fetch_assoc($query)){?>
                    <li>
                        <h3><?php echo $book["Title"]; ?></h3>
                        <h3><?php echo $book["Genre"]; ?></h3>
                        <p><?php echo $book["Author"]; ?></p>
                        <form action="return.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $book["BookID"]; ?>">
                            <input type="submit" value="Return">
                        </form>
                    </li>
                    <?php }
                    }
                ?>
            </ul>

            <?php
                if(isset($_POST['id'])){
                    $book_id = $_POST['id'];

                    $sql_return = "INSERT INTO returned_books (LoanID,BookID,UserID,DateBorrowed,DateReturned) SELECT LoanID,BookID,UserID,DateBorrowed,CURRENT_DATE FROM borrowed_books WHERE borrowed_books.BookID = $book_id";
                    $query_return = mysqli_query($conn,$sql_return);

                    if($query_return){
                        $sql_delete = "DELETE FROM borrowed_books WHERE borrowed_books.BookID = $book_id";
                        $query_delete = mysqli_query($conn,$sql_delete);
                    }

                    $sql_update = "UPDATE books SET Status = 'Available' WHERE BookID = $book_id";
                    $query_update = mysqli_query($conn,$sql_update);

                    if($query_return && $query_update){
                        header("Location:return.php");
                    }else{
                        echo "FAILED";
                    }
                }
            ?>

    <br><br>
    <a href="main.php">MAIN MENU</a> 
    
</body>
</html>