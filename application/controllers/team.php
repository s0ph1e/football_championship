<?php

class Team extends CI_Controller {
    
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
        //Правила валидации
		$this->form_validation->set_rules('team', 'Название команды', 'required');
		$this->form_validation->set_rules('city', 'Город', 'required');
        $this->form_validation->set_rules('trainer', 'Тренер', 'required');
        
        if ($this->form_validation->run() == FALSE)
		{
            $data['team'] =  $this->form_validation->set_value('team');
            $data['city'] =  $this->form_validation->set_value('city'); 
            $data['trainer'] = $this->form_validation->set_value('trainer'); 
            
            $this->load->view('header');
			$this->load->view('team_add_form', $data);
            $this->load->view('footer');
		}
		else
		{
            $team = $this->input->post('team');
            $city = $this->input->post('city');
            $trainer = $this->input->post('trainer');
            $this->team_model->add_team($team, $city, $trainer);
            
			$this->view();
		}
    }

}

