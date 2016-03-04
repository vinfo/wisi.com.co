<?php if($this->session->flashdata('success')):?>
<div class="alert alert-success">
    <ul>
        <li><?php echo $this->session->flashdata('success')?></li>
    </ul>
</div>
 <?php endif; ?>

