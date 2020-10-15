<h2><?= $authuser['username'] ?>の評価</h2>
<h3>今まで受けた数値評価の平均値</h3>
<?php
if (!empty($evaluateAve)) {
?>
  <h3><?= $this->Number->precision($evaluateAve, 1); ?></h3>
<?php
} else {
?>
  <h3>-</h3>
<?php
}
?>
<h3><?= $authuser['username'] ?>への評価一覧</h3>
<table cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th scope="col"><?= $this->Paginator->sort('商品名') ?></th>
      <th scope="col"><?= $this->Paginator->sort('評価者名') ?></th>
      <th scope="col"><?= $this->Paginator->sort('数値評価') ?></th>
      <th scope="col"><?= $this->Paginator->sort('評価コメント') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($evaluations as $evaluation) : ?>
      <tr>
        <td><?= h($evaluation->bidinfo->biditem->name) ?></td>
        <td><?= h($evaluation->user->username) ?></td>
        <td><?= h($evaluation->score) ?></td>
        <td><?= nl2br(h($evaluation->comment)) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<div class="paginator">
  <ul class="pagination">
    <?= $this->Paginator->first('<< ' . __('first')) ?>
    <?= $this->Paginator->prev('< ' . __('previous')) ?>
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next(__('next') . ' >') ?>
    <?= $this->Paginator->last(__('last') . ' >>') ?>
  </ul>
</div>
