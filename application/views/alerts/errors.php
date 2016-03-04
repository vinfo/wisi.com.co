 <?php  if(validation_errors()!=''):?>
<div class="alert alert-danger">
    <ul>
    <?php foreach(error_array() as $error):?>
        <li><?php echo $error?></li>
    <?php endforeach?>
    </ul>
</div>
 <?php endif; ?>

<?php if($this->session->flashdata('error')):?>
<div class="alert alert-danger">
    <ul>
        <li><?php echo $this->session->flashdata('error')?></li>
    </ul>
</div>
 <?php endif; ?>

    

