CREATE DATABASE library;
USE library;

CREATE TABLE `books` (
  `BookID` int(11) NOT NULL PRIMARY KEY,
  `Title` varchar(500) NOT NULL,
  `ISBN` int(13) NOT NULL,
  `Author` varchar(500) NOT NULL,
  `Genre` varchar(500) NOT NULL,
  `Publisher` varchar(500) NOT NULL,
  `Quantity` int(11) NOT NULL
);

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL PRIMARY KEY,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` varchar(25) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `UserRole` enum('Admin', 'Patron') NOT NULL
);

CREATE TABLE `borrowed_books` (
  `LoanID` int(11) NOT NULL PRIMARY KEY,
  `BookID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `DateBorrowed` date NOT NULL,
  `DateDue` date NOT NULL,
  `DateReturned` date NOT NULL,
  `LoanStatus` enum('Returned', 'Not Returned') NOT NULL,
  FOREIGN KEY (`BookID`) REFERENCES `books`(`BookID`),
  FOREIGN KEY (`UserID`) REFERENCES `users`(`UserID`)
);

DELIMITER //
CREATE TRIGGER SetDueDate
BEFORE INSERT ON borrowed_books
FOR EACH ROW
BEGIN
    SET NEW.DateDue = DATE_ADD(NEW.DateBorrowed, INTERVAL 14 DAY);
END;
//
DELIMITER ;

CREATE TABLE `fines` (
  `FineID` int(11) NOT NULL PRIMARY KEY,
  `UserID` int(11) NOT NULL,
  `LoanID` int(11) NOT NULL,
  `AmountDue` float NOT NULL,
  `Payment` date NOT NULL,
  FOREIGN KEY (`UserID`) REFERENCES `users`(`UserID`),
  FOREIGN KEY (`LoanID`) REFERENCES `borrowed_books`(`LoanID`)
);

DELIMITER //
CREATE TRIGGER UpdateFines
BEFORE UPDATE ON borrowed_books
FOR EACH ROW
BEGIN
    IF NEW.LoanStatus = 'Returned' AND NEW.DateReturned > NEW.DateDue THEN
        SET @days_overdue = DATEDIFF(NEW.DateReturned, NEW.DateDue);
        SET @fine_amount = @days_overdue * 55;
        
        -- Fetch the current AmountDue from the fines table
        SELECT AmountDue INTO @current_amount FROM fines WHERE LoanID = NEW.LoanID;

        -- Update the AmountDue in the fines table
        SET @new_amount = @current_amount + @fine_amount;
        UPDATE fines SET AmountDue = @new_amount WHERE LoanID = NEW.LoanID;
    END IF;
END;
//
DELIMITER ;

INSERT INTO `books` (`BookID`, `Title`, `ISBN`, `Author`, `Genre`, `Publisher`, `Quantity`)
VALUES
(1, 'Book 1 Title', 1234567890123, 'Author 1', 'Genre 1', 'Publisher 1', 5),
(2, 'Book 2 Title', 2345678901234, 'Author 2', 'Genre 2', 'Publisher 2', 3),
(3, 'Book 3 Title', 3456789012345, 'Author 3', 'Genre 1', 'Publisher 1', 2),
(4, 'Book 4 Title', 4567890123456, 'Author 4', 'Genre 2', 'Publisher 2', 1),
(5, 'Book 5 Title', 5678901234567, 'Author 1', 'Genre 3', 'Publisher 3', 4),
(6, 'Book 6 Title', 6789012345678, 'Author 2', 'Genre 3', 'Publisher 3', 2),
(7, 'Book 7 Title', 7890123456789, 'Author 5', 'Genre 4', 'Publisher 4', 3),
(8, 'Book 8 Title', 8901234567890, 'Author 6', 'Genre 4', 'Publisher 4', 6);

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Username`, `Email`, `PhoneNumber`, `Password`, `UserRole`)
VALUES
(1, 'Admin', 'User', 'adminuser', 'admin@example.com', '1234567890', 'adminpassword', 'Admin');

INSERT INTO `users` (`UserID`, `FirstName`, `LastName`, `Username`, `Email`, `PhoneNumber`, `Password`, `UserRole`)
VALUES
(2, 'Patron', 'User', 'patronuser', 'patron@example.com', '9876543210', 'patronpassword', 'Patron');
