<?php

echo $this->element('Admin/Images/image_manager_form');

?>

<div class="js-image_manager-container">
    <div class="row js-image_manager_images_list">

        <?php
        foreach($images as $image) {
            echo $this->ImageManager->image_thumb($image['Image']);
        }
        ?>
    </div>
</div>