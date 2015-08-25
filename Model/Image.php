<?php
App::uses('AppModel', 'Model');
App::import('Model', 'ImageManager.ImagesRelation');

/**
 * Image Model
 *
 * @property Page $Page
 * @property Project $Project
 */
class Image extends ImageManagerAppModel {

    var $inserted_ids = array();

    function afterSave($created, $options = array()) {
        if($created) {
            $this->inserted_ids[] = $this->getInsertID();
        }
        return true;
    }

    public $validate = array(
        'filename' => array(
            'rule' => array('extension',array('jpeg','jpg','png','gif')),
            'required' => false,
            'allowEmpty' => true
        ),
    );

    public function afterDelete() {
        $ImagesRelation = new ImagesRelation();
        $ImagesRelation->deleteAll(array(
            'image_id' => $this->id,
        ));
    }

    /**
     * Connect with MeioUpload Behavior from Plugin MeioUpload
     *
     * @var array
     */

    public function __construct($id = false, $table = null, $ds = null) {

        $options = Configure::read('ImageManager.Upload');

        $this->actsAs['ImageManager.Upload'] =
            Hash::merge(
                $this->actsAs_default['ImageManager.Upload'],
                $options
            );

        if(!empty($options['filename']['thumbnailSizes'])){
            $this->actsAs['ImageManager.Upload']['filename']['thumbnailSizes'] = $options['filename']['thumbnailSizes'];
        }

        parent::__construct($id, $table, $ds);
    }

    public $actsAs_default = array(
        'ImageManager.Upload' => array(
            'filename' => array(
                'path' =>          '{ROOT}webroot{DS}files{DS}images',
                'thumbnailPath' => '{ROOT}webroot{DS}files{DS}images{DS}thumbs',
                'pathMethod' => 'flat',
                'deleteOnUpdate' => true,
                'mimetypes' => array('image/jpeg', 'image/pjpeg', 'image/png'),
                'extensions' => array('.jpg', '.jpeg', '.png'),
                'thumbnailMethod' => 'php',
                'thumbnailQuality' => 100,
                'thumbnailSizes' => array(
                    'small' => '500x500',
                    'thumb' => '253x158',
                    'big' => '800l',
                    'nano' => '100x100',
                    'pico' => '70x70',
                )
            ),
        )
    );


    /**
     * VirtualFields for Image thumbnails
     *
     * @var array
     */


    public function afterFind($results, $primary = false) {
        // Bind size
        foreach ($results as $key => $val) {
            foreach($this->actsAs['ImageManager.Upload']['filename']['thumbnailSizes'] as $name_size=>$size){
                $results[$key][$this->alias][$name_size] = "files/images/thumbs/".$name_size."_".$results[$key][$this->alias]['filename'];
            }
            $results[$key][$this->alias]['fullsize'] = "files/images/".$results[$key][$this->alias]['filename'];
        }

        return $results;
    }


    public function attachInfo($id){
        $images = $this->find('all', array(
            'conditions' => array(
                'id' => $id,
            )
        ));

        $result = Hash::combine($images, '{n}.Image.id', '{n}.Image');

        return $result;
    }
}
