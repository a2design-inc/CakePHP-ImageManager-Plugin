<?php echo $this->Form->create('Image', array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal well')); ?>
<fieldset>
    <legend><?php echo __('Edit Image'); ?></legend>

    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->input('title'); ?>
    <?php
    echo $this->Form->input('filename', array(
        'type' => 'file',
    ));
    ?>

    <div class="form-actions">
        <?php echo $this->Form->submit('Save changes', array(
            'div' => false,
            'class' => 'btn btn-primary',
        )); ?>
        <?php echo $this->Html->link(__('Cancel'), array('action' => 'index'), array('class' => 'btn btn-link')); ?>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>
