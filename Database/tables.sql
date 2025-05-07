drop DATABASE Don_Bosco;
CREATE DATABASE Don_Bosco;
USE Don_Bosco;


CREATE TABLE users (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    First_name VARCHAR(255),
    Last_name VARCHAR(255),
    social_security_number VARCHAR(13),
    Username VARCHAR(255) not null unique,
    Password VARCHAR(255),
    Email VARCHAR(255) not null,
    Level VARCHAR(255),
    user_must_change_password boolean
    /* Zero is considered as false, nonzero values are considered as true.  */
);

INSERT INTO users (First_name, Last_name, social_security_number, Username, Password, Email, Level, user_must_change_password)
VALUES
("abolo", "ahmadi", "2003122601327", "admin", "password", "admin@gmail.com", "aDmin", 0),
("abolo", "ahmadi", "2003122601327", "abolo123", "123", "abolo@gmail.com", "cusTomer", 1);


CREATE TABLE IF NOT EXISTS reservation (
    ReservationID INT PRIMARY KEY AUTO_INCREMENT,
    Reserved_by_userID INT,
    StartMoment DATETIME,
    FOREIGN KEY (Reserved_by_userID) REFERENCES users(UserID) ON DELETE CASCADE
);

/* 
insert into reservation(Reserved_by_userID,StartMoment) Values(2, "2025-04-28 12:00");
insert into reservation(Reserved_by_userID,StartMoment) Values(2, "2025-04-30 10:00");
insert into reservation(Reserved_by_userID,StartMoment) Values(2, "2025-05-03 08:00"); 
*/