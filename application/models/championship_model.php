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
        $this->db->insert('matches', array('tour_id' => $tour_id, 'team1_id' => $team1, 'team2_id' => $team2, 'tour_day' => $day));
    }
    
    
}
