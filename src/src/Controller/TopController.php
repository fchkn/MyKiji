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
        $this->Articles = TableRegistry::get('articles');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $latest_articles = $this->Articles->find('all', [
            'contain' => ['Users'],
            'order' => ['articles.created' => 'desc'],
            'limit' => 10,
        ])->toArray();

        $this->set(compact('latest_articles'));
    }
}
