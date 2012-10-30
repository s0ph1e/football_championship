<?php
class Championship extends CI_Controller {
    
    public function __construct() 
    { 
        parent::__construct();
	$this->load->model(array('championship_model', 'team_model'));
    }
    
    public function index()
    {
        $this->view_calendar();
    }
    
    // Функция, которая генерирует календарь матчей на год вперед
    public function create_calendar()
    {
        $this->championship_model->clear_calendar();
        
        $start_date = new DateTime();   // Текущая дата, с которой начинается чемпионат
        $end_date = new DateTime();     // Дата окончания чемпионата
        $end_date->modify('+1 year');
        
        // Поиск ближайшей субботы.
        while ($start_date->format("w") != 6)
        {
            $start_date->modify('+1 day');
        }
        
        // Создание всех туров на год вперед
        while ($start_date->format('U') < $end_date->format('U'))
        {
            // Добавление тура в БД
            $tour_id = $this->championship_model->add_tour($start_date->format('Y-m-d'));
            // Получение массива с id всех команд
            $teams = $this->team_model->get_all_team_id();
            
            
            $i = 0; // Для определения, в какой день (1 или 2) будет играть команда
            while (!empty($teams))
            {
                $rand = rand(0, count($teams)-1);
                $team1 = $teams[$rand];         // Первая команда
                array_splice($teams, $rand, 1);    // Удаляем id первой команды из массива
                $rand = rand(0, count($teams)-1);
                $team2 = $teams[$rand];         // Вторая команда
                array_splice($teams, $rand, 1);    // Удаляем id второй команды из массива
                
                $day_after_start = $i++ % 2;  // День, когда будет данный матч (0 - сб, 1 - вс)
                $this->championship_model->add_match($tour_id, $team1, $team2, $day_after_start);       
            }
            
            // Переход к следующей неделе
            $start_date->modify('+7 day');
        }
    }
    
    public function view_calendar()
    {
        $data['tours'] = $this->championship_model->get_all_tours();
        
        $this->load->view('header');
        $this->load->view('calendar', $data);
        $this->load->view('footer');
    }
}
