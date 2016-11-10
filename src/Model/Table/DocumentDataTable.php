<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DocumentData Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Documents
 *
 * @method \App\Model\Entity\DocumentData get($primaryKey, $options = [])
 * @method \App\Model\Entity\DocumentData newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DocumentData[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DocumentData|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocumentData patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DocumentData[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DocumentData findOrCreate($search, callable $callback = null)
 */
class DocumentDataTable extends Table
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

        $this->table('document_data');
        $this->displayField('title');
        $this->primaryKey('id');

        $this->belongsTo('Documents', [
            'foreignKey' => 'document_id',
            'joinType' => 'INNER'
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
            ->allowEmpty('language');

        $validator
            ->allowEmpty('title');

        $validator
            ->allowEmpty('text');

        $validator
            ->boolean('is_draft')
            ->allowEmpty('is_draft');

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
        $rules->add($rules->existsIn(['document_id'], 'Documents'));

        return $rules;
    }
}
