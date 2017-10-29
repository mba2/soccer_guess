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
  