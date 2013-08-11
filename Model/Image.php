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

    function afterSave($created) {
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

    public $actsAs = array(
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
                    'thumb' => '200x200',
                    'big' => '800x800',
                    'nano' => '100x100',
                )
            ),
        )
    );


    /**
     * VirtualFields for Image thumbnails
     *
     * @var array
     */
    public $virtualFields = array(
        'small' => 'CONCAT("files/images/thumbs/small_", Image.filename)',
        'thumb' => 'CONCAT("files/images/thumbs/thumb_", Image.filename)',
        'big' => 'CONCAT("files/images/thumbs/big_", Image.filename)',
        'nano' => 'CONCAT("files/images/thumbs/nano_", Image.filename)',
        'fullsize' => 'CONCAT("files/images/", Image.filename)',
    );

}
