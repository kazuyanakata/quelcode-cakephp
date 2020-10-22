<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Evaluation Entity
 *
 * @property int $id
 * @property int $bidinfo_id
 * @property int $from_user_id
 * @property int $to_user_id
 * @property int $score
 * @property string $comment
 * @property \Cake\I18n\Time $created
 *
 * @property \App\Model\Entity\Bidinfo $bidinfo
 * @property \App\Model\Entity\FromUser $from_user
 * @property \App\Model\Entity\ToUser $to_user
 */
class Evaluation extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'bidinfo_id' => true,
        'from_user_id' => true,
        'to_user_id' => true,
        'score' => true,
        'comment' => true,
        'created' => true,
        'bidinfo' => true,
        'user' => true,
    ];
}
