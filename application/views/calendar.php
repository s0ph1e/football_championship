<?php

echo 'Вы можете сгенерировать новый календарь матчей '.anchor(site_url('championship/create_calendar'), img('data/images/magic.png'));

if($tours)
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
        
        if ($value)
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
else echo 'Календарь матчей не был составлен.';
