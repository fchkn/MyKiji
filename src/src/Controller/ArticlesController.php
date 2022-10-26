<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;
use DOMDocument;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // ログインアクションを認証を必要としないように設定することで、
        // 無限リダイレクトループの問題を防ぐ
        $this->Authentication->addUnauthenticatedActions(['view']);
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->Users = TableRegistry::get('users');
    }

    /**
     * 記事詳細画面処理
     */
    public function view() {
        $article_id = $this->request->getQuery('article_id');
        $article = null;

        // 記事データ取得
        if (!empty($article_id) && is_numeric($article_id)) {
            $article = $this->Articles->findById($article_id)->first();

            if (is_null($article)) {
                // 存在しない記事の場合はトップ画面に遷移させる
                return $this->redirect(['controller' => 'Top', 'action' => 'index']);
            }
        } else {
            // クエリパラメータが存在しない、または数値以外の場合はトップ画面に遷移させる
            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }

        // ユーザーデータ取得
        $user = $this->Users->findById($article->user_id)->first();

        $this->set(compact('article', 'user'));
    }

    /**
     * 記事追加処理
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $article = $this->Articles->newEmptyEntity();
            $data = array_merge(['user_id' => $this->auth_user->id], $this->request->getData());

            $dom = new DOMDocument;
            $dom->loadHTML($data['text']);
            $imgs = $dom->getElementsByTagName('img');

            if ($imgs->length != 0) {
                // imgタグが記事本文にある場合
                // 記事本文を一旦仮の文字列にしておく
                $data['text'] = '<p>tmp text</p>';
            }

            $this->Articles->patchEntity($article, $data);

            if ($this->Articles->save($article)) {
                if ($imgs->length != 0) {
                    // imgタグが記事本文にある場合
                    // 記事画像を作成し、記事本文を更新する
                    $this->createImageAndUpdateText($dom, $article);
                }

                return $this->redirect(['controller' => 'Users', 'action' => 'view?user_id='. $this->auth_user->id]);
            }

            $this->Flash->error(__('記事追加に失敗しました'));
        }
    }

    /**
     * 記事画像作成＆記事本文更新処理
     */
    private function createImageAndUpdateText(DOMDocument $dom, \App\Model\Entity\Article $article) {
        $article_id = $article->id;
        $imgs = $dom->getElementsByTagName('img');

        // 記事画像作成
        foreach ($imgs as $i => $img) {
            $src = $img->getAttribute('src');

            // 画像データをデコード
            $img_base64 = str_replace('base64,', '', strstr($src, 'base64,'));
            $img_decode = base64_decode($img_base64);

            // 画像保存パスを設定
            $img_ext = str_replace('data:image/', '', strstr($src, ';base64,', true));
            $img_name ="art". $article_id . "_img" . $i+1 . "." . $img_ext;
            $img_path = UPLOAD_ARTICLE_IMG_PATH . $img_name;

            // 画像を保存
            file_put_contents($img_path, $img_decode);

            // imgタグのsrcを画像保存パスに変更
            $img->setAttribute('src', "/upload/article_img/". $img_name);
        }

        // 元データに合わせて本文を調整
        $text = $dom->saveHTML();
        $text = str_replace('<html><body>', '', strstr($text, '<html><body>'));
        $text = str_replace('</body></html>', '', $text);

        // 記事テーブルの本文を更新
        $this->Articles->patchEntity($article, ['text' => $text]);
        $this->Articles->save($article);
    }
}
