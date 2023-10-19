<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books</title>
</head>
<body>
    

    <?php
        session_start();
        include 'config.php';

        $user_id = $_SESSION['UserID'];
        $sql = "SELECT * FROM borrowed_books WHERE UserID = $user_id";
        $query = mysqli_query($conn,$sql);

        if(mysqli_num_rows($query)>0){?>
        <h2>My Books</h2>
        
        <table>
            <tr><th colspan ="3">Borrowed Books</th></tr>

            <tr>
                <td>Name of Book</td>
                <td>Date Borrowed</td>
                <td>Return Date</td>
            </tr>
            <?php
                    $sql_retrieve = "SELECT books.Title, borrowed_books.DateBorrowed, borrowed_books.ReturnDate FROM books INNER JOIN borrowed_books on books.BookID = borrowed_books.BookID WHERE borrowed_books.UserID = $user_id;";
                    $query_retrieve = mysqli_query($conn,$sql_retrieve);
                    if(mysqli_num_rows($query_retrieve)>0){
                        while($books=mysqli_fetch_assoc($query_retrieve)){
                            echo '<tr>';
                            echo '<td>'.$books["Title"].'</td>';
                            echo '<td>'.$books["DateBorrowed"].'</td>';
                            echo '<td>'.$books["ReturnDate"].'</td>';
                            echo '</tr>';
                        }
                    }
            ?>
        </table>
        <?php
        }else{
            echo "No Books in your possession";
        }
    ?>
    <br><br>
    <a href="main.php">MAIN MENU</a> 

</body>
</html>