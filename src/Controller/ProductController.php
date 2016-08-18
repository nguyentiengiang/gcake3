<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Datasource\Exception\RecordNotFoundException;

/**
 * Product Controller
 *
 * @property \App\Model\Table\ProductTable $Product
 */
class ProductController extends AppController
{
    
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $pro_list = $this->Product->find('all')->contain(['Category']);
        $product = $this->paginate($pro_list, [
            'limit' => 2,
            'maxLimit' => 100
        ]);

        $this->set(compact('product'));
        $this->set('_serialize', ['product']);
    }

    /**
     * View method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $product = $this->Product->get($id, [
            'contain' => ['Category'],       
        ]);
        
        $this->set('product', $product);
        $this->set('_serialize', ['product']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $product = $this->Product->newEntity();
        if ($this->request->is('post')) {
            $product = $this->Product->patchEntity($product, $this->request->data);
            if ($this->Product->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        $query = TableRegistry::get('category')->find("list");
        $this->set('category', $query->toArray());
        
        $this->set('statusArr', [0 => 'Ẩn', 1 => 'Hiện']);
        
        $this->set(compact('product'));
        $this->set('_serialize', ['product']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $product = $this->Product->get($id, [
            'conditions' => ['is_delete !=' => 1],
            'contain' => []
        ]);
        
//        if (empty($product)) {
//            throw new RecordNotFoundException(__('Product not found'));
//        }
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $product = $this->Product->patchEntity($product, $this->request->data);
            if ($this->Product->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The product could not be saved. Please, try again.'));
            }
        }
        //set category to dropdownlist
        $query = TableRegistry::get('category')->find("list");
        $this->set('category', $query->toArray());
        
        //
        $this->set('isHiddenArr', [0 => 'Ẩn', 1 => 'Hiện']);
        
        $this->set(compact('product'));
        $this->set('_serialize', ['product']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Product id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Product->get($id);
        if ($this->Product->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
