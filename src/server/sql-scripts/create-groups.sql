USE soccer_guess;
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