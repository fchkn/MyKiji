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

        switch ($favorite_flg) {
            case 0 :
                // お気に入り削除の場合
                $favorite_id = $this->Favorites->find()->where([
                    'user_id' => $user_id,
                    'article_id' => $article_id
                ])->first()->id;
                $favorite = $this->Favorites->get($favorite_id);
                $this->Favorites->delete($favorite);
                break;
            case 1 :
                // お気に入り追加の場合
                $favorite = $this->Favorites->newEmptyEntity();
                $this->Favorites->patchEntity($favorite, [
                    'user_id' => $user_id,
                    'article_id' => $article_id
                ]);
                $this->Favorites->save($favorite);
                break;
        }

        return $this->redirect(['controller' => 'Articles', 'action' => 'view?article_id=' . $article_id]);
    }
}
