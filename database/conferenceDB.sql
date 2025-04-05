DROP DATABASE IF EXISTS conferenceDB;
CREATE DATABASE conferenceDB;
USE conferenceDB;

CREATE TABLE SubCommittee (
    SCid         INT NOT NULL,
    SCname       VARCHAR(50) NOT NULL,
    PRIMARY KEY(SCid)
);

CREATE TABLE Member (
    Mid         INT NOT NULL,
    FName       VARCHAR(20),
    LName       VARCHAR(20),
    PRIMARY KEY(Mid)
);

CREATE TABLE Attendee (
    Aid         INT NOT NULL,
    FName       VARCHAR(20),
    LName       VARCHAR(20),
    PRIMARY KEY(Aid)
);

CREATE TABLE SponsorCompany (
    SPCid       INT AUTO_INCREMENT PRIMARY KEY,
    SPCName     VARCHAR(50),
    SponsorLvl ENUM('Platinum', 'Gold', 'Silver', 'Bronze'),
    EMSent      INT DEFAULT NULL
);

CREATE TABLE HotelRoom (
    RoomNum   INT NOT NULL,
    NumOfBeds INT NOT NULL,
    PRIMARY KEY(RoomNum)
);

CREATE TABLE Session (
    SessionID INT AUTO_INCREMENT PRIMARY KEY,
    SName       VARCHAR(50) NOT NULL,
    Day         DATE NOT NULL,
    RoomNum     INT NOT NULL,
    StartTime   TIME,
    EndTime     TIME
);

CREATE TABLE Student (
    Aid INT NOT NULL,
    SRate INT,
    PRIMARY KEY(Aid),
    FOREIGN KEY (Aid) REFERENCES Attendee(Aid) ON DELETE CASCADE
);

CREATE TABLE Professional (
    Aid INT NOT NULL,
    PRate INT,
    PRIMARY KEY(Aid),
    FOREIGN KEY (Aid) REFERENCES Attendee(Aid) ON DELETE CASCADE
);

CREATE TABLE SponsorAttendee (
    Aid INT PRIMARY KEY,  
    SPCid INT NOT NULL,  
    FOREIGN KEY (Aid) REFERENCES Attendee(Aid) ON DELETE CASCADE,
    FOREIGN KEY (SPCid) REFERENCES SponsorCompany(SPCid) ON DELETE CASCADE
);

CREATE TABLE Speaker (
    Aid INT NOT NULL,
    PRIMARY KEY(Aid),
    FOREIGN KEY (Aid) REFERENCES Attendee(Aid) ON DELETE CASCADE
);

CREATE TABLE Ad (
    JobTitle     VARCHAR(50) NOT NULL, 
    LocationCity VARCHAR(50),         
    LocationProv VARCHAR(50),                    
    PayRate      DECIMAL(10, 2),                  
    SPCid        INT NOT NULL,                 
    PRIMARY KEY (JobTitle, SPCid),                   
    FOREIGN KEY (SPCid) REFERENCES SponsorCompany(SPCid) ON DELETE CASCADE
);

CREATE TABLE Attending (
    Aid INT NOT NULL,
    SessionID INT,
    PRIMARY KEY (Aid, SessionID),
    FOREIGN KEY (Aid) REFERENCES Attendee(Aid) ON DELETE CASCADE,
    FOREIGN KEY (SessionID) REFERENCES Session(SessionID)
);

CREATE TABLE Speaking (
    Aid INT NOT NULL,               
    SessionID INT,
    PRIMARY KEY (Aid, SessionID),  
    FOREIGN KEY (Aid) REFERENCES Speaker(Aid) ON DELETE CASCADE,
    FOREIGN KEY (SessionID) REFERENCES Session(SessionID)
);

CREATE TABLE Made_of (
    Mid INT NOT NULL,
    SCid INT NOT NULL,
    PRIMARY KEY (Mid, SCid),
    FOREIGN KEY (Mid) REFERENCES Member(Mid) ON DELETE CASCADE,
    FOREIGN KEY (SCid) REFERENCES SubCommittee(SCid) ON DELETE CASCADE
);

ALTER TABLE SubCommittee ADD COLUMN ChairMid INT UNIQUE;
ALTER TABLE SubCommittee ADD FOREIGN KEY (ChairMid) REFERENCES Member(Mid) ON DELETE SET NULL;

ALTER TABLE Student ADD COLUMN RoomNum INT;
ALTER TABLE Student ADD FOREIGN KEY (RoomNum) REFERENCES HotelRoom(RoomNum) ON DELETE SET NULL;


INSERT INTO SubCommittee (SCid, SCname) VALUES
(1, 'Program Committee'),
(2, 'Registration Committee'),
(3, 'Sponsorship Committee'),
(4, 'Logistics Committee'),
(5, 'Security Committee'),
(6, 'Speaker Committee');

INSERT INTO Member (Mid, FName, LName) VALUES
(1, 'Luka', 'Doncic'),
(2, 'Giannis', 'Antetokounmpo'),
(3, 'Scottie', 'Barnes'),
(4, 'Brandon', 'Ingram'),
(5, 'Sophia', 'Fung'),
(6, 'Ethan', 'Mah'),
(7, 'Wendy', 'Powley'),
(8, 'Steph', 'Curry');

INSERT INTO Made_of (Mid, SCid) VALUES
(1, 1), (2, 1), (3, 1),
(4, 2), (5, 2), (6, 2),
(7, 3), (8, 3),
(5, 4), (6, 4), 
(7, 5), (8, 5),
(1, 6), (2, 6); 

UPDATE SubCommittee SET ChairMid = 1 WHERE SCid = 1; 
UPDATE SubCommittee SET ChairMid = 3 WHERE SCid = 2; 
UPDATE SubCommittee SET ChairMid = 4 WHERE SCid = 3; 
UPDATE SubCommittee SET ChairMid = 5 WHERE SCid = 4; 
UPDATE SubCommittee SET ChairMid = 7 WHERE SCid = 5; 
UPDATE SubCommittee SET ChairMid = 6 WHERE SCid = 6;

INSERT INTO Attendee (Aid, FName, LName) VALUES
(1, 'RJ', 'Barrett'),
(2, 'Shai', 'Gilgeous-Alexander'),
(3, 'Kelly', 'Olynyk'),
(4, 'Nikola', 'Jokic'),
(5, 'Anthony', 'Edwards'),
(6, 'Alperun', 'Sengun'),
(7, 'Jalen', 'Brunson'),
(8, 'Josh', 'Hart'),
(9, 'Mikal', 'Bridges'),
(11, 'Michael', 'Scott'),
(12, 'Forrest', 'Frank'),
(13, 'Miles', 'Morales'),
(14, 'Ja', 'Morant');

INSERT INTO Student (Aid, SRate) VALUES 
(1, 50), (2, 50), (3, 50), (9, 50), (11, 50), (12, 50), (13, 50), (14,50);
INSERT INTO Professional (Aid, PRate) VALUES 
(4, 100), (5, 100), (6, 100), (11, 100); 

INSERT INTO SponsorCompany (SPCid, SPCName, SponsorLvl) VALUES
(1, 'QueensUni', 'Platinum'),
(2, '710Can', 'Gold'),
(3, 'KPop Sushi', 'Silver'),
(4, 'Mekong', 'Bronze'),
(5, 'Shelbys', 'Gold'),
(6, 'Costco', 'Platinum');

INSERT INTO SponsorAttendee (Aid, SPCid) VALUES
(7, 1), (8, 2),
(5, 3), (6, 4),
(2, 5); 

UPDATE SponsorCompany SET EMSent = 5 WHERE SPCid = 1; 
UPDATE SponsorCompany SET EMSent = 4 WHERE SPCid = 2; 
UPDATE SponsorCompany SET EMSent = 3 WHERE SPCid = 3;
UPDATE SponsorCompany SET EMSent = 0 WHERE SPCid = 4;
UPDATE SponsorCompany SET EMSent = 4 WHERE SPCid = 5;

INSERT INTO HotelRoom (RoomNum, NumOfBeds) VALUES
(101, 2), (102, 3), (103, 2), (104, 3), (105, 1);

UPDATE Student SET RoomNum = 101 WHERE Aid = 1; 
UPDATE Student SET RoomNum = 101 WHERE Aid = 2; 
UPDATE Student SET RoomNum = 102 WHERE Aid = 3; 
UPDATE Student SET RoomNum = 102 WHERE Aid = 11; 
UPDATE Student SET RoomNum = 103 WHERE Aid = 12;
UPDATE Student SET RoomNum = 103 WHERE Aid = 13;
UPDATE Student SET RoomNum = 104 WHERE Aid = 14; 
UPDATE Student SET RoomNum = 105 WHERE Aid = 9;

INSERT INTO Ad (JobTitle, LocationCity, LocationProv, PayRate, SPCid) VALUES
('Software Professor', 'Kingston', 'Ontario', 49000, 1), 
('Cashier', 'New York', 'New York', 32000, 2),
('Chef', 'Toronto', 'Ontario', 70000, 3),
('Taster', 'Vancouver', 'British Columbia', 60000, 4), 
('Media Manager', 'Los Angeles', 'California', 75000, 5),
('Food Court', 'Saskatoon', 'Saskachewan', 100000, 6); 

INSERT INTO Session (SName, Day, StartTime, EndTime, RoomNum) VALUES
('Machine Learning', '2025-05-01', '09:00:00', '11:00:00', 101),
('Data Security', '2025-05-01', '11:30:00', '13:30:00', 102),
('Blockchain', '2025-05-02', '09:00:00', '11:00:00', 101),
('Cloud Computing', '2025-05-02', '14:00:00', '16:00:00', 102),
('AI Ethics', '2025-05-02', '16:30:00', '18:00:00', 103),
('Quantum Computing', '2025-05-02', '09:00:00', '11:00:00', 104);

UPDATE Attending a, Session s
SET a.SessionID = s.SessionID
WHERE a.Aid = 1 AND s.SName = 'Machine Learning' AND s.Day = '2025-05-01' AND s.RoomNum = 101;

UPDATE Attending a, Session s
SET a.SessionID = s.SessionID
WHERE a.Aid = 2 AND s.SName = 'Data Security' AND s.Day = '2025-05-01' AND s.RoomNum = 102;

UPDATE Attending a, Session s
SET a.SessionID = s.SessionID
WHERE a.Aid = 3 AND s.SName = 'Blockchain' AND s.Day = '2025-05-02' AND s.RoomNum = 101;

UPDATE Attending a, Session s
SET a.SessionID = s.SessionID
WHERE a.Aid = 4 AND s.SName = 'Cloud Computing' AND s.Day = '2025-05-02' AND s.RoomNum = 102;

UPDATE Attending a, Session s
SET a.SessionID = s.SessionID
WHERE a.Aid = 5 AND s.SName = 'AI Ethics' AND s.Day = '2025-05-02' AND s.RoomNum = 103;

UPDATE Attending a, Session s
SET a.SessionID = s.SessionID
WHERE a.Aid = 6 AND s.SName = 'Quantum Computing' AND s.Day = '2025-05-02' AND s.RoomNum = 104;

UPDATE Speaking s, Session se
SET s.SessionID = se.SessionID
WHERE s.Aid = 1 AND se.SName = 'Machine Learning' AND se.Day = '2025-05-01' AND se.RoomNum = 101;

UPDATE Speaking s, Session se
SET s.SessionID = se.SessionID
WHERE s.Aid = 2 AND se.SName = 'Data Security' AND se.Day = '2025-05-01' AND se.RoomNum = 102;

UPDATE Speaking s, Session se
SET s.SessionID = se.SessionID
WHERE s.Aid = 3 AND se.SName = 'Blockchain' AND se.Day = '2025-05-02' AND se.RoomNum = 101;

UPDATE Speaking s, Session se
SET s.SessionID = se.SessionID
WHERE s.Aid = 4 AND se.SName = 'Cloud Computing' AND se.Day = '2025-05-02' AND se.RoomNum = 102;

UPDATE Speaking s, Session se
SET s.SessionID = se.SessionID
WHERE s.Aid = 5 AND se.SName = 'AI Ethics' AND se.Day = '2025-05-02' AND se.RoomNum = 103;

UPDATE Speaking s, Session se
SET s.SessionID = se.SessionID
WHERE s.Aid = 6 AND se.SName = 'Quantum Computing' AND se.Day = '2025-05-02' AND se.RoomNum = 104;