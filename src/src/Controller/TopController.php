<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Top Controller
 *
 * @method \App\Model\Entity\Top[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TopController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['index']);
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->Articles = TableRegistry::get('Articles');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $latest_articles = [];

        try {
            $latest_articles = $this->Articles->find('all', [
                'contain' => ['Users'],
                'order' => ['Articles.created' => 'desc'],
                'limit' => 10,
            ])->toArray();
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

        $hasPaginator = false;

        $this->set(compact('latest_articles', 'hasPaginator'));
    }
}
