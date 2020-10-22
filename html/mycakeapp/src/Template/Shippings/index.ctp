<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Shipping[]|\Cake\Collection\CollectionInterface $shippings
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Shipping'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="shippings index large-9 medium-8 columns content">
    <h3><?= __('Shippings') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('bidinfo_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('receive_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('receive_address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('receive_phone_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_sent') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_received') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('updated') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shippings as $shipping): ?>
            <tr>
                <td><?= $this->Number->format($shipping->bidinfo_id) ?></td>
                <td><?= h($shipping->receive_name) ?></td>
                <td><?= h($shipping->receive_address) ?></td>
                <td><?= h($shipping->receive_phone_number) ?></td>
                <td><?= h($shipping->is_sent) ?></td>
                <td><?= h($shipping->is_received) ?></td>
                <td><?= h($shipping->created) ?></td>
                <td><?= h($shipping->updated) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $shipping->bidinfo_id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $shipping->bidinfo_id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $shipping->bidinfo_id], ['confirm' => __('Are you sure you want to delete # {0}?', $shipping->bidinfo_id)]) ?>
                </td>
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
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
