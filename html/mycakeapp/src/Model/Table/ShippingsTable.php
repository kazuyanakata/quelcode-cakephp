<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Shippings Model
 *
 * @method \App\Model\Entity\Shipping get($primaryKey, $options = [])
 * @method \App\Model\Entity\Shipping newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Shipping[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Shipping|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shipping saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shipping patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Shipping[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Shipping findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ShippingsTable extends Table
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

        $this->setTable('shippings');
        $this->setDisplayField('bidinfo_id');
        $this->setPrimaryKey('bidinfo_id');

        $this->addBehavior('Timestamp');
        $this->belongsTo('Bidinfo', [
            'foreignKey' => 'bidinfo_id',
            'joinType' => 'INNER',
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
            ->integer('bidinfo_id')
            ->allowEmptyString('bidinfo_id', null, 'create');

        $validator
            ->scalar('receive_name')
            ->maxLength('receive_name', 100, '100文字いないで入力してください。')
            ->requirePresence('receive_name', 'create')
            ->notEmptyString('receive_name', '必ず入力してください。');

        $validator
            ->scalar('receive_address')
            ->maxLength('receive_address', 1000, '1000文字以内で入力してください')
            ->requirePresence('receive_address', 'create')
            ->notEmptyString('receive_address', '必ず入力してください');

        $validator
            ->scalar('receive_phone_number')
            ->minLength('receive_phone_number', 10, '半角数字のみ10文字以上12文字以内で入力してください。')
            ->maxLength('receive_phone_number', 12, '半角数字のみ10文字以上12文字以内で入力してください。')
            ->requirePresence('receive_phone_number', 'create')
            ->notEmptyString('receive_phone_number', '必ず入力してください')
            ->regex('receive_phone_number', '/^[0-9]+$/', '半角数字のみで入力してください(例)1234567890');

        $validator
            ->boolean('is_sent')
            ->requirePresence('is_sent', 'create')
            ->notEmptyString('is_sent');

        $validator
            ->boolean('is_received')
            ->requirePresence('is_received', 'create')
            ->notEmptyString('is_received');

        return $validator;
    }
}
