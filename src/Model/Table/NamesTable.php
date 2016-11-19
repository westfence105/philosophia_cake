<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Event\Event;
use ArrayObject;

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
    const DISPLAY_LEBEL = [ 'normal' => 0, 'full' => 1, 'private' => 2 ];

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

    public static function displayDescriptions() {
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

        $this->belongsTo('Users', [ 'foreignKey' => 'username' ] );

        $this->_validatorClass = '\App\Model\Validation\NameValidator';
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
                'username', 'order_key', 'name', 'type', 'display', 'preset'
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
            ->allowEmpty('clipped')
            ->notEmpty('preset');

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
        $rules->add( $rules->existsIn( 'username', 'users' ) );
        return $rules;
    }

    public function beforeMarshal( Event $event, ArrayObject $data, ArrayObject $options ){
        if( $data->offsetExists('order_key') ){}
        else {
            $data['order_key'] = $this->find()
                                      ->select(['order_key'])
                                      ->order(['order_key' => 'DESC'])
                                      ->first()
                                      ->order_key + 1;
        }
        if( $data->offsetExists('display') && is_string($data['display']) && 
            array_key_exists( $data['display'], self::DISPLAY ) ){
            $data['display'] = self::DISPLAY[$data['display']];
        }
    }

    public function getPresets( string $username ){
        $query = $this->find()
                      ->select(['preset'])
                      ->distinct(['preset'])
                      ->where(['username' => $username ])
                      ->order(['order_key' => 'ASC'])
                    ;
        $ret = [];
        foreach( $query as $entity ){
            $ret[] = $entity->preset;
        }
        return $ret;
    }

    public function hasPreset( string $username, string $preset ){
        return (bool)$this->find()->where(['username' => $username, 'preset' => $preset ])->count();
    }

    public function getName( string $username, string $preset, array $options = [] ){
        $display_lebel = self::DISPLAY_LEBEL['normal'];
        if( array_key_exists('display_lebel',$options) ){
            if( is_string($options['display_lebel']) ){
                $display_lebel = self::DISPLAY_LEBEL[ $options['display_lebel'] ];
            }
            else if( is_int($options['display_lebel']) ){
                $display_lebel = $options['display_lebel'];
            }
        }

        $query = $this->find()
                      ->select(['name','type','short','display'])
                      ->where(['username' => $username, 'preset' => $preset ])
                      ->order(['order_key' => 'ASC'])
                    ;
        foreach( $query as $row ){
            if( $row->display == self::DISPLAY['private'] && $display_lebel < self::DISPLAY_LEBEL['private'] ){
                continue;
            }
            else if( $row->display <= self::DISPLAY['omit'] && $display_lebel < self::DISPLAY_LEBEL['full'] ){
                continue;
            }
            else {
                $name = '';
                if( $row->display == self::DISPLAY['short'] && $display_lebel < self::DISPLAY_LEBEL['full'] ){
                    $name = $row->short;
                }
                else {
                    $name = $row->name;
                }
                $names[] = ['name' => $name, 'type' => $row->type ];
                unset($name);
            }
            unset($row);
        }
        return isset($names) ? $names : [];
    }

    public function getNameData( string $username, array $options = [] ){
        $query = $this->find()
                      ->select(['name','type','display','short','preset'])
                      ->where(['username' => $username])
                      ->order(['preset' => 'ASC', 'order_key' => 'ASC'])
                    ;
        if( array_key_exists( 'preset', $options ) ){
            $query->where(['preset' => $options['preset']]);
        }
        foreach ( $query as $entity ) {
            $array = $entity->toArray();
            unset($array['preset']);
            if( array_key_exists('display',$options ) && $options['display'] == 'string' ){
                $ret = array_search( $entity['display'], self::DISPLAY );
                $array['display'] = $ret !== false ? $ret : '';
            }
            $data[ $entity->preset ][] = $array;
        }
        return isset($data) ? $data : [];
    }

    public function setNameData( string $username, array $data, array $options = [], array &$errors = [] ){
        $r_data = [];
        foreach ( $data as $preset => $names ) {
            $insert = [];
            $modified = [];
            foreach ( $names as $i => $name ) {
                $entity = $this->find()
                               ->select(['order_key','name','type','display','short'])
                               ->where( function( $exp, $q ) use ( $modified, $i ){
                                    if( !empty($modified) ){
                                        $exp->notIn('id', $modified );
                                    }
                                    return $exp->notEq('order_key', $i ) ;
                                })
                               ->where( array_merge(['username' => $username, 'preset' => $preset ], $name ) )
                               ->first()
                            ;
                if( $entity ){
                    $this->patchEntity($entity, ['order_key' => $i ]);
                    if( empty($entity->errors()) && $this->save($entity) ){
                        array_push( $modified, $entity->id );
                        continue;
                    }
                }

                $insert[$i] = $name;
                unset($entity);
            }

            $this->query()
                 ->delete()
                 ->where( function( $exp, $q ) use ($modified) {
                    if( !empty($modified) ){
                        $exp->notIn('id', $modified);
                    }
                    return $exp;
                 })
                 ->where(['username' => $username, 'preset' => $preset ])
                 ->execute();

            foreach ( $insert as $i => &$name ) {
                $name = array_merge( $name, ['username' => $username, 'order_key' => $i, 'preset' => $preset ]);
            }
            unset($name);
            
            $entities = $this->newEntities( $insert );
            
            $ret = $this->saveMany( $entities );

            if( $ret === false ){
                foreach ( $entities as $i => $entity ) {
                    $errors[] = [ $entity->name, $entity->errors() ];
                }
                return false;
            }

            $array = $this->find()
                          ->select(['name','type','display','short'])
                          ->where( ['username' => $username, 'preset' => $preset ])
                          ->order( ['order_key' => 'ASC' ])
                          ->hydrate(false)
                          ->toArray()
                        ;
            if( array_key_exists('display', $options ) && $options['display'] == 'string' ){
                foreach( $array as $i => &$name ){
                    $display_str = array_search( $name['display'], self::DISPLAY );
                    $name['display'] = $display_str !== false ? $display_str : '';
                }
                unset($name);
            }
            $r_data[$preset] = $array;
        }
        
        return $r_data;
    }

    public function renamePreset( string $username, string $preset, string $new_name ){
        $presets_exist = $this->getPresets( $username );
        if( array_search( $preset, $presets_exist ) !== false && array_search( $new_name, $presets_exist ) === false ){
            return $this->updateAll( ['preset' => $new_name ], ['preset' => $preset ] );
        }
        else {
            return false;
        }
    }

    public function removePreset( string $username, string $preset ){
        $this->query()->delete()->where(['username' => $username, 'preset' => $preset ])->execute();
    }
}
