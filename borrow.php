<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Books</title>
</head>
<body>

    <?php
        session_start();
        include 'config.php';

        $sql = "SELECT * FROM books WHERE Status = 'Available'";
        $query = mysqli_query($conn,$sql);
        
        if(mysqli_num_rows($query) >0){

        ?>

    <h2>Available Books</h2>

        <ul>
            <?php
            while($book=mysqli_fetch_assoc($query)){?>
                <li>
                    <h3><?php echo $book["Title"]; ?></h3>
                    <h3><?php echo $book["Genre"]; ?></h3>
                    <p><?php echo $book["Author"]; ?></p>
                    <form action="borrow.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $book["BookID"]; ?>">
                        <input type="submit" value="Borrow">
                    </form>
                </li>
                <?php }
                }else{
                    echo "NO AVAILABLE BOOKS";
                }
            ?>
        </ul>

        <?php
            if(isset($_POST['id'])){
                $book_id = $_POST['id'];
                $user_id = $_SESSION['UserID'];

                $sql_loan = "INSERT INTO borrowed_books (BookID,UserID,DateBorrowed,ReturnDate) VALUES ('$book_id','$user_id',CURRENT_DATE,DATE_ADD(CURRENT_DATE, INTERVAL 2 WEEK))";
                $query_loan = mysqli_query($conn,$sql_loan);             
                

                $sql_update = "UPDATE books SET Status = 'Not Available' WHERE BookID = $book_id";
                $query_book_update = mysqli_query($conn,$sql_update);

                if($query_book_update && $query_loan){
                    header("Location:borrow.php");
                }else{
                    echo "FAILED";
                }
            }
        ?>

    <br><br>
    <a href="main.php">MAIN MENU</a>    
</body>
</html>