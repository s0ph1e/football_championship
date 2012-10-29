<?php
    if ($teams)
    {
        $tmpl = array ( 'table_open'  => '<table class = "teams_table">',
                        'heading_row_start'   => '<tr class = "teams_table_heading">',
                         'row_start'           => '<tr class = "teams_table_row">');
        $this->table->set_template($tmpl);
        $this->table->set_heading(array('id', 'Команда', 'Город', 'Тренер', 'Действия'));
        
        // Добавляем в таблицу все команды
        foreach ($teams as $team)
        {
            $actions = '<center>'.anchor(site_url('team/update/'.$team->id), img('data/images/edit.png'), array('title'=>"Изменить"));
            $actions .='&nbsp'.anchor(site_url('team/delete/'.$team->id), img('data/images/delete.png'), array('title'=>"Удалить"));
            $this->table->add_row($team->id, $team->team, $team->city, $team->trainer, $actions);
        }
        // Вывод таблицы команд
        echo $this->table->generate();
        $this->table->clear();
    }
    else {echo 'Нет команд.';}
    echo 'Вы можете добавить команду '.anchor(site_url('team/add'), img('data/images/add.png'), array('title'=>"Добавить"));