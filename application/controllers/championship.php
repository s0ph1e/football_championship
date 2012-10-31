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
                
                $day_offset = $i++ % 2;  // День, когда будет данный матч (0 - сб, 1 - вс)
                $this->championship_model->add_match($tour_id, $team1, $team2, $day_offset);       
            }
            
            // Переход к следующей неделе
            $start_date->modify('+1 week');
        }
        
        redirect(site_url('/championship'));
    }
    
    public function view_calendar()
    {
        $tours = $this->championship_model->get_all_tours();
        
        foreach($tours as $tour)
        {
            $matches = $this->championship_model->get_matches_in_tour($tour->id);
            foreach ($matches as $match)
            {
                $match_info['id'] = $match->id;
                
                // Дата проведения матча (дата начала тура + отступ перед матчем)
                $date = new DateTime($tour->start_date);
                $date->modify('+'.$match->day_offset.' day');
                $match_info['date'] = $date->format('d-m-Y');
                
                // Команды
                $team1 = $this->team_model->get_team($match->team1_id);
                $match_info['team1'] = $team1->team.' ('.$team1->city.')';
                $team2 = $this->team_model->get_team($match->team2_id);
                $match_info['team2'] = $team2->team.' ('.$team2->city.')';
                
                // Счет
                $match_info['goals1'] = $match->team1_goals === null ? '?' : $match->team1_goals;
                $match_info['goals2'] = $match->team2_goals === null ? '?' : $match->team2_goals;
                
                // В массив всех матчей тура добавляем данный матч
                $matches_in_tour[] = $match_info;
            }
            
            $data['tours'][$tour->id] = $matches_in_tour;
            unset($matches_in_tour);
        }
        
        $this->load->view('header');
        $this->load->view('calendar', $data);
        $this->load->view('footer');
    }
    
    public function delete($id)
    {
        $this->championship_model->delete_match_result($id);
        redirect(site_url('/championship'));
    }
    
    public function update($id)
    {
        // Поле очков не обязательно, 
        // но если оно указано, в нем должно быть число >=0 и не больше 2 разрядов
        // оба поля должны быть или заполнены или пусты одновременно
        $this->form_validation->set_rules('team1_goals', 'Очки первой команды', 'max_length[2]|is_natural|callback_match_result_check');
        $this->form_validation->set_rules('team2_goals', 'Очки второй команды', 'max_length[2]|is_natural');
        
        if ($this->form_validation->run() == FALSE)
	{
            // Информация о матче
            $match = $this->championship_model->get_match($id);
            // Команды, принимавшие участие в матче
            $team1 = $this->team_model->get_team($match->team1_id);
            $team2 = $this->team_model->get_team($match->team2_id);
            // Данные команд
            $data['team1'] = $team1->team;
            $data['team2'] = $team2->team;
            $data['team1_city'] = $team1->city;
            $data['team2_city'] = $team2->city;
            
            // Очки первой и второй команды
            $data['team1_goals'] =  $this->form_validation->set_value('team1_goals', $match->team1_goals);
            $data['team2_goals'] =  $this->form_validation->set_value('team2_goals', $match->team2_goals);

            $this->load->view('header');
            $this->load->view('match_result_form', $data);
            $this->load->view('footer');
	}
	else
	{
            $team1_goals = $this->input->post('team1_goals');
            $team2_goals = $this->input->post('team2_goals');

            $this->championship_model->update_match_result($id, $team1_goals, $team2_goals);
            
            redirect(site_url('/championship'));
	}
    }
    
    // Функция, проверяющая заполненность полей резуьлтата матча
    public function match_result_check($str)
    {
        $team1_goals = $this->input->post('team1_goals');
        $team2_goals = $this->input->post('team2_goals');
        
        if(isset($team1_goals) === isset($team2_goals))
        {
            return true;
        }
        else 
        {
            $this->form_validation->set_message('match_result_check', 'Both fields must be set or unset');
            return false;
        }
    }
}
