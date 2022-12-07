<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Mailer\MailerAwareTrait;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

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
        $this->Authentication->addUnauthenticatedActions([
            'view',
            'add',
            'delete_complete',
            'login',
            'logout',
            'send_reissue_password_mail',
            'send_reissue_password_mail_complete',
            'reissue_password',
            'reissue_password_complete'
        ]);
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->Articles = TableRegistry::get('articles');
        $this->Favorites = TableRegistry::get('favorites');
        $this->Follows = TableRegistry::get('follows');
        $this->Tokens = TableRegistry::get('tokens');
    }

    /**
     * ユーザー画面処理
     */
    public function view() {
        $user_id = $this->request->getQuery('user_id');
        $user = null;
        $post_articles = null;
        $favorites = null;
        $follows = null;
        $followers = null;
        $hasFollow = false;
        $hasPaginator = true;

        try {
            // ユーザーデータ取得
            if (!empty($user_id) && is_numeric($user_id)) {
                $user = $this->Users->findById($user_id)->first();

                if (is_null($user)) {
                    // 存在しないユーザーの場合はトップ画面に遷移させる
                    return $this->redirect(['controller' => 'Top', 'action' => 'index']);
                }
            } else {
                // クエリパラメータが存在しない、または数値以外の場合はトップ画面に遷移させる
                return $this->redirect(['controller' => 'Top', 'action' => 'index']);
            }

            // 投稿記事データ取得
            $post_articles = $this->paginate($this->Articles->find('all', [
                'conditions' => ['articles.user_id' => $user_id],
                'contain' => ['Users'],
                'order' => ['articles.created' => 'desc'],
            ]), ['limit' => 5, 'scope' => 'articles'])->toArray();

            // お気に入りデータ取得
            $favorites = $this->paginate($this->Favorites->find('all', [
                'conditions' => ['favorites.user_id' => $user_id],
                'contain' => ['Articles.Users'],
                'order' => ['favorites.created' => 'desc'],
            ]), ['limit' => 5,'scope' => 'favorites'])->toArray();

            // Followsのエイリアス名を一時退避
            $tmpAlias = $this->Follows->getAlias();

            // フォローデータ取得
            $follows = $this->paginate($this->Follows->setAlias('follows')->find('all', [
                'conditions' => ['follows.user_id' => $user_id],
                'contain' => ['FollowUsers'],
                'order' => ['follows.created' => 'desc'],
            ]), ['limit' => 10, 'scope' => 'follows'])->toArray();

            // フォロワーデータ取得
            $followers = $this->paginate($this->Follows->setAlias('followers')->find('all', [
                'conditions' => ['followers.follow_user_id' => $user_id],
                'contain' => ['FollowerUsers'],
                'order' => ['followers.created' => 'desc'],
            ]), ['limit' => 10, 'scope' => 'followers'])->toArray();

            // Followsのエイリアス名を元に戻す
            $this->Follows->setAlias($tmpAlias);

            // ログインユーザーがフォロー中か確認
            if ($this->hasAuth) {
                $follow = $this->Follows->find()->where([
                    'user_id' => $this->auth_user->id,
                    'follow_user_id' => $user_id])->first();
                if(!empty($follow)) {
                    $hasFollow = true;
                }
            }
        } catch (\Exception $e) {
            // エラーログを出力
            $error = implode("\n", [
                "\nStatus Code: " . $e->getCode(),
                "Message: " . $e->getMessage(),
                "File: " . $e->getFile() . ", line " . $e->getLine(),
                "Stack Trace:\n" . $e->getTraceAsString()
            ]);
            $this->log($error);

            $this->Flash->error(__('異常なエラーが発生しました。'));
        }

        // マイページ判定
        $isMypage = false;
        if ($this->hasAuth && $user_id == $this->auth_user->id) {
            $isMypage = true;
        }

        // リダイレクトプロパティ取得
        $session = $this->getRequest()->getSession();
        $redirect = $session->read('redirect');
        if (!empty($redirect)) {
            $session->delete('redirect');
        }

        $this->set(compact(
            'user',
            'post_articles',
            'favorites',
            'follows',
            'followers',
            'hasFollow',
            'hasPaginator',
            'isMypage',
            'redirect'
        ));
    }

    /**
     * ユーザー登録処理
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();

            try {
                // ユーザデータを登録
                $this->Users->patchEntity($user, $this->request->getData());
                $this->Users->saveOrFail($user);

                // トークンテーブルを登録
                $token = $this->Tokens->newEmptyEntity();
                $this->Tokens->patchEntity($token, ['user_id' => $user->id]);
                $this->Tokens->saveOrFail($token);

                // プロフィール画像にデフォルトアイコンを設定
                if(!copy(WWW_ROOT . 'img/default_icon.jpg', UPLOAD_PROFILE_IMG_PATH . 'user_' . $user->id .  '.jpg')){
                    throw new \Exception('プロフィール画像の作成に失敗しました。', 500);
                }

                // 登録完了メールを送信
                $this->getMailer('User')->send('register', [$user]);

                // 認証設定してログイン状態にする
                $this->Authentication->setIdentity($user);

                // コミット
                $connection->commit();

                return $this->redirect(['controller' => 'Users', 'action' => 'add_complete']);
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                // バリデーション違反時の例外処理

                // ロールバック
                $connection->rollback();

                $this->Flash->error(__('登録ができませんでした。'));
            } catch (\Exception $e) {
                // その他例外処理

                // ロールバック
                $connection->rollback();

                // プロフィール画像削除
                $img_path = UPLOAD_PROFILE_IMG_PATH . 'user_' . $user->id .  '.jpg';
                if (file_exists($img_path)) {
                    unlink($img_path);
                }

                // エラーログを出力
                $error = implode("\n", [
                    "\nStatus Code: " . $e->getCode(),
                    "Message: " . $e->getMessage(),
                    "File: " . $e->getFile() . ", line " . $e->getLine(),
                    "Stack Trace:\n" . $e->getTraceAsString()
                ]);
                $this->log($error);

                $this->Flash->error(__('異常なエラーが発生しました。'));
            }
        }
        $this->set(compact('user'));
    }

    /**
     * ユーザー登録完了処理
     */
    public function add_complete()
    {
    }

    /**
     * ユーザー編集処理
     */
    public function edit()
    {
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();

            try {
                $user = $this->Users->get($this->auth_user->id);
                $data = $this->request->getData();

                switch ($data['edit_target']) {
                    case 'profileinfo' :
                        // ユーザー名変更
                        $this->Users->patchEntity($user, ['name' => $data['name']]);
                        break;
                    case 'email' :
                        // メールアドレス変更
                        $this->Users->patchEntity($user, ['email' => $data['email']]);
                        break;
                    case 'password' :
                        // パスワード変更
                        $this->Users->patchEntity($user, [
                            'password' => $data['password'],
                            'password_re' => $data['password_re'],
                            'password_curt' => $data['password_curt'],
                            'password_curt_registered' => $this->auth_user->password
                        ]);
                        break;
                }

                // ユーザーデータを更新
                $this->Users->saveOrFail($user);

                // プロフィール画像を変更
                if ($data['edit_target'] == "profileinfo" &&
                    !empty($data['profile_img']->getClientFilename())) {
                    $this->saveProfileImg($data['profile_img'], $user->id);
                }

                // ログイン認証を再設定
                $this->Authentication->setIdentity($user);

                // 変更保存完了ポップアップの判定パラメータをセッションに格納
                $session = $this->getRequest()->getSession();
                $session->write('redirect', 'users_edit');

                // コミット
                $connection->commit();

                // ページを更新
                return $this->redirect($this->request->referer());
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                // バリデーション違反時の例外処理

                // ロールバック
                $connection->rollback();

                $this->Flash->error(__('変更ができませんでした。'));
            }  catch (\Exception $e) {
                // その他例外処理

                // ロールバック
                $connection->rollback();

                // エラーログを出力
                $error = implode("\n", [
                    "\nStatus Code: " . $e->getCode(),
                    "Message: " . $e->getMessage(),
                    "File: " . $e->getFile() . ", line " . $e->getLine(),
                    "Stack Trace:\n" . $e->getTraceAsString()
                ]);
                $this->log($error);

                $this->Flash->error(__('異常なエラーが発生しました。'));
            }
        } else {
            $this->Users->patchEntity($user, [
                'name' => $this->auth_user->name,
                'email' => $this->auth_user->email,
            ]);
        }

        // リダイレクトプロパティ取得
        $session = $this->getRequest()->getSession();
        $redirect = $session->read('redirect');
        if (!empty($redirect)) {
            $session->delete('redirect');
        }

        $this->set(compact('user', 'redirect'));
    }

    /**
     * ユーザー退会処理
     */
    public function delete()
    {
        if ($this->request->is('post')) {
            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();

            try {
                $user = $this->Users->get($this->auth_user->id);

                $articles = $this->Articles->find('all', [
                    'conditions' => ['user_id' => $this->auth_user->id]
                ])->toArray();

                // ユーザーデータを削除
                $this->Users->deleteOrFail($user);

                // プロフィール画像を削除
                unlink(UPLOAD_PROFILE_IMG_PATH . 'user_' . $user->id .  '.jpg');

                // 記事画像を削除
                foreach ($articles as $article) {
                    $img_dir_path = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article->id;
                    if (file_exists($img_dir_path)) {
                        array_map('unlink', glob($img_dir_path. '/*.*'));
                        rmdir($img_dir_path);
                    }
                }

                // ログアウト処理を実行
                if ($this->Authentication->getResult()->isValid()) {
                    $this->Authentication->logout();
                }

                // 退会完了メールを送信
                $this->getMailer('User')->send('withdrawal', [$user]);

                // コミット
                $connection->commit();

                return $this->redirect(['controller' => 'Users', 'action' => 'delete_complete']);
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                // バリデーション違反時の例外処理

                // ロールバック
                $connection->rollback();

                $this->Flash->error(__('退会ができませんでした。'));
            } catch (\Exception $e) {
                // その他例外処理

                // ロールバック
                $connection->rollback();

                // エラーログを出力
                $error = implode("\n", [
                    "\nStatus Code: " . $e->getCode(),
                    "Message: " . $e->getMessage(),
                    "File: " . $e->getFile() . ", line " . $e->getLine(),
                    "Stack Trace:\n" . $e->getTraceAsString()
                ]);
                $this->log($error);

                $this->Flash->error(__('異常なエラーが発生しました。'));
            }
        }
    }

    /**
     * ユーザー退会完了処理
     */
    public function delete_complete()
    {
    }

    /**
     * ログイン処理
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $user = $this->Users->newEmptyEntity();

        $result = $this->Authentication->getResult();
        // POSTやGETに関係なく、ユーザーがログインしていればリダイレクトする
        if ($result->isValid()) {
            // ログイン成功後に Top画面 にリダイレクトする
            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }
        // ユーザーの送信と認証に失敗した場合にエラーを表示する
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Users->patchEntity($user, $this->request->getData());
            $this->Flash->error(__('メールアドレスまたはパスワードが間違っています。'));
        }

        $this->set(compact('user'));
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
     * パスワード再発行メール送信処理
     */
    public function send_reissue_password_mail()
    {
        $user_entity = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();

            try {
                $email = $this->request->getData('email');
                $this->Users->patchEntity($user_entity, ['email' => $email]);

                $user = $this->Users->find('all', [
                    'conditions' => ['email' => $email],
                ])->first();

                if (!empty($user)) {
                    // トークンと有効期限(30分)を生成
                    $tmp_token = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, 16);
                    $limit_time = time() + 1800;

                    // トークンテーブルに設定
                    $token_id = $this->Tokens->find('all', [
                        'conditions' => ['user_id' => $user->id],
                    ])->first()->id;
                    $token = $this->Tokens->get($token_id);
                    $this->Tokens->patchEntity($token, ['token' => $tmp_token, 'limit_time' => $limit_time]);
                    $this->Tokens->saveOrFail($token);

                    // パスワード再発行画面のurlを設定
                    $url = Router::url('/users/reissue_password?ui='. $user->id . '&tk=' . $tmp_token, true);

                    // パスワード再発行メールを送信
                    $this->getMailer('User')->send('reissue_password', [$user, $url]);

                    // コミット
                    $connection->commit();

                    return $this->redirect(['controller' => 'Users', 'action' => 'send_reissue_password_mail_complete']);
                } else if (empty($user) && $user_entity->hasErrors()) {
                    $this->Flash->error(__('送信ができませんでした。'));
                } else if (empty($user) && !$user_entity->hasErrors()) {
                    $this->Flash->error(__('登録されていないメールアドレスです。'));
                }
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                // バリデーション違反時の例外処理

                // ロールバック
                $connection->rollback();

                $this->Flash->error(__('送信ができませんでした。'));
            } catch (\Exception $e) {
                // その他例外処理

                // ロールバック
                $connection->rollback();

                // エラーログを出力
                $error = implode("\n", [
                    "\nStatus Code: " . $e->getCode(),
                    "Message: " . $e->getMessage(),
                    "File: " . $e->getFile() . ", line " . $e->getLine(),
                    "Stack Trace:\n" . $e->getTraceAsString()
                ]);
                $this->log($error);

                $this->Flash->error(__('異常なエラーが発生しました。'));
            }
        }

        $this->set(compact('user_entity'));
    }

    /**
     * パスワード再発行メール送信処理
     */
    public function send_reissue_password_mail_complete()
    {
    }

    /**
     * パスワード再発行処理
     */
    public function reissue_password()
    {
        $params = $this->request->getQueryParams();
        $user = null;
        $isEnableAccess = false;

        // トランザクション開始
        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {
            // ユーザーデータ取得
            $user = $this->Users->get($params['ui']);

            // トークンデータ取得
            $token_id = $this->Tokens->find('all', [
                'conditions' => ['user_id' => $user->id],
            ])->first()->id;
            $token = $this->Tokens->get($token_id);

            // 有効なアクセスであることを判定
            if (!empty($user) && !empty($token) && $token->token == $params['tk'] && time() < $token->limit_time) {
                $isEnableAccess = true;

                if ($this->request->is('post')) {
                    $data = $this->request->getData();

                    // パスワードを更新
                    $this->Users->patchEntity($user, [
                        'password' => $data['password'],
                        'password_re' => $data['password_re']]);
                    $this->Users->saveOrFail($user);

                    // トークンテーブルを初期化
                    $this->Tokens->patchEntity($token, ['token' => null, 'limit_time' => null]);
                    $this->Tokens->saveOrFail($token);

                    // コミット
                    $connection->commit();

                    return $this->redirect(['controller' => 'Users', 'action' => 'reissue_password_complete']);
                } else {
                    $user = $this->Users->newEmptyEntity();
                }
            }
        } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
            // バリデーション違反時の例外処理

            // ロールバック
            $connection->rollback();

            $this->Flash->error(__('再発行ができませんでした。'));
        } catch (\Exception $e) {
            // その他例外処理

            // ロールバック
            $connection->rollback();

            // エラーログを出力
            $error = implode("\n", [
                "\nStatus Code: " . $e->getCode(),
                "Message: " . $e->getMessage(),
                "File: " . $e->getFile() . ", line " . $e->getLine(),
                "Stack Trace:\n" . $e->getTraceAsString()
            ]);
            $this->log($error);

            $this->Flash->error(__('異常なエラーが発生しました。'));
        }



        $this->set(compact('user', 'isEnableAccess'));
    }

    /**
     * パスワード再発行完了処理
     */
    public function reissue_password_complete()
    {
    }

    /**
     * プロフィール画像保存処理
     *
     * @param LaminasDiactorosUploadedFile $profile_img
     * @param int $user_id
     */
    private function saveProfileImg($profile_img, $user_id)
    {
        $file = $profile_img->getStream()->getMetadata('uri');
        $type = $profile_img->getClientMediaType();

        // 元画像のバックアップを作成
        $profile_img_path = UPLOAD_PROFILE_IMG_PATH . 'user_'. $user_id . '.jpg';
        $profile_img_backup_path = UPLOAD_PROFILE_IMG_PATH . 'user_' . $user_id .  '_backup.jpg';
        copy($profile_img_path, $profile_img_backup_path);
        if(!copy($profile_img_path, $profile_img_backup_path)){
            throw new \Exception('プロフィール画像のバックアップ作成に失敗しました。', 500);
        }

        // 元画像のファイルデータを作成
        $src_img = null;
        if ($type == 'image/jpg' || $type == 'image/jpeg') {
            $src_img = imagecreatefromjpeg($file);
        } else if ($type == 'image/png') {
            $src_img = imagecreatefrompng($file);
        }

        // 元画像の縦横の大きさを比べて小さい方に合わせる
        // 縦横の差をコピー開始位置として使えるようセット
        $src_w = imagesx($src_img);
        $src_h = imagesy($src_img);
        $src_x = 0;
        $src_y = 0;
        if ($src_w > $src_h){
            $src_x = (int)(($src_w - $src_h) * 0.5);
            $src_w = $src_h;
        } else if($src_w < $src_h){
            $src_y = (int)(($src_h - $src_w) * 0.5);
            $src_h = $src_w;
        }

        // 規定サイズで新規画像作成
        $dst_w = 240;
        $dst_h = 240;
        $dst_img = imagecreatetruecolor($dst_w, $dst_h);
        imagefill($dst_img , 0 , 0 , 0xFFFFFF);

        // 新規画像にプロフィール画像を貼付け
        imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        // プロフィール画像を保存
        if(imagejpeg($dst_img, $profile_img_path)) {
            // バックアップを削除
            unlink($profile_img_backup_path);
        } else {
            // バックアップを元画像にコピー
            !copy($profile_img_backup_path, $profile_img_path);
            // バックアップを削除
            unlink($profile_img_backup_path);
            throw new \Exception('プロフィール画像変更に失敗しました。', 500);
        }

        // リソースを解放
        imagedestroy($src_img);
        imagedestroy($dst_img);
    }
}
