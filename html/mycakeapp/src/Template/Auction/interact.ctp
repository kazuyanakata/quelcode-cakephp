<h2>商品を発送する</h2>
<fieldset>
  <legend>発送先を入力：</legend>
  <?php
  if (empty($shipping) && $bidinfo->biditem->user_id === $loginId) {
    echo '発送先は落札者のみ入力可能です。';
  } elseif ((empty($shipping) || $shipping['error'] === 1) && $bidinfo->user_id === $loginId) {
    echo $this->Form->create($entity);
    echo $this->Form->input('info.receive_name', [
      'type' => 'text',
      'label' => '発送先氏名',
      'maxLength' => 100
    ]);;
    echo $this->Form->input('info.receive_address', [
      'type' => 'text',
      'label' => '発送先住所',
      'maxLength' => 1000
    ]);
    echo $this->Form->input('info.receive_phone_number', [
      'type' => 'text',
      'label' => '発送先電話番号',
      'maxLength' => 12
    ]);
    echo $this->Form->button(__('Submit'));
    echo $this->Form->end();
  } elseif (!empty($shipping)) {
  ?>
    <p><b>配送先氏名</b></p>
    <p><?= h($shipping->receive_name) ?></p>
    <p><b>配送先住所</b></p>
    <p><?= h($shipping->receive_address) ?></p>
    <p><b>配送電話番号</b></p>
    <p><?= h($shipping->receive_phone_number) ?></p>
  <?php
  }
  ?>
</fieldset>
<fieldset>
  <legend>発送報告：</legend>
  <?php
  if ((empty($shipping) || !empty($shipping) && (int)$shipping->is_sent === 0) && $bidinfo->user_id === $loginId) {
    echo '発送報告は出品者のみ操作可能です。';
  } elseif (empty($shipping) && $bidinfo->biditem->user_id === $loginId) {
  ?>
    <p>まだ操作できません</p>
  <?php
  } elseif (!empty($shipping) && (int)$shipping->is_sent === 0) {
    echo $this->Form->create();
    echo $this->Form->hidden('send');
    echo $this->Form->button(__('発送しました'));
    echo $this->Form->end();
  } elseif (!empty($shipping) && (int)$shipping->is_sent === 1) {
  ?>
    <p>発送済</p>
  <?php
  }
  ?>
</fieldset>
<fieldset>
  <legend>受取報告：</legend>
  <?php
  if ((empty($shipping) || !empty($shipping) && (int)$shipping->is_received == 0) && $bidinfo->biditem->user_id === $loginId) {
    echo '受取報告は落札者のみ操作可能です。';
  } elseif ((empty($shipping) || !empty($shipping) && (int)$shipping->is_sent == 0 && (int)$shipping->is_received === 0) && $bidinfo->user_id === $loginId) {
  ?>
    <p>まだ操作できません。出品者の発送確認後に操作できるようになります。</p>
  <?php
  } elseif (!empty($shipping) && (int)$shipping->is_sent === 1 && (int)$shipping->is_received === 0) {
    echo $this->Form->create();
    echo $this->Form->hidden('receive');
    echo $this->Form->button(__('受け取りました'));
    echo $this->Form->end();
  } elseif (!empty($shipping) && (int)$shipping->is_sent == 1 && (int)$shipping->is_received === 1) {
  ?>
    <p>受取済</p>
  <?php
  }
  ?>
</fieldset>
<a href="<?= $this->Url->build(['controller' => 'Evaluations', 'action' => 'evaluate', $bidinfo->id]) ?>">評価ページへ進む</a>
