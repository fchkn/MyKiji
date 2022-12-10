<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

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
        // トランザクション開始
        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {
            $article_id = $this->request->getQuery('article_id');
            $favorite = $this->Favorites->newEmptyEntity();

            $this->Favorites->patchEntity($favorite, [
                'user_id' => $this->auth_user->id,
                'article_id' => $article_id
            ]);
            $this->Favorites->saveOrFail($favorite);

            // コミット
            $connection->commit();
        } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
            // バリデーション違反時の例外処理

            // ロールバック
            $connection->rollback();

            $this->Flash->error(__('お気に入りに追加できませんでした。'));
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
