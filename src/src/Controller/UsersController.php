<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
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
        $this->Authentication->addUnauthenticatedActions(['view', 'add', 'delete_complete', 'login', 'logout']);
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->Articles = TableRegistry::get('articles');
        $this->Favorites = TableRegistry::get('favorites');
        $this->Follows = TableRegistry::get('follows');
    }

    /**
     * ユーザー画面処理
     */
    public function view() {
        $user_id = $this->request->getQuery('user_id');
        $user = null;

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

        // Artilcesのエイリアス名を一時退避
        $tmpAlias = $this->Articles->getAlias();

        // 投稿記事データ取得
        $post_articles = $this->paginate($this->Articles->setAlias('post_articles')->find('all', [
            'conditions' => ['post_articles.user_id' => $user_id],
            'contain' => ['Users'],
            'order' => ['post_articles.created' => 'desc'],
        ]), ['limit' => 5, 'scope' => 'post_articles'])->toArray();
        $this->set(compact('post_articles'));

        // お気に入り記事データ取得
        $favorite_articles = [];
        $favorites = $this->Favorites->find('all', [
            'conditions' => ['favorites.user_id' => $user_id],
        ])->toArray();
        if (!empty($favorites)) {
            $condition = [];
            foreach ($favorites as $favorite) {
                $condition[] = ['favorite_articles.id' => $favorite->article_id];
            }
            $favorite_articles = $this->paginate($this->Articles->setAlias('favorite_articles')->find('all', [
                'conditions' => ['OR' => $condition],
                'contain' => ['Users'],
                'order' => ['favorite_articles.created' => 'desc'],
            ]), ['limit' => 5,'scope' => 'favorite_articles'])->toArray();
        }
        $this->set(compact('favorite_articles'));

        // Articlesのエイリアス名を元に戻す
        $this->Articles->setAlias($tmpAlias);

        // Followsのエイリアス名を一時退避
        $tmpAlias = $this->Follows->getAlias();

        // フォローデータ取得
        $follows = $this->paginate($this->Follows->setAlias('follows')->find('all', [
            'conditions' => ['follows.user_id' => $user_id],
            'contain' => ['FollowUsers'],
            'order' => ['follows.created' => 'desc'],
        ]), ['limit' => 10, 'scope' => 'follows'])->toArray();
        $this->set(compact('follows'));

        // フォロワーデータ取得
        $followers = $this->paginate($this->Follows->setAlias('followers')->find('all', [
            'conditions' => ['followers.follow_user_id' => $user_id],
            'contain' => ['FollowerUsers'],
            'order' => ['followers.created' => 'desc'],
        ]), ['limit' => 10, 'scope' => 'followers'])->toArray();
        $this->set(compact('followers'));

        // Followsのエイリアス名を元に戻す
        $this->Follows->setAlias($tmpAlias);

        // ログインユーザーがフォロー中か確認
        $hasFollow = false;
        if ($this->hasAuth) {
            $follow = $this->Follows->find()->where([
                'user_id' => $this->auth_user->id,
                'follow_user_id' => $user_id])->first();
            if(!empty($follow)) {
                $hasFollow = true;
            }
        }

        // ページネーション要否
        $hasPaginator = true;

        // マイページ判定
        $isMypage = false;
        if ($this->hasAuth && $user_id == $this->auth_user->id) {
            $isMypage = true;
        }

        $this->set(compact('user', 'post_articles', 'hasFollow', 'hasPaginator', 'isMypage'));
    }

    /**
     * ユーザー登録処理
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->newEmptyEntity();
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                // 認証設定してログイン状態にする
                $this->Authentication->setIdentity($user);

                // プロフィール画像にデフォルトアイコンを設定
                copy(WWW_ROOT . 'img/default_icon.jpg', UPLOAD_PROFILE_IMG_PATH . 'user_' . $user->id .  '.jpg');

                // 登録完了メールを送信
                $this->getMailer('User')->send('register', [$user]);

                return $this->redirect(['controller' => 'Users', 'action' => 'add_complete']);
            }
            $this->Flash->error(__('登録ができませんでした。'));
        }
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
        if ($this->request->is('post')) {
            $user = $this->Users->get($this->auth_user->id);
            $data = $this->request->getData();

            if (isset($data['edit_profileinfo'])) {
                // プロフィール画像変更
                if (!empty($data['profile_img']->getClientFilename())) {
                    $this->saveProfileImg($data['profile_img'], $user->id);
                }

                // ユーザー名変更
                $this->Users->patchEntity($user, ['name' => $data['name']]);
            } else if (isset($data['edit_email'])) {
                // メールアドレス変更
                $this->Users->patchEntity($user, ['email' => $data['email']]);
            } else if (isset($data['edit_password'])) {
                // パスワード変更
                $this->Users->patchEntity($user, ['password' => $data['password_new']]);
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
     * ユーザー退会処理
     */
    public function delete()
    {
        if ($this->request->is('post')) {
            $user = $this->Users->get($this->auth_user->id);

            // ユーザーデータを削除
            if ($this->Users->delete($user)) {
                // ログアウト処理を実行
                if ($this->Authentication->getResult()->isValid()) {
                    $this->Authentication->logout();
                }

                // プロフィール画像を削除
                unlink(UPLOAD_PROFILE_IMG_PATH . 'user_' . $user->id .  '.jpg');

                // 退会完了メールを送信
                $this->getMailer('User')->send('withdrawal', [$user]);

                return $this->redirect(['controller' => 'Users', 'action' => 'delete_complete']);
            }
            $this->Flash->error(__('退会ができませんでした。'));
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
     *
     * @param LaminasDiactorosUploadedFile $profile_img
     * @param int $user_id
     */
    private function saveProfileImg($profile_img, $user_id)
    {
        $file = $profile_img->getStream()->getMetadata('uri');
        $type = $profile_img->getClientMediaType();

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

        // 成型したプロフィール画像を保存
        $profile_img_path = UPLOAD_PROFILE_IMG_PATH . 'user_'. $user_id . '.jpg';
        imagejpeg($dst_img, $profile_img_path);

        // リソースを解放
        imagedestroy($src_img);
        imagedestroy($dst_img);
    }
}
