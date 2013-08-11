<?php
    foreach($images as $image) {
        echo $this->element('Admin/Images/image_manager_image', array('image' => $image));
    }