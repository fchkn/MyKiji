<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;
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
        $this->Users = TableRegistry::get('Users');
        $this->Favorites = TableRegistry::get('Favorites');
    }

    /**
     * 記事詳細画面処理
     */
    public function view() {
        $article_id = $this->request->getQuery('article_id');
        $article = null;
        $user = null;
        $hasFavorite = false;
        $existing_imgs_size_csv = "";
        $hasError = false;

        try {
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

            if ($this->hasAuth) {
                // お気に入り登録の有無を確認
                $favorite = $this->Favorites->find()->where([
                    'user_id' => $this->auth_user->id,
                    'article_id' => $article_id])->first();
                if(!empty($favorite)) {
                    $hasFavorite = true;
                }
            }

            // 既存記事画像のファイルサイズを取得
            $existing_imgs_size = [];
            $img_dir_path = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id;
            if (file_exists($img_dir_path)) {
                if ($handle = opendir($img_dir_path)) {
                    while(false !== ($file = readdir($handle))) {
                        if ($file != "." && $file != "..") {
                            $existing_imgs_size[] += filesize($img_dir_path . '/' . $file);
                        }
                    }
                    closedir($handle);
                }
            }
            $existing_imgs_size_csv = !empty($existing_imgs_size) ? implode(",", $existing_imgs_size) : "";
        } catch (\Exception $e) {
            $hasError = true;

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

        // リダイレクトプロパティ取得
        $session = $this->getRequest()->getSession();
        $redirect = $session->read('redirect');
        if (!empty($redirect)) {
            $session->delete('redirect');
        }

        $this->set(compact(
            'article',
            'user',
            'hasFavorite',
            'existing_imgs_size_csv',
            'redirect',
            'hasError',
        ));
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
        $hasPaginator = true;

        try {
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

        $this->set(compact('target', 'search', 'search_articles', 'order' ,'hasPaginator'));
    }

    /**
     * 記事追加処理
     */
    public function add()
    {
        if ($this->request->is('post')) {
            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();

            $article = $this->Articles->newEmptyEntity();

            try {
                $data = array_merge(['user_id' => $this->auth_user->id], $this->request->getData());

                $dom = new DOMDocument;
                $dom->loadHTML(mb_convert_encoding($data['text'], 'HTML-ENTITIES', 'UTF-8'));
                $imgs = $dom->getElementsByTagName('img');
    
                if ($imgs->length != 0) {
                    // imgタグが記事本文にある場合
                    // 記事本文を一旦仮の文字列にして保存する
                    $data['text'] = '<p>tmp text</p>';
                    $this->Articles->patchEntity($article, $data);
                    $this->Articles->saveOrFail($article);

                    // 記事画像を作成し、記事本文を校正する
                    $text = $this->createImageAndEmendText($dom, $article->id, 'add');

                    // 記事本文を更新する
                    $this->Articles->patchEntity($article, ['text' => $text]);
                    $this->Articles->saveOrFail($article);
                } else {
                    $this->Articles->patchEntity($article, $data);
                    $this->Articles->saveOrFail($article);
                }

                // 記事追加完了ポップアップの判定パラメータをセッションに格納
                $session = $this->getRequest()->getSession();
                $session->write('redirect', 'articles_add');

                // コミット
                $connection->commit();

                return $this->redirect(['controller' => 'Users', 'action' => 'view?user_id='. $this->auth_user->id]);
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                // バリデーション違反時の例外処理

                // ロールバック
                $connection->rollback();

                // 記事画像を削除
                if (!empty($article->id)) {
                    $this->deleteImage($article->id);
                }

                $this->Flash->error(__('記事追加に失敗しました'));
            } catch (\Exception $e) {
                // その他例外処理

                // ロールバック
                $connection->rollback();

                // 記事画像を削除
                if (!empty($article->id)) {
                    $this->deleteImage($article->id);
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
    }

    /**
     * 記事編集処理
     */
    public function edit() {
        $article_id = $this->request->getQuery('article_id');
        $data = $this->request->getData();

        if ($this->request->is('post')) {
            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();

            try {
                $article = $this->Articles->get($article_id);

                $dom = new DOMDocument;
                $dom->loadHTML(mb_convert_encoding($data['text'], 'HTML-ENTITIES', 'UTF-8'));
                $imgs = $dom->getElementsByTagName('img');

                if ($imgs->length != 0) {
                    // imgタグが記事本文にある場合
                    // 記事データを更新
                    $this->Articles->patchEntity($article, [
                        'title' => $data['title'],
                        'text' => $this->createImageAndEmendText($dom, $article_id, 'edit'),
                        'tag_1' => $data['tag_1'],
                        'tag_2' => $data['tag_2'],
                        'tag_3' => $data['tag_3'],
                        'tag_4' => $data['tag_4'],
                        'tag_5' => $data['tag_5'],
                        'tag_6' => $data['tag_6'],
                    ]);
                    $this->Articles->saveOrFail($article);

                    // 記事画像バックアップを削除する
                    $this->deleteImageBackup($article->id);
                } else {
                    // imgタグが記事本文にない場合
                    // 記事データを更新
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
                    $this->Articles->saveOrFail($article);

                    // 既存記事画像を削除
                    $this->deleteImage($article->id);
                }

                // 記事編集完了ポップアップの判定パラメータをセッションに格納
                $session = $this->getRequest()->getSession();
                $session->write('redirect', 'articles_edit');

                // コミット
                $connection->commit();
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                // バリデーション違反時の例外処理

                // ロールバック
                $connection->rollback();

                // 記事画像をバックアップから復元
                $this->restoreImageBackup($article_id);

                $this->Flash->error(__('退会ができませんでした。'));
            } catch (\Exception $e) {
                // その他例外処理

                // ロールバック
                $connection->rollback();

                if ($e->getMessage() == "記事画像のバックアップ作成に失敗しました。") {
                    // 記事画像バックアップフォルダを削除する
                    $this->deleteImageBackup($article_id);
                } else {
                    // 記事画像をバックアップから復元する
                    $this->restoreImageBackup($article_id);
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

        $this->redirect(['controller' => 'Articles', 'action' => 'view?article_id='. $article_id]);
    }

    /**
     * 記事削除処理
     */
    public function delete() {
        $article_id = $this->request->getQuery('article_id');

        if ($this->request->is('post')) {
            // トランザクション開始
            $connection = ConnectionManager::get('default');
            $connection->begin();

            try {
                $article = $this->Articles->get($article_id);

                // 記事データを削除
                $this->Articles->deleteOrFail($article);

                // 記事画像を削除
                $this->deleteImage($article_id);

                // 記事削除完了ポップアップの判定パラメータをセッションに格納
                $session = $this->getRequest()->getSession();
                $session->write('redirect', 'articles_delete');

                // コミット
                $connection->commit();

                return $this->redirect(['controller' => 'Users', 'action' => 'view?user_id='. $this->auth_user->id]);
            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                // バリデーション違反時の例外処理

                // ロールバック
                $connection->rollback();

                $this->Flash->error(__('記事を削除できませんでした。'));
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

        $this->redirect(['controller' => 'Articles', 'action' => 'view?article_id='. $article_id]);
    }

    /**
     * 記事画像作成＆記事本文校正処理
     *
     * @param DOMDocument $dom
     * @param int article_id
     * @param string $mode
     * @return string $text
     */
    private function createImageAndEmendText($dom, $article_id, $mode) {
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
                        if (!copy($img_dir_path . '/' . $file, $img_dir_path_backup . '/' . $file)) {
                            throw new \Exception('記事画像のバックアップ作成に失敗しました。', 500);
                        }
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
                if (file_put_contents($img_path, $img_decode) === false) {
                    throw new \Exception('記事画像作成に失敗しました。', 500);
                }
            } else {
                // 既存画像の場合
                // 既存ファイル名を取得
                $old_img_name_include_param = str_replace('/upload/article_img/article_' . $article_id . '/', '', strstr($src, '/upload'));
                $old_img_name = strstr($old_img_name_include_param, '?', true);

                // 改名して記事画像フォルダにコピーする
                $src_path = $img_dir_path_backup . "/" . $old_img_name;
                $img_ext = pathinfo($src_path, PATHINFO_EXTENSION);
                $img_name = "img_" . $i+1 . "." . $img_ext;
                if (!copy($src_path, $img_dir_path . '/' . $img_name)) {
                    throw new \Exception('記事画像作成に失敗しました。', 500);
                }
            }

            // imgタグのsrcを画像保存パスに変更
            // ※キャッシュ回避用にパラメータを付与
            $img->setAttribute('src', '/upload/article_img/article_' . $article_id . '/' . $img_name . '?'. $img_param);
        }

        // 元データに合わせて本文を校正
        $text = $dom->saveHTML();
        if ($text !== false) {
            $text = $dom->saveHTML();
            $text = str_replace('<html><body>', '', strstr($text, '<html><body>'));
            $text = str_replace('</body></html>', '', $text);
        } else {
            throw new \Exception('記事本文の校正に失敗しました。', 500);
        }

        return $text;
    }

    /**
     * 記事画像削除処理
     *
     * @param int article_id
     */
    private function deleteImage($article_id) {
        $img_dir_path = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id;
        if (file_exists($img_dir_path)){
            array_map('unlink', glob($img_dir_path. '/*.*'));
            rmdir($img_dir_path);
        }
    }

    /**
     * 記事画像バックアップ削除処理
     *
     * @param int article_id
     */
    private function deleteImageBackup($article_id) {
        $img_dir_path_backup = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id ."_backup";
        if (file_exists($img_dir_path_backup)) {
            array_map('unlink', glob($img_dir_path_backup. '/*.*'));
            rmdir($img_dir_path_backup);
        }
    }

    /**
     * 記事画像バックアップ復元処理
     *
     * @param int article_id
     */
    private function restoreImageBackup($article_id) {
        $img_dir_path = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id;
        $img_dir_path_backup = UPLOAD_ARTICLE_IMG_PATH . "article_" . $article_id ."_backup";
        if (file_exists($img_dir_path) && file_exists($img_dir_path_backup)) {
            // 記事画像フォルダの中身を削除する
            array_map('unlink', glob($img_dir_path. '/*.*'));

            // バックアップフォルダの中身を記事画像フォルダにコピーする
            if ($handle = opendir($img_dir_path_backup)) {
                while(false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != "..") {
                        copy($img_dir_path_backup . '/' . $file, $img_dir_path . '/' . $file);
                    }
                }
                closedir($handle);
            }

            // バックアップフォルダを削除する
            array_map('unlink', glob($img_dir_path_backup. '/*.*'));
            rmdir($img_dir_path_backup);
        }
    }
}
