<?php

echo anchor(site_url('championship/create_calendar'), 'Генерировать календарь матчей');

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
        $this->table->set_heading(array('Дата', 'Команда1', 'Команда2', 'Голы1', 'Голы2'));
        
        foreach ($value as $match)
        {
            $this->table->add_row($match['date'], $match['team1'], $match['team2'], $match['goals1'], $match['goals2']);
        }
        // Вывод таблицы команд
        echo $this->table->generate();
        $this->table->clear();
    }
}
else echo 'Календарь матчей не был составлен.';
