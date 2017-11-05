USE SOCCER_GUESS;

DROP TABLE IF EXISTS SG_TEAMS;


CREATE TABLE SG_TEAMS (
  'ID'           TINYINT   NOT NULL AUTO_INCREMENT,
  'FULLNAME'     VARCHAR(100) NOT NULL UNIQUE,
  'SHORTNAME'    CHAR(3) NOT NULL UNIQUE,
  'FLAG'         VARCHAR(255) DEFAULT 'team_flag.png',
  PRIMARY KEY(ID)
);

INSERT INTO SG_TEAMS VALUES
  (NULL,'BRAZIL','BRA','brazil_flag.png'),
  (NULL,'ARGENTINA','ARG','argentina_flag.png'),
  (NULL,'URUGAI','URU','urugai_flag.png'),
  (NULL,'PARAGUAI','PAR','paraguai_flag.png'),
  (NULL,'CHILE','CHILE','chile_flag.png');
