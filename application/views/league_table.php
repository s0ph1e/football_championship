<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(isset($table))
{
    echo '<h4>Турнирная таблица</h4>';
    $this->table->set_template($this->config->item('table_template'));
    $this->table->set_heading(array('Команда', 'И', 'В', 'Н', 'П', 'З - П', 'О'));
    
    foreach($table as $row)
    {
        $this->table->add_row($row['team'], $row['games'], $row['win'], $row['dead_heat'], $row['defeat'], $row['scored_goals'].' - '.$row['missed_goals'], $row['score']);
    }
    
    // Вывод таблицы команд
    echo $this->table->generate();
    $this->table->clear();
    
    echo <<<END
        <br>*И - игр сыграно;
        В - выиграшей;
        Н - сыграно в ничью;
        П - проиграшей;
        З-П - голов забито-пропущено;
        О - очки (О = 3*В + 1*Н);
END;
}
else 
{
    echo '<div class = "errors">Турнирной таблицы нет!</div>';
}
