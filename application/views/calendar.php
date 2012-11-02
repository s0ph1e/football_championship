<?php
// Ссылка на скрипт генерации календаря
echo '<p>Вы можете сгенерировать новый календарь матчей '.anchor(site_url('championship/create_calendar'), img('data/images/magic.png')).'</p>';

// Форма для фильтра
echo form_open('championship/view_calendar');
echo form_input(array('name' => 'search_team', 'style' => 'display:inline-block'));
$btn_search = array(
                    'type'      => 'image',
                    'src'        => base_url().'data/images/filter.png',
                    'name'        => 'submit_search',
                    'width'     => '32',
                    'height'    => '32'
                );
echo form_submit($btn_search);
echo form_close();

// Вывод ошибок, если они есть
if(isset($error))
{
    echo '<div class = "errors">'.$error.'</div>';
    echo '<p>Вы можете добавить или удалить команду в '.anchor(site_url('team'), 'списке команд').' или просмотреть '.anchor(site_url('championship'), 'текущий календарь').'.</p>';
}

// Календарь
if(isset($tours))
{
    foreach ($tours as $key => $value)
    {
        echo '<h4>'.$key.' тур </h4>';  // № тура
        
        $tmpl = array ( 'table_open'  => '<table class = "teams_table">',
                        'heading_row_start'   => '<tr class = "teams_table_heading">',
                        'row_start'           => '<tr class = "teams_table_row">',
                        'row_alt_start'   =>  '<tr class = "teams_table_row">');
        $this->table->set_template($tmpl);
        $this->table->set_heading(array('Дата', 'Игрок', 'Счет', 'Игрок', 'Действия'));
        
        if (isset($value))
        {
            foreach ($value as $match)
            {
                $actions = anchor(site_url('championship/update/'.$match['id']), 
                                            img('data/images/edit.png'), 
                                            array('class' => "upd_match", 'title'=>"Изменить", 'id' => "upd_".$match['id']) );
                $actions .='&nbsp;'.anchor(site_url('championship/delete/'.$match['id']), 
                                        img('data/images/delete.png'), 
                                        array('class' => "del_match", 'title'=>"Удалить", 'id' => "del_".$match['id']));
                $this->table->add_row($match['date'], $match['team1'], $match['goals1'].' : '.$match['goals2'], $match['team2'], $actions);
            }
            // Вывод таблицы команд
            echo $this->table->generate();
            $this->table->clear();
        }
        else echo 'Матчей нет!';
    }
}
else echo '<p>Календарь чемпионата не был составлен.</p>';
