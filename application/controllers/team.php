<?php

class Team extends CI_Controller {
    
    // Правила валидации для форм добавления и обновления
    private $validation_rules = array(
        array (
            'field'   => 'team', 
            'label'   => 'Команда', 
            'rules'   => 'required'
        ),
        array (
            'field'   => 'city', 
            'label'   => 'Город', 
            'rules'   => 'required'
        ),
        array (
            'field'   => 'trainer', 
            'label'   => 'Тренер', 
            'rules'   => 'required'
        )
    );
    
    public function __construct() 
    { 
        parent::__construct();
	$this->load->model(array('team_model'));
    }
    
    public function index()
    {   
        $this->view();
    }
    
    public function view()
    {
        $data['teams'] = $this->team_model->get_all();
        $this->load->view('header');
        $this->load->view('team_list', $data);
        $this->load->view('footer');
    }
    
    public function add()
    {
        // Указываем правила валидации для формы
        $this->form_validation->set_rules($this->validation_rules);
        
        if ($this->form_validation->run() == FALSE)
	{
            $data['team'] =  $this->form_validation->set_value('team');
            $data['city'] =  $this->form_validation->set_value('city'); 
            $data['trainer'] = $this->form_validation->set_value('trainer'); 
            
            $this->load->view('header');
            $this->load->view('team_add_upd_form', $data);
            $this->load->view('footer');
	}
	else
	{
            $team = $this->input->post('team');
            $city = $this->input->post('city');
            $trainer = $this->input->post('trainer');
            $this->team_model->add_team($team, $city, $trainer);
            
            redirect(site_url('/team'));
	}
    }
    
    public function delete($id)
    {
        $this->team_model->delete_team($id);
        
        redirect(site_url('/team'));
    }
    
    public function update($id)
    {
        //Указываем правила валидации
        $this->form_validation->set_rules($this->validation_rules);
        
        $team = $this->team_model->get_team($id);
        
        if ($this->form_validation->run() == FALSE)
	{
            
            $data['team'] =  $this->form_validation->set_value('team',  $team->team);
            $data['city'] =  $this->form_validation->set_value('city', $team->city);
            $data['trainer'] =  $this->form_validation->set_value('trainer', $team->trainer);
            
            $this->load->view('header');
            $this->load->view('team_add_upd_form', $data);
            $this->load->view('footer');
	}
	else
	{
            $team = $this->input->post('team');
            $city = $this->input->post('city');
            $trainer = $this->input->post('trainer');
            $this->team_model->update_team($id, $team, $city, $trainer);
            
            redirect(site_url('/team'));
	}
    }

}

