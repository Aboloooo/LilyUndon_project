CREATE DATABASE IF NOT EXISTS Don_Bosco;
USE Don_Bosco;


CREATE TABLE users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255),
    Password VARCHAR(255),
    First_name VARCHAR(255),
    Last_name VARCHAR(255),
    Email VARCHAR(255),
    Level VARCHAR(255),
    user_must_change_password boolean;
    /* Zero is considered as false, nonzero values are considered as true.  */
);

INSERT INTO users (Username, Password, First_name, Last_name, Email, Level, user_must_change_password)
VALUES
('admin', 'password', 'abolo', 'ahmadi', 'abolo@gmail.com', 'admin', 0), 
('abolo123', '123', 'abolo', 'ahmadi', 'abolo@gmail.com', 'user', 1);

CREATE TABLE IF NOT EXISTS reservation (
    ReservationID INT PRIMARY KEY AUTO_INCREMENT,
    Reserved_by_userID INT,
    StartTime TIME,
    EndTime TIME,
    ReserveDate DATE,
    FOREIGN KEY (Reserved_by_userID) REFERENCES users(UserID) ON DELETE CASCADE
);
