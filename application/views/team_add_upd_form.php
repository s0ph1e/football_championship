<center>
<?php echo validation_errors(); ?>
<?php 
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
<p>Название команды:<br />
    <?php echo form_input(array('name'=>'team','value'=>$team));?>
</p>
<p>Город:<br />
    <?php echo form_input(array('name'=>'city','value'=>$city));?>
</p>
<p>Тренер:<br />
    <?php echo form_input(array('name'=>'trainer','value'=>$trainer));?>
</p>
<p><?php echo form_submit('submit_upd', $button_text,'id="regbtn"');?></p>

<?php echo form_close();?>