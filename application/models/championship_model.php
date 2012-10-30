<?php

class Championship_model extends CI_Model {
    
    public function __construct() 
    { 
        parent::__construct();
    }
    
    // Очистка таблицы туров
    public function clear_tours()
    {
        $this->db->truncate('tours');
    }
    
    // Добавление нового тура
    public function add_tour($date)
    {
        $this->db->insert('tours', array('start_date' => $date));
        return $this->db->insert_id(); 
    }
    
    public function get_all_tours()
    {
        $query = $this->db->get('tours');
        return $query->result();
    }
}
