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

}

