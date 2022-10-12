<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Mailer\MailerAwareTrait;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    use MailerAwareTrait;

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // ログインアクションを認証を必要としないように設定することで、
        // 無限リダイレクトループの問題を防ぐ
        $this->Authentication->addUnauthenticatedActions(['view', 'add', 'login', 'logout']);
    }

    /**
     * ユーザー画面処理
     */
    public function view() {
        $user_id = $this->request->getQuery('user_id');
        $user_name = "";
        $isMypage = false;

        // ユーザーデータ取得処理
        if (!empty($user_id) && is_numeric($user_id)) {
            $user = $this->Users->findById($user_id);

            if (!$user->isEmpty()) {
                $user_name = $user->first()['name'];
            } else {
                // 存在しないユーザーの場合はトップ画面に遷移させる。
                return $this->redirect(['controller' => 'Top', 'action' => 'index']);
            }
        } else {
            // クエリパラメータが存在しない、または数値以外の場合はトップ画面に遷移させる。
            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }

        // マイページ判定
        if ($this->hasAuth && $user_id == $this->auth_user->id) {
            $isMypage = true;
        }

        $this->set(compact('user_name', 'isMypage'));
    }

    /**
     * ユーザー登録処理
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                // 登録完了メールを送信
                $this->getMailer('User')->send('register', [$user]);

                // 認証設定してログイン状態にする
                $this->Authentication->setIdentity($user);

                return $this->redirect(['controller' => 'Users', 'action' => 'complete']);
            }
            $this->Flash->error(__('登録ができませんでした。'));
        }
    }

    /**
     * ユーザー登録完了処理
     */
    public function complete()
    {
    }

    /**
     * ユーザー編集処理
     */
    public function edit()
    {
    }
    
    /**
     * ログイン処理
     */
    public function login()
    {
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

    /**
     * ログアウト処理
     */
    public function logout()
    {
        $result = $this->Authentication->getResult();
        // POSTやGETに関係なく、ユーザーがログインしていればリダイレクトする
        if ($result->isValid()) {
            $this->Authentication->logout();
            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }
    }
}
