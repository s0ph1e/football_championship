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
        if($this->team_model->teams_count() % 2 == 1)
        {
            $this->load->view('header');
            $this->load->view('calendar', array('error' => 'Нельзя создать календарь с нечетным количеством команд!'));
            $this->load->view('footer');
            return;
        }
        
        // truncate таблиц прошлого календаря
        $this->championship_model->clear_calendar();
        
        $start_date = new DateTime();   // Текущая дата, с которой начинается чемпионат
        $end_date = new DateTime();     // Дата окончания чемпионата
        $end_date->modify('+1 year');
        
        // Идентификаторы всех команд, которые будут участвовать в чемпионате
        $teams = $this->team_model->get_all_team_id();
        
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
            
            $i = 0; // Переменная для определения, в какой день (1 или 2) будет играть команда
            $tmp_teams = $teams;        // Массив идентификаторов команд
            while (!empty($tmp_teams))  // Пока массив не пустой
            {
                $rand = rand(0, count($tmp_teams)-1);   // Рандомно выбираем элемент массива
                $team1 = $tmp_teams[$rand];         // Первая команда - значение этого элемента
                array_splice($tmp_teams, $rand, 1);    // Удаляем элемент из массива
                $rand = rand(0, count($tmp_teams)-1); // Рандомно выбираем элемент для второй команды
                $team2 = $tmp_teams[$rand];         // Вторая команда
                array_splice($tmp_teams, $rand, 1);   // Удаляем id второй команды из массива
                
                $day_offset = $i++ % 2;  // Кол-во дней от старта перед матчем (т.е. 0 - сб, 1 - вс)
                $this->championship_model->add_match($tour_id, $team1, $team2, $day_offset);       
            }
            
            // Переход к следующей неделе
            $start_date->modify('+1 week');
        }
        
        redirect(site_url('championship'));
    }
    
    public function view_calendar()
    {
        // Массив для передачи в представление
        $data = array();
        
        // Получаем все туры
        $tours = $this->championship_model->get_all_tours();
        
        // Для каждого тура получаем все матчи
        foreach($tours as $tour)    
        {
            $matches = $this->championship_model->get_matches_in_tour($tour->id);
            
            // Для каждого матча получаем информацию
            foreach ($matches as $match)
            {
                $match_info['id'] = $match->id;
                
                // Дата проведения матча (дата начала тура + отступ перед матчем)
                $date = new DateTime($tour->start_date);
                $date->modify('+'.$match->day_offset.' day');
                $match_info['date'] = $date->format('d-m-Y');
                
                // Команды
                $team1 = $this->team_model->get_team($match->team1_id);
                $team2 = $this->team_model->get_team($match->team2_id);
                
                // Названия команд
                $match_info['team1'] = $team1->team.' ('.$team1->city.')';
                $match_info['team2'] = $team2->team.' ('.$team2->city.')';
                
                // Очки команд
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
    
    // Турнирная таблица
    public function league_table()
    {
        // Получаем все команды
        $teams = $this->team_model->get_all();
        
        // массив для передачи во вьюшку
        $data = array();
        
        // Для каждой команды получаем количество игр, голов, выиграшей и т.п.
        foreach ($teams as $team)
        { 
            // Название команды
            $team_info['team'] = $team->team;
            
            // Массив матчей, в которых принимала участие команда
            $matches = $this->championship_model->get_team_games($team->id);
           
            // Количество игр
            $team_info['games'] = count($matches);
            
            $win = 0;   // Победы
            $defeat = 0;    // Поражения
            $dead_heat = 0; // Ничьи
            $scored_goals = 0; // Забитые голы
            $missed_goals = 0; // Пропущенные голы
            
            
            // Для каждого матча команды определяем выиграла она или проиграла, сколько голов забила и пропустила etc
            foreach ($matches as $match)
            {
                // Если указанная команда team1, то команда team2 - противник
                if($match->team1_id == $team->id)
                {
                    $team_goals = 'team1_goals';
                    $enemy_goals = 'team2_goals';
                }
                else // Если указанная команда team2, то противник team1
                {
                    $team_goals = 'team2_goals';
                    $enemy_goals = 'team1_goals';
                }
                
                // Определяем количество забитых и пропущенных голов
                $scored_goals += $match->$team_goals;
                $missed_goals += $match->$enemy_goals;

                // Определяем выиграш, проиграш или ничью
                if ($match->$team_goals > $match->$enemy_goals)
                {
                    $win ++;
                }
                elseif ($match->$team_goals == $match->$enemy_goals)
                {
                    $dead_heat ++;
                }
                elseif ($match->$team_goals > $match->$enemy_goals)
                {
                    $defeat ++;
                }
            }
            // Запись в массив команды
            $team_info['win'] = $win;
            $team_info['defeat'] = $defeat;
            $team_info['dead_heat'] = $dead_heat;
            $team_info['scored_goals'] = $scored_goals;
            $team_info['missed_goals'] = $missed_goals;    
            
            $data['table'][] = $team_info;
        }
        usort($data['table'], array("Championship", "cmp"));
        $this->load->view('header');
        $this->load->view('league_table', $data);
        $this->load->view('footer');
    }
    
    // Функция для сортировке команд по выигранным играм
    public static function cmp($a, $b)
    {
        if ($a['win'] == $b['win']) {
            return 0;
        }
        return ($a['win'] < $b['win']) ? 1 : -1;
    }
    
    // Функция, проверяющая заполненность полей результата матча
    public function match_result_check()
    {
        $team1_goals = $this->input->post('team1_goals');
        $team2_goals = $this->input->post('team2_goals');
        
        // если оба поля одновременно пусты или заполнены, то все ок
        if((strlen($team1_goals) == 0 && strlen($team2_goals) == 0) || 
           (strlen($team1_goals) > 0 && strlen($team2_goals) > 0 ))
        {
            return true;
        }
        else    // иначе добавляем сообщение об ошибке
        {
            $this->form_validation->set_message('match_result_check', 'Оба поля должны быть пусты или заполнены одновременно.');
            return false;
        }
    }
}
