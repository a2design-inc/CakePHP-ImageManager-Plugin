<?php
App::uses('AppController', 'Controller');
/**
 * Images Controller
 *
 * @property Image $Image
 */
class ImagesController extends ImageManagerAppController {

    public $uses = array('ImageManager.Image');

    public function beforeFilter(){
        parent::beforeFilter();

        $this->Auth->allow(array(
            'scripts',
        ));
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        if (isset($this->request->query['type'])) {
            $this->layout = 'ajax';
        }

        $this->Image->recursive = 0;
        $images = $this->Image->find('all', array(
            'order' => array('id' => 'desc')
        ));
        $this->set('images', $images);
    }

    /**
     * admin_add method
     *
     * @throws BadRequestException
     */
    public function admin_add() {
        if (!$this->request->is('post')) {
            throw new BadRequestException();
        }

        $this->Image->create();

        if($this->request->is('ajax')) {
            $this->autoRender = false;

            if(empty($this->request->data['Image'])) {
                return $this->response->statusCode(400);
            }

            if($this->Image->saveAll($this->request->data['Image'])) {
                if($this->request->is('ajax')) {
                    $this->layout = 'ajax';
                    $images = $this->Image->find('all', array(
                        'conditions' => array ('id' => $this->Image->inserted_ids),
                    ));
                    $this->set('images', $images);
                    return $this->render('../Elements/Admin/Images/image_manager_list');
                }
            } else {
                return $this->response->statusCode(400);
            }
        }

        $this->Image->save($this->request->data);
        $this->redirect($this->request->referer());
    }

    /**
     * admin_edit method
     *
     * @param $id
     * @throws NotFoundException
     * @return void
     */
    public function admin_edit($id) {
        if(!$this->Image->exists($id)) {
            throw new NotFoundException(__('Invalid image'));
        }

        if($this->request->is('post') || $this->request->is('put')) {
            if(!$this->request->data['Image']['filename']['name']) {
                unset($this->request->data['Image']['filename']);
            }
            if($this->Image->save($this->request->data)) {
                $this->Session->setFlash(__('Картинка сохранена'), 'flash/success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Не удалось сохранить картинку'), 'flash/error');
            }
        } else {
            $this->request->data = $this->Image->find('first', array(
                'conditions' => array('id' => $id),
            ));
        }
    }

    /**
     * admin_delete method
     *
     * @param null|integer $id
     * @throws NotFoundException|MethodNotAllowedException
     * @return void
     */
    public function admin_delete($id = null) {
        $this->layout = 'ajax';
        $this->autoRender = false;
        if (!$this->request->is('post')) {
            $this->response->statusCode(503);
        }
        $this->Image->id = $id;
        if (!$this->Image->exists()) {
            $this->response->statusCode(404);
        }
        if ($this->Image->delete()) {
            $this->response->statusCode(200);
        } else {
            $this->response->statusCode(500);
        }
    }

    public function scripts() {
        $imagesUrlBase = Router::url(
            array (
                'controller' => 'images',
                'action' => 'index',
                'admin' => true,
            ),
            true
        );
        if (substr($imagesUrlBase, -1) === '/')  {
            $imagesUrlBase = substr($imagesUrlBase, 0, -1);
        }
        $this->set('imagesUrlBase', $imagesUrlBase);
        $this->response->type('javascript');
        $this->autoRender = false;
        $this->render('../Elements/Admin/Images/image_manager_scripts', 'ajax');
    }

}
