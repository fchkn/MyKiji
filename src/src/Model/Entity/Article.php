<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Article Entity
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $text
 * @property string|null $tag_1
 * @property string|null $tag_2
 * @property string|null $tag_3
 * @property string|null $tag_4
 * @property string|null $tag_5
 * @property string|null $tag_6
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class Article extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'user_id' => true,
        'title' => true,
        'text' => true,
        'tag_1' => true,
        'tag_2' => true,
        'tag_3' => true,
        'tag_4' => true,
        'tag_5' => true,
        'tag_6' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
    ];
}
