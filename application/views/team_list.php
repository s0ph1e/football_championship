<?php
    if ($teams)
    {
        $tmpl = array ( 'table_open'  => '<table class = "teams_table">',
                        'heading_row_start'   => '<tr class = "teams_table_heading">',
                         'row_start'           => '<tr class = "teams_table_row">');
        $this->table->set_template($tmpl);
        $this->table->set_caption('Список команд');
        $this->table->set_heading(array('id', 'Команда', 'Город', 'Тренер'));
        
        // Добавляем в таблицу все команды
        foreach ($teams as $team)
        {
            $this->table->add_row($team->id, $team->team, $team->city, $team->trainer);
        }
        // Вывод таблицы команд
        echo $this->table->generate();
        $this->table->clear();
    }
    else echo 'Нет команд.';