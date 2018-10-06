DROP database if exists NBAStats;

CREATE database NBAStats;
USE NBAStats;

SET SQL_SAFE_UPDATES = 0;

CREATE TABLE Team (
      teamName varchar(25),
      teamLocation varchar(25) NOT NULL,
      PRIMARY KEY (teamName)
);

CREATE TABLE Coach (
	  firstName varchar(25) NOT NULL,
      lastName varchar(25) NOT NULL,
      numberOfWins int,
      numberOfLoses int,
      team varchar(25),
      coachID int NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (coachID),
	  FOREIGN KEY (team) REFERENCES Team(teamName) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE Player  (
      firstName varchar(25) NOT NULL,
      lastName varchar(25) NOT NULL,
      height int,
      age int,
      playerID int NOT NULL AUTO_INCREMENT,
      team varchar(25),
      PRIMARY KEY (playerID),
      FOREIGN KEY (team) REFERENCES Team(teamName) ON UPDATE CASCADE ON DELETE SET NULL
);


CREATE TABLE Season (
	  yearOfSeason int NOT NULL AUTO_INCREMENT,
      mvp int,
      champion varchar(25),
      FOREIGN KEY (champion) REFERENCES Team(teamName) ON UPDATE CASCADE ON DELETE SET NULL,
      FOREIGN KEY (mvp) REFERENCES Player(playerID) ON UPDATE CASCADE ON DELETE SET NULL,
      PRIMARY KEY(yearOfSeason)
);

CREATE TABLE Game (
       typeOfGame enum('season','playoff','final') NOT NULL,
       gameNumber int NOT NULL AUTO_INCREMENT,
       yearOfSeason int NOT NULL,
       homeTeam varchar(25) NOT NULL,
       awayTeam varchar(25) NOT NULL,
	   PRIMARY KEY (gameNumber),
       FOREIGN KEY (homeTeam) REFERENCES Team(teamName) ON UPDATE CASCADE ON DELETE CASCADE,
	   FOREIGN KEY (awayTeam) REFERENCES Team(teamName) ON UPDATE CASCADE ON DELETE CASCADE,
       FOREIGN KEY (yearOfSeason) REFERENCES Season(yearOfSeason) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE GameStats (
    gameNumber int NOT NULL, 
	playerID int NOT NULL,
    minutes int,
    points int,
    fg int,
    fga int, 
    rebounds int,
    assists int,
    steals int,
    blocks int,
    team ENUM ('home', 'away'), 
    PRIMARY KEY (gameNumber, PlayerID),
	FOREIGN KEY (playerID) REFERENCES Player(playerID) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (gameNumber) REFERENCES Game(gameNumber) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Stadium (
      seasonBuilt int NOT NULL, 
      capacity int NOT NULL,
      city varchar(25) NOT NULL,
      team varchar(25),
      stadiumID int NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (stadiumID),
      FOREIGN KEY (team) REFERENCES Team(teamName) ON UPDATE CASCADE ON DELETE SET NULL,
	  FOREIGN KEY (seasonBuilt) REFERENCES Season(yearOfSeason) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Contract (
      seasonSigned int NOT NULL,
      lengthOfContract int NOT NULL,
      salary int NOT NULL,
      team varchar(25) NOT NULL,
      playerID int NOT NULL,
      active int DEFAULT  1,
      contractID int NOT NULL AUTO_INCREMENT,
      PRIMARY KEY (contractID),
      FOREIGN KEY (playerID) REFERENCES Player(playerID) ON UPDATE CASCADE ON DELETE CASCADE,
      FOREIGN KEY (seasonSigned) REFERENCES Season(yearOfSeason) ON UPDATE CASCADE ON DELETE CASCADE,
      FOREIGN KEY (team) REFERENCES Team(teamName) ON UPDATE CASCADE ON DELETE CASCADE
);


DELIMITER //

CREATE TRIGGER update_curr_team
AFTER INSERT ON Contract
   FOR EACH ROW
   BEGIN 
   UPDATE Player p
    SET p.team = NEW.team
    WHERE p.playerID = NEW.playerID;
    END//
    
DELIMITER ;

DELIMITER //

CREATE TRIGGER cut_player_update
AFTER UPDATE ON Contract 
   FOR EACH ROW
   BEGIN 
   if (NEW.active = 0) THEN
       UPDATE Player p
          SET p.team = NULL
          WHERE  p.playerID = NEW.playerID;
    END IF;
    END//
    
DELIMITER ;

DELIMITER //

CREATE PROCEDURE sum_points_team
(
     gameNum int,
     team_insert enum ('home', 'away')
)
BEGIN
     SELECT SUM(points) as Points
       FROM GameStats 
       NATURAL JOIN Game
       WHERE gameNumber = gameNum &&
                      team = team_insert;
END//

DELIMITER ;

DELIMITER //

CREATE FUNCTION determine_winner
(
     gameNum int
)
RETURNS varchar(25)
BEGIN

   DECLARE pointsHome INT;
   DECLARE pointsAway INT;
   DECLARE winner varchar(25);
   
     SELECT SUM(points) 
	   INTO PointsHome
       FROM GameStats 
       NATURAL JOIN Game
       WHERE gameNumber = gameNum &&
                      team = 'home';
       
     SELECT SUM(points) 
	   INTO PointsAway
       FROM GameStats 
       NATURAL JOIN Game
       WHERE gameNumber = gameNum &&
                      team = 'away';
    
    IF (pointsHome > pointsAway) THEN
        SELECT homeTeam
		INTO  winner
           FROM Game
           WHERE gameNumber = gameNum;
	ELSE
        SELECT awayTeam
		INTO  winner
           FROM Game
           WHERE gameNumber = gameNum;
	END IF;
    RETURN winner;

END//

DELIMITER ;

DELIMITER //

CREATE TRIGGER year_increase
AFTER INSERT ON Season
FOR EACH ROW
BEGIN
  UPDATE Player p
    SET p.age = p.age + 1;
  UPDATE Contract
    SET active = 0
    WHERE seasonSigned + lengthOfContract < NEW.yearOfSeason;
END//

DELIMITER ;

DELIMITER $$
CREATE PROCEDURE get_roster
(
    team_name varchar(25)
)
BEGIN
  SELECT firstName, lastName
   FROM Player p
   WHERE team_name = team;
END$$
    
DELIMITER ;


DELIMITER //

CREATE TRIGGER has_enough_players
BEFORE INSERT ON Game
FOR EACH ROW
BEGIN

  DECLARE playersHome INT;
  DECLARE playersAway INT;
   
  SELECT COUNT(*)
     INTO playersHome
     FROM Player p 
	 JOIN Team t
     ON p.team = t.teamName
     WHERE teamName = NEW.homeTeam;
   
  SELECT COUNT(*)
     INTO playersAway
     FROM Player p 
	 JOIN Team t
     ON p.team = t.teamName
     WHERE team = NEW.awayTeam;
   
   IF playersHome < 5 || playersAway < 5  THEN
   SIGNAL SQLSTATE '45000' 
   SET MESSAGE_TEXT = 'not enough players';
   END IF;

END//

DELIMITER ;

DELIMITER //
CREATE TRIGGER already_in_game_or_wrong_team
BEFORE INSERT ON GameStats
FOR EACH ROW
BEGIN
DECLARE  c int;
DECLARE teamOfInsert varchar(25);



SELECT COUNT(*)
INTO c
FROM GameStats g
   WHERE g.gameNumber = new.gameNumber && g.playerID = new.playerID;
   

IF (c != 0) THEN  
   SIGNAL SQLSTATE '45000' 
   SET MESSAGE_TEXT = 'this player has already been accounted for';
   ELSE 
		IF NEW.team = 'home' THEN
        SELECT homeTeam
        INTO teamOfInsert
        FROM Game
        WHERE gameNumber = NEW.gameNumber;
        
	    ELSE
		SELECT awayTeam
        INTO teamOfInsert
        FROM Game
        WHERE gameNumber = NEW.gameNumber;
        
        END IF;
        
        SELECT COUNT(*)
        INTO c
        FROM Player
        WHERE PlayerID = new.PlayerID
					&& team = teamOfInsert;
                    
		if (c != 1) THEN
           SIGNAL SQLSTATE '45000' 
		   SET MESSAGE_TEXT = 'this player is not on the correct team';
		END IF;
   END IF;


END//

DELIMITER ;

DELIMITER //

CREATE PROCEDURE get_boxscore
(
     gameNum int
)
BEGIN
     SELECT g.team, firstName, lastName, minutes, points, fg, fga,  rebounds, assists, steals, blocks
       FROM GameStats g
    JOIN Player p
    ON p.playerID = g.playerID
       WHERE g.gameNumber = gameNum
       ORDER BY team, minutes DESC;
END//

DELIMITER ;

DELIMITER //

CREATE PROCEDURE get_season_stats
(
    season int
)
BEGIN
  SELECT  firstName, lastName, SUM(minutes), SUM(points), SUM(fg), SUM(fga), 
                   SUM(rebounds), SUM(assists), SUM(steals), SUM(blocks), COUNT(p.playerID) AS games_played
  FROM GameStats g
    JOIN Player p
    ON p.playerID = g.playerID
    JOIN Game ga
    ON ga.gameNumber = g.gameNumber
  WHERE ga.yearOfSeason = season
  GROUP BY p.playerID;
END//

DELIMITER ;

DELIMITER //
CREATE PROCEDURE get_per_game_stats
(
    season int
)
BEGIN
  SELECT  firstName, lastName, SUM(minutes) / COUNT(p.playerID), SUM(points)/COUNT(p.playerID), SUM(fg)/COUNT(p.playerID), SUM(fga)/COUNT(p.playerID), 
                   SUM(rebounds)/COUNT(p.playerID), SUM(assists)/COUNT(p.playerID), SUM(steals)/COUNT(p.playerID), SUM(blocks)/COUNT(p.playerID), 
                   COUNT(p.playerID) AS games_played
  FROM GameStats g
    JOIN Player p
    ON p.playerID = g.playerID
    JOIN Game ga
    ON ga.gameNumber = g.gameNumber
  WHERE ga.yearOfSeason = season
  GROUP BY g.playerID;
END//
DELIMITER;

DELIMITER //

CREATE PROCEDURE get_free_agents
(
)
BEGIN
  SELECT  firstName, lastName, height, age
  FROM Player
  WHERE team = null;
END//

DELIMITER ;


INSERT INTO Team (teamLocation, teamName)
VALUES ('Seattle', 'Emeralds'), ('New York', 'Cyborgs'), ('Toronto', 'Legends'), ('Boston', 'Bandits');
INSERT INTO Coach (firstname, lastname,numberOfWins,numberOfLoses,Team)
VALUES ('Jonathan', 'Smith', 112, 89, 'Emeralds'), ('Kevin', 'James', 0, 0, 'Cyborgs'), ('Willie', 'Thompson', 321, 284, 'Legends'), ('Patrick', 'McDavid', 104, 275, 'Bandits');
INSERT INTO Player (firstname, lastname, height, age, team)
 VALUES ('Demarcus', 'Fox', 79, 24,  'Emeralds'), ('Lawrence', 'Campbell', 72, 28, 'Emeralds'), ('Jason', 'Taylor', 82, 31, 'Emeralds'), ('David', 'Carpenter', 76, 29, 'Emeralds'),
 ('Ben-Jarvis', 'Green-Ellis', 92, 27, 'Emeralds'), ('LeBron', 'James', 80, 30, 'Bandits'), ('Tiny', 'Archibald', 60, 42, 'Bandits'), ('Coach', 'Wade', 64, 47, 'Bandits'),
 ('Michael', 'Carter-Williams', 68, 25, 'Bandits'), ('Tom', 'Brady', 66, 40, 'Bandits'), ('Joe', 'Shmo', 66, 40, 'Legends');


INSERT INTO Season (mvp, champion)
VALUES(1, 'Cyborgs'), (1,null);
INSERT INTO Game (typeofgame, yearofseason, homeTeam, awayTeam)
VALUES('season', 1,'Emeralds', 'Bandits'), ('season', 1,'Bandits', 'Emeralds');
INSERT INTO GameStats(gameNumber, playerID, minutes, points, fg, fga, rebounds, assists, steals, blocks,team)
VALUES(1, 1, 43,22,11,14,12,8,5,3,'home'), (1, 2, 26,2,1,0,12,3,1,0,'home'), (1, 3, 34,6,2,5,5,6,0,3,'home'), (1, 4, 33,48,20,33,11,8,1,2,'home'),
 (1, 6, 6, 3,2,1,3,2,0,0,'away'), (1, 7, 48,62,30,55,15,2,4,8,'away'),  (1, 8, 40,1,0,0,3,1,2,1,'away'),
 (2, 1, 43,22,11,14,12,8,5,3,'away');
INSERT INTO Stadium (seasonBuilt, capacity, city, team)
VALUES(1, 5000, 'Seattle', 'Emeralds');
INSERT INTO Contract (seasonSigned, lengthofcontract, salary, team, playerId)
VALUES(1, 5, 200000, 'Emeralds', 1), (2,2,400000, 'Emeralds', 2) , (2,2,400000, 'Emeralds', 11);

DELETE FROM Season WHERE yearOfSeason = 1;
