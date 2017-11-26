INSERT INTO SG_TOURNAMENTS VALUES
    (NULL, "SOUTH AMERCIA'S QUALIFIERS 2022",NULL,1),
    (NULL, "WORLD CUP 2018",NULL,1);

INSERT INTO SG_GROUPS (`NAME`,`TOURNAMENT_ID`)VALUES 
  ("A",1),
  ("A",2),
  ("B",2),
  ("C",2),
  ("D",2),
  ("E",2),
  ("F",2),
  ("G",2),
  ("H",2),
  ("I",2),
  ("J",2),
  ("K",2),
  ("L",2);

INSERT INTO SG_TEAMS VALUES
  (NULL,'brazil','1','brazil.png',1),
  (NULL,'germany','2','germany.png',1),
  (NULL,'URUGUAI','3','URUGUAI.png',1),
  (NULL,'ITALY','4','ITALY.png',1),
  (NULL,'CHILE','5','CHILE.png',1),
  (NULL,'france','6','france.png',1),
  (NULL,'spain','7','spain.png',1),
  (NULL,'argentina','8','argentina.png',1),  
  (NULL,'colombia','9','colombia.png',1),
  (NULL,'england','10','england.png',1),
  (NULL,'japan','11','japan.png',1),
  (NULL,'china','12','china.png',1),
  (NULL,'EQUADOR','13','EQUADOR.png',1),
  (NULL,'PORTUGAL','14','PORTUGAL.png',1);

INSERT INTO SG_GROUP_FORMATIONS (TEAM_ID,GROUP_ID) VALUES 
(1,1),
(2,2),
(3,1),
(4,2),
(5,1),
(6,2),
(7,2),
(8,1),
(9,1),
(10,2),
(11,2),
(12,2),
(13,2),
(14,2);
