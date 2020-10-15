<h2>評価する</h2>
<fieldset>
  <legend>評価を入力：</legend>
  <?php
  if (empty($bidinfo->shipping->is_received) || (int)$bidinfo->shipping->is_received === 0) {
    echo 'まだ操作できません。商品の受取確認後に操作できます。';
  } elseif (empty($evaluation)) {
    echo $this->Form->create($entity);
    echo $this->Form->input('evaluate.score', [
      'options' => [
        1 => '1(低)',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5(高)'
      ],
      'label' => '数値評価(必須)'
    ]);
    echo $this->Form->input('evaluate.comment', [
      'type' => 'textarea',
      'label' => 'コメント(必須)',
      'maxlength' => 1000
    ]);
    echo $this->Form->hidden('evaluate.bidinfo_id', ['value' => $bidinfo->id]);
    echo $this->Form->button(__('Submit'));
    echo $this->Form->end();
  } elseif (!empty($evaluation)) {
    echo '評価済';
  }
  ?>
</fieldset>
