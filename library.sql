CREATE DATABASE library;
USE library;

CREATE TABLE `books` (
  `BookID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,  
  image VARCHAR(255),
  `Title` varchar(500) NOT NULL,
  `ISBN` int(13) NOT NULL,
  `Author` varchar(500) NOT NULL,
  `Genre` varchar(500) NOT NULL,
  `Publisher` varchar(500) NOT NULL,
  `Quantity` int(11) NOT NULL
);

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` varchar(25) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `UserRole` enum('Admin', 'Patron') NOT NULL
);

CREATE TABLE `borrowed_books` (
  `LoanID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
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
  `FineID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
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


INSERT INTO `books` ( `image`, `Title`, `ISBN`, `Author`, `Genre`, `Publisher`, `Quantity`) 
VALUES
('resources/silent-patient.jpg', 'The Silent Patient', 2147483647, 'Alex Michaelides', 'Thriller', 'Celadon Books', 0),
('resources/becoming.jpg', 'Becoming', 2147483647, 'Michelle Obama', 'Memoir', 'Crown Publishing Group', 3),
('resources/educated.jpg', 'Educated', 2147483647, 'Tara Westover', 'Biography', 'Random House', 1),
('resources/CK-3.webp', 'The Great Gatsby', 2147483647, 'F. Scott Fitzgerald', 'Classic', 'Scribner', 4),
('resources/martian.jpg', 'The Martian', 2147483647, 'Andy Weir', 'Science Fiction', 'Crown Publishing', 2),
('resources/train.png', 'The Girl on the Train', 2147483647, 'Paula Hawkins', 'Mystery', 'Riverhead Books', 3),
('resources/sapiens.jpg', 'Sapiens: A Brief History of Humankind', 2147483647, 'Yuval Noah Harari', 'History', 'Harper', 6);
COMMIT;


INSERT INTO `users` (`FirstName`, `LastName`, `Username`, `Email`, `PhoneNumber`, `Password`, `UserRole`)
VALUES
('Admin', 'User', 'adminuser', 'admin@example.com', '1234567890', '1234', 'Admin');

INSERT INTO `users` (`FirstName`, `LastName`, `Username`, `Email`, `PhoneNumber`, `Password`, `UserRole`)
VALUES
('Patron', 'User', 'patronuser', 'patron@example.com', '9876543210', '1234', 'Patron');

INSERT INTO `borrowed_books` ( `BookID`, `UserID`, `DateBorrowed`, `DateDue`, `DateReturned`, `LoanStatus`) VALUES
(2, 2, '2023-08-21', '2023-10-04', '0000-00-00', 'Not Returned');

