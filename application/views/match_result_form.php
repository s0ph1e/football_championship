<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<div class ="form-container">
<p>Введите счет для матча</p>
<?php 
    echo '<div class = "errors">'.validation_errors().'</div>'; 

    echo form_open('championship/update/'.$this->uri->segment(3));
?>

<div class ="form-block">
    <label><?=$team1.' ('.$team1_city.')'?></label>
    <?= form_input(array('name' => 'team1_goals', 'value' => $team1_goals)); ?>
</div>

<div class ="form-block">
    <label><?=$team2.' ('.$team2_city.')';?></label>
    <?= form_input(array('name' => 'team2_goals', 'value' => $team2_goals)); ?>
</div>

<p><?php echo form_submit('submit_upd', 'Обновить');?></p>

<?php echo form_close();?>
</div>