<?php
App::uses('FormHelper', 'View/Helper');
App::uses('HtmlHelper', 'View/Helper');

class ImageManagerHelper extends FormHelper {
    public $helpers = array('Form','Html');

    public function gallery($text = 'Gallery', $options = null) {
        $options['text'] = $text;

        return $this->images($options);
    }

    public function image($fieldName = 'Gallery', $options = null) {
        $options['field_name'] = $fieldName;

        return $this->images($options);
    }

    public function images($options = null) {
        $output = '';
        $field_id_data = '';
        $hideButton = '';
        $field_id = 'Image';


        $limit = (empty($options['limit'])) ? false : $options['limit'];
        $fieldName = !empty($options['field_name']) ? $options['field_name'] : '';


        if(!empty($fieldName)){
            $limit = 1;

            if (strpos($fieldName, '.') !== false) {
                $fieldElements = explode('.', $fieldName);
                $text = array_pop($fieldElements);
            } else {
                $text = $fieldName;
            }
            if (substr($text, -3) === '_id') {
                $text = substr($text, 0, -3);
            }

            $text = __(Inflector::humanize(Inflector::underscore($text)));

            $field_id = $this->Form->domId($fieldName);
            $field_id_data = ' data-field-id="'.$field_id.'"';

            $output .= $this->Form->hidden($fieldName);
            $output .= $this->Form->error($fieldName, null, array('class' => 'help-block text-danger'));

            if(!empty($this->data[$field_id]) && !empty($this->data[$field_id]['id'])){
                $hideButton = ' display: none;';
            }
        }else{
            $fieldName = 'Image';
        }

        $text = !empty($options['text']) ? $options['text'] : 'Image';

        $limit_data = ($limit) ? ' data-limit="' . $limit . '"' : '';

        $output .= '<div class="form-group">';
        $output .= $this->Form->label($fieldName, $text ,array('class'=>"col col-xs-3 control-label"));
        $output .= '<div class="col col-xs-9 ">';
        $output .= '<div class="control-group"><div class="controls">
        <button ' . $limit_data . $field_id_data.' class="btn js-admin_get_image_manager" style="'.$hideButton.'">' . __('Add Image') . '</button>
        <ul class="top10 images-container thumbnails js-admin_images_container">';

        if (!empty($this->data[$field_id])) {
            if ($limit == 1) {
                if(!empty($this->data[$field_id]['id'])){
                    $output .= $this->image_thumb($this->data[$field_id], $options);
                }
            } else {
                $output .= $this->Form->hidden('Image.Image.', array('name' => 'data[Image][Image][]'));
                foreach ($this->data[$field_id] as $n => $image) {
                    $output .= $this->image_thumb($image, $options);
                }
            }
        }

        $output .= '</ul></div></div>';
        $output .= '</div> </div>';

        return $output;
    }

    public function image_thumb($image, $options = null){
        $output = '';

        $output .= '<div class="thumbnail col-xs-2 js-admin_image" data-id="' . $image['id'] . '">
                    <ul class="thumbnail-controls">
                    <li>';

        $output .= $this->Html->link(
            '<i class="fa fa-times"></i>',
            array(
                'controller' => 'images',
                'action' => 'delete',
                $image['id']
            ),
            array(
                'escape' => false,
                'class' => 'js-admin_image_delete'
            )
        );
        $output .= '</li></ul>';

        $output .= $this->Html->image('/' . $image['thumb']);


        if (empty($options['field_name'])) {
            $output .= $this->Form->hidden('Image.Image.' . $image['id'], array(
                'value' => $image['id'],
                'name' => 'data[Image][Image][]'
            ));
        }

        $output .= '</div>';

        return $output;
    }



}