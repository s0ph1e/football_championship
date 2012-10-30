<?php

echo anchor(site_url('championship/create_calendar'), 'Генерировать календарь матчей');

foreach ($tours as $tour)
{
    echo '<p>'.$tour->id.' '.$tour->start_date.'</p>';
}
