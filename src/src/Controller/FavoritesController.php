<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Favorites Controller
 *
 * @property \App\Model\Table\FavoritesTable $Favorites
 * @method \App\Model\Entity\Favorite[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FavoritesController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->Articles = TableRegistry::get('articles');
        $this->Favorites = TableRegistry::get('favorites');
    }

    /**
     * お気に入り編集処理
     */
    public function edit()
    {
        $parms = $this->request->getQueryParams();
        $article_id = $parms['article_id'];
        $user_id = $parms['user_id'];
        $favorite_flg = $parms['favorite_flg'];

        // お気に入りテーブルデータ取得
        $favorite_data = $this->Favorites->find()->where(['user_id' => $user_id])->first();
        $favorite_article_id_str = $favorite_data->article_id;

        switch ($favorite_flg) {
            case 0 :
                // お気に入り削除の場合
                $favorite_article_id_array = explode(',', $favorite_article_id_str);
                foreach ($favorite_article_id_array as $i => $favorite_article_id) {
                    if ($favorite_article_id == $article_id) {
                        unset($favorite_article_id_array[$i]);
                        break;
                    }
                }
                $favorite_article_id_str = implode(',', $favorite_article_id_array);
                break;
            case 1 :
                // お気に入り追加の場合
                if(!empty($favorite_article_id_str)) {
                    // お気に入りテーブルに何かしらの記事idが登録されている場合
                    $favorite_article_id_array = explode(',', $favorite_article_id_str);
                    foreach ($favorite_article_id_array as $favorite_article_id) {
                        if ($favorite_article_id == $article_id) {
                            // 既に登録済みの記事idの場合は追加処理を破棄
                            break 2;
                        }
                    }
                    array_push($favorite_article_id_array, $article_id);
                    $favorite_article_id_str = implode(',', $favorite_article_id_array);
                } else {
                    // お気に入りテーブルに記事idが未登録の場合
                    $favorite_article_id_str = $article_id;
                }
                break;
        }

        // お気に入りテーブル更新
        $favorite = $this->Favorites->get($favorite_data->id);
        $this->Favorites->patchEntity($favorite, ['article_id' => $favorite_article_id_str]);
        $this->Favorites->save($favorite);

        return $this->redirect(['controller' => 'Articles', 'action' => 'view?article_id=' . $article_id]);
    }
}
