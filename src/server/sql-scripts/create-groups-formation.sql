USE soccer_guess;
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
  (8,2);
  
  
  SELECT * FROM sg_group_formation
	INNER JOIN sg_teams USING(TEAM_ID)
    INNER JOIN sg_groups USING(GROUP_ID);
  