<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Product Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Category
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null)
 */
class ProductTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('product');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->belongsTo('Category', [
            'foreignKey' => 'category_id',
        ]);
        
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->notEmpty('name', 'Khong de trong!');

        $validator
            ->integer('prize')
            ->notEmpty('prize', 'Khong de trong!')->greaterThan('prize', 0, 'Toi thieu 1 dong');

        $validator
            ->allowEmpty('image');

        $validator
            ->allowEmpty('details');

        $validator
            ->integer('is_hidden')
            ->allowEmpty('is_hidden');

        $validator
            ->integer('is_delete')
            ->allowEmpty('is_delete');
        
        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['category_id'], 'Category'));

        return $rules;
    }
    
    /**
     * Delete by set is_delete = 1
     * 
     * @param \Cake\Datasource\EntityInterface $entity
     * @param type $options
     */
    public function delete(\Cake\Datasource\EntityInterface $entity, $options = array()) {
        parent::delete($entity, $options);
        $entity->is_delete = 1;
        return $this->save($entity);        
    }
    
}
