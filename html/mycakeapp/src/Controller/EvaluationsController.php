<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Shipping;
use Cake\Event\Event; // added.
use Exception; // added.
use Cake\I18n\Time;

/**
 * Evaluations Controller
 *
 * @property \App\Model\Table\EvaluationsTable $Evaluations
 *
 * @method \App\Model\Entity\Evaluation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EvaluationsController extends AuctionBaseController
{
    // デフォルトテーブルを使わない
    public $useTable = false;

    // 初期化処理
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        // 必要なモデルをすべてロード
        $this->loadModel('Users');
        $this->loadModel('Biditems');
        $this->loadModel('Bidrequests');
        $this->loadModel('Bidinfo');
        $this->loadModel('Bidmessages');
        $this->loadModel('Shippings');
        $this->loadModel('Evaluations');
        // ログインしているユーザー情報をauthuserに設定
        $this->set('authuser', $this->Auth->user());
        // レイアウトをauctionに変更
        $this->viewBuilder()->setLayout('auction');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Bidinfo', 'Users', 'Users'],
        ];
        $evaluations = $this->paginate($this->Evaluations);

        $this->set(compact('evaluations'));
    }

    /**
     * View method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => ['Bidinfo', 'Users', 'Users'],
        ]);

        $this->set('evaluation', $evaluation);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $evaluation = $this->Evaluations->newEntity();
        if ($this->request->is('post')) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->getData());
            if ($this->Evaluations->save($evaluation)) {
                $this->Flash->success(__('The evaluation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The evaluation could not be saved. Please, try again.'));
        }
        $bidinfo = $this->Evaluations->Bidinfo->find('list', ['limit' => 200]);
        $fromUsers = $this->Evaluations->Users->find('list', ['limit' => 200]);
        $toUsers = $this->Evaluations->Users->find('list', ['limit' => 200]);
        $this->set(compact('evaluation', 'bidinfo', 'fromUsers', 'toUsers'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $evaluation = $this->Evaluations->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $evaluation = $this->Evaluations->patchEntity($evaluation, $this->request->getData());
            if ($this->Evaluations->save($evaluation)) {
                $this->Flash->success(__('The evaluation has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The evaluation could not be saved. Please, try again.'));
        }
        $bidinfo = $this->Evaluations->Bidinfo->find('list', ['limit' => 200]);
        $fromUsers = $this->Evaluations->Users->find('list', ['limit' => 200]);
        $toUsers = $this->Evaluations->Users->find('list', ['limit' => 200]);
        $this->set(compact('evaluation', 'bidinfo', 'fromUsers', 'toUsers'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Evaluation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $evaluation = $this->Evaluations->get($id);
        if ($this->Evaluations->delete($evaluation)) {
            $this->Flash->success(__('The evaluation has been deleted.'));
        } else {
            $this->Flash->error(__('The evaluation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    // 評価機能の表示
    public function evaluate($bidinfo_id)
    {
        $bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems', 'Shippings']]); //該当する落札情報抽出
        $loginId = $this->Auth->user('id'); //ログイン中のユーザーID
        $entity = $this->Evaluations->newEntity();
        try { //既に評価済か確認
            $evaluation = $this->Evaluations->find()->where(['bidinfo_id' => $bidinfo_id])->andWhere(['from_user_id' => $loginId])->toArray();
        } catch (Exception $e) {
            $evaluation = null;
        }
        // 出品者と落札者のみアクセス可能にし、それ以外のユーザーのアクセスの場合はindexへ戻す。
        if ($bidinfo->user_id !== $loginId && $bidinfo->biditem->user_id !== $loginId) {
            return $this->redirect(['controller' => 'Auction', 'action' => 'index']);
        }
        if ($this->request->is('post')) {
            $data = $this->request->data['evaluate'];
            $data['from_user_id'] = $loginId;
            if ($bidinfo->user_id === $loginId) { //落札者が評価した場合
                $data['to_user_id'] = $bidinfo->biditem->user_id;
            } elseif ($bidinfo->biditem->user_id === $loginId) { //出品者が評価した場合
                $data['to_user_id'] = $bidinfo->user_id;
            }
            $data['created'] = Time::now();
            $evaluation = $this->Evaluations->newEntity($data);
            if (!empty($evaluation) && $this->Evaluations->save($evaluation)) {
                $this->Flash->success(__('評価を送信しました。'));
            } elseif (!empty($evaluation)) {
                $entity = $evaluation;
                $this->Flash->error(__('評価の送信に失敗しました。もう一度入力下さい。'));
            }
        }
        $this->set(compact('bidinfo', 'evaluation', 'entity'));
    }

    // ユーザごとの評価の表示
    public function userevaluation($id)
    {
        $evaluations = $this->Evaluations->find()->where(['to_user_id' => $id]);
        $evaluations = $this->paginate($evaluations, [
            'order' => ['created' => 'desc'],
            'limit' => 10,
            'contain' => ['Bidinfo' => ['Biditems'], 'Users']
        ]);
        $evaluateAve = $this->Evaluations->find()->where(['to_user_id' => $id])->avg('score');
        $this->set(compact('evaluations', 'evaluateAve'));
    }
}
