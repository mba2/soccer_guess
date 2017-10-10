DROP TABLE IF EXISTS sg_teams;

CREATE TABLE sg_teams (
  TEAM_ID           TINYINT(5) AUTO_INCREMENT NOT NULL,
  TEAM_FULLNAME     VARCHAR(255) NOT NULL UNIQUE,
  TEAM_SHORTNAME    VARCHAR(3) NOT NULL UNIQUE,
  TEAM_FLAG         VARCHAR(255) NULL,
  PRIMARY KEY(TEAM_ID)
)


/*
 INSERT INTO sg_teams VALUES
  (NULL,'BRASIL','BRA','brazil.png'),
  (NULL,'RÚSSIA','RUS','russia.png'),
  (NULL,'ALEMANHA','ALE','alemanha.png'),
  (NULL,'ITÁLIA','ITA','russia.png'),
  (NULL,'ARGENTINA','ARG','argentina.png'),
  (NULL,'HOLANDA','HOL','holanda.png'),
  (NULL,'FRA','FRA','franca.png'),
  (NULL,'ESPANHA','ESP','epanha.png')
*/