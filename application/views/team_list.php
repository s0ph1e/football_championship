<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo 'Вы можете добавить команду '.anchor(site_url('team/add'), img('data/images/add.png'), array('title'=>"Добавить"));

if (isset($teams))
{
    // Шаблон для таблицы
    $this->table->set_template($this->config->item('table_template'));
    $this->table->set_heading(array('id', 'Команда', 'Город', 'Тренер', 'Действия'));

    // Добавляем в таблицу все команды
    foreach ($teams as $team)
    {
        // $actions - кнопки изменить, удалить для команды
        $actions = anchor(site_url('team/update/'.$team->id), 
                                   img('data/images/edit.png'), 
                                   array('class' => "upd_team", 'title'=>"Изменить", 'id' => "upd_".$team->id) );
        $actions .=' '.anchor(site_url('team/delete/'.$team->id), 
                              img('data/images/delete.png'), 
                              array('class' => "del_team", 'title'=>"Удалить", 'id' => "del_".$team->id) );
        $this->table->add_row($team->id, $team->team, $team->city, $team->trainer, $actions);
    }
    // Вывод таблицы команд
    echo $this->table->generate();
    $this->table->clear();
}
else 
{
    echo 'Нет команд.';
}
    