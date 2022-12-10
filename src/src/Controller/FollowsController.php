<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Datasource\ConnectionManager;

/**
 * Follows Controller
 *
 * @property \App\Model\Table\FollowsTable $Follows
 * @method \App\Model\Entity\Follow[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FollowsController extends AppController
{
    /**
     * フォロー追加処理
     */
    public function add()
    {
        // トランザクション開始
        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {
            $follow_user_id = $this->request->getQuery('follow_user_id');
            $follow = $this->Follows->newEmptyEntity();

            $this->Follows->patchEntity($follow, [
                'user_id' => $this->auth_user->id,
                'follow_user_id' => $follow_user_id
            ]);
            $this->Follows->saveOrFail($follow);

            // コミット
            $connection->commit();
        } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
            // バリデーション違反時の例外処理

            // ロールバック
            $connection->rollback();

            $this->Flash->error(__('フォローできませんでした。'));
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
     * フォロー削除処理
     */
    public function delete()
    {
        // トランザクション開始
        $connection = ConnectionManager::get('default');
        $connection->begin();

        try {
            $follow_user_id = $this->request->getQuery('follow_user_id');
            $follow_id = $this->Follows->find()->where([
                'user_id' => $this->auth_user->id,
                'follow_user_id' => $follow_user_id
            ])->first()->id;

            $follow = $this->Follows->get($follow_id);

            $this->Follows->deleteOrFail($follow);

            // コミット
            $connection->commit();
        } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
            // バリデーション違反時の例外処理

            // ロールバック
            $connection->rollback();

            $this->Flash->error(__('フォローを外せませんでした。'));
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
}
