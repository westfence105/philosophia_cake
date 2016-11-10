<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DocumentVersions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $DocumentData
 *
 * @method \App\Model\Entity\DocumentVersion get($primaryKey, $options = [])
 * @method \App\Model\Entity\DocumentVersion newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DocumentVersion[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DocumentVersion|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DocumentVersion patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DocumentVersion[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DocumentVersion findOrCreate($search, callable $callback = null)
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocumentVersionsTable extends Table
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

        $this->table('document_versions');
        $this->displayField('id');
        $this->primaryKey(['id', 'language']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('DocumentData', [
            'foreignKey' => 'data_id',
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
            ->allowEmpty('language', 'create');

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
        $rules->add($rules->existsIn(['data_id'], 'DocumentData'));

        return $rules;
    }
}
