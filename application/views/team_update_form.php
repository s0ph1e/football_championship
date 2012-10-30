<center>
<?php echo validation_errors(); ?>

<?php echo form_open('team/update/'.$this->uri->segment(3)); ?>
<p>Название команды:<br />
    <?php echo form_input(array('name'=>'team','value'=>$team));?>
</p>
<p>Город:<br />
    <?php echo form_input(array('name'=>'city','value'=>$city));?>
</p>
<p>Тренер:<br />
    <?php echo form_input(array('name'=>'trainer','value'=>$trainer));?>
</p>
<p><?php echo form_submit('submit_upd', 'Обновить','id="regbtn"');?></p>

<?php echo form_close();?>