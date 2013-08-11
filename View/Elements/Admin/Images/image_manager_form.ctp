<?php
echo $this->Form->create('Image',
    array(
        'enctype' => 'multipart/form-data',
        'type' => 'file',
        'class' => 'form-inline js-admin_image_uploader',
        'url' => array (
            'controller' => 'images',
            'action' => 'add',
            'admin' => true,
        )
    ));
?>

    <fieldset>
        <legend>Add Image</legend>

        <div class="file-drop-wrapper">
            <?php
            echo $this->Form->input('title', array(
                'label' => false,
                'placeholder' => 'title',
                'div' => false,
                'divControls' => false,
            ));
            ?>
            <?php
            echo $this->Form->input('filename', array(
                'type' => 'file',
                'label' => false,
                'div' => false,
                'divControls' => false,
                'class' => 'js-image_upload',
                'name' => 'data[Image][][filename]'
            ));
            ?>

            <?php echo $this->Form->submit('Upload Image', array(
                'class' => 'btn btn-primary',
            )); ?>
        </div>
    </fieldset>
    <hr>

<?php echo $this->Form->end(); ?>