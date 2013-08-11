<?php

    echo $this->element('Admin/Images/image_manager_form');

?>

<ul class="thumbnails js-image_manager_images_list">

    <?php echo $this->element('Admin/Images/image_manager_list', array('images' => $images)); ?>

</ul>
