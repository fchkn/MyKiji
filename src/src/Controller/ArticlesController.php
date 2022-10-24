<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{
    /**
     * 記事追加処理
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $article = $this->Articles->newEmptyEntity();
            $post_data = array_merge(['user_id' => $this->auth_user->id], $this->request->getData());
            $this->Articles->patchEntity($article, $post_data);

            if ($this->Articles->save($article)) {
                return $this->redirect(['controller' => 'Users', 'action' => 'view?user_id='. $this->auth_user->id]);
            }
            $this->Flash->error(__('記事追加に失敗しました'));
        }
    }
}
