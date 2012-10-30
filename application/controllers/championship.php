<?php
class Championship extends CI_Controller {
    
    public function __construct() 
    { 
        parent::__construct();
	$this->load->model(array('championship_model', 'team_model'));
    }
    
    public function index()
    {
        $this->create_calendar();
    }
    
    // Функция, которая генерирует календарь матчей на год вперед
    public function create_calendar()
    {
        $this->championship_model->clear_tours();
        
        $start_date = new DateTime();   // Текущая дата, с которой начинается чемпионат
        $end_date = new DateTime();     // Дата окончания чемпионата
        $end_date->modify('+1 year');
        
        // Поиск ближайшей субботы
        while ($start_date->format("w") != 6)
        {
            $start_date->modify('+1 day');
        }
        
        // Создание всех туров на год вперед
        while ($start_date->format('U') < $end_date->format('U'))
        {
            $this->championship_model->add_tour($start_date->format('Y-m-d'));
            $start_date->modify('+7 day');
        }
    }
}
