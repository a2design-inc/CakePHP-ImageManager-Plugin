<?php
foreach ($images as $image) {
    echo $this->ImageManager->image_thumb($image['Image']);
}