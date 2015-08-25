<?php

echo $this->element('Admin/Images/image_manager_form');

?>

<div class="js-image_manager-container">
    <div class="row js-image_manager_images_list">

        <?php echo $this->element('Admin/Images/image_manager_list', array('images' => $images)); ?>
    </div>
</div>