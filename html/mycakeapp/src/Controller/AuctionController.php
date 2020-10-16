<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Shipping;
use Cake\Event\Event; // added.
use Exception; // added.
use Cake\I18n\Time;

class AuctionController extends AuctionBaseController
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

	// トップページ
	public function index()
	{
		// ページネーションでBiditemsを取得
		$auction = $this->paginate('Biditems', [
			'order' => ['endtime' => 'desc'],
			'limit' => 10
		]);
		$this->set(compact('auction'));
	}

	// 商品情報の表示
	public function view($id = null)
	{
		// $idのBiditemを取得
		$biditem = $this->Biditems->get($id, [
			'contain' => ['Users', 'Bidinfo', 'Bidinfo.Users']
		]);
		// オークション終了時の処理
		if ($biditem->endtime < new \DateTime('now') and $biditem->finished == 0) {
			// finishedを1に変更して保存
			$biditem->finished = 1;
			$this->Biditems->save($biditem);
			// Bidinfoを作成する
			$bidinfo = $this->Bidinfo->newEntity();
			// Bidinfoのbiditem_idに$idを設定
			$bidinfo->biditem_id = $id;
			// 最高金額のBidrequestを検索
			$bidrequest = $this->Bidrequests->find('all', [
				'conditions' => ['biditem_id' => $id],
				'contain' => ['Users'],
				'order' => ['price' => 'desc']
			])->first();
			// Bidrequestが得られた時の処理
			if (!empty($bidrequest)) {
				// Bidinfoの各種プロパティを設定して保存する
				$bidinfo->user_id = $bidrequest->user->id;
				$bidinfo->user = $bidrequest->user;
				$bidinfo->price = $bidrequest->price;
				$this->Bidinfo->save($bidinfo);
			}
			// Biditemのbidinfoに$bidinfoを設定
			$biditem->bidinfo = $bidinfo;
		}
		// Bidrequestsからbiditem_idが$idのものを取得
		$bidrequests = $this->Bidrequests->find('all', [
			'conditions' => ['biditem_id' => $id],
			'contain' => ['Users'],
			'order' => ['price' => 'desc']
		])->toArray();
		// オブジェクト類をテンプレート用に設定
		$this->set(compact('biditem', 'bidrequests'));
	}

	// 出品する処理
	public function add()
	{
		// Biditemインスタンスを用意
		$biditem = $this->Biditems->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $biditemにフォームの送信内容を反映
			$biditem = $this->Biditems->patchEntity($biditem, $this->request->getData());
			// $biditemを保存する
			if ($this->Biditems->save($biditem)) {
				// 成功時のメッセージ
				$this->Flash->success(__('保存しました。'));
				// トップページ（index）に移動
				return $this->redirect(['action' => 'index']);
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
		}
		// 値を保管
		$this->set(compact('biditem'));
	}

	// 入札の処理
	public function bid($biditem_id = null)
	{
		// 入札用のBidrequestインスタンスを用意
		$bidrequest = $this->Bidrequests->newEntity();
		// $bidrequestにbiditem_idとuser_idを設定
		$bidrequest->biditem_id = $biditem_id;
		$bidrequest->user_id = $this->Auth->user('id');
		// POST送信時の処理
		if ($this->request->is('post')) {
			// $bidrequestに送信フォームの内容を反映する
			$bidrequest = $this->Bidrequests->patchEntity($bidrequest, $this->request->getData());
			// Bidrequestを保存
			if ($this->Bidrequests->save($bidrequest)) {
				// 成功時のメッセージ
				$this->Flash->success(__('入札を送信しました。'));
				// トップページにリダイレクト
				return $this->redirect(['action' => 'view', $biditem_id]);
			}
			// 失敗時のメッセージ
			$this->Flash->error(__('入札に失敗しました。もう一度入力下さい。'));
		}
		// $biditem_idの$biditemを取得する
		$biditem = $this->Biditems->get($biditem_id);
		$this->set(compact('bidrequest', 'biditem'));
	}

	// 落札者とのメッセージ
	public function msg($bidinfo_id = null)
	{
		// Bidmessageを新たに用意
		$bidmsg = $this->Bidmessages->newEntity();
		// POST送信時の処理
		if ($this->request->is('post')) {
			// 送信されたフォームで$bidmsgを更新
			$bidmsg = $this->Bidmessages->patchEntity($bidmsg, $this->request->getData());
			// Bidmessageを保存
			if ($this->Bidmessages->save($bidmsg)) {
				$this->Flash->success(__('保存しました。'));
			} else {
				$this->Flash->error(__('保存に失敗しました。もう一度入力下さい。'));
			}
		}
		try { // $bidinfo_idからBidinfoを取得する
			$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems']]);
		} catch (Exception $e) {
			$bidinfo = null;
		}
		// Bidmessageをbidinfo_idとuser_idで検索
		$bidmsgs = $this->Bidmessages->find('all', [
			'conditions' => ['bidinfo_id' => $bidinfo_id],
			'contain' => ['Users'],
			'order' => ['created' => 'desc']
		]);
		$this->set(compact('bidmsgs', 'bidinfo', 'bidmsg'));
	}

	// 配送情報の表示
	public function interact($bidinfo_id)
	{
		$bidinfo = $this->Bidinfo->get($bidinfo_id, ['contain' => ['Biditems']]); //該当する落札情報抽出
		$loginId = $this->Auth->user('id'); //ログイン中のユーザーID
		$entity = $this->Shippings->newEntity();
		try { //発送先情報が既にあれば抽出し、無ければnullにする
			$shipping = $this->Shippings->get($bidinfo_id);
		} catch (Exception $e) {
			$shipping = null;
		}
		// 出品者と落札者のみアクセス可能にし、それ以外のユーザーのアクセスの場合はindexへ戻す。
		if ($bidinfo->user_id !== $loginId && $bidinfo->biditem->user_id !== $loginId) {
			return $this->redirect(['controller' => 'Auction', 'action' => 'index']);
		}
		if ($this->request->is('post') && isset($this->request->data['info'])) { //配送先に関するフォーム送信があった場合
			$bidinfo = $this->Bidinfo->get($this->request->data['info']['bidinfo_id'], ['contain' => ['Biditems']]);
			if ($bidinfo->user_id === $loginId) { //postにてデータ改ざんがなく、正常な場合
				$data = $this->request->data['info'];
				$data['is_sent'] = 0;
				$data['is_received'] = 0;
				$data['created'] = Time::now();
				$data['updated'] = Time::now();
				$shipping = $this->Shippings->newEntity($data);
				if (!empty($shipping) && $this->Shippings->save($shipping)) {
					$this->Flash->success(__('発送先情報を保存しました。'));
				} elseif (!empty($shipping)) {
					$entity = $shipping;
					$shipping['error'] = 1;
					$this->Flash->error(__('発送先情報の保存に失敗しました。もう一度入力下さい。'));
				}
			}
		} elseif ($this->request->is('post') && isset($this->request->data['send'])) { //発送ボタンが押された場合
			$bidinfo = $this->Bidinfo->get($this->request->data['send']['bidinfo_id'], ['contain' => ['Biditems']]);
			if ($bidinfo->biditem->user_id === $loginId) { //postにてデータ改ざんがなく、正常な場合
				$shipping = $this->Shippings->get($this->request->data['send']['bidinfo_id']);
				$shipping->is_sent = 1;
				$shipping->updated = Time::now();
				if (!empty($shipping) && $this->Shippings->save($shipping)) {
					$this->Flash->success(__('配送確認いたしました。'));
				} elseif (!empty($shipping)) {
					$this->Flash->error(__('配送確認に失敗しました。もう一度ボタンを押して下さい。'));
				}
			}
		} elseif ($this->request->is('post') && isset($this->request->data['receive'])) { //受取ボタンが押された場合
			$bidinfo = $this->Bidinfo->get($this->request->data['receive']['bidinfo_id'], ['contain' => ['Biditems']]);
			if ($bidinfo->user_id === $loginId) { //postにてデータ改ざんがなく、正常な場合
				$shipping = $this->Shippings->get($this->request->data['receive']['bidinfo_id']);
				$shipping->is_received = 1;
				$shipping->updated = Time::now();
				if (!empty($shipping) && $this->Shippings->save($shipping)) {
					$this->Flash->success(__('受取確認いたしました。'));
				} elseif (!empty($shipping)) {
					$this->Flash->error(__('受取確認に失敗しました。もう一度ボタンを押して下さい。'));
				}
			}
		}
		$this->set(compact('bidinfo', 'shipping', 'loginId', 'entity'));
	}

	// 落札情報の表示
	public function home()
	{
		// 自分が落札したBidinfoをページネーションで取得
		$bidinfo = $this->paginate('Bidinfo', [
			'conditions' => ['Bidinfo.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Biditems'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('bidinfo'));
	}

	// 出品情報の表示
	public function home2()
	{
		// 自分が出品したBiditemをページネーションで取得
		$biditems = $this->paginate('Biditems', [
			'conditions' => ['Biditems.user_id' => $this->Auth->user('id')],
			'contain' => ['Users', 'Bidinfo'],
			'order' => ['created' => 'desc'],
			'limit' => 10
		])->toArray();
		$this->set(compact('biditems'));
	}
}
