<?php

App::uses('ModelBehavior', 'Model');
App::import('Model', 'ImageManager.ImagesRelation');

class ImageManagerBehavior extends ModelBehavior {

    public $settings = array();

    private $__defaults = array(
    );

    private $images = array();
    private $assocData = array();

    private $relations = null;

    public function setup(Model $model, $config = array()) {
        if (!isset($this->settings[$model->alias])) {
            $this->settings[$model->alias] = $this->__defaults;
        }

        $this->settings[$model->alias] = array_merge($this->settings[$model->alias], (array) $config);
    }

    public function beforeSave(Model $model, $options = array()) {
        if(!empty($model->data['Image'])) {
            $this->images = $model->data['Image']['Image'];
            unset($model->data['Image']);
        }
    }

    public function afterSave(Model $model, $created, $options = array()) {
        if($this->images) {
            $this->relations = new ImagesRelation();

            $this->relations->deleteAll(array(
                'foreign_id' => $model->id,
                'foreign_name' => $model->alias,
            ));

            foreach($this->images as $image) {
                $this->assocData[] = array(
                    'image_id' => $image,
                    'foreign_id' => $model->id,
                    'foreign_name' => $model->alias,
                );
            }
            $this->relations->saveMany($this->assocData);
        }
    }
}
