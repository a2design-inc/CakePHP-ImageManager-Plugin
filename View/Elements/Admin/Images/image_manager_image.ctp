<li class="js-admin_image" data-id="<?php echo $image['Image']['id']; ?>">
    <i class="thumbnail">
        <ul class="thumbnail-controls">
            <li>
                <?php
                echo $this->Html->link(
                    '<i class="icon icon-edit"></i>',
                    array(
                        'controller' => 'images',
                        'action' => 'edit',
                        $image['Image']['id']
                    ),
                    array(
                        'escape' => false,
                        'class' => 'js-admin_image_edit',
                        'target' => '_blank'
                    )
                );
                ?>
            </li>
            <li>
                <?php
                echo $this->Html->link(
                    '<i class="icon icon-remove"></i>',
                    array(
                        'controller' => 'images',
                        'action' => 'delete',
                        $image['Image']['id']
                    ),
                    array(
                        'escape' => false,
                        'class' => 'js-admin_image_delete'
                    )
                );
                ?>
            </li>
        </ul>
        <?php
            echo $this->Html->image('/'. $image['Image']['nano'], array(
                'data-small' => $image['Image']['small'],
                'data-thumb' => $image['Image']['thumb'],
                'data-big' => $image['Image']['big'],
                'data-fullsize' => $image['Image']['fullsize'],
            ));
        ?>
    </i>
    <?php
    echo $this->Form->hidden('Image.Image.' . $image['Image']['id'], array(
        'value' => $image['Image']['id'],
        'name' => 'data[Image][Image][]'
    ));
    ?>
</li>