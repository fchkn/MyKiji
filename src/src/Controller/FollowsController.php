<?php
declare(strict_types=1);

namespace App\Controller;

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
        $follow_user_id = $this->request->getQuery('follow_user_id');
        $follow = $this->Follows->newEmptyEntity();

        $this->Follows->patchEntity($follow, [
            'user_id' => $this->auth_user->id,
            'follow_user_id' => $follow_user_id
        ]);

        if ($this->Follows->save($follow)) {
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('The follow could not be saved. Please, try again.'));

        return $this->redirect($this->referer());
    }

    /**
     * フォロー削除処理
     */
    public function delete()
    {
        $follow_user_id = $this->request->getQuery('follow_user_id');
        $follow_id = $this->Follows->find()->where([
            'user_id' => $this->auth_user->id,
            'follow_user_id' => $follow_user_id
        ])->first()->id;

        $follow = $this->Follows->get($follow_id);

        if ($this->Follows->delete($follow)) {
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('The follow could not be deleted. Please, try again.'));

        return $this->redirect($this->referer());
    }
}
