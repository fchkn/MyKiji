<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User newEmptyEntity()
 * @method \App\Model\Entity\User newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator->setProvider('custom', 'App\Model\Validation\CustomValidation');

        $validator
            ->maxLength('name', 20)
            ->notEmpty('name', 'ユーザー名を入力してください。')
            ->add('name', 'notSpace', [
                'rule' => ['notSpace'],
                'provider' => 'custom',
                'message' => 'ユーザー名に半角/全角スペースを含めないでください。']);

        $validator
            ->maxLength('password', 20)
            ->notEmpty('password', 'パスワードを入力してください。')
            ->add('password', 'alphaNumeric', [
                'rule' => ['alphaNumericCustom'],
                'provider' => 'custom',
                'message' => 'パスワードは半角英数字で入力してください。']);

        $validator
            ->maxLength('password_re', 20)
            ->notEmpty('password_re', 'パスワードを入力してください。')
            ->add('password_re', 'alphaNumeric', [
                'rule' => ['alphaNumericCustom'],
                'provider' => 'custom',
                'message' => 'パスワードは半角英数字で入力してください。'])
            ->equalToField('password_re', 'password', '再入力したパスワードが間違っています。');

        $validator
            ->maxLength('password_curt', 20)
            ->notEmpty('password_curt', 'パスワードを入力してください。')
            ->add('password_curt', 'alphaNumeric', [
                'rule' => ['alphaNumericCustom'],
                'provider' => 'custom',
                'message' => 'パスワードは半角英数字で入力してください。'])
            ->add('password_curt', 'matchCurrentPassword', [
                'rule' => ['matchCurrentPassword'],
                'provider' => 'custom',
                'message' => '現在のパスワードが間違っています。']);

        $validator
            ->email('email', false, '正しい形式でメールアドレスを入力してください。')
            ->maxLength('email', 254)
            ->notEmpty('email', 'メールアドレスを入力してください。');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name']), ['errorField' => 'name', 'message' => 'このユーザー名は既に登録されています。']);
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email', 'message' => 'このメールアドレスは既に登録されています。']);

        return $rules;
    }
}
