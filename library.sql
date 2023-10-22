DROP DATABASE IF EXISTS library;

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
  `UserRole` enum('Admin', 'Patron') NOT NULL,
  `TotalFines` FLOAT NOT NULL
);

CREATE TABLE `borrowed_books` (
  `LoanID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `BookID` int NOT NULL,
  `UserID` int NOT NULL,
  `DateBorrowed` date NOT NULL,
  `DateDue` date NOT NULL,
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

CREATE TABLE `returned_books` (
  `ReturnID` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `LoanID` int NOT NULL,
  `BookID` int NOT NULL,
  `UserID` int NOT NULL,
  `ReturnDate` date NOT NULL,
  `FineAmount` float NOT NULL,
  FOREIGN KEY `LoanID` (`LoanID`)  REFERENCES `borrowed_books`(`LoanID`),
  FOREIGN KEY `BookID` (`BookID`) REFERENCES `books`(`BookID`),
  FOREIGN KEY `UserID` (`UserID`) REFERENCES `users`(`UserID`)
);


CREATE TABLE payments (
    PaymentID INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    UserID INT NOT NULL,
    Amount INT NOT NULL,
    PaymentDate DATE NOT NULL,
    FOREIGN KEY (UserID) REFERENCES users(UserID)
);

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

INSERT INTO `borrowed_books` ( `BookID`, `UserID`, `DateBorrowed`, `DateDue`) VALUES
(2, 2, '2023-06-06', '2023-10-04');

