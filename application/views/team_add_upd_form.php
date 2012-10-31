<center>
<?php 

    echo validation_errors();
    
    // Разные actions формы и надписи кнопок, в зависимости от того, из какой функции они вызваны

    if($this->uri->segment(2) == 'update')
    {
        echo form_open('team/update/'.$this->uri->segment(3));
        $button_text = "Обновить";
    }
    else if($this->uri->segment(2) == 'add')
    {
        echo form_open('team/add');
        $button_text = "Добавить";
    }
?>
<label>Название команды:</label>
    <?php echo form_input(array('name'=>'team','value'=>$team));?>

<label>Город:</label>
    <?php echo form_input(array('name'=>'city','value'=>$city));?>

<label>Тренер:</label>
    <?php echo form_input(array('name'=>'trainer','value'=>$trainer));?>
<?php echo form_submit('submit_upd', $button_text,'id="regbtn"');?>

<?php echo form_close();?>
