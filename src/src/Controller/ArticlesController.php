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
    public $paginate = [
        'limit' => 10,
    ];

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // ログインアクションを認証を必要としないように設定することで、
        // 無限リダイレクトループの問題を防ぐ
        $this->Authentication->addUnauthenticatedActions(['view', 'search']);
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->Users = TableRegistry::get('users');
        $this->Favorites = TableRegistry::get('favorites');
        $this->Follows = TableRegistry::get('follows');
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

        $favorite_flg = 0;
        $hasFollow = false;
        if ($this->hasAuth) {
            // お気に入り登録の有無を確認
            $favorite = $this->Favorites->find()->where([
                'user_id' => $this->auth_user->id,
                'article_id' => $article_id])->first();
            if(!empty($favorite)) {
                $favorite_flg = 1;
            }
            // フォローの有無を確認
            $follow = $this->Follows->find()->where([
                'user_id' => $this->auth_user->id,
                'follow_user_id' => $user->id])->first();
            if(!empty($follow)) {
                $hasFollow = true;
            }
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            if (isset($data['edit_article'])) {
                // 編集内容保存ボタン押下の場合
                $this->edit($data, $article_id);
                $this->redirect(['controller' => 'Articles', 'action' => 'view?article_id='. $article_id . '&redirect=articles_edit']);
            } else if (isset($data['delete_article'])) {
                // 編集内容保存ボタン押下の場合
                $this->delete($article_id);
            }
        }

        $this->set(compact('article', 'user', 'favorite_flg', 'hasFollow'));
    }

    /**
     * 記事検索処理
     */
    public function search() {
        $q = $this->request->getQuery();
        $target = "";
        $search = "";
        $search_articles = [];
        $order = "desc";

        if (!empty($q['order']) && $q['order'] == "asc") {
            // 昇順でソートの場合
            $order = "asc";
        }

        if (!empty($q['word'])) {
            // 検索バーから検索の場合
            $target = "word";
            $search = $q['word'];
            $search_articles = $this->paginate($this->Articles->find('all', [
                'conditions' => ['OR' => [
                    'Articles.title LIKE' => '%' . $search . '%',
                    'Articles.tag_1' => $search,
                    'Articles.tag_2' => $search,
                    'Articles.tag_3' => $search,
                    'Articles.tag_4' => $search,
                    'Articles.tag_5' => $search,
                    'Articles.tag_6' => $search,
                ]],
                'contain' => ['Users'],
                'order' => ['Articles.created' => $order],
            ]))->toArray();
        } else if (!empty($q['tag'])) {
            // タグから検索の場合
            $target = "tag";
            $search = $q['tag'];
            $search_articles = $this->paginate($this->Articles->find('all', [
                'conditions' => ['OR' => [
                    'Articles.tag_1' => $search,
                    'Articles.tag_2' => $search,
                    'Articles.tag_3' => $search,
                    'Articles.tag_4' => $search,
                    'Articles.tag_5' => $search,
                    'Articles.tag_6' => $search,
                ]],
                'contain' => ['Users'],
                'order' => ['Articles.created' => $order],
            ]))->toArray();
        } else {
            // クエリパラメータが存在しない場合はトップ画面に遷移させる
            return $this->redirect(['controller' => 'Top', 'action' => 'index']);
        }

        $hasPaginator = true;

        $this->set(compact('target', 'search', 'search_articles', 'order' ,'hasPaginator'));
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
            $dom->loadHTML(mb_convert_encoding($data['text'], 'HTML-ENTITIES', 'UTF-8'));
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
                    $this->createImageAndUpdateText($dom, $article, 'add');
                }

                return $this->redirect(['controller' => 'Users', 'action' => 'view?user_id='. $this->auth_user->id . '&redirect=articles_add']);
            }

            $this->Flash->error(__('記事追加に失敗しました'));
        }
    }

    /**
     * 記事編集処理
     *
     * @param array<string, string|int> $data
     * @param int $article_id
     */
    private function edit($data, $article_id) {
        $article = $this->Articles->get($article_id);

        $dom = new DOMDocument;
        $dom->loadHTML(mb_convert_encoding($data['text'], 'HTML-ENTITIES', 'UTF-8'));
        $imgs = $dom->getElementsByTagName('img');

        $img_dir_path = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id;

        if ($imgs->length == 0) {
            // imgタグが記事本文にない場合
            // そのまま更新する
            $this->Articles->patchEntity($article, [
                'title' => $data['title'],
                'text' => $data['text'],
                'tag_1' => $data['tag_1'],
                'tag_2' => $data['tag_2'],
                'tag_3' => $data['tag_3'],
                'tag_4' => $data['tag_4'],
                'tag_5' => $data['tag_5'],
                'tag_6' => $data['tag_6'],
            ]);
            $this->Articles->save($article);

            if (file_exists($img_dir_path)){
                // 既存の記事画像がある場合はフォルダごと削除する
                array_map('unlink', glob($img_dir_path. '/*.*'));
                rmdir($img_dir_path);
            }
        } else {
            // imgタグが記事本文にある場合
            // 記事タイトルはそのまま更新し、記事本文はcreateImageAndUpdateText()で更新する
            $this->Articles->patchEntity($article, [
                'title' => $data['title'],
                'tag_1' => $data['tag_1'],
                'tag_2' => $data['tag_2'],
                'tag_3' => $data['tag_3'],
                'tag_4' => $data['tag_4'],
                'tag_5' => $data['tag_5'],
                'tag_6' => $data['tag_6'],
            ]);
            $this->createImageAndUpdateText($dom, $article, 'edit');
        }
    }

    /**
     * 記事削除処理
     *
     * @param int $article_id
     */
    private function delete($article_id) {
        $article = $this->Articles->get($article_id);
        $img_dir_path = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id;

        // ユーザーデータを削除
        if ($this->Articles->delete($article)) {
            // 記事画像を削除
            array_map('unlink', glob($img_dir_path . '/*.*'));
            rmdir($img_dir_path);

            return $this->redirect(['controller' => 'Users', 'action' => 'view?user_id='. $this->auth_user->id . '&redirect=articles_delete']);
        }
        $this->Flash->error(__('記事を削除できませんでした。'));
    }

    /**
     * 記事画像作成＆記事本文更新処理
     *
     * @param DOMDocument $dom
     * @param \App\Model\Entity\Article $dom
     * @param string $mode
     */
    private function createImageAndUpdateText($dom, $article, $mode) {
        $article_id = $article->id;
        $imgs = $dom->getElementsByTagName('img');
        $img_param = date('YmdHis');

        $img_dir_path = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id;
        $img_dir_path_backup = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id ."_backup";

        if ($mode == "edit" && file_exists($img_dir_path)) {
            // 既存の記事画像が存在する場合
            // 記事画像のバックアップ用フォルダ作成
            mkdir($img_dir_path_backup, 0777);

            // 記事画像フォルダの中身をバックアップ用フォルダにコピーする
            if ($handle = opendir($img_dir_path)) {
                while(false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        copy($img_dir_path . '/' . $file, $img_dir_path_backup . '/' . $file);
                    }
                }
                closedir($handle);
            }

            // 記事画像フォルダの中身を削除する
            array_map('unlink', glob($img_dir_path. '/*.*'));
        } else if ($mode == 'add' || ($mode == "edit" && !file_exists($img_dir_path))) {
            // 既存の記事画像が存在しない場合
            // 記事画像保存用フォルダ作成
            mkdir($img_dir_path, 0777);
        }

        // 記事画像作成
        foreach ($imgs as $i => $img) {
            $src = $img->getAttribute('src');
            $img_name = "";

            if (strpos($src, 'data:image') !== false) {
                // 新規追加画像の場合
                // 画像データをデコード
                $img_base64 = str_replace('base64,', '', strstr($src, 'base64,'));
                $img_decode = base64_decode($img_base64);

                // 画像保存パスを設定
                $img_ext = str_replace('data:image/', '', strstr($src, ';base64,', true));
                $img_name = "img_" . $i+1 . "." . $img_ext;
                $img_path = $img_dir_path . "/" . $img_name;

                // 画像を保存
                file_put_contents($img_path, $img_decode);
            } else {
                // 既存画像の場合
                // 既存ファイル名を取得
                $old_img_name_include_param = str_replace('/upload/article_img/article_' . $article_id . '/', '', strstr($src, '/upload'));
                $old_img_name = strstr($old_img_name_include_param, '?', true);

                // 改名して記事画像フォルダにコピーする
                $src_path = $img_dir_path_backup . "/" . $old_img_name;
                $img_ext = pathinfo($src_path, PATHINFO_EXTENSION);
                $img_name = "img_" . $i+1 . "." . $img_ext;
                copy($src_path, $img_dir_path . '/' . $img_name);
            }

            // imgタグのsrcを画像保存パスに変更
            // ※キャッシュ回避用にパラメータを付与
            $img->setAttribute('src', '/upload/article_img/article_' . $article_id . '/' . $img_name . '?'. $img_param);
        }

        // 元データに合わせて本文を調整
        $text = $dom->saveHTML();
        $text = str_replace('<html><body>', '', strstr($text, '<html><body>'));
        $text = str_replace('</body></html>', '', $text);

        // 記事テーブルの本文を更新
        $this->Articles->patchEntity($article, ['text' => $text]);
        $this->Articles->save($article);

        // バックアップ用フォルダを削除する
        if ($mode == "edit" && file_exists($img_dir_path_backup)) {
            array_map('unlink', glob($img_dir_path_backup. '/*.*'));
            rmdir($img_dir_path_backup);
        }
    }
}
