<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Shipping $shipping
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Shipping'), ['action' => 'edit', $shipping->bidinfo_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Shipping'), ['action' => 'delete', $shipping->bidinfo_id], ['confirm' => __('Are you sure you want to delete # {0}?', $shipping->bidinfo_id)]) ?> </li>
        <li><?= $this->Html->link(__('List Shippings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Shipping'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="shippings view large-9 medium-8 columns content">
    <h3><?= h($shipping->bidinfo_id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Receive Name') ?></th>
            <td><?= h($shipping->receive_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Receive Address') ?></th>
            <td><?= h($shipping->receive_address) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Receive Phone Number') ?></th>
            <td><?= h($shipping->receive_phone_number) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bidinfo Id') ?></th>
            <td><?= $this->Number->format($shipping->bidinfo_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($shipping->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Updated') ?></th>
            <td><?= h($shipping->updated) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Sent') ?></th>
            <td><?= $shipping->is_sent ? __('Yes') : __('No'); ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Received') ?></th>
            <td><?= $shipping->is_received ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
