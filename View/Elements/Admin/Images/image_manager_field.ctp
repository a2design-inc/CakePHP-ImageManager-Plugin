<?php
    $limit = (empty($limit)) ? false : $limit;
    $fieldName = '';
?>
<div class="control-group">
    <div class="controls">
        <button <?php echo ($limit) ? 'data-limit="' . $limit . '"' : ''; ?> class="btn js-admin_get_image_manager"><?php echo __('Add Image');?></button>
        <ul class="top10 images-container thumbnails js-admin_images_container">
            <?php  echo $this->Form->hidden('Image.Image.', array(
                'name' => 'data[Image][Image][]'
            )); ?>
            <?php if(!empty($this->data['Image'])): ?>
            <?php foreach($this->data['Image'] as $n => $image): ?>
                <?php
                    echo $this->element('ImageManager.Admin/Images/image_manager_image', array(
                        'image' => array('Image' => $image),
                    ))
                ?>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</div>

