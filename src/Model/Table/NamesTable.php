<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Names Model
 *
 * @method \App\Model\Entity\Name get($primaryKey, $options = [])
 * @method \App\Model\Entity\Name newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Name[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Name|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Name patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Name[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Name findOrCreate($search, callable $callback = null)
 */
class NamesTable extends Table
{
    const DISPLAY = [ 'private' => 0, 'omit' => 1, 'short' => 2, 'display' => 3  ];
    const DISPLAY_LEBEL = [ 'private' => 0, 'full' => 1, 'normal' => 3 ];

    public static function types(){
        return [
            'given'         => __('Given Name'),
            'family'        => __('Family Name'),
            'middle'        => __('Middle Name'),
            'hypocoristic'  => __('Hypocoristic'),
            'patronym'      => __('Patronym'),
            'alias'         => __('Alias'),
            'pseudonym'     => __('Pseudonym'),
            'courtesy'      => __('Courtesy Name / Zi'),
            'clan'          => __('Clan Name / Shi'),
            'title'         => __('Title'),
            'other'         => __x('other name type','Other'),
        ];
    }

    public static function display() {
        return [
            'display'   => __('Display'),
            'private'   => __('Private'),
            'omit'      => __('Omit'),
            'short'     => __('Short'),
        ];
    }

    public static function displayDescription() {
        return [
            'display'   => __('Always displayed'),
            'private'   => __('Not displayed for other users'),
            'short'     => __('Shorten except profile page'),
            'omit'      => __('Displayed only profile page'),
        ];
    }

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('names');
        $this->displayField('name');
        $this->primaryKey('id');
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

        $validator->requirePresence([
                'username', 'order_key', 'name', 'type', 'display'
            ]);

        $validator
            ->notEmpty('username');

        $validator
            ->numeric('order_key')
            ->integer('order_key')
            ->notEmpty('order_key');

        $validator
            ->notEmpty('name');

        $validator
            ->notEmpty('type');

        $validator
            ->numeric('display')
            ->integer('display')
            ->notEmpty('display');

        $validator
            ->allowEmpty('clipped');

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
        return $rules;
    }

    public function getName( string $username, int $display_lebel = self::DISPLAY_LEBEL['normal'] ){
        $query = $this->find()
                      ->select(['name','type','short','display'])
                      ->where(['username' => $username])
                      ->order(['order_key' => 'ASC'])
                    ;
        foreach( $query as $row ){
            if( $row->display == self::DISPLAY['private'] && $display_lebel != self::DISPLAY_LEBEL['private'] ){
                continue;
            }
            else if( $row->display == self::DISPLAY['omit'] && $display_lebel > self::DISPLAY_LEBEL['full'] ){
                continue;
            }
            else {
                $name = '';
                if( $row->display == self::DISPLAY['short'] && $display_lebel > self::DISPLAY_LEBEL['full'] ){
                    $name = $row->short;
                }
                else {
                    $name = $row->name;
                }
                $names[] = ['name' => $name, 'type' => $row->type ];
            }
        }
        return isset($names) ? $names : [];
    }

    public function getNameData( string $username, array $options = [] ){
        $query = $this->find()
                      ->select(['name','type','display','short'])
                      ->where(['username' => $username])
                      ->order(['order_key' => 'ASC'])
                      ->hydrate(false)
                    ;
        $data = $query->toList();
        if( isset($options['display']) && $options['display'] == 'string' ){
            foreach ( $data as $i => &$name ) {
                $name['display'] = array_search( $name['display'], self::DISPLAY );
            }
        }
        return $data;
    }
}
