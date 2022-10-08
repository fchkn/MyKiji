<?php
declare(strict_types=1);

namespace App\Controller;

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

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
    }
}
