<?php

class Championship_model extends CI_Model {
    
    public function __construct() 
    { 
        parent::__construct();
    }
    
    // Очистка таблицы туров
    function clear_calendar()
    {
        $this->db->truncate('tours');
        $this->db->truncate('matches');
    }
    
    // Добавление нового тура
    function add_tour($date)
    {
        $this->db->insert('tours', array('start_date' => $date));
        return $this->db->insert_id(); 
    }
    
    function get_all_tours()
    {
        $query = $this->db->get('tours');
        return $query->result();
    }
    
    function add_match($tour_id, $team1, $team2, $day)
    {
        $this->db->insert('matches', array('tour_id' => $tour_id, 'team1_id' => $team1, 'team2_id' => $team2, 'day_offset' => $day));
    }
    
    function get_matches_in_tour($tour_id)
    {
        $this->db->where(array('tour_id' => $tour_id));
        $this->db->order_by('day_offset', 'asc'); 
        $query = $this->db->get('matches');
        return $query->result();
    }
    
    function get_matches_in_tour_by_team($tour_id, $team)
    {
        // Получаем id команд по названию
        $this->db->like('team', $team);
        $teams = $this->db->get('teams')->result();
        
        if(count($teams))
        {
            foreach ($teams as $team)
            {
                $teams_id[] = $team->id;
            }
            $team_id_str = implode($teams_id, ',');

            $query = "SELECT * FROM  `matches` WHERE 
                        `tour_id` = $tour_id AND
                        (`team1_id` IN ($team_id_str)
                        OR `team2_id` IN ($team_id_str))";

            return $this->db->query($query)->result();
        }
        else return false;
        
    }
    
    function delete_match_result($id)
    {
        $this->db->update('matches', array('team1_goals' => null, 'team2_goals' => null), array('id' => $id));
    }
    
    function get_match($id)
    {
        $this->db->where(array('id' => $id));
        $query = $this->db->get('matches');
        return $query->row();
    }
    
    function update_match_result($id, $team1_goals, $team2_goals)
    {
        // Чтоб бд не преобразовывала null в 0
        $data = array(  'team1_goals' => $team1_goals === '' ? null : $team1_goals,
                        'team2_goals' => $team2_goals === '' ? null : $team2_goals);
        $this->db->update('matches', $data, array('id' => $id));
    }
    
    // Получить все матчи, в которых участвовала команда
    function get_team_matches($team_id)
    {
        // Получаем все матчи, где указанная команда были team1 или team2
        // где указан счет
        $query = "SELECT * FROM  `matches` WHERE  
                    `team1_goals` IS NOT NULL AND  `team2_goals` IS NOT NULL AND 
                    (`team1_id` = $team_id OR  `team2_id` = $team_id)";
        
        return $this->db->query($query)->result();
    }
}
