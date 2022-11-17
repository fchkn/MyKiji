<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Favorites Controller
 *
 * @property \App\Model\Table\FavoritesTable $Favorites
 * @method \App\Model\Entity\Favorite[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FavoritesController extends AppController
{
    /**
     * お気に入り追加処理
     */
    public function add()
    {
        $article_id = $this->request->getQuery('article_id');
        $favorite = $this->Favorites->newEmptyEntity();

        $this->Favorites->patchEntity($favorite, [
            'user_id' => $this->auth_user->id,
            'article_id' => $article_id
        ]);

        if ($this->Favorites->save($favorite)) {
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('The favorite could not be saved. Please, try again.'));

        return $this->redirect($this->referer());
    }

    /**
     * お気に入り削除処理
     */
    public function delete()
    {
        $article_id = $this->request->getQuery('article_id');
        $favorite_id = $this->Favorites->find()->where([
            'user_id' => $this->auth_user->id,
            'article_id' => $article_id
        ])->first()->id;

        $favorite = $this->Favorites->get($favorite_id);

        if ($this->Favorites->delete($favorite)) {
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('The favorite could not be deleted. Please, try again.'));

        return $this->redirect($this->referer());
    }
}
