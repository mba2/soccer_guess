USE soccer_guess;


-- ALL TEAMS
-- SELECT * FROM sg_teams;




-- ALL GROUPS
-- SELECT * FROM sg_groups;

  SELECT  *
    FROM sg_group_formation
    LEFT JOIN sg_teams USING(TEAM_ID)
    LEFT JOIN sg_groups USING(GROUP_ID);