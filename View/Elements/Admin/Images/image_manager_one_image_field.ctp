<?php
    $limit = (empty($limit)) ? false : $limit;
?>
<div class="control-group">
    <div class="controls">
        <button <?php echo ($limit) ? 'data-limit="' . $limit . '"' : ''; ?> class="btn js-admin_get_image_manager">Add Image</button>
        <ul class="top10 images-container thumbnails js-admin_images_container">
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

