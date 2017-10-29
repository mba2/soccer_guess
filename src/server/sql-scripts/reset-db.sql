USE soccer_guess;


DROP TABLE IF EXISTS sg_teams;

CREATE TABLE sg_teams (
  TEAM_ID           TINYINT(5) AUTO_INCREMENT NOT NULL,
  TEAM_FULLNAME     VARCHAR(255) NOT NULL UNIQUE,
  TEAM_SHORTNAME    VARCHAR(3) NOT NULL UNIQUE,
  TEAM_FLAG         VARCHAR(255) NULL UNIQUE,
  PRIMARY KEY(TEAM_ID)
);

INSERT INTO sg_teams VALUES
  (NULL,'TEAM 1','1','team_1.png'),
  (NULL,'TEAM 2','2','team_2.png'),
  (NULL,'TEAM 3','3','team_3.png'),
  (NULL,'TEAM 4','4','team_4.png'),
  (NULL,'TEAM 5','5','team_5.png'),
  (NULL,'TEAM 6','6','team_6.png'),
  (NULL,'TEAM 7','7','team_7.png'),
  (NULL,'TEAM 8','8','team_8.png'),  
  (NULL,'TEAM 9','9','team_9.png'),
  (NULL,'TEAM 10','10','team_10.png'),
  (NULL,'TEAM 11','11','team_11.png'),
  (NULL,'TEAM 12','12','team_12.png'),
  (NULL,'TEAM 13','13','team_13.png'),
  (NULL,'TEAM 14','14','team_14.png'),
  (NULL,'TEAM 15','15','team_15.png'),
  (NULL,'TEAM 16','16','team_16.png'),
  (NULL,'TEAM 17','17','team_17.png'),
  (NULL,'TEAM 18','18','team_18.png'),
  (NULL,'TEAM 19','19','team_19.png'),
  (NULL,'TEAM 20','20','team_20.png'),
  (NULL,'TEAM 21','21','team_21.png'),
  (NULL,'TEAM 22','22','team_22.png'),
  (NULL,'TEAM 23','23','team_23.png'),
  (NULL,'TEAM 24','24','team_24.png'),
  (NULL,'TEAM 25','25','team_25.png'),
  (NULL,'TEAM 26','26','team_26.png'),
  (NULL,'TEAM 27','27','team_27.png'),
  (NULL,'TEAM 28','28','team_28.png'),
  (NULL,'TEAM 29','29','team_29.png'),
  (NULL,'TEAM 30','30','team_30.png'),
  (NULL,'TEAM 31','31','team_31.png'),
  (NULL,'TEAM 32','32','team_32.png');






DROP TABLE IF EXISTS sg_groups;

CREATE TABLE sg_groups (
  GROUP_ID    TINYINT(5) AUTO_INCREMENT NOT NULL,
  GROUP_NAME  CHAR(1) NOT NULL UNIQUE,
  PRIMARY KEY(GROUP_ID)
); 

 INSERT INTO sg_groups VALUES
  (NULL,'A'),
  (NULL,'B'),
  (NULL,'C'),
  (NULL,'D'),
  (NULL,'E'),
  (NULL,'F'),
  (NULL,'G'),
  (NULL,'H');





DROP TABLE IF EXISTS sg_group_formation;
CREATE TABLE sg_group_formation (
    TEAM_ID     TINYINT(5) NOT NULL UNIQUE,
    GROUP_ID    TINYINT(5) NOT NULL,
    PRIMARY KEY(TEAM_ID,GROUP_ID),
    FOREIGN KEY(TEAM_ID) REFERENCES sg_teams(TEAM_ID),
	FOREIGN KEY(GROUP_ID) REFERENCES sg_groups(GROUP_ID)
);

INSERT INTO sg_group_formation VALUES
  (1,1),
  (2,1),
  (3,1),
  (4,1),
  (5,2),
  (6,2),
  (7,2),
  (8,2),
  (9,3),
  (10,3),
  (11,3),
  (12,3),
  (13,4),
  (14,4),
  (15,4),
  (16,4),
  (17,5),
  (18,5),
  (19,5),
  (20,5),
  (21,6),
  (22,6),
  (23,6),
  (24,6),
  (25,7),
  (26,7),
  (27,7),
  (28,7),
  (29,8),
  (30,8),
  (31,8),
  (32,8);
  

