<?php

class Team_model extends CI_Model {
    
    public function __construct() 
    { 
        parent::__construct();
	}
    
    function get_all() 
    {
        $query = $this->db->get('teams');
        return $query->result();
    }
    
    function add_team($team, $city, $trainer)
    {
        $data = array ('team' => $team,
                       'city' => $city,
                       'trainer' => $trainer);
        $query = $this->db->insert('teams', $data);
    }
}