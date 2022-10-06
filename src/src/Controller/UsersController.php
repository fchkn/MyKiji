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
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // ログインアクションを認証を必要としないように設定することで、
        // 無限リダイレクトループの問題を防ぐ
        $this->Authentication->addUnauthenticatedActions(['login']);
    }


    /**
     * login method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function login()
    {
        $this->viewBuilder()->setLayout('common');

        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        // POSTやGETに関係なく、ユーザーがログインしていればリダイレクトする
        if ($result->isValid()) {
            // ログイン成功後に Top画面 にリダイレクトする
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Top',
                'action' => 'index',
            ]);
    
            return $this->redirect($redirect);
        }
        // ユーザーの送信と認証に失敗した場合にエラーを表示する
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('メールアドレスまたはパスワードが間違っています。'));
        }
    }
}
