DROP TABLE IF EXISTS sg_group_formation;

CREATE TABLE sg_group_formation (
    TEAM_ID     TINYINT(5) AUTO_INCREMENT NOT NULL,
    GROUP_ID    TINYINT(5) AUTO_INCREMENT NOT NULL
    -- PRIMARY KEY(TEAM_ID,GROUP_ID)
)

/*
INSERT INTO sg_group_formation VALUES
  (1,1),
  (2,1),
  (3,1),
  (4,1),
  (5,2),
  (6,2),
  (7,2),
  (8,2)
*/