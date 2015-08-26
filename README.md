CakePHP-ImageManager-Plugin
===========================

## Setting up
*Put this into your Cake's plugin directory*


### In your model

        public $actsAs = array('ImageManager.ImageManager');
        public $hasAndBelongsToMany = array(
                'Image' => array(
                        'className' => 'ImageManager.Image',
                        'foreignKey' => 'foreign_id',
                        'associationForeignKey' => 'image_id',
                        'joinTable' => 'images_relations',
                        'conditions' => array('foreign_name' => 'Page')
                ),
        );


### In your admin layout

        echo $this->Html->script(Router::url(array(
                'controller'=> 'images',
                'action' => 'scripts',
                'full_base' => true,
                'admin' => false,
                'plugin' => 'image_manager',
        )));
        echo $this->Html->script('ImageManager.image_manager');
        echo $this->Html->script('ImageManager.jquery.drag.drop');
        echo $this->Html->css('ImageManager.image_manager');


### In your config

        Configure::write(
            array (
                'ImageManager.Upload' => array(
                    'filename' => array(
                        'thumbnailSizes' => array(
                            'small' => '500x500',
                            'thumb' => '253x158',
                            'big' => '800l',
                            'nano' => '100x100',
                            'pico' => '70x70',
                        )
                    ),
                )
            )
        );


### Run MySQL script

        CREATE TABLE `images` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `foreign_key` int(10) unsigned DEFAULT NULL,
              `model` varchar(255) NOT NULL DEFAULT '',
              `filename` varchar(255) NOT NULL DEFAULT '',
              `dir` int(11) unsigned DEFAULT NULL,
              `order` int(11) unsigned DEFAULT NULL,
              `is_slider` int(2) unsigned DEFAULT NULL,
              `site_id` int(11) unsigned DEFAULT NULL,
              PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

        CREATE TABLE `images_relations` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `foreign_id` int(11) unsigned NOT NULL,
              `foreign_name` varchar(255) NOT NULL,
              `image_id` int(11) unsigned NOT NULL,
              PRIMARY KEY (`id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;