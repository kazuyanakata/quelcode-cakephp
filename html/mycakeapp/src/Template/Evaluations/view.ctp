<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Evaluation $evaluation
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Evaluation'), ['action' => 'edit', $evaluation->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Evaluation'), ['action' => 'delete', $evaluation->id], ['confirm' => __('Are you sure you want to delete # {0}?', $evaluation->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Evaluations'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Evaluation'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="evaluations view large-9 medium-8 columns content">
    <h3><?= h($evaluation->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Comment') ?></th>
            <td><?= h($evaluation->comment) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($evaluation->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Bidinfo Id') ?></th>
            <td><?= $this->Number->format($evaluation->bidinfo_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('From User Id') ?></th>
            <td><?= $this->Number->format($evaluation->from_user_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('To User Id') ?></th>
            <td><?= $this->Number->format($evaluation->to_user_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Score') ?></th>
            <td><?= $this->Number->format($evaluation->score) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($evaluation->created) ?></td>
        </tr>
    </table>
</div>
