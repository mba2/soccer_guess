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
  (NULL,'TEAM 8','8','team_8.png');  

  SELECT * FROM sg_teams;

