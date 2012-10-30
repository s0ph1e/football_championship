<?php

class Team_model extends CI_Model {
    
    public function __construct() 
    { 
        parent::__construct();
	}
    
    // Получение списка всех комманд
    function get_all() 
    {
        $query = $this->db->get('teams');
        return $query->result();
    }
    
    // Добавление новой команды
    function add_team($team, $city, $trainer)
    {
        $data = array ('team' => $team,
                       'city' => $city,
                       'trainer' => $trainer);
        $this->db->insert('teams', $data);
    }
    
    // Удаление команды
    function delete_team($id)
    {
        $this->db->delete('teams', array( 'id' => $id));
    }
}