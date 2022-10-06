<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * login method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function login()
    {
        $this->viewBuilder()->setLayout('common');
    }
}
