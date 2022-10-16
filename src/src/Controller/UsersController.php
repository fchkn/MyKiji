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
        // 登録完了メールを送信
        $this->getMailer('User')->send('register', [$this->auth_user]);

        // プロフィール画像にデフォルトアイコンを設定
        copy(WWW_ROOT . 'img/default_icon.jpg', UPLOAD_PROFILE_IMG_PATH . 'user_' . $this->auth_user->id .  '.jpg');
    }

    /**
     * ユーザー編集処理
     */
    public function edit()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->get($this->auth_user->id);
            $post_data = $this->request->getData();

            if (isset($post_data['edit_profileinfo'])) {
                // プロフィール画像変更
                if (!empty($post_data['profile_img']->getClientFilename())) {
                    $this->saveProfileImg($post_data['profile_img'], $user->id);
                }

                // ユーザー名変更
                $this->Users->patchEntity($user, ['name' => $post_data['name']]);
            } else if (isset($post_data['edit_email'])) {
                // メールアドレス変更
                $this->Users->patchEntity($user, ['email' => $post_data['email']]);
            } else if (isset($post_data['edit_password'])) {
                // パスワード変更
                $this->Users->patchEntity($user, ['password' => $post_data['password_new']]);
            }

            // テーブルを更新
            if ($this->Users->save($user)) {
                // 認証を再設定
                $this->Authentication->setIdentity($user);
                $auth_user = $this->Authentication->getIdentity();
                $this->set(compact('auth_user'));
                echo '<script>alert("変更しました。")</script>';
            } else {
                $this->Flash->error(__('変更に失敗しました。'));
            }
        }
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

    /**
     * プロフィール画像保存処理
     */
    private function saveProfileImg($profile_img, $user_id)
    {
        // プロフィール画像保存先のパス
        $profile_img_path = UPLOAD_PROFILE_IMG_PATH . 'user_'. $user_id . '.jpg';

        $type = $profile_img->getClientMediaType();
        $file = $profile_img->getStream()->getMetadata('uri');
        $png_img = $type == 'image/png' ? imagecreatefrompng($file) : false;
        if ($png_img) {
            // プロフィール画像がpngの場合はjpgに変換して保存
            imagejpeg($png_img, $profile_img_path);
        } else {
            // プロフィール画像がjpgの場合はそのまま保存
            $profile_img->moveTo($profile_img_path);
        }
    }
}
