<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Team_model extends CI_Model {
    
    public function __construct() 
    { 
        parent::__construct();
    }
    
    // Получение списка всех комманд
    function get_all() 
    {
        return $this->db->get('teams')
                        ->result();
    }
    
    // Получение id всех команд
    function get_all_team_id()
    {
        $query = $this->db->select('id')
                          ->get('teams')
                          ->result();
        foreach ($query as $row)
        {
            $result[] = $row->id;
        }
        return $result;
    }
    
    function teams_count()
    {
        return $this->db->count_all('teams');
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
        $this->db->delete('matches', array('team1_id' => $id));
        $this->db->delete('matches', array('team2_id' => $id));
    }
    
    function update_team($id, $team, $city, $trainer)
    {
        $data = array ( 'team' => $team,
                        'city' => $city,
                        'trainer' => $trainer);
        $this->db->update('teams', $data, array('id' => $id));
    }
    
    function get_team($id)
    {
        return $this->db->get_where('teams', array('id' => $id))
                        ->row();
    }
}